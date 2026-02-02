<?php

namespace Natom\Ecoride\Models;

use Natom\Ecoride\Core\Database;
use PDO;

class Participation
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Vérifie si l’utilisateur a déjà réservé ce trajet
     */
    public function dejaReserve(int $trajet_id, int $user_id): bool
    {
        $sql = "SELECT id 
                FROM participations 
                WHERE id_trajet = :trajet_id 
                AND id_utilisateur = :user_id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':trajet_id' => $trajet_id,
            ':user_id'   => $user_id
        ]);

        return $stmt->fetch() !== false;
    }

    /**
     * Réserve un trajet pour un utilisateur
     */
    public function reserver(int $trajet_id, int $user_id): bool
    {
        $sql = "INSERT INTO participations (id_trajet, id_utilisateur)
                VALUES (:trajet_id, :user_id)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':trajet_id' => $trajet_id,
            ':user_id'   => $user_id
        ]);
    }
}
