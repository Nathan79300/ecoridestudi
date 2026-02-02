<?php

namespace Natom\Ecoride\Models;

use Natom\Ecoride\Core\Database;
use PDO;

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Récupérer un utilisateur via email
    public function getByEmail(string $email)
    {
        $sql = "SELECT * FROM utilisateurs WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Récupérer un utilisateur via ID
    public function getById(int $id)
    {
        $sql = "SELECT * FROM utilisateurs WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mettre à jour un profil
    public function updateProfile(int $id, string $prenom, string $nom, string $role)
    {
        $sql = "UPDATE utilisateurs 
                SET prenom = :prenom, nom = :nom, role = :role 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    // Récupérer les trajets d’un chauffeur
    public function getTrajets(int $user_id)
    {
        // ATTENTION: La vraie colonne = conducteur_id
        $sql = "SELECT * FROM trajets WHERE conducteur_id = :id ORDER BY date_depart DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function createUser(string $prenom, string $nom, string $email, string $password)
{
    $sql = "INSERT INTO utilisateurs (prenom, nom, email, mot_de_passe, role, credits, photo)
            VALUES (:prenom, :nom, :email, :password, 'utilisateur', 20, 'default.jpg')";

    $stmt = $this->db->prepare($sql);

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt->execute([
        ':prenom' => $prenom,
        ':nom' => $nom,
        ':email' => $email,
        ':password' => $hashedPassword,
    ]);
}

}
