<?php
session_start();
require_once(__DIR__ . '/../includes/db.php');

// Sécurité
if (!isset($_SESSION['employe_id'])) {
    header('Location: /ecoridestudi/ecoride/index.php?page=connexion_employe');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['avis_id'])) {

    $avis_id = (int) $_POST['avis_id'];

    // Marquer comme traité
    $sql = "UPDATE avis SET traite = 1 WHERE id = ?";
    $pdo->prepare($sql)->execute([$avis_id]);
}

// Retour vers l'espace employé
header('Location: /ecoridestudi/ecoride/index.php?page=espace_employe');
exit;
