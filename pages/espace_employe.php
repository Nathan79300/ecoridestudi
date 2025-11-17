<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../includes/db.php');
require_once(__DIR__ . '/../includes/nav.php');


if (!isset($_SESSION['employe_id'])) {
    header('Location: /ecoridestudi/ecoride/index.php?page=connexion_employe');
    exit;
}



$stmt_avis = $pdo->query("
    SELECT a.id, a.note, a.commentaire, 
           u.username AS pseudo,
           t.ville_depart, t.ville_arrivee
    FROM avis a
    JOIN utilisateurs u ON a.id_utilisateur = u.id
    JOIN trajets t ON a.id_trajet = t.id
    WHERE a.valide = 0
");

$avis_attente = $stmt_avis->fetchAll(PDO::FETCH_ASSOC);



$stmt_signales = $pdo->query("
    SELECT a.id AS avis_id, a.commentaire, a.traite,
           u.username AS pseudo, u.email,
           t.id AS trajet_id, t.ville_depart, 
           t.ville_arrivee, t.date_depart
    FROM avis a
    JOIN utilisateurs u ON a.id_utilisateur = u.id
    JOIN trajets t ON a.id_trajet = t.id
    WHERE a.probleme = 1
");

$trajets_signales = $stmt_signales->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Employé</title>

    <link rel="stylesheet" href="/ecoridestudi/ecoride/assets/style.css">

    <style>
        body { background-color:#eaf6ec; font-family:'Segoe UI'; }
        .container { max-width:900px; margin:2rem auto; background:#fff; padding:2rem; border-radius:12px; }
        h2, h3 { color:#2e7d32; }
        .avis-card, .trajet-card {
            background:#f5f5f5; padding:1rem; margin-bottom:1rem;
            border-left:5px solid #4CAF50; border-radius:8px;
        }
        .trajet-card { border-left-color:#e53935; }
        .valider { background:#4CAF50; color:white; padding:5px 12px; border:none; border-radius:4px; cursor:pointer; }
        .refuser { background:#e53935; color:white; padding:5px 12px; border:none; border-radius:4px; cursor:pointer; }
        .traite { background:#1976d2; color:white; padding:5px 12px; border:none; border-radius:4px; cursor:pointer; }
        .tag-traite { color:green; font-weight:bold; font-size:1.1rem; }
    </style>
</head>

<body>

<div class="container">
    <h2>👨‍💼 Espace Employé</h2>

    
    <h3>📝 Avis en attente de validation</h3>

    <?php if ($avis_attente): ?>
        <?php foreach ($avis_attente as $avis): ?>
            <div class="avis-card">
                <p><strong><?= htmlspecialchars($avis['pseudo']) ?></strong> — <?= $avis['note'] ?>/5</p>
                <p><?= htmlspecialchars($avis['commentaire']) ?></p>
                <p><em>Trajet : <?= htmlspecialchars($avis['ville_depart']) ?> → <?= htmlspecialchars($avis['ville_arrivee']) ?></em></p>

                <form method="POST" action="/ecoridestudi/ecoride/index.php?page=valider_avis">
                    <input type="hidden" name="avis_id" value="<?= $avis['id'] ?>">
                    <button class="valider" name="action" value="valider">Valider</button>
                    <button class="refuser" name="action" value="refuser">Refuser</button>
                </form>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="color:#666; font-style:italic;">Aucun avis à valider.</p>
    <?php endif; ?>

    
    <h3>🚨 Covoiturages signalés</h3>

    <?php if ($trajets_signales): ?>
        <?php foreach ($trajets_signales as $t): ?>
            <div class="trajet-card">
                <p><strong>Trajet #<?= $t['trajet_id'] ?></strong></p>
                <p><?= htmlspecialchars($t['ville_depart']) ?> → <?= htmlspecialchars($t['ville_arrivee']) ?></p>
                <p><strong>Départ :</strong> <?= htmlspecialchars($t['date_depart']) ?></p>
                <p><strong>Participant :</strong> <?= htmlspecialchars($t['pseudo']) ?> — <?= htmlspecialchars($t['email']) ?></p>
                <p><strong>Problème :</strong> <?= htmlspecialchars($t['commentaire']) ?></p>

                <?php if ($t['traite'] == 0): ?>
                    <form method="POST" action="/ecoridestudi/ecoride/index.php?page=marquer_signale_traite">
                        <input type="hidden" name="avis_id" value="<?= $t['avis_id'] ?>">
                        <button class="traite">Traité ✔️</button>
                    </form>
                <?php else: ?>
                    <span class="tag-traite">✔ Signalement traité</span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="color:#666; font-style:italic;">Aucun trajet signalé.</p>
    <?php endif; ?>

</div>

</body>
</html>
