<?php

class Table {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }
    
    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM tables WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getZonesWithTables(): array
    {
        $zoneModel = new Zone();
        $zones = $zoneModel->findAll();
        $result = [];
        foreach ($zones as $zone) {
            $zone['tables'] = $this->getTablesByZone($zone['id']);
            $result[] = $zone;
        }
        return $result;
    }
    
    public function create($data) {
        $zoneModel = new Zone();
        $zone = $zoneModel->find($data['zone_id']);
        
        if (!$zone || !isset($zone['prefixe'])) {
            throw new Exception("Zone ou préfixe de zone non trouvé.");
        }

        $numero = $this->getNextNumeroForZone($data['zone_id']);
        $nom = $zone['prefixe'] . $numero;

        $stmt = $this->pdo->prepare(
            "INSERT INTO tables (nom, numero, zone_id, capacite, statut) VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $nom, $numero, $data['zone_id'], $data['capacite'], 'libre'
        ]);
    }

    public function createMultiple(array $data): bool
    {
        $this->pdo->beginTransaction();
        try {
            $zoneModel = new Zone();
            $zone = $zoneModel->find($data['zone_id']);
            
            if (!$zone || !isset($zone['prefixe'])) {
                throw new Exception("Zone ou préfixe de zone non trouvé.");
            }

            $stmt = $this->pdo->prepare(
                "INSERT INTO tables (nom, numero, zone_id, capacite, statut) VALUES (?, ?, ?, ?, ?)"
            );
            for ($i = $data['numero_debut']; $i <= $data['numero_fin']; $i++) {
                $nom = $zone['prefixe'] . $i;
                $stmt->execute([$nom, $i, $data['zone_id'], $data['capacite'], 'libre']);
            }
            $this->pdo->commit();
            return true;
        } catch(Exception $e) {
            $this->pdo->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }
    
    public function update($id, $data) {
        $zone = (new Zone())->find($data['zone_id']);
        if (!$zone) {
            throw new Exception("Zone non trouvée.");
        }
        $nom = ($zone['prefixe'] ?? '') . $data['numero'];

        $stmt = $this->pdo->prepare(
            "UPDATE tables SET nom = ?, numero = ?, zone_id = ?, capacite = ? WHERE id = ?"
        );
        return $stmt->execute([
            $nom,
            $data['numero'],
            $data['zone_id'],
            $data['capacite'],
            $id
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("UPDATE tables SET actif = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function updateStatut($tableId, $statut) {
        $stmt = $this->pdo->prepare("UPDATE tables SET statut = ? WHERE id = ?");
        return $stmt->execute([$statut, $tableId]);
    }
    
    public function getTablesByZone(int $zoneId, ?string $statut = null)
    {
        $sql = "SELECT * FROM tables WHERE zone_id = ? AND actif = 1";
        $params = [$zoneId];

        if ($statut) {
            $sql .= " AND statut = ?";
            $params[] = $statut;
        }

        $sql .= " ORDER BY numero";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTablesByZoneAndStatuses(int $zoneId, array $statuses): array
    {
        if (empty($statuses)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($statuses), '?'));
        $sql = "SELECT * FROM tables WHERE zone_id = ? AND actif = 1 AND statut IN ({$placeholders}) ORDER BY numero";
        $params = array_merge([$zoneId], $statuses);

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getZonesWithTablesByStatuses(array $statuses): array
    {
        $zoneModel = new Zone();
        $zones = $zoneModel->findAll();
        $result = [];
        foreach ($zones as $zone) {
            $tables = $this->getTablesByZoneAndStatuses($zone['id'], $statuses);
            if (!empty($tables)) {
                $zone['tables'] = $tables;
                $result[] = $zone;
            }
        }
        return $result;
    }

    public function getZonesWithTablesAndSpecificStatus(string $status): array
    {
        $zoneModel = new Zone();
        $zones = $zoneModel->findAll();
        $result = [];
        foreach ($zones as $zone) {
            $tables = $this->getTablesByZone($zone['id'], $status);
            if (!empty($tables)) {
                $zone['tables'] = $tables;
                $result[] = $zone;
            }
        }
        return $result;
    }
}