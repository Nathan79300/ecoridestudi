<?php
require_once __DIR__ . '/../config.php';
?>

<footer class="site-footer">
  <div class="footer-inner">

    <p class="footer-copy">
      EcoRide © <?= date("Y") ?>
    </p>

    <div class="footer-links">
      <a href="<?= BASE_URL ?>index.php?url=mentions">
        📄 Mentions légales
      </a>

      <span class="sep">•</span>

      <a href="<?= BASE_URL ?>index.php?url=cgu">
        📜 CGU
      </a>
    </div>

  </div>
</footer>