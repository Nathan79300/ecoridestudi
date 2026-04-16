<?php

namespace Natom\Ecoride\Models;

use Natom\Ecoride\Core\Database;
use PDO;

class Avis
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function getValidatedByConducteur(int $conducteurId, int $limit = 10): array
    {
        $limit = max(1, min(50, $limit));

        $sql = "
            SELECT a.auteur, a.note, a.commentaire
            FROM avis a
            WHERE a.id_conducteur = :id
              AND a.valide = 1
            ORDER BY a.id DESC
            LIMIT :lim
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $conducteurId, PDO::PARAM_INT);
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function getMoyenneByConducteur(int $conducteurId): float
    {
        $sql = "
            SELECT AVG(note) AS moyenne
            FROM avis
            WHERE id_conducteur = :id AND valide = 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $conducteurId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return isset($row['moyenne']) && $row['moyenne'] !== null ? (float)$row['moyenne'] : 0.0;
    }

    public function countValidatedByConducteur(int $conducteurId): int
    {
        $sql = "
            SELECT COUNT(*) AS nb
            FROM avis
            WHERE id_conducteur = :id AND valide = 1
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $conducteurId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)($row['nb'] ?? 0);
    }
}
