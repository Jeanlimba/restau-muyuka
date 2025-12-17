<?php

require_once __DIR__ . '/Commande.php';
require_once __DIR__ . '/LigneVente.php';

class Vente {
    private $pdo;
    public $lastError = null;
    
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function find(int $id) {
        $stmt = $this->pdo->prepare("SELECT * FROM ventes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findWithDetails(int $id)
    {
        $stmt = $this->pdo->prepare(
            "SELECT v.*, t.nom as table_nom, z.nom as table_zone 
             FROM ventes v
             LEFT JOIN tables t ON v.table_id = t.id
             LEFT JOIN zones z ON t.zone_id = z.id
             WHERE v.id = ?"
        );
        $stmt->execute([$id]);
        $vente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($vente) {
            $commandeModel = new Commande();
            $ligneVenteModel = new LigneVente();
            $commandes = $commandeModel->findByVenteId($id);
            
            $vente['commandes'] = [];
            foreach ($commandes as $commande) {
                $commande['lignes'] = $ligneVenteModel->findByCommandeId($commande['id']);
                $vente['commandes'][] = $commande;
            }
        }

        return $vente;
    }

    public function findAllByDateGrouped(string $date, array $user): array
    {
        // Cette méthode doit être revue car le comptage d'articles est plus complexe
        // Pour l'instant, on la garde simple
        $sql = "SELECT 
                v.id, v.numero_vente, v.total, v.statut, v.created_at, t.nom as table_nom,
                (SELECT COUNT(lv.id) FROM lignes_vente lv JOIN commandes c ON lv.commande_id = c.id WHERE c.vente_id = v.id) as count_articles
             FROM ventes v
             LEFT JOIN tables t ON v.table_id = t.id
             WHERE DATE(v.created_at) = ?";
        
        $params = [$date];

        if (isset($user['fonction']) && $user['fonction'] !== 'Administrateur') {
            $sql .= " AND v.user_id = ?";
            $params[] = $user['id'];
        }

        $sql .= " GROUP BY v.id ORDER BY v.created_at DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAllDetailedByDate(string $date, array $user): array
    {
        $sql = "SELECT 
                    v.id as vente_id, v.numero_vente, v.total as vente_total, v.created_at, t.nom as table_nom,
                    c.id as commande_id,
                    lv.quantite, lv.prix_unitaire_ht,
                    a.nom as article_nom
                FROM ventes v
                JOIN commandes c ON v.id = c.vente_id
                JOIN lignes_vente lv ON c.id = lv.commande_id
                JOIN articles a ON lv.article_id = a.id
                LEFT JOIN tables t ON v.table_id = t.id
                WHERE DATE(v.created_at) = ?";
        
        $params = [$date];

        if (isset($user['fonction']) && $user['fonction'] !== 'Administrateur') {
            $sql .= " AND v.user_id = ?";
            $params[] = $user['id'];
        }

        $sql .= " ORDER BY v.created_at DESC, c.date_commande ASC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $flat_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Grouper les résultats par vente
        $ventes = [];
        foreach ($flat_results as $row) {
            $vente_id = $row['vente_id'];
            if (!isset($ventes[$vente_id])) {
                $ventes[$vente_id] = [
                    'numero_vente' => $row['numero_vente'],
                    'table_nom' => $row['table_nom'],
                    'created_at' => $row['created_at'],
                    'vente_total' => $row['vente_total'],
                    'lignes' => []
                ];
            }
            $ventes[$vente_id]['lignes'][] = [
                'article_nom' => $row['article_nom'],
                'quantite' => $row['quantite'],
                'prix_unitaire_ht' => $row['prix_unitaire_ht']
            ];
        }
        return $ventes;
    }

    public function create(array $data)
    {
        try {
            $numero_vente = $this->generateNumeroVente();
            $stmt = $this->pdo->prepare(
                "INSERT INTO ventes (numero_vente, table_id, user_id, total, statut, created_at) 
                 VALUES (?, ?, ?, ?, ?, NOW())"
            );
            $stmt->execute([
                $numero_vente, 
                $data['table_id'], 
                $data['user_id'], 
                $data['total'],
                $data['statut'] ?? 'payee'
            ]);
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }
    
    public function createForPostPayment(array $data)
    {
        $this->pdo->beginTransaction();
        try {
            $numero_vente = $this->generateNumeroVente();
            $stmt = $this->pdo->prepare(
                "INSERT INTO ventes (numero_vente, table_id, user_id, total, statut, created_at) 
                 VALUES (?, ?, ?, 0, 'en_cours', NOW())"
            );
            $stmt->execute([$numero_vente, $data['table_id'], $data['user_id']]);
            $venteId = $this->pdo->lastInsertId();
            
            (new Table())->updateStatut($data['table_id'], 'occupee');

            $this->pdo->commit();
            return (int)$venteId;

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    public function updateStatut(int $id, string $statut): bool {
        $stmt = $this->pdo->prepare("UPDATE ventes SET statut = ? WHERE id = ?");
        return $stmt->execute([$statut, $id]);
    }

    public function delete(int $id): bool {
        // La suppression en cascade devrait s'occuper des commandes et lignes
        $stmt = $this->pdo->prepare("DELETE FROM ventes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getOpenVenteForTable(int $tableId) {
        $stmt = $this->pdo->prepare("SELECT * FROM ventes WHERE table_id = ? AND statut = 'en_cours'");
        $stmt->execute([$tableId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateTotal(int $venteId) {
        $stmt = $this->pdo->prepare(
           "SELECT SUM(lv.prix_unitaire_ht * lv.quantite * (1 + lv.tva / 100)) as total
            FROM lignes_vente lv
            JOIN commandes c ON lv.commande_id = c.id
            WHERE c.vente_id = ?"
        );
        $stmt->execute([$venteId]);
        $total = $stmt->fetchColumn();

        $stmtUpdate = $this->pdo->prepare("UPDATE ventes SET total = ? WHERE id = ?");
        return $stmtUpdate->execute([$total ?? 0, $venteId]);
    }

    public function close(int $venteId) {
        $vente = $this->find($venteId);
        if ($vente) {
            $this->updateTotal($venteId); // On recalcule une dernière fois
            $this->updateStatut($venteId, 'payee');
            (new Table())->updateStatut($vente['table_id'], 'libre');
            return true;
        }
        return false;
    }

    private function generateNumeroVente(): string {
        $year = date('Y');
        $prefix = "VTE-{$year}-";

        $stmt = $this->pdo->prepare(
            "SELECT numero_vente FROM ventes 
             WHERE numero_vente LIKE ? 
             ORDER BY id DESC LIMIT 1"
        );
        $stmt->execute(["{$prefix}%"]);
        $lastNumero = $stmt->fetchColumn();

        if ($lastNumero) {
            $lastId = (int) str_replace($prefix, '', $lastNumero);
            $newId = $lastId + 1;
        } else {
            $newId = 1;
        }

                return $prefix . str_pad($newId, 4, '0', STR_PAD_LEFT);

            }

        

            public function getSalesReport(string $startDate, string $endDate, string $groupBy = 'day') {

                $select_group = "DATE(v.created_at) as periode";

                $group_by_sql = "GROUP BY DATE(v.created_at)";

                

                if ($groupBy === 'month') {

                    $select_group = "DATE_FORMAT(v.created_at, '%Y-%m') as periode";

                    $group_by_sql = "GROUP BY DATE_FORMAT(v.created_at, '%Y-%m')";

                }

        

                $sql = "SELECT 

                            {$select_group},

                            SUM(v.total) as total_ventes,

                            COUNT(v.id) as nombre_ventes

                        FROM ventes v

                        WHERE v.statut = 'payee' AND DATE(v.created_at) BETWEEN ? AND ?

                        {$group_by_sql}

                        ORDER BY periode ASC";

        

                $stmt = $this->pdo->prepare($sql);

                $stmt->execute([$startDate, $endDate]);

                return $stmt->fetchAll(PDO::FETCH_ASSOC);

            }

        }

        