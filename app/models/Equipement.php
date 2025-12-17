<?php

class Equipement {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM equipements ORDER BY nom ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM equipements WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO equipements (nom, description, quantite_en_service, quantite_en_reparation, quantite_hors_service, date_achat, valeur, fournisseur) 
             VALUES (:nom, :description, :qte_service, :qte_reparation, :qte_hors_service, :date_achat, :valeur, :fournisseur)"
        );
        return $stmt->execute([
            ':nom' => $data['nom'],
            ':description' => $data['description'] ?? null,
            ':qte_service' => $data['quantite_en_service'] ?? 0,
            ':qte_reparation' => $data['quantite_en_reparation'] ?? 0,
            ':qte_hors_service' => $data['quantite_hors_service'] ?? 0,
            ':date_achat' => $data['date_achat'] ?: null,
            ':valeur' => $data['valeur'] ?: null,
            ':fournisseur' => $data['fournisseur'] ?? null
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare(
            "UPDATE equipements SET 
                nom = :nom, 
                description = :description, 
                quantite_en_service = :qte_service,
                quantite_en_reparation = :qte_reparation,
                quantite_hors_service = :qte_hors_service,
                date_achat = :date_achat, 
                valeur = :valeur, 
                fournisseur = :fournisseur
             WHERE id = :id"
        );
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $data['nom'],
            ':description' => $data['description'] ?? null,
            ':qte_service' => $data['quantite_en_service'] ?? 0,
            ':qte_reparation' => $data['quantite_en_reparation'] ?? 0,
            ':qte_hors_service' => $data['quantite_hors_service'] ?? 0,
            ':date_achat' => $data['date_achat'] ?: null,
            ':valeur' => $data['valeur'] ?: null,
            ':fournisseur' => $data['fournisseur'] ?? null
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM equipements WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getGlobalStats() {
        $stmt = $this->pdo->query(
            "SELECT 
                SUM(quantite_en_service) as total_en_service, 
                SUM(quantite_en_reparation) as total_en_reparation,
                SUM(quantite_hors_service) as total_hors_service,
                SUM(quantite_en_service + quantite_en_reparation + quantite_hors_service) as total_equipements,
                SUM(valeur * (quantite_en_service + quantite_en_reparation + quantite_hors_service)) as total_valeur 
             FROM equipements"
        );
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
