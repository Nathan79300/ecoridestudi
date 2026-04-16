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
                $dsn = "mysql:host=localhost;port=3307;dbname=ecoride;charset=utf8mb4";

                self::$pdo = new PDO($dsn, "root", "", [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);

                self::$pdo->exec("SET NAMES utf8mb4");
            } catch (PDOException $e) {
                die("Erreur connexion DB : " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
