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

  

    public function getByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM utilisateurs WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT * FROM utilisateurs WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

   

    public function updateProfile(int $id, string $prenom, string $nom, string $role): bool
    {
        $sql = "
            UPDATE utilisateurs 
            SET prenom = :prenom, nom = :nom, role = :role 
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':prenom' => $prenom,
            ':nom'    => $nom,
            ':role'   => $role,
            ':id'     => $id,
        ]);
    }

    public function getTrajets(int $userId): array
    {
        
        $sql = "SELECT * FROM trajets WHERE conducteur_id = :id ORDER BY date_depart DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

   

    public function createUser(string $prenom, string $nom, string $email, string $password): bool
    {
        $sql = "
            INSERT INTO utilisateurs (prenom, nom, email, mot_de_passe, role, credits, photo)
            VALUES (:prenom, :nom, :email, :password, 'utilisateur', 20, 'default.jpg')
        ";

        $stmt = $this->db->prepare($sql);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        return $stmt->execute([
            ':prenom'   => $prenom,
            ':nom'      => $nom,
            ':email'    => $email,
            ':password' => $hashedPassword,
        ]);
    }

   

    public function getCreditsById(int $id): int
    {
        $sql = "SELECT credits FROM utilisateurs WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

  
    public function setCredits(int $userId, int $credits): bool
    {
        $sql = "UPDATE utilisateurs SET credits = :credits WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':credits' => $credits,
            ':id'      => $userId,
        ]);
    }

  
    public function debitCredits(int $id, int $montant): bool
    {
        if ($montant <= 0) {
            return true;
        }

        $sql = "
            UPDATE utilisateurs
            SET credits = credits - :m1
            WHERE id = :id AND credits >= :m2
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':m1', $montant, PDO::PARAM_INT);
        $stmt->bindValue(':m2', $montant, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function crediterCredits(int $id, int $montant): bool
    {
        if ($montant <= 0) {
            return true;
        }

        $sql = "UPDATE utilisateurs SET credits = credits + :m WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':m'  => $montant,
            ':id' => $id,
        ]);
    }
}
