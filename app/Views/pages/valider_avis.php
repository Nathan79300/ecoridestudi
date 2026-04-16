<?php
session_start();
require_once(__DIR__ . '/../includes/db.php');

// Sécurité : accès réservé employé
if (!isset($_SESSION['employe_id'])) {
    header('Location: /ecoridestudi/ecoride/index.php?page=connexion_employe');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['avis_id']) && !empty($_POST['action'])) {

    $avis_id = (int) $_POST['avis_id'];
    $action  = $_POST['action'];

    if ($action === 'valider') {
        $sql = "UPDATE avis SET valide = 1 WHERE id = ?";
        $pdo->prepare($sql)->execute([$avis_id]);

    } elseif ($action === 'refuser') {
        $sql = "UPDATE avis SET valide = -1 WHERE id = ?";
        $pdo->prepare($sql)->execute([$avis_id]);
    }
}

// Retour propre vers l’espace employé via index
header('Location: /ecoridestudi/ecoride/index.php?page=espace_employe');
exit;
