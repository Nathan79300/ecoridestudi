<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si l'utilisateur N'EST PAS connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: index.php?page=connexion");
    exit;
}
