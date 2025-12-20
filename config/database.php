<?php
// config/database.php

// Charger les variables d'environnement
$dotenv = parse_ini_file(__DIR__ . '/../.env');

// Configuration basée sur .env
return [
    'host' => $dotenv['DB_HOST'] ?? 'localhost',
    'dbname' => $dotenv['DB_NAME'] ?? 'db_muyak',
    'username' => $dotenv['DB_USER'] ?? 'root',
    'password' => $dotenv['DB_PASS'] ?? ''
];