<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';

$erreur = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email'] ?? '');
    $motdepasse = trim($_POST['motdepasse'] ?? '');

    // Récupération de l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification du mot de passe
    if ($utilisateur && password_verify($motdepasse, $utilisateur['mot_de_passe'])) {

       
        $_SESSION['utilisateur_id'] = $utilisateur['id'];
        $_SESSION['email'] = $utilisateur['email'];
        $_SESSION['username'] = !empty($utilisateur['pseudo']) ? $utilisateur['pseudo'] : ($utilisateur['username'] ?? '');
        $_SESSION['credits'] = (int)($utilisateur['credits'] ?? 0);
        $_SESSION['role'] = $utilisateur['role'];

        header("Location: index.php?page=profil");
        exit;

    } else {
        $erreur = "❌ Email ou mot de passe incorrect.";
    }
}
?>

<style>
  .auth-wrap{
    min-height: 65vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding: 2rem 1rem;
  }
  .auth-card{
    width: 100%;
    max-width: 520px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 18px 40px rgba(0,0,0,.08);
    overflow:hidden;
    border: 1px solid rgba(46,125,50,.10);
  }
  .auth-header{
    padding: 1.6rem 1.6rem 1rem;
    background: linear-gradient(180deg, rgba(46,125,50,.10), rgba(46,125,50,0));
  }
  .auth-title{
    display:flex;
    align-items:center;
    gap:.7rem;
    margin:0;
    font-size: 1.6rem;
    color:#1b5e20;
    font-weight: 800;
  }
  .auth-subtitle{
    margin:.4rem 0 0;
    color:#5f6b61;
    font-size:.95rem;
    line-height:1.4;
  }
  .auth-body{
    padding: 1.4rem 1.6rem 1.6rem;
  }
  .field{ margin-bottom: 1rem; }
  .field label{
    display:block;
    font-weight: 600;
    margin-bottom: .4rem;
    color:#234;
  }
  .input{
    width:100%;
    padding:.85rem .9rem;
    border-radius: 12px;
    border:1px solid #dfe7e1;
    background:#fbfcfb;
    outline:none;
    transition:.2s;
  }
  .input:focus{
    border-color: rgba(46,125,50,.45);
    box-shadow: 0 0 0 4px rgba(46,125,50,.12);
    background:#fff;
  }
  .btn{
    width:100%;
    padding:.9rem 1rem;
    border:none;
    border-radius: 12px;
    background:#2e7d32;
    color:#fff;
    font-weight: 800;
    cursor:pointer;
    transition:.2s;
  }
  .btn:hover{ transform: translateY(-1px); filter: brightness(1.02); }
  .btn:active{ transform: translateY(0px); }

  .alert{
    margin-top: 1rem;
    padding: .85rem .9rem;
    border-radius: 12px;
    background: rgba(198,40,40,.08);
    border: 1px solid rgba(198,40,40,.20);
    color:#b71c1c;
    font-weight: 700;
    text-align:center;
  }
  .hint{
    margin-top:.8rem;
    padding: .75rem .9rem;
    border-radius: 12px;
    background: rgba(25,118,210,.07);
    border: 1px solid rgba(25,118,210,.15);
    color:#0d47a1;
    font-size:.92rem;
  }
  .auth-footer{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:1rem;
    margin-top: 1rem;
    font-size:.92rem;
  }
  .auth-footer a{
    color:#2e7d32;
    text-decoration:none;
    font-weight:700;
  }
  .auth-footer a:hover{ text-decoration:underline; }

  @media (max-width:520px){
    .auth-header, .auth-body{ padding: 1.2rem; }
    .auth-title{ font-size: 1.35rem; }
    .auth-footer{ flex-direction:column; align-items:flex-start; }
  }
</style>

<div class="auth-wrap">
  <div class="auth-card">

    <div class="auth-header">
      <h2 class="auth-title">🔐 Connexion</h2>
      <p class="auth-subtitle">
        Connecte-toi pour accéder à ton espace EcoRide (profil, trajets, réservations).
      </p>
    </div>

    <div class="auth-body">
      <form method="POST" action="">
        <div class="field">
          <label for="email">Email</label>
          <input class="input" type="email" id="email" name="email" required
                 value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                 placeholder="ex : nom@exemple.com">
        </div>

        <div class="field">
          <label for="motdepasse">Mot de passe</label>
          <input class="input" type="password" id="motdepasse" name="motdepasse" required
                 placeholder="••••••••">
        </div>

        <button class="btn" type="submit">Se connecter</button>

        <?php if (!empty($erreur)): ?>
          <div class="alert"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <div class="hint">
          💡 Accès <strong>admin</strong> / <strong>employé</strong> :
          <br>
          <a href="index.php?url=connexionAdmin">Connexion administrateur</a> •
          <a href="index.php?url=connexionEmploye">Connexion employé</a>
        </div>

        <div class="auth-footer">
          <span>Pas encore de compte ?</span>
          <a href="index.php?url=inscription">Créer un compte</a>
        </div>
      </form>
    </div>

  </div>
</div>
