<?php

class LigneInventaire {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function createMany($inventaireId, $lignes) {
        $sql = "INSERT INTO lignes_inventaire (inventaire_id, article_id, stock_theorique, stock_physique, justification) 
                VALUES (:inventaire_id, :article_id, :stock_theorique, :stock_physique, :justification)";
        $stmt = $this->pdo->prepare($sql);

        foreach ($lignes as $ligne) {
            $stmt->execute([
                ':inventaire_id' => $inventaireId,
                ':article_id' => $ligne['article_id'],
                ':stock_theorique' => $ligne['stock_theorique'],
                ':stock_physique' => $ligne['stock_physique'],
                ':justification' => $ligne['justification'] ?? null
            ]);
        }
        return true;
    }
}
