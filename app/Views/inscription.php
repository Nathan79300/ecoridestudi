<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../includes/db.php';

$erreur = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $motdepasse = trim($_POST['motdepasse'] ?? '');

    if ($prenom === '' || $nom === '' || $email === '' || $motdepasse === '') {
        $erreur = "❌ Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = "❌ Email invalide.";
    } elseif (strlen($motdepasse) < 6) {
        $erreur = "❌ Mot de passe trop court (6 caractères minimum).";
    } else {

        
        $check = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ? LIMIT 1");
        $check->execute([$email]);

        if ($check->fetch()) {
            $erreur = "❌ Un compte existe déjà avec cet email.";
        } else {

            $hash = password_hash($motdepasse, PASSWORD_DEFAULT);

            
            $username = strtolower(preg_replace('/\s+/', '', $prenom)) . rand(10, 99);

            // 20 crédits à l'inscription
            $credits = 20;

            $stmt = $pdo->prepare("
                INSERT INTO utilisateurs (username, pseudo, nom, prenom, email, mot_de_passe, role, credits, suspendu)
                VALUES (?, NULL, ?, ?, ?, ?, 'utilisateur', ?, 0)
            ");

            $ok = $stmt->execute([
                $username,
                $nom,
                $prenom,
                $email,
                $hash,
                $credits
            ]);

            if ($ok) {
                $success = "✅ Compte créé ! Tu peux te connecter.";
               
            } else {
                $erreur = "❌ Une erreur est survenue lors de l'inscription.";
            }
        }
    }
}
?>

<style>
  .auth-wrap{ min-height:65vh; display:flex; align-items:center; justify-content:center; padding:2rem 1rem; }
  .auth-card{ width:100%; max-width:560px; background:#fff; border-radius:16px; box-shadow:0 18px 40px rgba(0,0,0,.08);
    overflow:hidden; border:1px solid rgba(46,125,50,.10); }
  .auth-header{ padding:1.6rem 1.6rem 1rem; background:linear-gradient(180deg, rgba(46,125,50,.10), rgba(46,125,50,0)); }
  .auth-title{ display:flex; align-items:center; gap:.7rem; margin:0; font-size:1.6rem; color:#1b5e20; font-weight:800; }
  .auth-subtitle{ margin:.4rem 0 0; color:#5f6b61; font-size:.95rem; line-height:1.4; }
  .auth-body{ padding:1.4rem 1.6rem 1.6rem; }

  .grid{ display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
  .field{ margin-bottom:1rem; }
  .field label{ display:block; font-weight:600; margin-bottom:.4rem; color:#234; }
  .input{ width:100%; padding:.85rem .9rem; border-radius:12px; border:1px solid #dfe7e1; background:#fbfcfb; outline:none; transition:.2s; }
  .input:focus{ border-color:rgba(46,125,50,.45); box-shadow:0 0 0 4px rgba(46,125,50,.12); background:#fff; }

  .btn{ width:100%; padding:.9rem 1rem; border:none; border-radius:12px; background:#2e7d32; color:#fff; font-weight:800; cursor:pointer; transition:.2s; }
  .btn:hover{ transform:translateY(-1px); filter:brightness(1.02); }
  .btn:active{ transform:translateY(0px); }

  .alert{ margin-top:1rem; padding:.85rem .9rem; border-radius:12px; background:rgba(198,40,40,.08);
    border:1px solid rgba(198,40,40,.20); color:#b71c1c; font-weight:700; text-align:center; }

  .success{ margin-top:1rem; padding:.85rem .9rem; border-radius:12px; background:rgba(46,125,50,.10);
    border:1px solid rgba(46,125,50,.22); color:#1b5e20; font-weight:800; text-align:center; }

  .hint{ margin-top:1rem; padding:.9rem; border-radius:14px; background:rgba(25,118,210,.07);
    border:1px solid rgba(25,118,210,.15); color:#0d47a1; font-size:.92rem; line-height:1.35; }

  .auth-footer{ display:flex; justify-content:space-between; align-items:center; gap:1rem; margin-top:1rem; font-size:.92rem; }
  .auth-footer a{ color:#2e7d32; text-decoration:none; font-weight:800; }
  .auth-footer a:hover{ text-decoration:underline; }

  @media (max-width:620px){
    .auth-header, .auth-body{ padding:1.2rem; }
    .auth-title{ font-size:1.35rem; }
    .grid{ grid-template-columns:1fr; }
    .auth-footer{ flex-direction:column; align-items:flex-start; }
  }
</style>

<div class="auth-wrap">
  <div class="auth-card">
    <div class="auth-header">
      <h2 class="auth-title">📝 Inscription</h2>
      <p class="auth-subtitle">Crée ton compte EcoRide (🎁 20 crédits offerts à l’inscription).</p>
    </div>

    <div class="auth-body">
      <form method="POST" action="">
        <div class="grid">
          <div class="field">
            <label for="prenom">Prénom</label>
            <input class="input" type="text" id="prenom" name="prenom" required
                   value="<?= htmlspecialchars($_POST['prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   placeholder="ex : Alice">
          </div>

          <div class="field">
            <label for="nom">Nom</label>
            <input class="input" type="text" id="nom" name="nom" required
                   value="<?= htmlspecialchars($_POST['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                   placeholder="ex : Martin">
          </div>
        </div>

        <div class="field">
          <label for="email">Email</label>
          <input class="input" type="email" id="email" name="email" required
                 value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                 placeholder="ex : nom@exemple.com">
        </div>

        <div class="field">
          <label for="motdepasse">Mot de passe</label>
          <input class="input" type="password" id="motdepasse" name="motdepasse" required
                 placeholder="6 caractères minimum">
        </div>

        <button class="btn" type="submit">Créer mon compte</button>

        <?php if (!empty($erreur)): ?>
          <div class="alert"><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
          <div class="success"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <div class="hint">
          ✅ Déjà inscrit ? <a href="index.php?url=connexion" style="font-weight:800; color:#0d47a1; text-decoration:none;">Se connecter</a>
        </div>

        <div class="auth-footer">
          <span>En créant un compte, tu acceptes les CGU.</span>
          <a href="index.php?url=cgu">Lire les CGU</a>
        </div>
      </form>
    </div>
  </div>
</div>
