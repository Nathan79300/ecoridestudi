<?php

namespace Natom\Ecoride\Core;

use MongoDB\Client;
use MongoDB\Database;
use RuntimeException;

class MongoDatabase
{
    private static ?Database $database = null;

    public static function getConnection(): Database
    {
        if (self::$database === null) {
            $uri = getenv('MONGODB_URI');

            if (!$uri) {
                throw new RuntimeException(
                    'La variable d environnement MONGODB_URI n est pas définie.'
                );
            }

            try {
                $client = new Client($uri);

                self::$database = $client->selectDatabase('ecoride');

            } catch (\Throwable $e) {
                throw new RuntimeException(
                    'Erreur de connexion MongoDB : ' . $e->getMessage()
                );
            }
        }

        return self::$database;
    }
}