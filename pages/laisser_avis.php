<?php
require_once(__DIR__ . '/../includes/db.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: index.php?page=connexion');
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];
$trajet_id = $_GET['trajet_id'] ?? null;
$erreur = null;
$success = null;


$stmt = $pdo->prepare("
    SELECT t.*, u.username AS conducteur_nom
    FROM trajets t
    JOIN utilisateurs u ON t.conducteur_id = u.id
    JOIN participations p ON p.id_trajet = t.id
    WHERE t.id = ? AND p.id_utilisateur = ? AND t.etat = 'termine'
");
$stmt->execute([$trajet_id, $utilisateur_id]);
$trajet = $stmt->fetch();

if (!$trajet) {
    $erreur = "❌ Ce trajet n'existe pas ou vous ne pouvez pas laisser d'avis.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['note'], $_POST['commentaire'])) {
    $note = (int) $_POST['note'];
    $commentaire = trim($_POST['commentaire']);

    if ($note < 1 || $note > 5 || empty($commentaire)) {
        $erreur = "❌ Veuillez fournir une note entre 1 et 5 et un commentaire.";
    } else {
        
        $check = $pdo->prepare("SELECT COUNT(*) FROM avis WHERE id_utilisateur = ? AND id_trajet = ?");
        $check->execute([$utilisateur_id, $trajet_id]);
        if ($check->fetchColumn() > 0) {
            $erreur = "❌ Vous avez déjà laissé un avis pour ce trajet.";
        } else {
            $insert = $pdo->prepare("INSERT INTO avis (id_utilisateur, id_conducteur, id_trajet, note, commentaire, valide, probleme, statut) 
                VALUES (?, ?, ?, ?, ?, 0, 0, 'en attente')");
            $insert->execute([$utilisateur_id, $trajet['conducteur_id'], $trajet_id, $note, $commentaire]);

            $success = "✅ Votre avis a été enregistré et sera visible après validation.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta charset="UTF-8">
    <title>Laisser un avis - EcoRide</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #eaf6ec;
            padding: 2rem;
        }

        .avis-container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
        }

        h2 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 1.5rem;
        }

        label {
            font-weight: bold;
            margin-top: 1rem;
            display: block;
        }

        input[type="number"],
        textarea {
            width: 100%;
            padding: 0.6rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-top: 0.4rem;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        button {
            background-color: #2e7d32;
            color: white;
            padding: 0.7rem 1.2rem;
            border: none;
            border-radius: 6px;
            margin-top: 1.5rem;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #256b2f;
        }

        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 6px;
            text-align: center;
        }

        .alert-success {
            background-color: #d0f0c0;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }

        .alert-error {
            background-color: #ffdede;
            color: #c62828;
            border: 1px solid #e57373;
        }
    </style>
</head>
<body>

<div class="avis-container">
    <h2>📝 Laisser un avis</h2>

    <?php if ($erreur): ?>
        <div class="alert alert-error"><?= htmlspecialchars($erreur) ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($trajet && !$success): ?>
        <p><strong>Trajet :</strong> <?= htmlspecialchars($trajet['ville_depart']) ?> → <?= htmlspecialchars($trajet['ville_arrivee']) ?> avec <?= htmlspecialchars($trajet['conducteur_nom']) ?></p>

        <form method="POST">
            <label for="note">Note (1 à 5) :</label>
            <input type="number" id="note" name="note" min="1" max="5" required>

            <label for="commentaire">Commentaire :</label>
            <textarea id="commentaire" name="commentaire" required></textarea>

            <button type="submit">💬 Envoyer l'avis</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
