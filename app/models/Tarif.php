<?php

class Tarif {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    /**
     * Récupère tous les tarifs pour un article donné.
     * @param int $articleId
     * @return array
     */
    public function findByArticleId(int $articleId): array {
        $stmt = $this->pdo->prepare(
            "SELECT z.id as zone_id, z.nom as zone_nom, t.prix 
             FROM zones z
             LEFT JOIN tarifs t ON z.id = t.zone_id AND t.article_id = ?
             WHERE z.actif = 1"
        );
        $stmt->execute([$articleId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour ou insère les tarifs pour un article donné.
     * @param int $articleId
     * @param array $tarifs Un tableau associatif [zone_id => prix].
     * @return bool
     */
    public function saveForArticle(int $articleId, array $tarifs): bool {
        $this->pdo->beginTransaction();
        try {
            // D'abord, supprimer les anciens tarifs pour cet article
            $stmtDelete = $this->pdo->prepare("DELETE FROM tarifs WHERE article_id = ?");
            $stmtDelete->execute([$articleId]);

            // Ensuite, insérer les nouveaux tarifs
            $stmtInsert = $this->pdo->prepare(
                "INSERT INTO tarifs (article_id, zone_id, prix) VALUES (?, ?, ?)"
            );
            foreach ($tarifs as $zoneId => $prix) {
                // N'insérer que si un prix est spécifié
                if (!empty($prix) && is_numeric($prix)) {
                    $stmtInsert->execute([$articleId, $zoneId, $prix]);
                }
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Récupère le prix d'un article pour une zone donnée.
     * @param int $articleId
     * @param int $zoneId
     * @return float|null
     */
    public function getPrix(int $articleId, int $zoneId): ?float {
        $stmt = $this->pdo->prepare("SELECT prix FROM tarifs WHERE article_id = ? AND zone_id = ?");
        $stmt->execute([$articleId, $zoneId]);
        $result = $stmt->fetchColumn();
        return $result ? (float)$result : null;
    }
}
