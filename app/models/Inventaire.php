<?php

class Inventaire {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function all() {
        $sql = "SELECT i.*, u.nom as responsable_nom 
                FROM inventaires i
                LEFT JOIN users u ON i.responsable_id = u.id
                ORDER BY i.date_inventaire DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM inventaires WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function findWithDetails($id) {
        $inventaire = $this->find($id);
        if (!$inventaire) return null;

        $sql = "SELECT li.*, a.nom as article_nom, um.nom as unite_mesure
                FROM lignes_inventaire li
                JOIN articles a ON li.article_id = a.id
                LEFT JOIN unites_mesure um ON a.unite_mesure_id = um.id
                WHERE li.inventaire_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $inventaire['lignes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $inventaire;
    }

    public function create($data) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO inventaires (date_inventaire, responsable_id, notes, conclusion) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$data['date_inventaire'], $data['responsable_id'], $data['notes'], $data['conclusion']]);
        return $this->pdo->lastInsertId();
    }
    
    public function updateStatus($id, $statut) {
        $stmt = $this->pdo->prepare("UPDATE inventaires SET statut = ? WHERE id = ?");
        return $stmt->execute([$statut, $id]);
    }
}
