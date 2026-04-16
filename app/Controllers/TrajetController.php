<?php

namespace Natom\Ecoride\Controllers;

use Natom\Ecoride\Core\Controller;
use Natom\Ecoride\Models\Trajet;
use Natom\Ecoride\Models\Participation;
use Natom\Ecoride\Models\Avis;
use Natom\Ecoride\Models\User;

class TrajetController extends Controller
{
    public function proposer()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['utilisateur_id'])) {
            header("Location: /ecoridestudi/ecoride/public/index.php?url=connexion");
            exit;
        }

        if (!in_array($_SESSION['role'], ['chauffeur', 'passager_chauffeur'], true)) {
            header("Location: /ecoridestudi/ecoride/public/index.php?url=profil");
            exit;
        }

        $message = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $trajetModel = new Trajet();

            $trajetModel->create([
                'conducteur_id'    => (int)$_SESSION['utilisateur_id'],
                'ville_depart'     => trim($_POST['ville_depart'] ?? ''),
                'ville_arrivee'    => trim($_POST['ville_arrivee'] ?? ''),
                'date_depart'      => trim($_POST['date_depart'] ?? ''),
                'heure_depart'     => trim($_POST['heure_depart'] ?? ''),
                'prix'             => (int)($_POST['prix'] ?? 0),
                'places_restantes' => min((int)($_POST['places'] ?? 1), 5),
                'ecologique'       => isset($_POST['ecologique']) ? 1 : 0
            ]);

            $message = "Votre trajet a bien été créé.";
        }

        $this->render('trajets/proposer', ['message' => $message]);
    }

    public function recherche()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $resultats = [];
        $prochain  = null;
        $noResults = false;
        $hasSearch = false;

        if (isset($_GET['ville_depart'], $_GET['ville_arrivee'], $_GET['date_depart'], $_GET['passagers'])) {
            $hasSearch = true;

            $params = [
                'ville_depart'  => trim($_GET['ville_depart']),
                'ville_arrivee' => trim($_GET['ville_arrivee']),
                'date_depart'   => trim($_GET['date_depart']),
                'passagers'     => (int)$_GET['passagers'],
                'ecologique'    => isset($_GET['ecologique']) ? 1 : null,
                'prix_max'      => ($_GET['prix_max'] ?? '') !== '' ? (int)$_GET['prix_max'] : null,
                'note_min'      => ($_GET['note_min'] ?? '') !== '' ? (int)$_GET['note_min'] : null
            ];

            $trajetModel = new Trajet();
            $resultats = $trajetModel->rechercheComplete($params);

            if (empty($resultats)) {
                $noResults = true;
                $prochain = $trajetModel->searchNext(
                    $params['ville_depart'],
                    $params['ville_arrivee'],
                    $params['date_depart']
                );
            }
        }

        $this->render('trajets/recherche', [
            'resultats' => $resultats,
            'prochain'  => $prochain,
            'noResults' => $noResults,
            'hasSearch' => $hasSearch
        ]);
    }

    public function details()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_GET['id'])) {
            die("Trajet introuvable.");
        }

        $trajetModel = new Trajet();
        $trajet = $trajetModel->getById((int)$_GET['id']);

        if (!$trajet) {
            die("Trajet introuvable.");
        }

        $conducteurId = (int)($trajet['conducteur_id'] ?? 0);

        $userModel = new User();
        $conducteur = $conducteurId > 0 ? $userModel->getById($conducteurId) : null;

        $avisModel = new Avis();
        $moyenneAvis = $conducteurId > 0 ? $avisModel->getMoyenneByConducteur($conducteurId) : 0.0;
        $nbAvis      = $conducteurId > 0 ? $avisModel->countValidatedByConducteur($conducteurId) : 0;
        $avisList    = $conducteurId > 0 ? $avisModel->getValidatedByConducteur($conducteurId, 10) : [];

        $alreadyReserved = false;
        if (!empty($_SESSION['utilisateur_id'])) {
            $participationModel = new Participation();
            $alreadyReserved = $participationModel->dejaReserve((int)$trajet['id'], (int)$_SESSION['utilisateur_id']);
        }

        $this->render('trajets/details', [
            'trajet'          => $trajet,
            'conducteur'      => $conducteur,
            'moyenneAvis'     => $moyenneAvis,
            'nbAvis'          => $nbAvis,
            'avisList'        => $avisList,
            'alreadyReserved' => $alreadyReserved
        ]);
    }

    public function reserver()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['utilisateur_id'])) {
            header("Location: /ecoridestudi/ecoride/public/index.php?url=connexion");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['trajet_id'])) {
            header("Location: /ecoridestudi/ecoride/public/index.php?url=recherche");
            exit;
        }

        $trajetId = (int)$_POST['trajet_id'];
        $userId   = (int)$_SESSION['utilisateur_id'];

        $trajetModel = new Trajet();
        $partModel   = new Participation();
        $userModel   = new User();

        $trajet = $trajetModel->getById($trajetId);
        if (!$trajet) {
            $_SESSION['flash_error'] = "Trajet introuvable.";
            header("Location: /ecoridestudi/ecoride/public/index.php?url=recherche");
            exit;
        }

        if ($partModel->dejaReserve($trajetId, $userId)) {
            $_SESSION['flash_error'] = "Vous avez déjà réservé ce trajet.";
            header("Location: /ecoridestudi/ecoride/public/index.php?url=details&id=$trajetId");
            exit;
        }

        if (!$trajetModel->aDesPlaces($trajetId)) {
            $_SESSION['flash_error'] = "Ce trajet est complet.";
            header("Location: /ecoridestudi/ecoride/public/index.php?url=details&id=$trajetId");
            exit;
        }

        $prix = (int)($trajet['prix'] ?? 0);
        $creditsAvant = $userModel->getCreditsById($userId);

        if ($creditsAvant < $prix) {
            $_SESSION['flash_error'] = "Crédits insuffisants pour réserver ce trajet.";
            header("Location: /ecoridestudi/ecoride/public/index.php?url=details&id=$trajetId");
            exit;
        }

        if (!$userModel->debitCredits($userId, $prix)) {
            $_SESSION['flash_error'] = "Impossible de débiter vos crédits.";
            header("Location: /ecoridestudi/ecoride/public/index.php?url=details&id=$trajetId");
            exit;
        }

        if (!$partModel->reserver($trajetId, $userId)) {
            $userModel->crediterCredits($userId, $prix);
            $_SESSION['flash_error'] = "Impossible d'enregistrer la réservation.";
            header("Location: /ecoridestudi/ecoride/public/index.php?url=details&id=$trajetId");
            exit;
        }

        if (!$trajetModel->retirerUnePlace($trajetId)) {
            $partModel->annuler($trajetId, $userId);
            $userModel->crediterCredits($userId, $prix);
            $_SESSION['flash_error'] = "Impossible de réserver : plus de place.";
            header("Location: /ecoridestudi/ecoride/public/index.php?url=details&id=$trajetId");
            exit;
        }

        $_SESSION['credits'] = $userModel->getCreditsById($userId);
        $_SESSION['flash_success'] = "✅ Réservation confirmée ! (-$prix crédits)";

        header("Location: /ecoridestudi/ecoride/public/index.php?url=details&id=$trajetId");
        exit;
    }

    public function annulerReservation()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['utilisateur_id'])) {
            header("Location: /ecoridestudi/ecoride/public/index.php?url=connexion");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['trajet_id'])) {
            header("Location: /ecoridestudi/ecoride/public/index.php?url=recherche");
            exit;
        }

        $trajetId = (int)$_POST['trajet_id'];
        $userId   = (int)$_SESSION['utilisateur_id'];

        $trajetModel = new Trajet();
        $partModel   = new Participation();
        $userModel   = new User();

        $trajet = $trajetModel->getById($trajetId);
        if (!$trajet) {
            $_SESSION['flash_error'] = "Trajet introuvable.";
            header("Location: /ecoridestudi/ecoride/public/index.php?url=recherche");
            exit;
        }

        if (!$partModel->dejaReserve($trajetId, $userId)) {
            $_SESSION['flash_error'] = "Vous n'avez pas de réservation sur ce trajet.";
            header("Location: /ecoridestudi/ecoride/public/index.php?url=details&id=$trajetId");
            exit;
        }

        $prix = (int)($trajet['prix'] ?? 0);

        if (!$partModel->annuler($trajetId, $userId)) {
            $_SESSION['flash_error'] = "Impossible d'annuler la réservation.";
            header("Location: /ecoridestudi/ecoride/public/index.php?url=details&id=$trajetId");
            exit;
        }

        $trajetModel->ajouterUnePlace($trajetId);
        $userModel->crediterCredits($userId, $prix);

        $_SESSION['credits'] = $userModel->getCreditsById($userId);
        $_SESSION['flash_success'] = "❌ Réservation annulée. +$prix crédits remboursés.";

        header("Location: /ecoridestudi/ecoride/public/index.php?url=details&id=$trajetId");
        exit;
    }



    public function mesReservations()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['utilisateur_id'])) {
            header("Location: /ecoridestudi/ecoride/public/index.php?url=connexion");
            exit;
        }

        $userId = (int)$_SESSION['utilisateur_id'];

        
        $participationModel = new Participation();
        $reservations = $participationModel->getReservationsByUser($userId);

        $this->render('trajets/mes_reservations', [
            'reservations' => $reservations
        ]);
    }

    public function mesTrajets()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['utilisateur_id'])) {
            header("Location: /ecoridestudi/ecoride/public/index.php?url=connexion");
            exit;
        }

        if (!in_array($_SESSION['role'] ?? '', ['chauffeur', 'passager_chauffeur'], true)) {
            header("Location: /ecoridestudi/ecoride/public/index.php?url=profil");
            exit;
        }

        $trajetModel = new Trajet();
        $trajets = $trajetModel->getByConducteur((int)$_SESSION['utilisateur_id']);

        $this->render('trajets/mes_trajets', [
            'trajets' => $trajets
        ]);
    }
}
