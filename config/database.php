<?php
// config/database.php

// Charger les variables d'environnement
$dotenv = parse_ini_file(__DIR__ . '/../.env');

// Configuration basée sur .env
return [
    'host' => 'localhost',
    'dbname' => 'db_muyak',
    'username' => 'root',
    'password' =>  ''
];