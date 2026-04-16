<?php
require_once(__DIR__ . '/../includes/db.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['utilisateur_id']) || !in_array($_SESSION['role'], ['chauffeur', 'passager_chauffeur'])) {
    header("Location: index.php?page=connexion");
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['trajet_id'], $_POST['action'])) {
    $trajet_id = (int) $_POST['trajet_id'];
    $action = $_POST['action'];

    
    $stmt = $pdo->prepare("SELECT * FROM trajets WHERE id = ? AND conducteur_id = ?");
    $stmt->execute([$trajet_id, $utilisateur_id]);
    $trajet = $stmt->fetch();

    if (!$trajet) {
        die("❌ Ce trajet ne vous appartient pas ou n'existe pas.");
    }

    switch ($action) {
        case 'demarrer':
            if (in_array($trajet['etat'], ['en_attente', 'prévu'])) {

                $stmt = $pdo->prepare("UPDATE trajets SET etat = 'en_cours' WHERE id = ?");
                $stmt->execute([$trajet_id]);
            }
            break;

        case 'terminer':
            if ($trajet['etat'] === 'en_cours') {
                $stmt = $pdo->prepare("UPDATE trajets SET etat = 'termine' WHERE id = ?");
                $stmt->execute([$trajet_id]);

                
            }
            break;

        case 'annuler':
            if ($trajet['etat'] === 'en_attente') {
                $stmt = $pdo->prepare("UPDATE trajets SET etat = 'annule' WHERE id = ?");
                $stmt->execute([$trajet_id]);

                
                $stmt = $pdo->prepare("UPDATE utilisateurs SET credits = credits + 2 WHERE id = ?");
                $stmt->execute([$utilisateur_id]);

                
            }
            break;

        default:
            die("❌ Action inconnue.");
    }

    header("Location: index.php?page=profil&message=action_ok");
    exit;

} else {
    die("❌ Requête invalide.");
}
