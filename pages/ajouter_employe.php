<?php

require_once(__DIR__ . '/../includes/db.php');


if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php?page=connexion_admin');
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom'], $_POST['email'], $_POST['motdepasse'])) {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $motdepasse = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);

    
    $verif = $pdo->prepare("SELECT id FROM employes WHERE email = ?");
    $verif->execute([$email]);

    if ($verif->fetch()) {
        echo "❌ Cet email existe déjà.";
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO employes (nom, email, mot_de_passe) VALUES (?, ?, ?)");
    $stmt->execute([$nom, $email, $motdepasse]);

    header('Location: index.php?page=espace_admin');
    exit;
} else {
    echo "❌ Requête invalide.";
}
