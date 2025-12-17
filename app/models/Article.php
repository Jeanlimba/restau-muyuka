<?php

class Article {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function all($filters = []) {
        $sql = "SELECT a.*, a.stock as stock_actuel,
                    um_vente.nom as unite_vente_nom, um_vente.symbole as unite_vente_symbole,
                    um_achat.nom as unite_achat_nom, um_achat.symbole as unite_achat_symbole
             FROM articles a
             LEFT JOIN unites_mesure um_vente ON a.unite_mesure_id = um_vente.id
             LEFT JOIN unites_mesure um_achat ON a.purchase_unite_mesure_id = um_achat.id
             WHERE a.actif = 1";
        
        $params = [];

        if (!empty($filters['nom'])) {
            $sql .= " AND a.nom LIKE ?";
            $params[] = '%' . $filters['nom'] . '%';
        }
        if (!empty($filters['categorie'])) {
            $sql .= " AND a.categorie = ?";
            $params[] = $filters['categorie'];
        }
        
        if (!empty($filters['stock_situation'])) {
            switch ($filters['stock_situation']) {
                case 'en_stock':
                    $sql .= " AND a.stock > 0";
                    break;
                case 'stock_bas':
                    $sql .= " AND a.stock > 0 AND a.stock <= a.stock_seuil";
                    break;
                case 'en_rupture':
                    $sql .= " AND a.stock <= 0";
                    break;
            }
        }

        $sql .= " ORDER BY a.nom";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDistinctCategories() {
        $stmt = $this->pdo->query("SELECT DISTINCT categorie FROM articles WHERE actif = 1 AND categorie IS NOT NULL AND categorie != '' ORDER BY categorie");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function findAllWithUnite() {
        $stmt = $this->pdo->query(
            "SELECT a.id, a.nom, a.prix AS prix_vente_ht, 20.00 AS tva, um.nom as unite_nom, um.symbole as unite_symbole
             FROM articles a
             LEFT JOIN unites_mesure um ON a.unite_mesure_id = um.id
             WHERE a.actif = 1 ORDER BY a.nom"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare(
            "SELECT a.*, 
                    um_vente.nom as unite_vente_nom, um_vente.symbole as unite_vente_symbole,
                    um_achat.nom as unite_achat_nom, um_achat.symbole as unite_achat_symbole
             FROM articles a
             LEFT JOIN unites_mesure um_vente ON a.unite_mesure_id = um_vente.id
             LEFT JOIN unites_mesure um_achat ON a.purchase_unite_mesure_id = um_achat.id
             WHERE a.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO articles (nom, categorie, unite_mesure_id, purchase_unite_mesure_id, conversion_factor, type_tarification, prix) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $data['nom'], 
                $data['categorie'], 
                $data['unite_mesure_id'],
                $data['purchase_unite_mesure_id'] ?: null,
                $data['conversion_factor'] ?: 1,
                $data['type_tarification'],
                $data['prix'] ?? 0.00
            ]);
            $articleId = $this->pdo->lastInsertId();

            if ($data['type_tarification'] === 'varie' && isset($data['tarifs'])) {
                (new Tarif())->saveForArticle($articleId, $data['tarifs']);
            }
            
            return $articleId;
        } catch (Exception $e) {
            return false;
        }
    }

    public function update($id, $data) {
        try {
            $stmt = $this->pdo->prepare(
                "UPDATE articles SET nom = ?, categorie = ?, unite_mesure_id = ?, purchase_unite_mesure_id = ?, conversion_factor = ?, type_tarification = ?, prix = ? 
                 WHERE id = ?"
            );
            $stmt->execute([
                $data['nom'], 
                $data['categorie'], 
                $data['unite_mesure_id'],
                $data['purchase_unite_mesure_id'] ?: null,
                $data['conversion_factor'] ?: 1,
                $data['type_tarification'], 
                $data['prix'] ?? 0.00,
                $id
            ]);

            $tarifModel = new Tarif();
            if ($data['type_tarification'] === 'varie' && isset($data['tarifs'])) {
                $tarifModel->saveForArticle($id, $data['tarifs']);
            } else {
                $tarifModel->saveForArticle($id, []);
            }
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("UPDATE articles SET actif = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function decrementStock(int $articleId, int $quantite): bool {
        $stmt = $this->pdo->prepare("UPDATE articles SET stock = COALESCE(stock, 0) - ? WHERE id = ?");
        return $stmt->execute([$quantite, $articleId]);
    }

    public function incrementStock(int $articleId, int $quantite): bool {
        $stmt = $this->pdo->prepare("UPDATE articles SET stock = COALESCE(stock, 0) + ? WHERE id = ?");
        return $stmt->execute([$quantite, $articleId]);
    }

    public function updateStock(int $articleId, float $newStock): bool {
        $stmt = $this->pdo->prepare("UPDATE articles SET stock = ? WHERE id = ?");
        return $stmt->execute([$newStock, $articleId]);
    }

    public function updateLastCost(int $articleId, float $cost): bool {
        $stmt = $this->pdo->prepare("UPDATE articles SET dernier_cout_achat = ? WHERE id = ?");
        return $stmt->execute([$cost, $articleId]);
    }

    public function getProfitReport(string $startDate, string $endDate) {
        $sql = "SELECT 
                    a.id as article_id,
                    a.nom as article_nom,
                    SUM(lv.quantite) as total_quantite_vendue,
                    SUM(lv.quantite * lv.prix_unitaire_ht) as chiffre_affaires_ht,
                    SUM(lv.quantite * lv.cout_achat_unitaire) as cout_total_ht,
                    (SUM(lv.quantite * lv.prix_unitaire_ht) - SUM(lv.quantite * lv.cout_achat_unitaire)) as gain_brut
                FROM lignes_vente lv
                JOIN commandes c ON lv.commande_id = c.id
                JOIN ventes v ON c.vente_id = v.id
                JOIN articles a ON lv.article_id = a.id
                WHERE DATE(v.created_at) BETWEEN ? AND ?
                GROUP BY a.id, a.nom
                ORDER BY gain_brut DESC";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPrixPourZone(int $articleId, int $zoneId): ?float {
        $article = $this->find($articleId);
        if (!$article) {
            return null;
        }

        if ($article['type_tarification'] === 'standard') {
            return (float)$article['prix'];
        } else {
            return (new Tarif())->getPrix($articleId, $zoneId);
        }
    }
}