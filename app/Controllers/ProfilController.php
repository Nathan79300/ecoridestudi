<?php

namespace Natom\Ecoride\Controllers;

use Natom\Ecoride\Core\Controller;
use Natom\Ecoride\Models\User;
use Natom\Ecoride\Models\Trajet;

class ProfilController extends Controller
{
    public function index()
    {
        session_start();

        // Sécurité : empêcher l’accès si non connecté
        if (!isset($_SESSION['utilisateur_id'])) {
            header("Location: /ecoridestudi/ecoride/public/connexion");
            exit;
        }

        $user_id = $_SESSION['utilisateur_id'];
        $userModel = new User();
        $trajetModel = new Trajet();
        $message = null;

        // ---------- MISE À JOUR DU PROFIL ----------
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $prenom = trim($_POST['prenom'] ?? '');
            $nom    = trim($_POST['nom'] ?? '');
            $role   = trim($_POST['role'] ?? '');

            // Update DB
            $userModel->updateProfile($user_id, $prenom, $nom, $role);

            // Update session
            $_SESSION['role'] = $role;

            $message = "Votre profil a bien été mis à jour.";
        }

        // ---------- RÉCUPÉRATION DES DONNÉES ----------
        $utilisateur = $userModel->getById($user_id);

        // Récupérer trajets seulement si chauffeur ou passager/chauffeur
        $trajets = [];
        if (in_array($_SESSION['role'], ['chauffeur', 'passager_chauffeur'])) {
            $trajets = $trajetModel->getByConducteur($user_id);
        }

        // ---------- AFFICHAGE ----------
        $this->render('profil', [
            'utilisateur' => $utilisateur,
            'trajets' => $trajets,
            'message' => $message
        ]);
    }
}
