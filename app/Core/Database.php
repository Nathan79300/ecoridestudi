<?php

namespace Natom\Ecoride\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {

            $server = $_SERVER['SERVER_NAME'] ?? '';

            $isLocal = in_array(
                $server,
                ['localhost', '127.0.0.1'],
                true
            );

            // =========================
            // CONNEXION LOCALE
            // =========================
            if ($isLocal) {

                $dbHost = getenv('DB_HOST') ?: 'localhost';
                $dbName = 'ecoride';
                $dbUser = 'root';
                $dbPass = '';
                $dbPort = 3307;

                $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";

                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ];

            // =========================
            // CONNEXION PRODUCTION AIVEN
            // =========================
            } else {

                $dbHost = 'ecoride-db-nat-753a.aivencloud.com';
                $dbName = 'defaultdb';
                $dbUser = 'avnadmin';
                $dbPass = getenv('DB_PASSWORD');
                $dbPort = 20257;

                $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";

                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

                    // Aiven exige une connexion SSL
                    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
                ];
            }

            // =========================
            // CONNEXION PDO
            // =========================
            try {

                self::$connection = new PDO(
                    $dsn,
                    $dbUser,
                    $dbPass,
                    $options
                );

            } catch (PDOException $e) {

                die(
                    "Erreur connexion DB : " . $e->getMessage()
                );
            }
        }

        return self::$connection;
    }
}