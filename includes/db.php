```php
<?php

/*
 * ============================================================
 * CONNEXION À LA BASE DE DONNÉES
 * ============================================================
 *
 * LOCAL :
 * XAMPP / MariaDB
 * localhost:3307
 *
 * PRODUCTION :
 * Render + Aiven MySQL
 *
 * Sur Render, la présence de DB_PASSWORD permet de détecter
 * automatiquement l'environnement de production.
 */


// ============================================================
// DÉTECTION DE L'ENVIRONNEMENT
// ============================================================

$isLocal = !empty($_SERVER['SERVER_NAME'])
    && in_array(
        $_SERVER['SERVER_NAME'],
        ['localhost', '127.0.0.1'],
        true
    );


// ============================================================
// CONFIGURATION LOCALE - XAMPP
// ============================================================

if ($isLocal) {

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

}


// ============================================================
// CONFIGURATION PRODUCTION - RENDER + AIVEN
// ============================================================

else {

    $dbHost = 'ecoride-db-nat-753a.l.aivencloud.com';
    $dbName = 'defaultdb';

    // Utilisateur Aiven
    $dbUser = 'avnadmin';

    // Mot de passe enregistré dans les variables
    // d'environnement de Render
    $dbPass = getenv('DB_PASSWORD');

    $dbPort = 20257;

    $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4;port={$dbPort}";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

        // Aiven impose une connexion SSL.
        // La vérification du certificat est désactivée ici
        // pour éviter un problème de certificat sur Render.
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    ];
}


// ============================================================
// CONNEXION PDO
// ============================================================

try {

    $pdo = new PDO(
        $dsn,
        $dbUser,
        $dbPass,
        $options
    );

} catch (PDOException $e) {

    die(
        "Erreur de connexion à la base de données : "
        . $e->getMessage()
    );
}
```
