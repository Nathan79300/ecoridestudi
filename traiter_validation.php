<?php
require_once __DIR__ . '/includes/db.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;

$trajet_id = (int) $_POST['trajet_id'];
$etat = $_POST['etat'];
$note = $_POST['note'];
$avis = $_POST['avis'];
$commentaire = $_POST['commentaire'] ?? null;
$utilisateur_id = $_SESSION['utilisateur_id'];


$stmt = $pdo->prepare("INSERT INTO validation_trajet (id_trajet, id_utilisateur, etat, commentaire, date_validation) VALUES (?, ?, ?, ?, NOW())");
$stmt->execute([$trajet_id, $utilisateur_id, $etat, $commentaire]);


if (!empty($note)) {
    $stmt = $pdo->prepare("INSERT INTO avis (id_trajet, id_utilisateur, note, avis, statut) VALUES (?, ?, ?, ?, 'en attente')");
    $stmt->execute([$trajet_id, $utilisateur_id, $note, $avis]);
}


if ($etat === 'ok') {
    
    $stmt = $pdo->prepare("SELECT conducteur_id FROM trajets WHERE id = ?");
    $stmt->execute([$trajet_id]);
    $chauffeur_id = $stmt->fetchColumn();

    
    $stmt = $pdo->prepare("UPDATE utilisateurs SET credits = credits + 5 WHERE id = ?");
    $stmt->execute([$chauffeur_id]);
}

header("Location: index.php?page=historique&message=Validation enregistrée");


