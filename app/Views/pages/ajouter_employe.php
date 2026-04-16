<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? null) !== 'admin') {
    header('Location: index.php?url=connexionAdmin');
    exit;
}

require_once(__DIR__ . '/../../../includes/db.php');

$erreur = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mdp = trim($_POST['motdepasse'] ?? '');

    if ($nom === '' || $prenom === '' || $email === '' || $mdp === '') {
        $erreur = "❌ Tous les champs sont obligatoires.";
    } else {
        $verif = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ? LIMIT 1");
        $verif->execute([$email]);

        if ($verif->fetch()) {
            $erreur = "❌ Cet email existe déjà.";
        } else {

            $motdepasse = password_hash($mdp, PASSWORD_DEFAULT);
            $username = strtolower(preg_replace('/\s+/', '', $prenom));

            $stmt = $pdo->prepare("
                INSERT INTO utilisateurs (username, nom, prenom, email, mot_de_passe, role, credits, photo, suspendu)
                VALUES (?, ?, ?, ?, ?, 'employe', 0, 'default.jpg', 0)
            ");

            $stmt->execute([$username, $nom, $prenom, $email, $motdepasse]);

            $success = "✅ Employé créé avec succès.";
        }
    }
}
?>

<h2 style="text-align:center; margin-top:2rem; color:#2e7d32;">➕ Ajouter un employé</h2>

<form method="POST" style="max-width:520px;margin:2rem auto;background:#fff;padding:2rem;border-radius:12px;box-shadow:0 0 10px rgba(0,0,0,.06);">
    <label>Nom :</label>
    <input name="nom" required style="width:100%;padding:.6rem;margin:.4rem 0 1rem;border:1px solid #ccc;border-radius:6px;"
           value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">

    <label>Prénom :</label>
    <input name="prenom" required style="width:100%;padding:.6rem;margin:.4rem 0 1rem;border:1px solid #ccc;border-radius:6px;"
           value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>">

    <label>Email :</label>
    <input type="email" name="email" required style="width:100%;padding:.6rem;margin:.4rem 0 1rem;border:1px solid #ccc;border-radius:6px;"
           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

    <label>Mot de passe :</label>
    <input type="password" name="motdepasse" required style="width:100%;padding:.6rem;margin:.4rem 0 1rem;border:1px solid #ccc;border-radius:6px;">

    <button type="submit" style="width:100%;padding:.8rem;background:#2e7d32;color:#fff;border:none;border-radius:8px;">
        Créer l’employé
    </button>

    <?php if ($erreur): ?>
        <p style="color:#c62828;text-align:center;margin-top:1rem;font-weight:600;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color:#2e7d32;text-align:center;margin-top:1rem;font-weight:600;"><?= htmlspecialchars($success) ?></p>
        <p style="text-align:center;margin-top:.5rem;">
            <a href="index.php?url=admin">⬅ Retour espace admin</a>
        </p>
    <?php endif; ?>
</form>
