<?php

namespace Natom\Ecoride\Models;

use Natom\Ecoride\Core\Database;
use PDO;

class Trajet
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Création d'un trajet
     */
    public function create(array $data)
    {
        $sql = "INSERT INTO trajets 
                (conducteur_id, ville_depart, ville_arrivee, date_depart, heure_depart, prix, etat, places_restantes)
                VALUES 
                (:conducteur_id, :ville_depart, :ville_arrivee, :date_depart, :heure_depart, :prix, 'prévu', :places_restantes)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':conducteur_id'    => $data['conducteur_id'],
            ':ville_depart'     => $data['ville_depart'],
            ':ville_arrivee'    => $data['ville_arrivee'],
            ':date_depart'      => $data['date_depart'],
            ':heure_depart'     => $data['heure_depart'],
            ':prix'             => $data['prix'],
            ':places_restantes' => $data['places_restantes'] ?? 3,
        ]);
    }

    /**
     * Récupère les trajets proposés par un conducteur
     */
    public function getByConducteur(int $id)
    {
        $sql = "SELECT * FROM trajets 
                WHERE conducteur_id = :id 
                ORDER BY date_depart DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recherche avancée complète
     */
    public function rechercheComplete(array $params)
    {
        $sql = "SELECT t.*, u.pseudo, u.prenom, u.nom, u.note_moyenne
                FROM trajets t
                JOIN utilisateurs u ON u.id = t.conducteur_id
                WHERE t.ville_depart = :ville_depart
                  AND t.ville_arrivee = :ville_arrivee
                  AND DATE(t.date_depart) = :date_depart
                  AND t.places_restantes >= :passagers";

        // Paramètres obligatoires
        $bind = [
            ':ville_depart' => $params['ville_depart'],
            ':ville_arrivee' => $params['ville_arrivee'],
            ':date_depart'   => $params['date_depart'],
            ':passagers'     => $params['passagers']
        ];

        // -------- FILTRES OPTIONNELS -----------

        if (!empty($params['ecologique'])) {
            $sql .= " AND t.ecologique = 1";
        }

        if (!empty($params['prix_max'])) {
            $sql .= " AND t.prix <= :prix_max";
            $bind[':prix_max'] = $params['prix_max'];
        }

        if (!empty($params['duree_max'])) {
            $sql .= " AND (TIME_TO_SEC(t.heure_arrivee) - TIME_TO_SEC(t.heure_depart)) <= (:duree_max * 60)";
            $bind[':duree_max'] = $params['duree_max'];
        }

        if (!empty($params['note_min'])) {
            $sql .= " AND u.note_moyenne >= :note_min";
            $bind[':note_min'] = $params['note_min'];
        }

        // ---------------------------------------

        $stmt = $this->db->prepare($sql);
        $stmt->execute($bind);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recherche exacte simple
     */
    public function search(string $depart, string $arrivee, string $date)
    {
        $sql = "SELECT t.*, u.prenom, u.nom
                FROM trajets t
                JOIN utilisateurs u ON u.id = t.conducteur_id
                WHERE t.ville_depart = :depart
                AND t.ville_arrivee = :arrivee
                AND t.date_depart = :date
                AND t.places_restantes > 0
                ORDER BY t.heure_depart ASC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':depart'  => $depart,
            ':arrivee' => $arrivee,
            ':date'    => $date
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Prochain trajet disponible
     */
    public function searchNext(string $depart, string $arrivee, string $date)
    {
        $sql = "SELECT t.*, u.prenom, u.nom
                FROM trajets t
                JOIN utilisateurs u ON u.id = t.conducteur_id
                WHERE t.ville_depart = :depart
                AND t.ville_arrivee = :arrivee
                AND t.date_depart > :date
                AND t.places_restantes > 0
                ORDER BY t.date_depart ASC, t.heure_depart ASC
                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':depart'  => $depart,
            ':arrivee' => $arrivee,
            ':date'    => $date
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Vérifie si des places restent
     */
    public function aDesPlaces(int $id): bool
    {
        $sql = "SELECT places_restantes 
                FROM trajets 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        $places = $stmt->fetchColumn();
        return $places > 0;
    }

    public function retirerUnePlace(int $id)
    {
        $sql = "UPDATE trajets 
                SET places_restantes = places_restantes - 1
                WHERE id = :id 
                AND places_restantes > 0";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Récupère un trajet par ID
     */
    public function getById(int $id)
    {
        $sql = "SELECT t.*, u.prenom, u.nom, u.pseudo
                FROM trajets t
                JOIN utilisateurs u ON u.id = t.conducteur_id
                WHERE t.id = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
