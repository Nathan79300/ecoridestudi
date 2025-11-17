<?php
require_once(__DIR__ . '/../includes/db.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_GET['id'])) {
    echo "ID du trajet manquant.";
    exit;
}

$id = intval($_GET['id']);

// Récupération trajet + conducteur + véhicule
$sql = "
    SELECT t.*, u.nom AS conducteur_nom, u.prenom, u.id AS conducteur_id, 
           v.marque, v.modele, v.energie
    FROM trajets t
    JOIN utilisateurs u ON t.conducteur_id = u.id
    LEFT JOIN vehicules v ON u.id = v.id_utilisateur
    WHERE t.id = ?
";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$trajet = $stmt->fetch();

if (!$trajet) {
    echo "Trajet introuvable.";
    exit;
}

// Préférences
$prefStmt = $pdo->prepare("SELECT * FROM preferences WHERE id_utilisateur = ?");
$prefStmt->execute([$trajet['conducteur_id']]);
$preferences = $prefStmt->fetch();

// Avis
$avisStmt = $pdo->prepare("SELECT * FROM avis WHERE id_conducteur = ?");
$avisStmt->execute([$trajet['conducteur_id']]);
$avisList = $avisStmt->fetchAll();

// Participation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['participer'])) {

    if (!isset($_SESSION['utilisateur_id'])) {
        header('Location: ../index.php?page=connexion');
        exit;
    }

    $utilisateur_id = $_SESSION['utilisateur_id'];
    $stmtUser = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
    $stmtUser->execute([$utilisateur_id]);
    $utilisateur = $stmtUser->fetch();

    if (!$utilisateur || strtolower($utilisateur['role']) !== 'utilisateur') {
        $erreur = "Vous devez avoir un compte utilisateur pour participer.";
    } elseif ($utilisateur['credits'] < 1) {
        $erreur = "Crédits insuffisants pour participer.";
    } elseif ($trajet['places_restantes'] <= 0) {
        $erreur = "Plus de places disponibles pour ce trajet.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO participations (id_trajet, id_utilisateur) VALUES (?, ?)");
        $stmt->execute([$trajet['id'], $utilisateur_id]);

        $pdo->prepare("UPDATE utilisateurs SET credits = credits - 1 WHERE id = ?")
            ->execute([$utilisateur_id]);

        $pdo->prepare("UPDATE trajets SET places_restantes = places_restantes - 1 WHERE id = ?")
            ->execute([$trajet['id']]);

        $_SESSION['credits']--;

        $confirmation = "✅ Participation confirmée ! 1 crédit a été déduit.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail du trajet</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">

    <script>
        function confirmParticipation() {
            return confirm("Confirmez-vous vouloir utiliser 1 crédit pour ce trajet ?");
        }
    </script>
</head>
<body>

<div class="details-container">
    <h1>🌿 Détails du trajet</h1>

    <!-- MESSAGE DE CONFIRMATION -->
    <?php if (isset($confirmation)): ?>
        <div class="success-message"><?= $confirmation ?></div>

    <!-- MESSAGE D'ERREUR -->
    <?php elseif (isset($erreur)): ?>
        <div class="error-message"><?= $erreur ?></div>

        <!-- BOUTON CHANGER DE RÔLE -->
  <?php if ($erreur === "Vous devez avoir un compte utilisateur pour participer.") : ?>
    <div style="text-align:center; margin-top:12px;">
        <a href="index.php?page=profil"
           style="
               display:inline-block;
               padding:10px 18px;
               background:#2e7d32;
               color:white;
               border-radius:8px;
               font-weight:bold;
               text-decoration:none;
               transition:0.2s;
           "
           onmouseover="this.style.background='#276b2b'"
           onmouseout="this.style.background='#2e7d32'">
            🔄 Gérer mon rôle
        </a>
    </div>
<?php endif; ?>


    <?php endif; ?>

    <!-- INFO TRAJET -->
    <div class="section">
        <h2>🛣️ Informations du trajet</h2>
        <p><strong>Départ :</strong> <?= htmlspecialchars($trajet['ville_depart']) ?></p>
        <p><strong>Arrivée :</strong> <?= htmlspecialchars($trajet['ville_arrivee']) ?></p>
        <p><strong>Date :</strong> <?= htmlspecialchars($trajet['date_depart']) ?></p>
        <p><strong>Heure :</strong> <?= htmlspecialchars($trajet['heure_depart']) ?></p>
        <p><strong>Places disponibles :</strong> <?= htmlspecialchars($trajet['places_restantes']) ?></p>
        <p><strong>Écologique :</strong> <?= $trajet['ecologique'] ? 'Oui' : 'Non' ?></p>
    </div>

    <!-- VEHICULE -->
    <?php if ($trajet['marque']): ?>
    <div class="section">
        <h2>🚗 Véhicule</h2>
        <p><strong>Marque :</strong> <?= htmlspecialchars($trajet['marque']) ?></p>
        <p><strong>Modèle :</strong> <?= htmlspecialchars($trajet['modele']) ?></p>
        <p><strong>Énergie :</strong> <?= htmlspecialchars($trajet['energie']) ?></p>
    </div>
    <?php endif; ?>

    <!-- PREFERENCES -->
    <?php if ($preferences): ?>
    <div class="section">
        <h2>💬 Préférences du conducteur</h2>
        <p><strong>Musique :</strong> <?= $preferences['musique'] ? 'Oui' : 'Non' ?></p>
        <p><strong>Animaux :</strong> <?= $preferences['animaux'] ? 'Oui' : 'Non' ?></p>
        <p><strong>Discussions :</strong> <?= $preferences['discussions'] ? 'Oui' : 'Non' ?></p>
    </div>
    <?php endif; ?>

    <!-- AVIS -->
    <div class="section">
        <h2>⭐ Avis sur le conducteur</h2>
        <?php if ($avisList): ?>
            <ul>
                <?php foreach ($avisList as $avis): ?>
                    <li>
                        <strong><?= htmlspecialchars($avis['auteur']) ?> :</strong>
                        <?= htmlspecialchars($avis['commentaire']) ?>
                        <span class="note">(<?= $avis['note'] ?>/5)</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucun avis pour ce conducteur.</p>
        <?php endif; ?>
    </div>

    <!-- PARTICIPER -->
    <div class="section">
        <?php if (!isset($_SESSION['utilisateur_id'])): ?>
            <p>👉 <a href="index.php?page=connexion">Connectez-vous</a> pour participer.</p>

        <?php elseif ($trajet['places_restantes'] <= 0): ?>
            <p>😥 Ce trajet est complet.</p>

        <?php else: ?>
            <form method="post" onsubmit="return confirmParticipation();">
                <input type="hidden" name="participer" value="1">
                <button type="submit" class="btn-participer">
                    🚗 Participer à ce trajet (1 crédit)
                </button>
            </form>
        <?php endif; ?>
    </div>

    <!-- BOUTON RETOUR -->
    <div class="retour-btn-container">
        <a href="<?= htmlspecialchars($_SERVER['HTTP_REFERER'] ?? '/ecoride/index.php?page=home') ?>"
           class="retour-btn">← Retour</a>
    </div>
</div>

</body>
</html>
