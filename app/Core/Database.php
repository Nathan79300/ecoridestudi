<?php

namespace Natom\Ecoride\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO
    {
        if (self::$pdo === null) {
            try {

                // Détection de l'environnement
                $isLocal = !empty($_SERVER['SERVER_NAME'])
                    && in_array(
                        $_SERVER['SERVER_NAME'],
                        ['localhost', '127.0.0.1'],
                        true
                    );


                // ============================================================
                // LOCAL - XAMPP
                // ============================================================

                if ($isLocal) {

                    $dsn = "mysql:host=localhost;port=3307;dbname=ecoride;charset=utf8mb4";

                    $user = "root";
                    $password = "";

                    $options = [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                    ];

                }


                // ============================================================
                // PRODUCTION - RENDER + AIVEN
                // ============================================================

                else {

                    $dsn = "mysql:host=ecoride-db-nat-753a.l.aivencloud.com;port=20257;dbname=defaultdb;charset=utf8mb4";

                    $user = "avnadmin";
                    $password = getenv('DB_PASSWORD');

                    $options = [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,

                        // SSL Aiven
                        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
                    ];
                }


                // ============================================================
                // CONNEXION
                // ============================================================

                self::$pdo = new PDO(
                    $dsn,
                    $user,
                    $password,
                    $options
                );

                self::$pdo->exec("SET NAMES utf8mb4");

            } catch (PDOException $e) {

                die("Erreur connexion DB : " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}