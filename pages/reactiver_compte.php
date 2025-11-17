<?php
session_start();
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php?page=connexion_admin');
    exit;
}

if (isset($_POST['id'])) {
    $stmt = $pdo->prepare("UPDATE utilisateurs SET suspendu = 0 WHERE id = ?");
    $stmt->execute([$_POST['id']]);
}

header('Location: index.php?page=espace_admin');
exit;
