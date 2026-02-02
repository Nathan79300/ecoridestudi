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
                self::$pdo = new PDO(
                    "mysql:host=localhost;port=3307;dbname=ecoride;charset=utf8",
                    "root",
                    ""
                );
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // DEBUG : voir quelle base est utilisée
                var_dump("Connecté à la base :", self::$pdo->query("SELECT DATABASE()")->fetchColumn());

            } catch (PDOException $e) {
                die("Erreur connexion DB : " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
