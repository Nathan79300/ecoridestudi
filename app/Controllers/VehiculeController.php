<?php

namespace Natom\Ecoride\Controllers;

use Natom\Ecoride\Core\Controller;
use PDO;

class VehiculeController extends Controller
{
    private PDO $pdo;

    public function __construct()
    {
        // DB
        require __DIR__ . '/../../includes/db.php';
        $this->pdo = $pdo;

        // Session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Sécurité : utilisateur connecté
        if (!isset($_SESSION['utilisateur_id'])) {
            header("Location: index.php?url=connexion");
            exit;
        }

        // Sécurité : uniquement chauffeur ou les deux
        $role = $_SESSION['role'] ?? 'utilisateur';
        if (!in_array($role, ['chauffeur', 'passager_chauffeur'], true)) {
            header("Location: index.php?url=profil");
            exit;
        }
    }

  
    public function index(): void
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM vehicules
            WHERE id_utilisateur = ?
            ORDER BY id DESC
        ");
        $stmt->execute([$_SESSION['utilisateur_id']]);
        $vehicules = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('vehicules/index', [
            'vehicules' => $vehicules
        ]);
    }

 
    public function ajouter(): void
    {
        $erreur = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $immatriculation = strtoupper(trim($_POST['immatriculation'] ?? ''));
            $date_immat      = trim($_POST['date_immat'] ?? '');
            $marque          = trim($_POST['marque'] ?? '');
            $modele          = trim($_POST['modele'] ?? '');
            $energie         = trim($_POST['energie'] ?? '');
            $couleur         = trim($_POST['couleur'] ?? '');
            $places          = (int)($_POST['places'] ?? 1);
            $fumeurs         = isset($_POST['fumeurs']) ? 1 : 0;

            
            if ($immatriculation === '' || $marque === '' || $modele === '' || $energie === '' || $places < 1) {
                $erreur = "❌ Merci de remplir les champs obligatoires (immatriculation, marque, modèle, énergie, places).";
            } else {

               
                $check = $this->pdo->prepare("
                    SELECT id FROM vehicules
                    WHERE id_utilisateur = ? AND immatriculation = ?
                    LIMIT 1
                ");
                $check->execute([$_SESSION['utilisateur_id'], $immatriculation]);

                if ($check->fetch()) {
                    $erreur = "❌ Cette immatriculation existe déjà dans tes véhicules.";
                } else {

                    $stmt = $this->pdo->prepare("
                        INSERT INTO vehicules
                        (id_utilisateur, immatriculation, date_immat, marque, modele, energie, couleur, places, fumeurs)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");

                    $stmt->execute([
                        $_SESSION['utilisateur_id'],
                        $immatriculation,
                        $date_immat !== '' ? $date_immat : null,
                        $marque,
                        $modele,
                        $energie,
                        $couleur !== '' ? $couleur : null,
                        $places,
                        $fumeurs
                    ]);

                    header("Location: index.php?url=vehicules");
                    exit;
                }
            }
        }

        $this->render('vehicules/ajouter', [
            'erreur' => $erreur,
            'success' => $success
        ]);
    }

   
    public function supprimer(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $stmt = $this->pdo->prepare("
                DELETE FROM vehicules
                WHERE id = ? AND id_utilisateur = ?
            ");
            $stmt->execute([$id, $_SESSION['utilisateur_id']]);
        }

        header("Location: index.php?url=vehicules");
        exit;
    }
}
