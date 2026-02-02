<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$BASE_URL = "/ecoridestudi/ecoride/public";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? "EcoRide" ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $BASE_URL ?>/assets/style.css">
</head>

<body>

    <?php include __DIR__ . "/partials/nav.php"; ?>

    <main class="main-container">
        <?= $content ?>
    </main>

    <footer class="footer">
        <p>EcoRide © <?= date("Y") ?></p>

        <!-- ✅ Pas de route contact pour l’instant, donc on évite le 404 -->
        <!-- Quand tu auras la route, tu mettras: <?= $BASE_URL ?>/index.php?url=contact -->
        <a href="<?= $BASE_URL ?>/index.php">Accueil</a>
    </footer>

</body>
</html>
