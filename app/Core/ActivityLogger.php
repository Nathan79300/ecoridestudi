<?php

namespace Natom\Ecoride\Core;

use MongoDB\Collection;

class ActivityLogger
{
    private static ?Collection $collection = null;

    private static function getCollection(): Collection
    {
        if (self::$collection === null) {
            $database = MongoDatabase::getConnection();

            self::$collection = $database->selectCollection('activity_logs');
        }

        return self::$collection;
    }

    public static function log(
        string $action,
        ?int $userId = null,
        array $details = []
    ): void {
        self::getCollection()->insertOne([
            'action' => $action,
            'user_id' => $userId,
            'details' => $details,
            'created_at' => new \MongoDB\BSON\UTCDateTime(),
        ]);
    }
}