<?php

namespace Natom\Ecoride\Controllers;

use Natom\Ecoride\Core\Controller;
use Natom\Ecoride\Models\User;
use Natom\Ecoride\Models\Trajet;

class ProfilController extends Controller
{
    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Sécurité : empêcher l’accès si non connecté
        if (!isset($_SESSION['utilisateur_id'])) {
            header("Location: /ecoridestudi/ecoride/public/index.php?url=connexion");
            exit;
        }

        $user_id = (int)$_SESSION['utilisateur_id'];
        $userModel = new User();
        $trajetModel = new Trajet();
        $message = null;

        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $prenom = trim($_POST['prenom'] ?? '');
            $nom    = trim($_POST['nom'] ?? '');
            $role   = trim($_POST['role'] ?? 'utilisateur');

            // Sécurisation du rôle
            $rolesAutorises = ['utilisateur', 'chauffeur', 'passager_chauffeur'];
            if (!in_array($role, $rolesAutorises, true)) {
                $role = 'utilisateur';
            }

            $userModel->updateProfile($user_id, $prenom, $nom, $role);

            // Update session (pour l'affichage nav)
            $_SESSION['role'] = $role;

            $message = "Votre profil a bien été mis à jour.";
        }

        // ---------- RÉCUPÉRATION DES DONNÉES ----------
        $utilisateur = $userModel->getById($user_id);

        // Mettre à jour la session pour affichage navbar
        if (!empty($utilisateur)) {
            $_SESSION['username'] = $utilisateur['username'] ?? ($_SESSION['username'] ?? '');
            $_SESSION['credits']  = $utilisateur['credits'] ?? ($_SESSION['credits'] ?? 0);
            $_SESSION['role']     = $utilisateur['role'] ?? ($_SESSION['role'] ?? 'utilisateur');
        }

        // Trajets proposés uniquement si chauffeur / passager_chauffeur
        $trajets = [];
        $isChauffeur = in_array($_SESSION['role'], ['chauffeur', 'passager_chauffeur'], true);

        if ($isChauffeur) {
            $trajets = $trajetModel->getByConducteur($user_id);
        }

        // ---------- AFFICHAGE ----------
        $this->render('profil', [
            'utilisateur' => $utilisateur,
            'trajets'     => $trajets,
            'message'     => $message,
            'isChauffeur' => $isChauffeur
        ]);
    }
}
