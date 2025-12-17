<?php
session_start();
require_once '../utils/helpers.php';
require_once '../app/core/Database.php';

echo "<h1>Debug Session et BDD</h1>";

// Test BDD
try {
    $db = new Database();
    $users = $db->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
    echo "<h3>Utilisateurs en BDD:</h3>";
    echo "<pre>";
    print_r($users);
    echo "</pre>";
} catch (Exception $e) {
    echo "Erreur BDD: " . $e->getMessage();
}

// Test Session
echo "<h3>Session actuelle:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Test mot de passe
echo "<h3>Test mot de passe:</h3>";
$test = password_verify('password', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
echo "Password verify: " . ($test ? 'OK' : 'ERREUR');