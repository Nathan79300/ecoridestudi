<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../includes/db.php');

$erreur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $motdepasse = $_POST['motdepasse'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ? AND role = 'admin'");
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($motdepasse, $admin['mot_de_passe'])) {
        
        unset($_SESSION['utilisateur_id'], $_SESSION['username'], $_SESSION['credits']);

        
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_nom'] = $admin['nom'];
        header('Location: index.php?page=espace_admin');
        exit;
    } else {
        $erreur = "❌ Email ou mot de passe incorrect.";
    }
}
?>
<head><meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<h2 style="text-align:center; color:#2e7d32; margin-top:2rem;">🔐 Connexion Administrateur</h2>

<form method="POST" style="max-width: 400px; margin: 2rem auto; padding: 2rem; background: #ffffff; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
    <div style="margin-bottom: 1.2rem;">
        <label for="email" style="display:block; font-weight:bold; margin-bottom:0.5rem;">📧 Email :</label>
        <input type="email" name="email" id="email" required style="width:100%; padding:0.6rem; border-radius: 6px; border:1px solid #ccc;">
    </div>

    <div style="margin-bottom: 1.2rem;">
        <label for="motdepasse" style="display:block; font-weight:bold; margin-bottom:0.5rem;">🔑 Mot de passe :</label>
        <input type="password" name="motdepasse" id="motdepasse" required style="width:100%; padding:0.6rem; border-radius: 6px; border:1px solid #ccc;">
    </div>

    <button type="submit" style="width:100%; padding:0.8rem; background-color:#2e7d32; color:white; border:none; border-radius:8px; font-weight:bold; cursor:pointer;">
        ✅ Se connecter
    </button>

    <?php if ($erreur): ?>
        <p style="color: #c62828; text-align: center; margin-top: 1rem; font-weight:bold;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>
</form>
