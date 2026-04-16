<?php

// Chargement config + base
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';

// On inclut le header (nav + <head>)
include __DIR__ . '/includes/header.php';

// Récupère la page demandée
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Sécurisation du nom de fichier
if (!preg_match('/^[a-zA-Z0-9_]+$/', $page)) {
    echo "<h2>Page invalide</h2>";
    include __DIR__ . '/includes/footer.php';
    exit;
}

// Chemin réel du fichier dans /pages/
$pagePath = __DIR__ . '/pages/' . $page . '.php';

// On affiche la page
if (file_exists($pagePath)) {
    include $pagePath;
} else {
    echo "<h2>Page introuvable</h2>";
}

// Pied de page
include __DIR__ . '/includes/footer.php';
