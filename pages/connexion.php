<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';

$erreur = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $motdepasse = trim($_POST['motdepasse']);

    // Récupération de l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification du mot de passe
    if ($utilisateur && password_verify($motdepasse, $utilisateur['mot_de_passe'])) {

        // Session utilisateur
        $_SESSION['utilisateur_id'] = $utilisateur['id'];
        $_SESSION['email'] = $utilisateur['email'];
        $_SESSION['username'] = $utilisateur['pseudo'] ?: $utilisateur['username'];
        $_SESSION['credits'] = $utilisateur['credits'];
        $_SESSION['role'] = $utilisateur['role'];

        header("Location: index.php?page=profil");
        exit;

    } else {
        $erreur = "❌ Email ou mot de passe incorrect.";
    }
}
?>

<h2 style="text-align:center; margin-top:2rem;">🔐 Connexion</h2>

<form method="POST"
      style="max-width:400px;margin:2rem auto;padding:1.5rem;background:#fff;border-radius:10px;
             box-shadow:0 0 10px rgba(0,0,0,0.1);">

    <label>Email :</label>
    <input type="email" name="email" required
           style="width:100%;padding:0.6rem;margin-bottom:1rem;border-radius:6px;border:1px solid #ccc;">

    <label>Mot de passe :</label>
    <input type="password" name="motdepasse" required
           style="width:100%;padding:0.6rem;margin-bottom:1rem;border-radius:6px;border:1px solid #ccc;">

    <button type="submit"
            style="width:100%;padding:0.8rem;background:#2e7d32;color:white;border:none;border-radius:6px;">
        Se connecter
    </button>

    <?php if (!empty($erreur)): ?>
        <p style="text-align:center;color:red;margin-top:1rem;"><?= $erreur ?></p>
    <?php endif; ?>

</form>
