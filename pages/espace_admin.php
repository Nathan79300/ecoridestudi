<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php?page=connexion_admin');
    exit;
}

// Statistiques
$trajetData = $pdo->query("
    SELECT DATE(date_depart) AS jour, COUNT(*) AS total
    FROM trajets
    GROUP BY jour ORDER BY jour
")->fetchAll();

$creditsData = $pdo->query("
    SELECT DATE(date_depart) AS jour, COUNT(*)*2 AS credits
    FROM trajets
    GROUP BY jour ORDER BY jour
")->fetchAll();

$totalCredits = $pdo->query("
    SELECT COUNT(*)*2 AS total FROM trajets
")->fetchColumn();

// Liste utilisateurs (tous rôles)
$comptes = $pdo->query("
    SELECT id, email, role, suspendu
    FROM utilisateurs
    ORDER BY role
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Admin - EcoRide</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="section-admin">
    <h2>📊 Statistiques de la plateforme</h2>
    <p><strong>Total de crédits gagnés :</strong> <?= $totalCredits ?> crédits</p>

    <canvas id="trajetsChart"></canvas>
    <canvas id="creditsChart"></canvas>
</div>

<script>
new Chart(document.getElementById('trajetsChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($trajetData, 'jour')) ?>,
        datasets: [{
            label: 'Nombre de trajets',
            data: <?= json_encode(array_column($trajetData, 'total')) ?>,
            backgroundColor: '#4caf50'
        }]
    }
});

new Chart(document.getElementById('creditsChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode(array_column($creditsData, 'jour')) ?>,
        datasets: [{
            label: 'Crédits gagnés',
            data: <?= json_encode(array_column($creditsData, 'credits')) ?>,
            fill: false,
            borderColor: '#2196f3'
        }]
    }
});
</script>

<div class="section-admin">
    <h3>👥 Comptes utilisateurs</h3>

    <ul class="admin-account-list">

    <?php foreach ($comptes as $c): ?>
        <li style="<?= $c['suspendu'] ? 'background:#ffe6e6;' : '' ?>">

            <strong><?= htmlspecialchars($c['email']) ?></strong>  
            — rôle : <em><?= $c['role'] ?></em>  
            <?= $c['suspendu'] ? "<span style='color:red'> (SUSPENDU)</span>" : "" ?>

            <?php if (!$c['suspendu']): ?>
                <form action="index.php?page=suspendre_compte" method="POST">
                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                    <button>🚫 Suspendre</button>
                </form>
            <?php else: ?>
                <form action="index.php?page=reactiver_compte" method="POST">
                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                    <button style="background:green;color:white;">✔ Réactiver</button>
                </form>
            <?php endif; ?>

        </li>
    <?php endforeach; ?>

    </ul>
</div>

<a href="index.php?page=deconnexion_admin">Déconnexion</a>

</body>
</html>
