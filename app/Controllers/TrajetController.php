<?php

namespace Natom\Ecoride\Controllers;

use Natom\Ecoride\Core\Controller;
use Natom\Ecoride\Models\Trajet;
use Natom\Ecoride\Models\Participation;

class TrajetController extends Controller
{
    /**
     * Page : Proposer un trajet
     */
    public function proposer()
    {
        session_start();

        if (!isset($_SESSION['utilisateur_id'])) {
            header("Location: /ecoridestudi/ecoride/public/connexion");
            exit;
        }

        $message = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $trajetModel = new Trajet();

            $trajetModel->create([
                'conducteur_id'    => $_SESSION['utilisateur_id'],
                'ville_depart'     => trim($_POST['ville_depart']),
                'ville_arrivee'    => trim($_POST['ville_arrivee']),
                'date_depart'      => trim($_POST['date_depart']),
                'heure_depart'     => trim($_POST['heure_depart']),
                'prix'             => trim($_POST['prix']),
                'places_restantes' => 3
            ]);

            $message = "Votre trajet a été créé avec succès !";
        }

        $this->render('trajets/proposer', ['message' => $message]);
    }

    /**
     * Page : Recherche avancée (GET)
     */
    public function recherche()
    {
        session_start();

        $resultats = [];
        $prochain = null;
        $noResults = false;

        // SI le formulaire GET a été lancé
        if (isset($_GET['ville_depart'], $_GET['ville_arrivee'], $_GET['date_depart'], $_GET['passagers'])) {

            $params = [
                'ville_depart'  => trim($_GET['ville_depart']),
                'ville_arrivee' => trim($_GET['ville_arrivee']),
                'date_depart'   => trim($_GET['date_depart']),
                'passagers'     => intval($_GET['passagers']),
                'ecologique'    => $_GET['ecologique'] ?? null,
                'prix_max'      => $_GET['prix_max'] ?? null,
                'duree_max'     => $_GET['duree_max'] ?? null,
                'note_min'      => $_GET['note_min'] ?? null
            ];

            $trajetModel = new Trajet();
            $resultats = $trajetModel->rechercheComplete($params);

            if (empty($resultats)) {
                $noResults = true;

                // Recherche du prochain trajet
                $prochain = $trajetModel->searchNext(
                    $params['ville_depart'],
                    $params['ville_arrivee'],
                    $params['date_depart']
                );
            }
        }

        $this->render('trajets/recherche', [
            'resultats' => $resultats,
            'prochain' => $prochain,
            'noResults' => $noResults
        ]);
    }

    /**
     * Réservation d'un trajet
     */
    public function reserver()
    {
        session_start();

        if (!isset($_SESSION['utilisateur_id'])) {
            header("Location: /ecoridestudi/ecoride/public/connexion");
            exit;
        }

        $trajet_id = $_POST['trajet_id'] ?? null;

        if (!$trajet_id) {
            header("Location: /ecoridestudi/ecoride/public/recherche?error=missing_id");
            exit;
        }

        $passager_id = $_SESSION['utilisateur_id'];

        $trajetModel = new Trajet();
        $participationModel = new Participation();

        // Vérifier si déjà réservé
        if ($participationModel->dejaReserve($trajet_id, $passager_id)) {
            header("Location: /ecoridestudi/ecoride/public/details?id=$trajet_id&error=already_reserved");
            exit;
        }

        // Vérifier places restantes
        if (!$trajetModel->aDesPlaces($trajet_id)) {
            header("Location: /ecoridestudi/ecoride/public/details?id=$trajet_id&error=no_places");
            exit;
        }

        // Réserver
        $participationModel->reserver($trajet_id, $passager_id);
        $trajetModel->retirerUnePlace($trajet_id);

        header("Location: /ecoridestudi/ecoride/public/details?id=$trajet_id&success=reserved");
        exit;
    }

    /**
     * Page Détails d'un trajet
     */
    public function details()
    {
        session_start();

        if (!isset($_GET['id'])) {
            die("Trajet introuvable.");
        }

        $trajet_id = intval($_GET['id']);

        $trajetModel = new Trajet();
        $trajet = $trajetModel->getById($trajet_id);

        if (!$trajet) {
            die("Trajet introuvable.");
        }

        $this->render('trajets/details', ['trajet' => $trajet]);
    }
}
