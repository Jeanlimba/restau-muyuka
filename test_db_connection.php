<?php
/**
 * Script de test de connexion à la base de données
 * À utiliser lors du déploiement sur EC2
 * 
 * Usage: php test_db_connection.php
 *        php test_db_connection.php --username=testuser --password=testpass
 *        php test_db_connection.php -u testuser -p testpass
 */

class DatabaseConnectionTest {
    private $config;
    private $pdo = null;
    private $testUsername = null;
    private $testPassword = null;
    
    public function __construct($username = null, $password = null) {
        // Charger la configuration de base
        $this->config = require __DIR__ . '/config/database.php';
        
        // Utiliser les identifiants de test si fournis
        $this->testUsername = $username;
        $this->testPassword = $password;
    }
    
    /**
     * Obtenir les identifiants à utiliser pour la connexion
     */
    private function getCredentials() {
        if ($this->testUsername !== null && $this->testPassword !== null) {
            return [
                'username' => $this->testUsername,
                'password' => $this->testPassword
            ];
        }
        
        // Sinon utiliser la configuration normale
        return [
            'username' => $this->config['username'],
            'password' => $this->config['password']
        ];
    }
    
    /**
     * Test 1: Vérifier la connexion à la base de données
     */
    public function testConnection() {
        echo "=== Test 1: Connexion à la base de données ===\n";
        
        $credentials = $this->getCredentials();
        
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->config['host']};dbname={$this->config['dbname']};charset=utf8mb4",
                $credentials['username'],
                $credentials['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
            
            echo "✓ Connexion réussie à la base de données\n";
            echo "  - Host: {$this->config['host']}\n";
            echo "  - Database: {$this->config['dbname']}\n";
            echo "  - Username: {$credentials['username']}\n";
            
            // Tester l'authentification avec une requête simple
            $stmt = $this->pdo->query("SELECT CURRENT_USER() as current_user");
            $user = $stmt->fetch()['current_user'];
            echo "  - Utilisateur connecté: {$user}\n";
            
            return true;
        } catch (PDOException $e) {
            echo "✗ Échec de la connexion: " . $e->getMessage() . "\n";
            echo "  Identifiants utilisés:\n";
            echo "  - Username: {$credentials['username']}\n";
            echo "  - Password: " . (str_repeat('*', min(8, strlen($credentials['password'])))) . "\n";
            echo "\n  Vérifiez:\n";
            echo "  1. Que le nom d'utilisateur existe dans MySQL\n";
            echo "  2. Que le mot de passe est correct\n";
            echo "  3. Que l'utilisateur a accès à la base '{$this->config['dbname']}'\n";
            echo "  4. Que l'utilisateur peut se connecter depuis l'adresse IP de l'EC2\n";
            return false;
        }
    }
    
    /**
     * Test 2: Vérifier les permissions spécifiques de l'utilisateur
     */
    public function testUserPermissions() {
        if (!$this->pdo) {
            echo "✗ Impossible de tester les permissions: pas de connexion\n";
            return false;
        }
        
        echo "\n=== Test 2: Permissions de l'utilisateur ===\n";
        
        try {
            // Récupérer les permissions de l'utilisateur courant
            $stmt = $this->pdo->query("SHOW GRANTS FOR CURRENT_USER()");
            $grants = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            echo "✓ Permissions de l'utilisateur:\n";
            foreach ($grants as $grant) {
                echo "  - " . $grant . "\n";
            }
            
            // Vérifier les permissions essentielles
            $essentialPermissions = ['SELECT', 'INSERT', 'UPDATE', 'DELETE'];
            $hasEssential = true;
            
            foreach ($essentialPermissions as $perm) {
                // Tester chaque permission
                try {
                    $testTable = "test_perms_" . uniqid();
                    $this->pdo->exec("CREATE TEMPORARY TABLE {$testTable} (id INT)");
                    
                    switch ($perm) {
                        case 'SELECT':
                            $this->pdo->query("SELECT * FROM {$testTable}");
                            break;
                        case 'INSERT':
                            $this->pdo->exec("INSERT INTO {$testTable} (id) VALUES (1)");
                            break;
                        case 'UPDATE':
                            $this->pdo->exec("UPDATE {$testTable} SET id = 2 WHERE id = 1");
                            break;
                        case 'DELETE':
                            $this->pdo->exec("DELETE FROM {$testTable} WHERE id = 2");
                            break;
                    }
                    
                    $this->pdo->exec("DROP TEMPORARY TABLE {$testTable}");
                    echo "  ✓ Permission {$perm}: OK\n";
                    
                } catch (PDOException $e) {
                    echo "  ⚠ Permission {$perm}: LIMITÉE - " . $e->getMessage() . "\n";
                    $hasEssential = false;
                }
            }
            
            return $hasEssential;
            
        } catch (PDOException $e) {
            echo "✗ Erreur lors de la vérification des permissions: " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    /**
     * Test 3: Vérifier l'accès aux tables
     */
    public function testTableAccess() {
        if (!$this->pdo) {
            echo "✗ Impossible de vérifier l'accès aux tables: pas de connexion\n";
            return false;
        }
        
        echo "\n=== Test 3: Accès aux tables ===\n";
        
        try {
            $stmt = $this->pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            if (empty($tables)) {
                echo "⚠ Aucune table trouvée dans la base '{$this->config['dbname']}'\n";
                echo "  Cela peut être normal pour une nouvelle installation\n";
            } else {
                echo "✓ Tables accessibles (" . count($tables) . "):\n";
                
                // Lister les premières tables
                $sampleTables = array_slice($tables, 0, 10);
                foreach ($sampleTables as $table) {
                    echo "  - {$table}\n";
                }
                
                if (count($tables) > 10) {
                    echo "  ... et " . (count($tables) - 10) . " autres\n";
                }
            }
            
            return true;
            
        } catch (PDOException $e) {
            echo "✗ Erreur lors de la vérification des tables: " . $e->getMessage() . "\n";
            return false;
        }
    }
    
    /**
     * Test 4: Tester des opérations CRUD complètes
     */
    public function testCrudOperations() {
        if (!$this->pdo) {
            echo "✗ Impossible de tester les opérations CRUD: pas de connexion\n";
            return false;
        }
        
        echo "\n=== Test 4: Opérations CRUD complètes ===\n";
        
        $testTable = "test_crud_" . uniqid();
        $allPassed = true;
        
        try {
            // Création
            $this->pdo->exec("CREATE TEMPORARY TABLE {$testTable} (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(50), value INT)");
            echo "✓ Création de table: OK\n";
            
            // Insertion
            $stmt = $this->pdo->prepare("INSERT INTO {$testTable} (name, value) VALUES (?, ?)");
            $stmt->execute(['test1', 100]);
            $lastId = $this->pdo->lastInsertId();
            echo "✓ Insertion: OK (ID: {$lastId})\n";
            
            // Lecture
            $stmt = $this->pdo->query("SELECT * FROM {$testTable} WHERE id = {$lastId}");
            $row = $stmt->fetch();
            if ($row && $row['name'] === 'test1') {
                echo "✓ Lecture: OK\n";
            } else {
                echo "✗ Lecture: ÉCHEC\n";
                $allPassed = false;
            }
            
            // Mise à jour
            $stmt = $this->pdo->prepare("UPDATE {$testTable} SET value = ? WHERE id = ?");
            $stmt->execute([200, $lastId]);
            $affected = $stmt->rowCount();
            if ($affected === 1) {
                echo "✓ Mise à jour: OK\n";
            } else {
                echo "✗ Mise à jour: ÉCHEC\n";
                $allPassed = false;
            }
            
            // Suppression
            $stmt = $this->pdo->prepare("DELETE FROM {$testTable} WHERE id = ?");
            $stmt->execute([$lastId]);
            $affected = $stmt->rowCount();
            if ($affected === 1) {
                echo "✓ Suppression: OK\n";
            } else {
                echo "✗ Suppression: ÉCHEC\n";
                $allPassed = false;
            }
            
            // Nettoyage
            $this->pdo->exec("DROP TEMPORARY TABLE {$testTable}");
            
        } catch (PDOException $e) {
            echo "✗ Opérations CRUD: ÉCHEC - " . $e->getMessage() . "\n";
            $allPassed = false;
            
            // Nettoyer en cas d'erreur
            try {
                $this->pdo->exec("DROP TEMPORARY TABLE IF EXISTS {$testTable}");
            } catch (PDOException $e2) {
                // Ignorer l'erreur de nettoyage
            }
        }
        
        return $allPassed;
    }
    
    /**
     * Exécuter tous les tests
     */
    public function runAllTests() {
        echo "========================================\n";
        echo "Test de connexion à la base de données\n";
        echo "========================================\n\n";
        
        $credentials = $this->getCredentials();
        echo "Configuration de test:\n";
        echo "- Host: {$this->config['host']}\n";
        echo "- Database: {$this->config['dbname']}\n";
        echo "- Username: {$credentials['username']}\n";
        echo "- Using test credentials: " . ($this->testUsername !== null ? "YES" : "NO") . "\n\n";
        
        $results = [];
        
        $results['connection'] = $this->testConnection();
        
        if ($results['connection']) {
            $results['permissions'] = $this->testUserPermissions();
            $results['table_access'] = $this->testTableAccess();
            $results['crud_operations'] = $this->testCrudOperations();
        }
        
        echo "\n========================================\n";
        echo "Résumé des tests\n";
        echo "========================================\n";
        
        $passed = 0;
        $total = count($results);
        
        foreach ($results as $test => $result) {
            $status = $result ? '✓ PASSÉ' : '✗ ÉCHOUÉ';
            echo "{$test}: {$status}\n";
            if ($result) $passed++;
        }
        
        echo "\nScore: {$passed}/{$total} tests passés\n";
        
        if ($passed === $total) {
            echo "\n✅ Tous les tests sont passés avec succès !\n";
            echo "L'utilisateur '{$credentials['username']}' a les permissions nécessaires.\n";
            return 0; // Code de sortie pour succès
        } else {
            echo "\n❌ Certains tests ont échoué.\n";
            echo "L'utilisateur '{$credentials['username']}' peut avoir des permissions insuffisantes.\n";
            return 1; // Code de sortie pour échec
        }
    }
}

// Fonction pour analyser les arguments de ligne de commande
function parseArguments($argv) {
    $options = [
        'username' => null,
        'password' => null
    ];
    
    for ($i = 1; $i < count($argv); $i++) {
        if ($argv[$i] === '--username' && isset($argv[$i + 1])) {
            $options['username'] = $argv[++$i];
        } elseif ($argv[$i] === '--password' && isset($argv[$i + 1])) {
            $options['password'] = $argv[++$i];
        } elseif ($argv[$i] === '-u' && isset($argv[$i + 1])) {
            $options['username'] = $argv[++$i];
        } elseif ($argv[$i] === '-p' && isset($argv[$i + 1])) {
            $options['password'] = $argv[++$i];
        } elseif (strpos($argv[$i], '--username=') === 0) {
            $options['username'] = substr($argv[$i], 11);
        } elseif (strpos($argv[$i], '--password=') === 0) {
            $options['password'] = substr($argv[$i], 11);
        }
    }
    
    return $options;
}

// Exécution du script
if (php_sapi_name() === 'cli') {
    $options = parseArguments($argv);
    
    $tester = new DatabaseConnectionTest($options['username'], $options['password']);
    exit($tester->runAllTests());
} else {
    echo "<pre>";
    
    // Pour l'exécution web, on peut passer les paramètres via GET
    $username = $_GET['username'] ?? null;
    $password = $_GET['password'] ?? null;
    
    $tester = new DatabaseConnectionTest($username, $password);
    $tester->runAllTests();
    echo "</pre>";
}