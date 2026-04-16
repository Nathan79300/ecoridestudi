<?php

require_once(__DIR__ . '/includes/db.php');
require_once(__DIR__ . '/includes/fonctions.php');


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'chauffeur') {
    header('Location: index.php?page=connexion');
    exit;
}

if (!isset($_GET['trajet_id'])) {
    echo "ID de trajet manquant.";
    exit;
}

$trajet_id = (int) $_GET['trajet_id'];


$stmt = $pdo->prepare("UPDATE trajets SET etat = 'termine' WHERE id = ? AND conducteur_id = ?");
$stmt->execute([$trajet_id, $_SESSION['utilisateur_id']]);


$stmt = $pdo->prepare("
    SELECT u.email 
    FROM participations p 
    JOIN utilisateurs u ON u.id = p.id_utilisateur 
    WHERE p.id_trajet = ?
");
$stmt->execute([$trajet_id]);
$emails = $stmt->fetchAll(PDO::FETCH_COLUMN);


foreach ($emails as $email) {
    envoyer_mail(
        $email,
        "Merci d'avoir participé au covoiturage !",
        "Bonjour,\n\nVeuillez vous rendre sur votre espace personnel pour valider que tout s’est bien passé pour le trajet.\n\nMerci,\nL’équipe EcoRide"
    );
}

header('Location: index.php?page=profil&msg=trajet_cloture');
exit;
