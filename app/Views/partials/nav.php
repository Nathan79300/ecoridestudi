<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$BASE_URL = "/ecoridestudi/ecoride/public";
?>

<nav class="navbar">

  <a href="<?= $BASE_URL ?>/index.php" class="navbar_title" style="text-decoration:none;">
    <img src="<?= $BASE_URL ?>/assets/images/logo-voiture.webp" alt="Logo EcoRide">
    <h1><span class="eco">Eco</span><span class="ride">Ride</span></h1>
  </a>

  <button class="burger" type="button" onclick="toggleMenu()">☰</button>

  <div class="navbar_menu" id="navbarMenu">
    <ul>
      <li><a href="<?= $BASE_URL ?>/index.php">Accueil</a></li>
      <li><a href="<?= $BASE_URL ?>/index.php?url=recherche" class="green-text">Covoiturages</a></li>
      <!-- Contact seulement quand tu auras ajouté la route -->
      <!-- <li><a href="<?= $BASE_URL ?>/index.php?url=contact">Contact</a></li> -->
    </ul>

    <div class="navbar_user">
      <?php if (!empty($_SESSION['utilisateur_id'])): ?>
        👋 <strong><?= htmlspecialchars($_SESSION['username'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong>
        — 💳 <?= (int)($_SESSION['credits'] ?? 0) ?> crédits |
        <a href="<?= $BASE_URL ?>/index.php?url=profil">Mon espace</a>
        | <a href="<?= $BASE_URL ?>/index.php?url=logout" style="color:#c62828;">Se déconnecter</a>
      <?php else: ?>
        <a href="<?= $BASE_URL ?>/index.php?url=connexion">Connexion</a> |
        <a href="<?= $BASE_URL ?>/index.php?url=inscription">Inscription</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<script>
function toggleMenu() {
  document.getElementById("navbarMenu").classList.toggle("active");
}
</script>
