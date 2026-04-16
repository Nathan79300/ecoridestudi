<?php

namespace Natom\Ecoride\Models;

use Natom\Ecoride\Core\Database;
use PDO;

class Trajet
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function create(array $data): bool
    {
        $sql = "
            INSERT INTO trajets (conducteur_id, ville_depart, ville_arrivee, date_depart, heure_depart, prix, places_restantes, ecologique, etat)
            VALUES (:conducteur_id, :ville_depart, :ville_arrivee, :date_depart, :heure_depart, :prix, :places_restantes, :ecologique, 'prévu')
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':conducteur_id'    => (int)$data['conducteur_id'],
            ':ville_depart'     => $data['ville_depart'],
            ':ville_arrivee'    => $data['ville_arrivee'],
            ':date_depart'      => $data['date_depart'],
            ':heure_depart'     => $data['heure_depart'],
            ':prix'             => (int)$data['prix'],
            ':places_restantes' => (int)$data['places_restantes'],
            ':ecologique'       => isset($data['ecologique']) ? (int)$data['ecologique'] : 0,
        ]);
    }

    public function getById(int $id): ?array
    {
        $sql = "
            SELECT t.*,
                   u.username AS conducteur_username,
                   u.photo AS conducteur_photo,
                   u.note_moyenne AS conducteur_note_moyenne
            FROM trajets t
            LEFT JOIN utilisateurs u ON u.id = t.conducteur_id
            WHERE t.id = :id
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getPrix(int $trajetId): int
    {
        $sql = "SELECT prix FROM trajets WHERE id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $trajetId, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function aDesPlaces(int $trajetId): bool
    {
        $sql = "SELECT places_restantes FROM trajets WHERE id = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $trajetId, PDO::PARAM_INT);
        $stmt->execute();
        return ((int)$stmt->fetchColumn()) > 0;
    }

    public function retirerUnePlace(int $trajetId): bool
    {
        $sql = "
            UPDATE trajets 
            SET places_restantes = places_restantes - 1 
            WHERE id = :id AND places_restantes > 0
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $trajetId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function ajouterUnePlace(int $trajetId): bool
    {
        $sql = "UPDATE trajets SET places_restantes = places_restantes + 1 WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $trajetId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function rechercheComplete(array $params): array
    {
        $sql = "
            SELECT t.*,
                   u.username AS conducteur_username,
                   u.photo AS conducteur_photo,
                   u.note_moyenne AS conducteur_note_moyenne
            FROM trajets t
            LEFT JOIN utilisateurs u ON u.id = t.conducteur_id
            WHERE t.ville_depart = :vd
              AND t.ville_arrivee = :va
              AND t.date_depart = :dd
              AND t.places_restantes >= :passagers
        ";

        $bind = [
            ':vd' => $params['ville_depart'],
            ':va' => $params['ville_arrivee'],
            ':dd' => $params['date_depart'],
            ':passagers' => (int)$params['passagers'],
        ];

        if (!empty($params['ecologique'])) {
            $sql .= " AND t.ecologique = 1 ";
        }
        if (!empty($params['prix_max'])) {
            $sql .= " AND t.prix <= :prix_max ";
            $bind[':prix_max'] = (int)$params['prix_max'];
        }
        if (!empty($params['note_min'])) {
            $sql .= " AND COALESCE(u.note_moyenne, 0) >= :note_min ";
            $bind[':note_min'] = (int)$params['note_min'];
        }

        $sql .= " ORDER BY t.heure_depart ASC ";

        $stmt = $this->pdo->prepare($sql);
        foreach ($bind as $k => $v) {
            $stmt->bindValue($k, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function searchNext(string $villeDepart, string $villeArrivee, string $dateDepart): ?array
    {
        $sql = "
            SELECT * FROM trajets
            WHERE ville_depart = :vd
              AND ville_arrivee = :va
              AND date_depart > :dd
            ORDER BY date_depart ASC, heure_depart ASC
            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':vd' => $villeDepart,
            ':va' => $villeArrivee,
            ':dd' => $dateDepart
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

   
    public function getByConducteur(int $conducteurId): array
    {
        $sql = "
            SELECT t.*,
                   u.username AS conducteur_username,
                   u.photo AS conducteur_photo,
                   u.note_moyenne AS conducteur_note_moyenne
            FROM trajets t
            LEFT JOIN utilisateurs u ON u.id = t.conducteur_id
            WHERE t.conducteur_id = :id
            ORDER BY t.date_depart DESC, t.heure_depart DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $conducteurId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

   
    public function getReservationsByUser(int $userId): array
    {
        $sql = "
            SELECT 
                r.id AS reservation_id,
                r.statut AS reservation_statut,
                r.date_reservation,
                t.*,
                u.username AS conducteur_username,
                u.photo AS conducteur_photo,
                u.note_moyenne AS conducteur_note_moyenne
            FROM reservations r
            INNER JOIN trajets t ON t.id = r.trajet_id
            LEFT JOIN utilisateurs u ON u.id = t.conducteur_id
            WHERE r.utilisateur_id = :uid
            ORDER BY t.date_depart DESC, t.heure_depart DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
