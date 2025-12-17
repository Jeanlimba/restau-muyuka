<?php

class Personnel {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function create($data) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO personnel (user_id, fonction_id, salaire, date_embauche) VALUES (:user_id, :fonction_id, :salaire, :date_embauche)"
        );
        return $stmt->execute([
            ':user_id' => $data['user_id'],
            ':fonction_id' => $data['fonction_id'],
            ':salaire' => $data['salaire'] ?? 0.00,
            ':date_embauche' => $data['date_embauche'] ?? null
        ]);
    }

    public function findByUserId($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM personnel WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update($userId, $data) {
        $stmt = $this->pdo->prepare(
            "UPDATE personnel SET fonction_id = :fonction_id, salaire = :salaire, date_embauche = :date_embauche WHERE user_id = :user_id"
        );
        return $stmt->execute([
            ':user_id' => $userId,
            ':fonction_id' => $data['fonction_id'],
            ':salaire' => $data['salaire'] ?? 0.00,
            ':date_embauche' => $data['date_embauche'] ?? null
        ]);
    }

    public function allWithUserDetails() {
        $sql = "SELECT 
                    u.id as user_id, 
                    u.nom, 
                    u.email, 
                    u.actif,
                    p.id as personnel_id,
                    f.nom as fonction,
                    p.salaire,
                    p.date_embauche
                FROM users u
                INNER JOIN personnel p ON u.id = p.user_id
                INNER JOIN fonctions f ON p.fonction_id = f.id
                WHERE u.actif = 1";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
