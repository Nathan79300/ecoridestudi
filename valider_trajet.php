<?php
require_once 'includes/db.php';
session_start();

if (!isset($_SESSION['utilisateur_id']) || $_SESSION['role'] !== 'utilisateur') {
    header('Location: index.php?page=connexion');
    exit;
}

if (!isset($_GET['id'])) {
    echo "ID manquant.";
    exit;
}

$trajet_id = (int) $_GET['id'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta charset="UTF-8">
    <title>Valider le trajet - EcoRide</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #e6f2e6;
            margin: 0;
            padding: 2rem;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        h2 {
            color: #2e7d32;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-top: 1rem;
        }

        input[type="radio"] {
            margin-right: 0.5rem;
        }

        textarea, select {
            width: 100%;
            padding: 0.6rem;
            margin-top: 0.5rem;
            border-radius: 5px;
            border: 1px solid #ccc;
            resize: vertical;
        }

        button {
            background-color: #388e3c;
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 1.5rem;
        }

        button:hover {
            background-color: #2e7d32;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>🚗 Valider le trajet</h2>

    <form method="post" action="traiter_validation.php">
        <input type="hidden" name="trajet_id" value="<?= $trajet_id ?>">

        <label>
            <input type="radio" name="etat" value="ok" required>
            ✅ Tout s’est bien passé
        </label>

        <label>
            <input type="radio" name="etat" value="probleme" required>
            ❌ Il y a eu un problème
        </label>

        <label for="commentaire">💬 Commentaire (obligatoire en cas de problème)</label>
        <textarea name="commentaire" rows="3" placeholder="Ex. : Retard important, comportement du chauffeur, etc."></textarea>

        <label for="note">⭐ Note</label>
        <select name="note" id="note">
            <option value="">-- Sélectionnez une note --</option>
            <?php for ($i = 5; $i >= 1; $i--) echo "<option value='$i'>$i</option>"; ?>
        </select>

        <label for="avis">📝 Avis (facultatif)</label>
        <textarea name="avis" rows="3" placeholder="Partagez votre avis sur ce trajet..."></textarea>

        <button type="submit">Envoyer</button>
    </form>
</div>
</body>
</html>
