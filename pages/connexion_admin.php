<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../includes/db.php');

$erreur = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $motdepasse = trim($_POST['motdepasse'] ?? '');

    if ($email === '' || $motdepasse === '') {
        $erreur = "Veuillez remplir tous les champs.";
    } else {

        // On récupère l'admin dans la table utilisateurs
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ? AND role = 'admin'");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {

            if (password_verify($motdepasse, $admin['mot_de_passe'])) {

                // Nettoyage session utilisateur normal
                unset($_SESSION['utilisateur_id'], $_SESSION['role'], $_SESSION['credits']);

                // Session admin
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_nom'] = $admin['prenom'];

                header('Location: index.php?page=espace_admin');
                exit;

            } else {
                $erreur = "❌ Mot de passe incorrect.";
            }

        } else {
            $erreur = "❌ Aucun administrateur trouvé avec cet email.";
        }
    }
}
?>

<h2 style="text-align:center; color:#2e7d32; margin-top:2rem;">
    🔐 Connexion Administrateur
</h2>

<form method="POST"
      style="max-width: 400px; margin: 2rem auto; padding: 2rem; background: #ffffff; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
    
    <label>Email :</label>
    <input type="email" name="email" required
           style="width:100%; padding:0.6rem; margin-bottom:1rem; border-radius:6px; border:1px solid #ccc;">

    <label>Mot de passe :</label>
    <input type="password" name="motdepasse" required
           style="width:100%; padding:0.6rem; margin-bottom:1rem; border-radius:6px; border:1px solid #ccc;">

    <button type="submit"
            style="width:100%; padding:0.8rem; background:#2e7d32; color:white; border:none; border-radius:8px;">
        Se connecter
    </button>

    <?php if ($erreur): ?>
        <p style="color:#c62828; text-align:center; margin-top:1rem; font-weight:bold;">
            <?= htmlspecialchars($erreur) ?>
        </p>
    <?php endif; ?>
</form>
