<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../../../includes/db.php');

$erreur = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $motdepasse = trim($_POST['motdepasse'] ?? '');

    if ($email === '' || $motdepasse === '') {
        $erreur = "❌ Veuillez remplir tous les champs.";
    } else {

        $stmt = $pdo->prepare("
            SELECT * FROM utilisateurs 
            WHERE email = ? AND role = 'employe' AND suspendu = 0
            LIMIT 1
        ");
        $stmt->execute([$email]);
        $employe = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($employe && password_verify($motdepasse, $employe['mot_de_passe'])) {

            session_regenerate_id(true);
            $_SESSION = [];

            $_SESSION['user'] = [
                'id'       => (int)$employe['id'],
                'role'     => 'employe',
                'nom'      => $employe['nom'] ?? '',
                'prenom'   => $employe['prenom'] ?? '',
                'email'    => $employe['email'] ?? '',
                'username' => $employe['username'] ?? '',
            ];

            header('Location: index.php?url=employe');
            exit;

        } else {
            $erreur = "❌ Email ou mot de passe incorrect.";
        }
    }
}
?>

<h2 style="text-align:center; margin-top:2rem; color:#2e7d32;">👷 Connexion Employé</h2>

<form method="POST" 
      style="max-width:400px;margin:2rem auto;padding:1.5rem;background:#fff;border-radius:10px;box-shadow:0 0 10px rgba(0,0,0,0.1);">

    <label>Email :</label>
    <input type="email" name="email" required
           style="width:100%;padding:0.6rem;margin-bottom:1rem;border-radius:6px;border:1px solid #ccc;"
           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

    <label>Mot de passe :</label>
    <input type="password" name="motdepasse" required
           style="width:100%;padding:0.6rem;margin-bottom:1rem;border-radius:6px;border:1px solid #ccc;">

    <button type="submit"
            style="width:100%;padding:0.8rem;background:#2e7d32;color:white;border:none;border-radius:6px;">
        Se connecter
    </button>

    <?php if (!empty($erreur)): ?>
        <p style="text-align:center;color:#c62828;margin-top:1rem;font-weight:600;">
            <?= htmlspecialchars($erreur) ?>
        </p>
    <?php endif; ?>

</form>
