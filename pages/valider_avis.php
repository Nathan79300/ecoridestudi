<?php
session_start();
require_once(__DIR__ . '/../includes/db.php');

if (!isset($_SESSION['employe_id'])) {
    header('Location: ../index.php?page=connexion_employe');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['avis_id'], $_POST['action'])) {
    $avis_id = (int) $_POST['avis_id'];
    $action = $_POST['action'];

    if ($action === 'valider') {
        $pdo->prepare("UPDATE avis SET valide = 1 WHERE id = ?")->execute([$avis_id]);
    } elseif ($action === 'refuser') {
        $pdo->prepare("UPDATE avis SET valide = -1 WHERE id = ?")->execute([$avis_id]);
    }
}

header('Location: espace_employe.php');
exit;
