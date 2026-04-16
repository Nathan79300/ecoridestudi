<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../includes/db.php');
include_once(__DIR__ . '/../includes/protect.php');

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: index.php?page=connexion');
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];
$role_message = null;

// Mise à jour profil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $nouveau_role = $_POST['role'] ?? '';

    if ($prenom && $nom && in_array($nouveau_role, ['utilisateur', 'chauffeur', 'passager_chauffeur'])) {
        $stmt = $pdo->prepare("UPDATE utilisateurs SET prenom = ?, nom = ?, role = ? WHERE id = ?");
        $stmt->execute([$prenom, $nom, $nouveau_role, $utilisateur_id]);

        $_SESSION['role'] = $nouveau_role;
        $role_message = "Votre profil a bien été mis à jour.";
    }
}

// Récupération infos utilisateur
$stmt = $pdo->prepare("SELECT prenom, nom, email, role FROM utilisateurs WHERE id = ?");
$stmt->execute([$utilisateur_id]);
$utilisateur = $stmt->fetch();
$_SESSION['role'] = $utilisateur['role'];

// Récupération trajets si chauffeur
$trajets = [];
if (in_array($_SESSION['role'], ['chauffeur', 'passager_chauffeur'])) {
    $stmt_trajets = $pdo->prepare("SELECT * FROM trajets WHERE conducteur_id = ? ORDER BY date_depart DESC");
    $stmt_trajets->execute([$utilisateur_id]);
    $trajets = $stmt_trajets->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">
<title>Mon profil - EcoRide</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
    body {
        font-family: "Poppins", sans-serif;
        background: #e8f5e9;
        margin: 0;
        padding: 0;
    }

    .profil-container {
        max-width: 750px;
        margin: 3rem auto;
        background: white;
        padding: 3rem 3rem 2rem;
        border-radius: 20px;
        border-left: 6px solid #4caf50;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .profil-container h2 {
        font-size: 2.3rem;
        text-align: center;
        margin-bottom: 2rem;
        color: #2e7d32;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
    }

    .form-group {
        margin-bottom: 1.4rem;
    }

    .form-group label {
        font-weight: 600;
        display: block;
        margin-bottom: 6px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 0.9rem;
        border: 1px solid #cfcfcf;
        border-radius: 10px;
        font-size: 1rem;
        transition: border-color 0.2s;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #4caf50;
        outline: none;
    }

    .btn-submit {
        width: 100%;
        margin-top: 1rem;
        padding: 0.9rem;
        background: #2e7d32;
        border: none;
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 12px;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-submit:hover {
        background: #276b2b;
    }

    .msg-success {
        background: #e1f7e7;
        color: #2e7d32;
        border-left: 6px solid #4caf50;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        font-weight: 500;
    }

    hr {
        margin: 2.5rem 0;
        border: none;
        border-top: 1px solid #ddd;
    }

    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .action-button {
        padding: 0.9rem 1.4rem;
        background: #4caf50;
        color: white;
        font-weight: 600;
        text-decoration: none;
        border-radius: 12px;
        min-width: 200px;
        text-align: center;
        transition: 0.2s;
        box-shadow: 0 3px 10px rgba(0,0,0,0.15);
    }

    .action-button:hover {
        background: #3d8f41;
    }

    .trajet-card {
        background: #f6fdf7;
        padding: 1.2rem;
        border-radius: 15px;
        border-left: 5px solid #4caf50;
        margin-bottom: 1rem;
        box-shadow: 0 4px 14px rgba(0,0,0,0.08);
    }

    .trajet-card p {
        margin: 0.3rem 0;
    }

    .trajet-card form button {
        background: #2e7d32;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        margin-right: 0.5rem;
    }

    .trajet-card form button:hover {
        background: #276b2b;
    }
</style>
</head>

<body>

<div class="profil-container">

    <h2>👤 Mon profil</h2>

    <?php if ($role_message): ?>
        <div class="msg-success"><?= $role_message ?></div>
    <?php endif; ?>

    <form method="POST">

        <div class="form-group">
            <label>Prénom :</label>
            <input type="text" name="prenom" value="<?= htmlspecialchars($utilisateur['prenom']) ?>" required>
        </div>

        <div class="form-group">
            <label>Nom :</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($utilisateur['nom']) ?>" required>
        </div>

        <div class="form-group">
            <label>Je souhaite être :</label>
            <select name="role">
                <option value="utilisateur" <?= $_SESSION['role']=='utilisateur'?'selected':'' ?>>Passager</option>
                <option value="chauffeur" <?= $_SESSION['role']=='chauffeur'?'selected':'' ?>>Chauffeur</option>
                <option value="passager_chauffeur" <?= $_SESSION['role']=='passager_chauffeur'?'selected':'' ?>>Les deux</option>
            </select>
        </div>

        <button type="submit" class="btn-submit">💾 Mettre à jour</button>

    </form>

    <hr>

    <div class="action-buttons">
        <?php if ($_SESSION['role'] !== 'utilisateur'): ?>
            <a href="index.php?page=devenir_chauffeur" class="action-button">🚗 Ajouter un véhicule</a>
            <a href="index.php?page=saisir_trajet" class="action-button">🛣️ Proposer un trajet</a>
        <?php endif; ?>

        <a href="index.php?page=historique" class="action-button">🕒 Historique des trajets</a>
    </div>

    <?php if (!empty($trajets)): ?>
        <h3 style="margin-top: 2rem; color:#2e7d32;">🚗 Mes trajets proposés</h3>

        <?php foreach ($trajets as $trajet): ?>
            <div class="trajet-card">
                <p><strong>Départ :</strong> <?= htmlspecialchars($trajet['ville_depart']) ?></p>
                <p><strong>Arrivée :</strong> <?= htmlspecialchars($trajet['ville_arrivee']) ?></p>
                <p><strong>Prix :</strong> <?= htmlspecialchars($trajet['prix']) ?> crédits</p>
                <p><strong>État :</strong>
                    <?= match ($trajet['etat']) {
                        'en_attente' => "🟡 En attente",
                        'en_cours' => "🟠 En cours",
                        'termine' => "🟢 Terminé",
                        'annule' => "🔴 Annulé",
                        default => htmlspecialchars($trajet['etat']),
                    }; ?>
                </p>

                <?php if ($trajet['etat'] === 'en_attente'): ?>
                    <form method="POST" action="index.php?page=action_trajet">
                        <input type="hidden" name="trajet_id" value="<?= $trajet['id'] ?>">
                        <button name="action" value="demarrer">▶️ Démarrer</button>
                        <button name="action" value="annuler">❌ Annuler</button>
                    </form>

                <?php elseif ($trajet['etat'] === 'en_cours'): ?>
                    <form method="POST" action="index.php?page=action_trajet">
                        <input type="hidden" name="trajet_id" value="<?= $trajet['id'] ?>">
                        <button name="action" value="terminer">✅ Arrivée à destination</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>

</div>

</body>
</html>
