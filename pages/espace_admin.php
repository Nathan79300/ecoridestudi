<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../includes/db.php');

if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php?page=connexion_admin');
    exit;
}


$trajetData = $pdo->query("SELECT DATE(date_depart) as jour, COUNT(*) as total FROM trajets GROUP BY jour ORDER BY jour")->fetchAll();


$creditsData = $pdo->query("SELECT DATE(date_depart) as jour, COUNT(*)*2 as credits FROM trajets GROUP BY jour ORDER BY jour")->fetchAll();


$totalCredits = $pdo->query("SELECT COUNT(*)*2 AS total FROM trajets")->fetchColumn();


$comptes = $pdo->query("
    SELECT id, email, 'Utilisateur' as type FROM utilisateurs
    UNION
    SELECT id, email, 'Employé' as type FROM employes
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Espace Admin - EcoRide</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .section-admin {
            max-width: 900px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }

        .chart-container {
            width: 100%;
            overflow-x: auto;
            margin-bottom: 2rem;
        }

        canvas {
            width: 100% !important;
            height: auto !important;
        }

        .admin-account-list {
            list-style: none;
            padding: 0;
        }

        .admin-account-list li {
            margin-bottom: 1rem;
            background: #f5f5f5;
            padding: 1rem;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
        }

        .btn-suspendre {
            background-color: #e53935;
            color: white;
            padding: 0.4rem 1rem;
            border: none;
            border-radius: 5px;
            margin-top: 0.5rem;
            cursor: pointer;
            width: fit-content;
            align-self: flex-start;
        }

        .form-ajout-employe input {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 0.8rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-ajout-employe button {
            background-color: #4caf50;
            color: white;
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .section-admin {
                padding: 1rem;
                width: 95%;
            }

            .btn-suspendre {
                width: 100%;
            }

            .form-ajout-employe button {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="section-admin">
    <h2>📊 Statistiques de la plateforme</h2>
    <p><strong>Total de crédits gagnés :</strong> <?= $totalCredits ?> crédits</p>

    <div class="chart-container">
        <canvas id="trajetsChart"></canvas>
    </div>

    <div class="chart-container">
        <canvas id="creditsChart"></canvas>
    </div>
</div>

<script>
    const ctx1 = document.getElementById('trajetsChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($trajetData, 'jour')) ?>,
            datasets: [{
                label: 'Nombre de covoiturages',
                data: <?= json_encode(array_column($trajetData, 'total')) ?>,
                backgroundColor: '#4caf50'
            }]
        }
    });

    const ctx2 = document.getElementById('creditsChart').getContext('2d');
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($creditsData, 'jour')) ?>,
            datasets: [{
                label: 'Crédits gagnés',
                data: <?= json_encode(array_column($creditsData, 'credits')) ?>,
                backgroundColor: '#2196f3',
                fill: false,
                borderColor: '#2196f3',
                tension: 0.2
            }]
        }
    });
</script>

<div class="section-admin">
    <h3>👥 Comptes enregistrés</h3>
    <ul class="admin-account-list">
        <?php foreach ($comptes as $compte): ?>
            <li>
                <span><?= htmlspecialchars($compte['email']) ?> — <em><?= $compte['type'] ?></em></span>
                <form method="POST" action="index.php?page=suspendre_compte">
                    <input type="hidden" name="compte_id" value="<?= $compte['id'] ?>">
                    <input type="hidden" name="type" value="<?= $compte['type'] ?>">
                    <button class="btn-suspendre" onclick="return confirm('Suspendre ce compte ?')">🚫 Suspendre</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="section-admin">
    <h3>➕ Ajouter un employé</h3>
    <form method="POST" action="index.php?page=ajouter_employe" class="form-ajout-employe">
        <label for="nom">Nom :</label><br>
        <input type="text" name="nom" required><br>

        <label for="email">Email :</label><br>
        <input type="email" name="email" required><br>

        <label for="motdepasse">Mot de passe :</label><br>
        <input type="password" name="motdepasse" required><br>

        <button type="submit">Créer le compte</button>
    </form>
</div>

<div style="text-align: right; margin: 1rem 2rem;">
    <a href="index.php?page=deconnexion_admin" style="
        background-color: #e53935;
        color: white;
        padding: 0.4rem 1rem;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
    " onclick="return confirm('Se déconnecter ?')">
        🔓 Déconnexion admin
    </a>
</div>

</body>
</html>
