<?php

$server = $_SERVER['SERVER_NAME'] ?? '';
$isLocal = in_array($server, ['localhost', '127.0.0.1'], true);

// --- CONFIG LOCAL ---
if ($isLocal) {
    $dbHost = 'localhost';
    $dbName = 'ecoride';
    $dbUser = 'root';
    $dbPass = '';
    $dbPort = 3307; // ton port XAMPP
}
// --- CONFIG PROD (InfinityFree) ---
else {
    $dbHost = 'sql100.infinityfree.com';
    $dbName = 'if0_41196046_ecoride';
    $dbUser = 'if0_41196046';
    $dbPass = 'Ecoride2026';
    $dbPort = 3306;
}

try {
    $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4;port={$dbPort}";

    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

} catch (PDOException $e) {
    die("Erreur connexion DB : " . $e->getMessage());
}
