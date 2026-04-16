<?php

namespace Natom\Ecoride\Controllers;

use Natom\Ecoride\Core\Controller;

class AdminController extends Controller
{
    private function requireAdmin(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? null) !== 'admin') {
            header("Location: index.php?url=connexionAdmin");
            exit;
        }
    }

    public function index(): void
    {
        $this->requireAdmin();
        $this->render("pages/espace_admin");
    }

    public function connexion(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->render("pages/connexion_admin");
    }

    public function ajouterEmploye(): void
    {
        $this->requireAdmin();
        $this->render("pages/ajouter_employe");
    }

    public function suspendreCompte(): void
    {
        $this->requireAdmin();
        $this->render("pages/suspendre_compte");
    }

    public function reactiverCompte(): void
    {
        $this->requireAdmin();
        $this->render("pages/reactiver_compte");
    }

    public function traiterSuspension(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        require_once __DIR__ . '/../../includes/db.php';

        header('Content-Type: application/json; charset=utf-8');

        try {
            if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? null) !== 'admin') {
                echo json_encode([
                    'success' => false,
                    'message' => 'Accès refusé.'
                ]);
                exit;
            }

            $id = (int)($_POST['id'] ?? 0);
            $action = $_POST['action'] ?? '';

            if ($id <= 0 || !in_array($action, ['suspendre', 'reactiver'], true)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Données invalides.'
                ]);
                exit;
            }

            $value = ($action === 'suspendre') ? 1 : 0;

            $stmt = $pdo->prepare("UPDATE utilisateurs SET suspendu = ? WHERE id = ?");
            $ok = $stmt->execute([$value, $id]);

            if ($ok) {
                $logFile = dirname(__DIR__, 2) . '/logs.json';

                if (!file_exists($logFile)) {
                    file_put_contents(
                        $logFile,
                        json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                    );
                }

                $content = file_get_contents($logFile);
                $logs = json_decode($content, true);

                if (!is_array($logs)) {
                    $logs = [];
                }

                $logs[] = [
                    'date' => date('Y-m-d H:i:s'),
                    'user' => $_SESSION['user']['username'] ?? ($_SESSION['user']['email'] ?? 'admin'),
                    'role' => $_SESSION['user']['role'] ?? 'admin',
                    'action' => $action,
                    'user_id' => $id
                ];

                file_put_contents(
                    $logFile,
                    json_encode($logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                );
            }

            echo json_encode([
                'success' => $ok,
                'message' => $ok
                    ? ($action === 'suspendre'
                        ? 'Compte suspendu avec succès.'
                        : 'Compte réactivé avec succès.')
                    : 'Impossible de modifier le compte.'
            ]);
            exit;

        } catch (\Throwable $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur serveur : ' . $e->getMessage()
            ]);
            exit;
        }
    }
}