<?php

class DashboardController {
    public function index() {
        // Statistiques analytiques pour le dashboard
        $stats = [
            'tables_occupees' => $this->getTablesOccupeesCount(),
            'total_tables' => $this->getTotalTables(),
            'chiffre_affaires' => $this->getChiffreAffaires(),
            'ventes_du_jour' => $this->getVentesDuJour(),
            'zones_actives' => 3, // Salle, Terrasse, VIP
            'top_articles' => $this->getTopArticles(),
            'performance_zones' => $this->getPerformanceZones()
        ];
        
        view('dashboard/index', ['stats' => $stats]);
    }
    
    private function getTablesOccupeesCount() {
        $pdo = Database::getInstance()->getConnection();
        return $pdo->query("SELECT COUNT(*) FROM tables WHERE statut = 'occupée' AND actif = 1")->fetchColumn() ?? 0;
    }
    
    private function getTotalTables() {
        $pdo = Database::getInstance()->getConnection();
        return $pdo->query("SELECT COUNT(*) FROM tables WHERE actif = 1")->fetchColumn() ?? 30;
    }
    
    private function getChiffreAffaires() {
        $pdo = Database::getInstance()->getConnection();
        // Correction du nom de la colonne de date
        return $pdo->query(
            "SELECT SUM(total) as ca FROM ventes WHERE DATE(created_at) = CURDATE()"
        )->fetchColumn() ?? 0;
    }
    
    private function getVentesDuJour() {
        $pdo = Database::getInstance()->getConnection();
        // Correction du nom de la colonne de date
        return $pdo->query(
            "SELECT COUNT(*) as count FROM ventes WHERE DATE(created_at) = CURDATE()"
        )->fetchColumn() ?? 0;
    }
    
    private function getTopArticles() {
        // Placeholder pour les top articles (peut être récupéré de la DB plus tard)
        return [
            ['nom' => 'Café Expresso', 'quantite' => 45, 'total' => 225.00],
            ['nom' => 'Croissant', 'quantite' => 32, 'total' => 96.00],
            ['nom' => 'Sandwich Jambon', 'quantite' => 28, 'total' => 196.00],
            ['nom' => 'Eau Minérale', 'quantite' => 25, 'total' => 75.00],
            ['nom' => 'Tarte aux Pommes', 'quantite' => 18, 'total' => 90.00]
        ];
    }
    
    private function getPerformanceZones() {
        // Placeholder pour les performances par zone
        return [
            'salle' => ['ventes' => 45, 'ca' => 1125.00],
            'terrasse' => ['ventes' => 35, 'ca' => 875.00],
            'vip' => ['ventes' => 20, 'ca' => 600.00]
        ];
    }

    public function debugSchema() {
        $pdo = Database::getInstance()->getConnection();
        $stmt = $pdo->query("DESCRIBE articles");
        $schema = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<pre>";
        print_r($schema);
        echo "</pre>";
        exit;
    }
}
