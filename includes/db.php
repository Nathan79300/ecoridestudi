<?php

$server = $_SERVER['SERVER_NAME'] ?? '';
$isLocal = in_array($server, ['localhost', '127.0.0.1'], true);

if ($isLocal) {

    // ==========================================
    // CONFIGURATION LOCALE - XAMPP
    // ==========================================

    $dbHost = 'localhost';
    $dbName = 'ecoride';
    $dbUser = 'root';
    $dbPass = '';
    $dbPort = 3307;

    $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4;port={$dbPort}";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

} else {

    // ==========================================
    // CONFIGURATION PRODUCTION - RENDER + AIVEN
    // ==========================================

    $dbHost = 'ecoride-db-nat-753a.l.aivencloud.com';
    $dbName = 'defaultdb';
    $dbUser = 'ecoride';
    $dbPass = getenv('DB_PASSWORD');
    $dbPort = 20257;

    $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4;port={$dbPort}";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    ];
}

try {

    $pdo = new PDO(
        $dsn,
        $dbUser,
        $dbPass,
        $options
    );

} catch (PDOException $e) {

    die("Erreur connexion DB : " . $e->getMessage());
}