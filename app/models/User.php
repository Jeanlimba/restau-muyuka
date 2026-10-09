<?php

class User {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }
    
    public function authenticate($email, $password) {
        $stmt = $this->pdo->prepare(
            "SELECT u.id, u.nom, u.email, u.password, u.actif, f.nom AS fonction
             FROM users u
             LEFT JOIN personnel p ON u.id = p.user_id
             LEFT JOIN fonctions f ON p.fonction_id = f.id
             WHERE u.email = ? AND u.actif = 1"
        );
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
        
        return false;
    }
    
    // Fonction actuelle de l'utilisateur (nom), ou null s'il est inactif ou sans fonction
    public function getFonction($id) {
        $stmt = $this->pdo->prepare(
            "SELECT f.nom
             FROM users u
             JOIN personnel p ON u.id = p.user_id
             JOIN fonctions f ON p.fonction_id = f.id
             WHERE u.id = ? AND u.actif = 1"
        );
        $stmt->execute([$id]);
        return $stmt->fetchColumn() ?: null;
    }

    public function all() {
        $stmt = $this->pdo->query("SELECT id, nom, email, actif FROM users WHERE actif = 1");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($data) {
        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (nom, email, password) VALUES (?, ?, ?)"
        );
        $stmt->execute([
            $data['nom'], $data['email'], $passwordHash
        ]);
        
        return $this->pdo->lastInsertId();
    }

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare(
            "SELECT id, nom, email, actif FROM users WHERE email = ?"
        );
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT id, nom, email, actif FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $fields = [
            'nom' => $data['nom'],
            'email' => $data['email'],
        ];
        
        $sql = "UPDATE users SET nom = :nom, email = :email";
        
        if (!empty($data['password'])) {
            $fields['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            $sql .= ", password = :password";
        }
        
        $sql .= " WHERE id = :id";
        $fields['id'] = $id;

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($fields);
    }

    public function delete($id) {
        // Soft delete
        $stmt = $this->pdo->prepare("UPDATE users SET actif = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}