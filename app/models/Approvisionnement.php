<?php

class Approvisionnement {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    // CREATE
    public function create(array $data) {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO approvisionnements (numero_bon, date_approvisionnement, fournisseur, user_id, observation, created_at) 
                 VALUES (:numero_bon, :date_approvisionnement, :fournisseur, :user_id, :observation, NOW())"
            );
            $stmt->execute([
                ':numero_bon' => $data['numero_bon'],
                ':date_approvisionnement' => $data['date_approvisionnement'],
                ':fournisseur' => $data['fournisseur'],
                ':user_id' => $data['user_id'],
                ':observation' => $data['observation']
            ]);
            $approvisionnementId = $this->pdo->lastInsertId();
            LigneApprovisionnement::createLignes($approvisionnementId, $data['lignes'], $this->pdo);
            return $approvisionnementId;
        } catch (PDOException $e) {
            throw $e;
        }
    }

    // READ (Grouped for Index)
    public function findAllGrouped() {
        $stmt = $this->pdo->query(
            "SELECT 
                a.id, a.numero_bon, a.date_approvisionnement, a.fournisseur, u.nom AS user_nom,
                COUNT(la.id) as count_articles,
                SUM(la.quantite * la.prix_achat) as total_achat
             FROM approvisionnements a
             JOIN lignes_approvisionnement la ON a.id = la.approvisionnement_id
             JOIN users u ON a.user_id = u.id
             GROUP BY a.id
             ORDER BY a.date_approvisionnement DESC, a.id DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // READ (Single with details)
    public function findWithLignes(int $id) {
        $stmt = $this->pdo->prepare("SELECT * FROM approvisionnements WHERE id = ?");
        $stmt->execute([$id]);
        $appro = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($appro) {
            $stmt_lignes = $this->pdo->prepare(
                "SELECT la.*, art.nom as article_nom 
                 FROM lignes_approvisionnement la
                 JOIN articles art ON la.article_id = art.id
                 WHERE la.approvisionnement_id = ?"
            );
            $stmt_lignes->execute([$id]);
            $appro['lignes'] = $stmt_lignes->fetchAll(PDO::FETCH_ASSOC);
        }
        return $appro;
    }

    // UPDATE
    public function update(int $id, array $data) {
        $stmt = $this->pdo->prepare(
            "UPDATE approvisionnements 
             SET numero_bon = :numero_bon, date_approvisionnement = :date_approvisionnement, 
                 fournisseur = :fournisseur, observation = :observation
             WHERE id = :id"
        );
        return $stmt->execute([
            ':numero_bon' => $data['numero_bon'],
            ':date_approvisionnement' => $data['date_approvisionnement'],
            ':fournisseur' => $data['fournisseur'],
            ':observation' => $data['observation'],
            ':id' => $id
        ]);
    }

    // DELETE
    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM approvisionnements WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
