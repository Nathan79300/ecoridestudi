<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../includes/db.php');

$erreur = null;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $motdepasse = $_POST['motdepasse'] ?? '';

    
    $stmt = $pdo->prepare("SELECT * FROM employes WHERE email = ?");
    $stmt->execute([$email]);
    $employe = $stmt->fetch();

    
    if ($employe && password_verify($motdepasse, $employe['mot_de_passe'])) {
        
        $_SESSION['employe_id'] = $employe['id'];
        $_SESSION['employe_nom'] = $employe['nom'];
        header('Location: index.php?page=espace_employe');
        exit;
    } else {
        $erreur = "❌ Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta charset="UTF-8">
    <title>Connexion Employé - EcoRide</title>
</head>
<body>

<div class="login-container">
    <h2>👷 Connexion Employé</h2>

    <?php if ($erreur): ?>
        <div class="error-message"><?= htmlspecialchars($erreur) ?></div>
    <?php endif; ?>

    <form method="POST" class="login-form">
        <label for="email">📧 Email :</label>
        <input type="email" id="email" name="email" required>

        <label for="motdepasse">🔒 Mot de passe :</label>
        <input type="password" id="motdepasse" name="motdepasse" required>

        <button type="submit">Se connecter</button>
    </form>
</div>

<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #eaf8e6;
        margin: 0;
        padding: 2rem;
    }

    .login-container {
        max-width: 400px;
        margin: 60px auto;
        padding: 2rem;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(0, 80, 0, 0.1);
    }

    .login-container h2 {
        text-align: center;
        color: #2e7d32;
        margin-bottom: 1.5rem;
    }

    .login-form label {
        display: block;
        margin: 0.5rem 0 0.2rem;
        font-weight: bold;
    }

    .login-form input {
        width: 100%;
        padding: 0.6rem;
        margin-bottom: 1rem;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    .login-form button {
        width: 100%;
        padding: 0.7rem;
        background-color: #2e7d32;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        cursor: pointer;
    }

    .login-form button:hover {
        background-color: #1b5e20;
    }

    .error-message {
        background-color: #ffdede;
        color: #c62828;
        padding: 0.8rem;
        text-align: center;
        border-radius: 6px;
        margin-bottom: 1rem;
    }
</style>

</body>
</html>
