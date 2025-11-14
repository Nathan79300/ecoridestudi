<?php
require_once __DIR__ . '/../includes/db.php';

$erreur = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $utilisateur = $stmt->fetch();

    if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
        $_SESSION['utilisateur_id'] = $utilisateur['id'];
        $_SESSION['email'] = $utilisateur['email'];
        $_SESSION['username'] = $utilisateur['username'];
        $_SESSION['credits'] = $utilisateur['credits'];

        header("Location: index.php?page=home");
        exit();
    } else {
        $erreur = "❌ Email ou mot de passe incorrect";
    }
}
?>
<head><meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<main>
  <div class="form-container">
    <h2>🔐 Connexion à EcoRide</h2>

    <?php if (!empty($erreur)) : ?>
      <p class="error-message" style="color: red; font-weight: bold;"><?= $erreur ?></p>
    <?php endif; ?>

    <form method="POST" action="index.php?page=connexion">
      <label for="email">Email :</label>
      <input class="form-input" type="email" name="email" id="email" required>

      <label for="mot_de_passe">Mot de passe :</label>
      <input class="form-input" type="password" name="mot_de_passe" id="mot_de_passe" required>

      <button class="form-button" type="submit">Se connecter</button>
    </form>
  </div>
</main>
