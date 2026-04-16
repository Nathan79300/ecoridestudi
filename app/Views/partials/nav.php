<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$BASE_URL = "/ecoridestudi/ecoride/public";


function displayNameFromUser(array $u): string {
    $prenom = trim($u['prenom'] ?? '');
    $nom = trim($u['nom'] ?? '');
    $full = trim($prenom . ' ' . $nom);
    if ($full !== '') return $full;
    if (!empty($u['username'])) return $u['username'];
    if (!empty($u['email'])) return $u['email'];
    return 'Compte';
}


$isLoggedNew = !empty($_SESSION['user']);
$isLoggedOld = !empty($_SESSION['utilisateur_id']);
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
    </ul>

    <div class="navbar_user">
      <?php if ($isLoggedNew): ?>
        <?php
          $u = $_SESSION['user'];
          $role = $u['role'] ?? '';
          $name = displayNameFromUser($u);
          $credits = $u['credits'] ?? null; 
        ?>

        👋 <strong><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></strong>
        <?php if ($role !== ''): ?>
          — <span style="opacity:.8; font-size:.95em;"><?= htmlspecialchars($role) ?></span>
        <?php endif; ?>

        <?php if ($credits !== null && $role !== 'admin' && $role !== 'employe'): ?>
          — 💳 <?= (int)$credits ?> crédits
        <?php endif; ?>

        <?php if ($role === 'admin'): ?>
          | <a href="<?= $BASE_URL ?>/index.php?url=admin">Espace admin</a>
        <?php elseif ($role === 'employe'): ?>
          | <a href="<?= $BASE_URL ?>/index.php?url=employe">Espace employé</a>
        <?php else: ?>
          | <a href="<?= $BASE_URL ?>/index.php?url=profil">Mon espace</a>
        <?php endif; ?>

        | <a href="<?= $BASE_URL ?>/index.php?url=logout" style="color:#c62828;">Se déconnecter</a>

      <?php elseif ($isLoggedOld): ?>
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
  const menu = document.getElementById("navbarMenu");
  if (menu) menu.classList.toggle("active");
}
</script>
