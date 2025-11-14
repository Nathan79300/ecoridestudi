<?php
session_start();
require_once(__DIR__ . '/../includes/db.php');
require_once(__DIR__ . '/../includes/logger.php'); 


if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php?page=connexion_admin');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['compte_id'], $_POST['type'])) {
    $id = (int) $_POST['compte_id'];
    $type = $_POST['type'];

    
    if ($type === 'Utilisateur') {
        $stmtEmail = $pdo->prepare("SELECT email FROM utilisateurs WHERE id = ?");
    } elseif ($type === 'Employé') {
        $stmtEmail = $pdo->prepare("SELECT email FROM employes WHERE id = ?");
    } else {
        die("❌ Type de compte inconnu.");
    }

    $stmtEmail->execute([$id]);
    $email = $stmtEmail->fetchColumn();

    
    if ($type === 'Utilisateur') {
        $stmt = $pdo->prepare("UPDATE utilisateurs SET suspendu = 1 WHERE id = ?");
    } else {
        $stmt = $pdo->prepare("UPDATE employes SET suspendu = 1 WHERE id = ?");
    }

    $stmt->execute([$id]);

    
    log_admin_action($_SESSION['admin_id'], 'suspendre_compte', $email);

    header('Location: index.php?page=espace_admin');
    exit;
} else {
    die("❌ Requête invalide.");
}
