<?php

namespace Natom\Ecoride\Controllers;

use Natom\Ecoride\Core\Controller;
use Natom\Ecoride\Core\ActivityLogger;
use Natom\Ecoride\Models\User;

require_once __DIR__ . "/../../config.php";

class AuthController extends Controller
{
    /**
     * Page de connexion
     */
    public function connexion()
    {
        session_start();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['motdepasse'] ?? '');

            $userModel = new User();
            $utilisateur = $userModel->getByEmail($email);

            if ($utilisateur && password_verify($password, $utilisateur['mot_de_passe'])) {

                // Connexion réussie
                $_SESSION['utilisateur_id'] = $utilisateur['id'];
                $_SESSION['role'] = $utilisateur['role'];

                // Journalisation de la connexion dans MongoDB
                try {
                    ActivityLogger::log(
                        'connexion',
                        (int) $utilisateur['id'],
                        [
                            'role' => $utilisateur['role']
                        ]
                    );
                } catch (\Throwable $e) {
                    // Une erreur MongoDB ne doit pas bloquer la connexion
                }

                header("Location: " . BASE_URL . "index.php?url=profil");
                exit;
            }

            $error = "Email ou mot de passe incorrect.";
        }

        // Affichage
        $this->render('connexion', [
            'error' => $error
        ]);
    }


    /**
     * Page d'inscription
     */
    public function inscription()
    {
        session_start();
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $prenom = trim($_POST['prenom'] ?? '');
            $nom = trim($_POST['nom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['motdepasse'] ?? '');

            // Vérification simple
            if ($prenom === '' || $nom === '' || $email === '' || $password === '') {
                $error = "Tous les champs sont obligatoires.";
            } else {

                $userModel = new User();

                // Vérifier si e-mail existe déjà
                if ($userModel->getByEmail($email)) {
                    $error = "Cet email est déjà utilisé.";
                } else {
                    // Création du compte
                    $userModel->createUser($prenom, $nom, $email, $password);

                    // Redirection vers connexion
                    header("Location: " . BASE_URL . "index.php?url=connexion");
                    exit;
                }
            }
        }

        // Affichage
        $this->render('inscription', [
            'error' => $error
        ]);
    }


    /**
     * Déconnexion
     */
    public function logout()
    {
        session_start();
        session_destroy();

        header("Location: " . BASE_URL . "index.php?url=connexion");
        exit;
    }
}