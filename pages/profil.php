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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $nouveau_role = $_POST['role'] ?? '';

    if ($prenom && $nom && in_array($nouveau_role, ['utilisateur', 'chauffeur', 'passager_chauffeur'])) {
        $stmt = $pdo->prepare("UPDATE utilisateurs SET prenom = ?, nom = ?, role = ? WHERE id = ?");
        $stmt->execute([$prenom, $nom, $nouveau_role, $utilisateur_id]);

        $_SESSION['role'] = $nouveau_role;
        $role_message = "✅ Votre profil a bien été mis à jour.";
    }
}


$stmt = $pdo->prepare("SELECT prenom, nom, email, role FROM utilisateurs WHERE id = ?");
$stmt->execute([$utilisateur_id]);
$utilisateur = $stmt->fetch();
$_SESSION['role'] = $utilisateur['role'];

// Récupération des trajets uniquement si chauffeur ou passager_chauffeur
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
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #e6f2e6;
            margin: 0;
            padding: 0;
        }

        .centered-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 2rem;
            color: #2e7d32;
        }

        .profil-section {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-group input,
        select {
            padding: 0.6rem;
            width: 100%;
            max-width: 400px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button[type="submit"] {
            background-color: #2e7d32;
            color: white;
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #256b2f;
        }

        .action-buttons-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1.2rem;
            margin: 2rem 0;
        }

        .action-button {
            background-color: #4CAF50;
            color: white;
            padding: 0.8rem 1.6rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            text-align: center;
            min-width: 200px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: background-color 0.3s ease;
        }

        .action-button:hover {
            background-color: #388e3c;
        }

        .bloc-trajet {
            background: #f9f9f9;
            border-left: 5px solid #4CAF50;
            border-radius: 10px;
            padding: 1rem;
            margin: 1rem 0;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
        }

        .bloc-trajet p {
            margin: 0.3rem 0;
        }

        h3 {
            margin-top: 2rem;
            color: #2e7d32;
        }
    </style>
</head>
<body>

<section class="profil-section">
    <h2 class="centered-title">👤 Mon profil</h2>

    <form method="POST">
        <div class="form-group">
            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($utilisateur['prenom']) ?>" required>
        </div>

        <div class="form-group">
            <label for="nom">Nom :</label>
            <input type="text" name="nom" id="nom" value="<?= htmlspecialchars($utilisateur['nom']) ?>" required>
        </div>

        <div class="form-group">
            <label for="role">Je souhaite être :</label>
            <select name="role" id="role">
                <option value="utilisateur" <?= $_SESSION['role'] === 'utilisateur' ? 'selected' : '' ?>>Passager</option>
                <option value="chauffeur" <?= $_SESSION['role'] === 'chauffeur' ? 'selected' : '' ?>>Chauffeur</option>
                <option value="passager_chauffeur" <?= $_SESSION['role'] === 'passager_chauffeur' ? 'selected' : '' ?>>Les deux</option>
            </select>
        </div>

        <button type="submit">💾 Mettre à jour</button>
    </form>

    <?php if ($role_message): ?>
        <p style="color: green; font-weight: bold;">✅ <?= $role_message ?></p>
    <?php endif; ?>

    <hr>

    <div class="action-buttons-container">
        <?php if ($_SESSION['role'] === 'chauffeur' || $_SESSION['role'] === 'passager_chauffeur') : ?>
            <a href="index.php?page=devenir_chauffeur" class="action-button">🚗 Ajouter un véhicule</a>
            <a href="index.php?page=saisir_trajet" class="action-button">🛣️ Proposer un trajet</a>
        <?php endif; ?>

        <a href="index.php?page=historique" class="action-button">🕒 Historique des trajets</a>
    </div>

    <?php if (!empty($trajets)): ?>
        <h3>🚗 Mes trajets proposés</h3>
        <?php foreach ($trajets as $trajet): ?>
            <div class="bloc-trajet">
                <p><strong>Départ :</strong> <?= htmlspecialchars($trajet['ville_depart']) ?></p>
                <p><strong>Arrivée :</strong> <?= htmlspecialchars($trajet['ville_arrivee']) ?></p>
                <p><strong>Prix :</strong> <?= htmlspecialchars($trajet['prix']) ?> crédits</p>
                <p><strong>État :</strong>
                    <?= match ($trajet['etat']) {
                        'en_attente' => '🟡 En attente',
                        'en_cours' => '🟠 En cours',
                        'termine' => '✅ Terminé',
                        'annule' => '❌ Annulé',
                        default => htmlspecialchars($trajet['etat'])
                    }; ?>
                </p>

                <?php if ($trajet['etat'] === 'en_attente'): ?>
                    <form method="POST" action="index.php?page=action_trajet" style="display:inline;">
                        <input type="hidden" name="trajet_id" value="<?= $trajet['id'] ?>">
                        <button name="action" value="demarrer">▶️ Démarrer</button>
                        <button name="action" value="annuler" onclick="return confirm('Annuler ce trajet ?')">❌ Annuler</button>
                    </form>
                <?php elseif ($trajet['etat'] === 'en_cours'): ?>
                    <form method="POST" action="index.php?page=action_trajet" style="display:inline;">
                        <input type="hidden" name="trajet_id" value="<?= $trajet['id'] ?>">
                        <button name="action" value="terminer">✅ Arrivée à destination</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>

</body>
</html>
