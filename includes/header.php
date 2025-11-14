<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../config.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <meta charset="UTF-8">
  <title>EcoRide</title>

  <!-- Chemin corrigé -->
  <link rel="stylesheet" href="/ecoridestudi/ecoride/assets/style.css">
</head>
<body>

<?php include 'nav.php'; ?>
