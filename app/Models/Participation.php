<?php

namespace Natom\Ecoride\Models;

use Natom\Ecoride\Core\Database;
use PDO;

class Participation
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function dejaReserve(int $trajetId, int $userId): bool
    {
        $sql = "SELECT COUNT(*) 
                FROM participations 
                WHERE id_trajet = :tid 
                AND id_utilisateur = :uid";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':tid' => $trajetId,
            ':uid' => $userId
        ]);

        return (int)$stmt->fetchColumn() > 0;
    }

    public function reserver(int $trajetId, int $userId): bool
    {
        $sql = "INSERT INTO participations (id_trajet, id_utilisateur)
                VALUES (:tid, :uid)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':tid' => $trajetId,
            ':uid' => $userId
        ]);
    }

    public function annuler(int $trajetId, int $userId): bool
    {
        $sql = "DELETE FROM participations
                WHERE id_trajet = :tid
                AND id_utilisateur = :uid";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':tid' => $trajetId,
            ':uid' => $userId
        ]);
    }

   
    public function getReservationsByUser(int $userId): array
    {
        $sql = "
            SELECT 
                p.id AS participation_id,
                t.*,
                u.username AS conducteur_username,
                u.photo AS conducteur_photo,
                u.note_moyenne AS conducteur_note_moyenne
            FROM participations p
            INNER JOIN trajets t ON t.id = p.id_trajet
            LEFT JOIN utilisateurs u ON u.id = t.conducteur_id
            WHERE p.id_utilisateur = :uid
            ORDER BY t.date_depart DESC, t.heure_depart DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
