<?php

class Commande {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO commandes (vente_id, user_id, notes) VALUES (:vente_id, :user_id, :notes)"
        );
        if ($stmt->execute([
            ':vente_id' => $data['vente_id'],
            ':user_id' => $data['user_id'],
            ':notes' => $data['notes'] ?? null
        ])) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    public function findByVenteId($venteId) {
        $stmt = $this->pdo->prepare("SELECT * FROM commandes WHERE vente_id = ? ORDER BY date_commande DESC");
        $stmt->execute([$venteId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
