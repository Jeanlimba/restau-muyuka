<?php

class LigneVente {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }
    
    /**
     * Crée plusieurs lignes de vente pour une commande donnée.
     * @param int $commandeId L'ID de la commande parente.
     * @param array $lignes Les lignes de vente à créer.
     * @return bool True si la création réussit, false sinon.
     */
    public function create(int $commandeId, array $lignes): bool {
        try {
            $stmt = $this->pdo->prepare(
                'INSERT INTO lignes_vente (commande_id, article_id, quantite, prix_unitaire_ht, tva) 
                 VALUES (:commande_id, :article_id, :quantite, :prix_unitaire_ht, :tva, :cout_achat_unitaire)'
            );

            foreach ($lignes as $ligne) {
                $stmt->execute([
                    ':commande_id' => $commandeId,
                    ':article_id' => $ligne['article_id'],
                    ':quantite' => $ligne['quantite'],
                    ':prix_unitaire_ht' => $ligne['prix_unitaire_ht'],
                    ':tva' => $ligne['tva'] ?? 20.00,
                    ':cout_achat_unitaire' => $ligne['cout_achat_unitaire'] ?? 0
                ]);
            }
            return true;
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    /**
     * Récupère toutes les lignes de vente pour une commande donnée.
     */
    public function findByCommandeId(int $commandeId): array {
        $stmt = $this->pdo->prepare(
            'SELECT lv.*, a.nom AS article_nom 
             FROM lignes_vente lv
             JOIN articles a ON lv.article_id = a.id
             WHERE lv.commande_id = :commande_id'
        );
        $stmt->execute([':commande_id' => $commandeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id) {
        $stmt = $this->pdo->prepare('SELECT * FROM lignes_vente WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare('DELETE FROM lignes_vente WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}
