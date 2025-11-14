<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . '/../includes/db.php');
require_once(__DIR__ . '/../includes/nav.php');


if (!isset($_SESSION['employe_id'])) {
    header('Location: ../index.php?page=connexion_employe');
    exit;
}


$stmt_avis = $pdo->query("
    SELECT a.id, a.note, a.commentaire, u.username AS pseudo, t.ville_depart, t.ville_arrivee 
    FROM avis a
    JOIN utilisateurs u ON a.id_utilisateur = u.id
    JOIN trajets t ON a.id_trajet = t.id
    WHERE a.valide = 0
");
$avis_attente = $stmt_avis->fetchAll();


$stmt_signales = $pdo->query("
    SELECT a.id AS avis_id, a.commentaire, u.username AS pseudo, u.email, t.id AS trajet_id, 
           t.ville_depart, t.ville_arrivee, t.date_depart
    FROM avis a
    JOIN utilisateurs u ON a.id_utilisateur = u.id
    JOIN trajets t ON a.id_trajet = t.id
    WHERE a.probleme = 1
");
$trajets_signales = $stmt_signales->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta charset="UTF-8">
    <title>Espace Employé</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            background-color: #eaf6ec;
            font-family: 'Segoe UI', sans-serif;
        }

        .container {
            max-width: 900px;
            margin: 2rem auto;
            background: #fff;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.08);
        }

        h2, h3 {
            color: #2e7d32;
            margin-bottom: 1rem;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        .avis-card, .trajet-card {
            background: #f5f5f5;
            margin-bottom: 1.5rem;
            padding: 1rem 1.5rem;
            border-left: 5px solid #4CAF50;
            border-radius: 8px;
        }

        .trajet-card {
            border-left-color: #e53935;
        }

        .avis-card p,
        .trajet-card p {
            margin: 0.5rem 0;
        }

        .actions {
            margin-top: 0.5rem;
        }

        .actions button {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 0.4rem 1rem;
            margin-right: 0.5rem;
            cursor: pointer;
            transition: background 0.3s;
        }

        .actions button:hover {
            background-color: #388e3c;
        }

        .actions button[value="refuser"] {
            background-color: #e53935;
        }

        .actions button[value="refuser"]:hover {
            background-color: #c62828;
        }

        .empty {
            font-style: italic;
            color: #666;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>👨‍💼 Espace Employé</h2>

    <h3>📝 Avis en attente de validation</h3>
    <?php if ($avis_attente): ?>
        <ul>
            <?php foreach ($avis_attente as $avis): ?>
                <li class="avis-card">
                    <p><strong><?= htmlspecialchars($avis['pseudo']) ?></strong> — <?= $avis['note'] ?>/5</p>
                    <p><?= htmlspecialchars($avis['commentaire']) ?></p>
                    <p><em>Trajet : <?= htmlspecialchars($avis['ville_depart']) ?> → <?= htmlspecialchars($avis['ville_arrivee']) ?></em></p>
                    
<form method="POST" action="/ecoride/pages/valider_avis.php">


                        <input type="hidden" name="avis_id" value="<?= $avis['id'] ?>">
                        <button name="action" value="valider">✅ Valider</button>
                        <button name="action" value="refuser">❌ Refuser</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="empty">Aucun avis à valider.</p>
    <?php endif; ?>

    <h3>🚨 Covoiturages signalés</h3>
    <?php if ($trajets_signales): ?>
        <ul>
            <?php foreach ($trajets_signales as $t): ?>
                <li class="trajet-card">
                    <p><strong>Trajet #<?= $t['trajet_id'] ?> :</strong> <?= htmlspecialchars($t['ville_depart']) ?> → <?= htmlspecialchars($t['ville_arrivee']) ?></p>
                    <p><strong>Départ :</strong> <?= htmlspecialchars($t['date_depart']) ?></p>
                    <p><strong>Participant :</strong> <?= htmlspecialchars($t['pseudo']) ?> — <?= htmlspecialchars($t['email']) ?></p>
                    <p><strong>Problème signalé :</strong> <?= htmlspecialchars($t['commentaire']) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="empty">Aucun trajet signalé.</p>
    <?php endif; ?>
</div>

</body>
</html>
