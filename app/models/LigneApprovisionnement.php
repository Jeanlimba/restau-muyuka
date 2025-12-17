<?php

class LigneApprovisionnement {

    /**
     * Crée les lignes d'articles pour un approvisionnement donné.
     * Doit être appelée à l'intérieur d'une transaction.
     *
     * @param int $approvisionnementId L'ID de l'approvisionnement parent.
     * @param array $lignes Les articles à ajouter.
     * @param PDO $pdo L'instance PDO pour la transaction.
     * @return bool
     */
    public static function createLignes(int $approvisionnementId, array $lignes, PDO $pdo): bool {
        $stmt = $pdo->prepare(
            "INSERT INTO lignes_approvisionnement (approvisionnement_id, article_id, quantite, prix_achat) 
             VALUES (:approvisionnement_id, :article_id, :quantite, :prix_achat)"
        );

        foreach ($lignes as $ligne) {
            $stmt->execute([
                ':approvisionnement_id' => $approvisionnementId,
                ':article_id' => $ligne['article_id'],
                ':quantite' => $ligne['quantite'],
                ':prix_achat' => $ligne['prix_achat']
            ]);
        }

        return true;
    }
}
