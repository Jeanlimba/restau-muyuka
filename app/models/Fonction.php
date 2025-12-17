<?php

class Fonction {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM fonctions ORDER BY nom ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO fonctions (nom) VALUES (?)");
        if ($stmt->execute([$data['nom']])) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }
}
