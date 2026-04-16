<?php

namespace Natom\Ecoride\Controllers;

use Natom\Ecoride\Core\Controller;

class EmployeController extends Controller
{
    private function requireEmploye(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? null) !== 'employe') {
            header("Location: index.php?url=connexionEmploye");
            exit;
        }
    }

    public function index(): void
    {
        $this->requireEmploye();
        $this->render("pages/espace_employe");
    }

    public function connexion(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->render("pages/connexion_employe");
    }

    // ✅ POST : valider/refuser avis
    public function validerAvis(): void
    {
        $this->requireEmploye();
        require_once(__DIR__ . '/../../includes/db.php');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['avis_id']) && !empty($_POST['action'])) {

            $avis_id = (int)$_POST['avis_id'];
            $action  = $_POST['action'];

            if ($action === 'valider') {
                $pdo->prepare("UPDATE avis SET valide = 1 WHERE id = ?")->execute([$avis_id]);
            } elseif ($action === 'refuser') {
                $pdo->prepare("UPDATE avis SET valide = -1 WHERE id = ?")->execute([$avis_id]);
            }
        }

        header("Location: index.php?url=employe");
        exit;
    }

    // ✅ POST : marquer signalé traité
    public function marquerSignaleTraite(): void
    {
        $this->requireEmploye();
        require_once(__DIR__ . '/../../includes/db.php');

        $avis_id = (int)($_POST['avis_id'] ?? 0);
        if ($avis_id > 0) {
            $pdo->prepare("UPDATE avis SET traite = 1 WHERE id = ?")->execute([$avis_id]);
        }

        header("Location: index.php?url=employe");
        exit;
    }
}
