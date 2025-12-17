<?php

class UniteMesure {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }
    
    public function findAll() {
        $stmt = $this->pdo->query("SELECT * FROM unites_mesure WHERE actif = 1 ORDER BY nom");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByType(string $type) {
        $stmt = $this->pdo->prepare("SELECT * FROM unites_mesure WHERE type = ? AND actif = 1 ORDER BY nom");
        $stmt->execute([$type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM unites_mesure WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO unites_mesure (nom, symbole, type) VALUES (?, ?, ?)"
        );
        $stmt->execute(
            [$data['nom'], $data['symbole'], $data['type']]
        );
        return $this->pdo->lastInsertId();
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("UPDATE unites_mesure SET actif = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare(
            "UPDATE unites_mesure SET nom = ?, symbole = ?, type = ? WHERE id = ?"
        );
        return $stmt->execute(
            [$data['nom'], $data['symbole'], $data['type'], $id]
        );
    }
}