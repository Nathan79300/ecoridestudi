<?php
require_once(__DIR__ . '/includes/db.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['utilisateur_id']) || !in_array($_SESSION['role'], ['chauffeur', 'passager_chauffeur'])) {
    header('Location: index.php?page=connexion');
    exit;
}

if (!isset($_POST['trajet_id'])) {
    $message = "❌ ID de trajet manquant.";
} else {
    $trajet_id = (int) $_POST['trajet_id'];
    $conducteur_id = $_SESSION['utilisateur_id'];

    $stmt = $pdo->prepare("UPDATE trajets SET etat = 'en_cours' WHERE id = ? AND conducteur_id = ?");
    $success = $stmt->execute([$trajet_id, $conducteur_id]);

    if ($success) {
        $message = "✅ Le trajet n°{$trajet_id} a bien démarré.";
    } else {
        $message = "❌ Erreur lors du démarrage du trajet.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta charset="UTF-8">
    <title>Trajet démarré - EcoRide</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #e6f2e6;
            padding: 2rem;
            text-align: center;
        }
        .card {
            background-color: white;
            max-width: 500px;
            margin: 4rem auto;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
        .message {
            font-size: 1.2rem;
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
        }
        .success {
            background-color: #d0f0c0;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }
        .error {
            background-color: #f8d7da;
            color: #c62828;
            border: 1px solid #f5c6cb;
        }
        .btn {
            background-color: #388e3c;
            color: white;
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #2e7d32;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>🚗 Démarrage du trajet</h2>
        <div class="message <?= $success ? 'success' : 'error' ?>">
            <?= $message ?>
        </div>
        <a href="index.php?page=historique" class="btn">🔙 Retour à l’historique</a>
    </div>
</body>
</html>
