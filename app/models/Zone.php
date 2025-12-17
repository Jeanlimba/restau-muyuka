<?php

class Zone {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function find(int $id) {
        $stmt = $this->pdo->prepare("SELECT * FROM zones WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findAll() {
        $stmt = $this->pdo->query("SELECT id, nom, prefixe, description FROM zones ORDER BY nom");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO zones (nom, prefixe, description) VALUES (?, ?, ?)");
        return $stmt->execute([$data['nom'], $data['prefixe'] ?? null, $data['description']]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("UPDATE zones SET nom = ?, prefixe = ?, description = ? WHERE id = ?");
        return $stmt->execute([$data['nom'], $data['prefixe'] ?? null, $data['description'], $id]);
    }
}
