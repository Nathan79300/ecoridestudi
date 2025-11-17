<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config.php'; // IMPORTANT : require_once
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta charset="UTF-8">
  <title>EcoRide</title>

  <link rel="stylesheet" href="<?= BASE_URL ?>assets/style.css">
</head>
<body>

<?php require_once __DIR__ . '/nav.php'; ?>
