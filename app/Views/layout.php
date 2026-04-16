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

    <?php
      // footer dans /includes/footer.php (racine)
      include __DIR__ . "/../../includes/footer.php";
    ?>

</body>
</html>
