<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../includes/db.php');
require_once(__DIR__ . '/../includes/nav.php');

// Vérification employé connecté
if (!isset($_SESSION['employe_id'])) {
    header('Location: index.php?page=connexion_employe');
    exit;
}

// Avis non validés
$stmt_avis = $pdo->query("
    SELECT a.id, a.note, a.commentaire, u.username AS pseudo, 
           t.ville_depart, t.ville_arrivee 
    FROM avis a
    JOIN utilisateurs u ON a.id_utilisateur = u.id
    JOIN trajets t ON a.id_trajet = t.id
    WHERE a.valide = 0
");
$avis_attente = $stmt_avis->fetchAll();

// Trajets signalés
$stmt_signales = $pdo->query("
    SELECT a.id AS avis_id, a.commentaire, u.username AS pseudo, u.email, 
           t.id AS trajet_id, t.ville_depart, t.ville_arrivee, t.date_depart
    FROM avis a
    JOIN utilisateurs u ON a.id_utilisateur = u.id
    JOIN trajets t ON a.id_trajet = t.id
    WHERE a.probleme = 1
");
$trajets_signales = $stmt_signales->fetchAll();
?>
