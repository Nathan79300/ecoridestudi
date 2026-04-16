<section class="page page-recherche">

  <header class="page-head">
    <h1>🔎 Rechercher un trajet</h1>
    <p>Trouvez le covoiturage idéal selon vos critères.</p>
  </header>

  <!-- FORMULAIRE -->
  <form method="GET" action="/ecoridestudi/ecoride/public/index.php" class="card search-card">
    <input type="hidden" name="url" value="recherche">

    <label>Ville de départ</label>
    <input type="text" name="ville_depart" value="<?= htmlspecialchars($_GET['ville_depart'] ?? '') ?>" required>

    <label>Ville d’arrivée</label>
    <input type="text" name="ville_arrivee" value="<?= htmlspecialchars($_GET['ville_arrivee'] ?? '') ?>" required>

    <label>Date de départ</label>
    <input type="date" name="date_depart" value="<?= htmlspecialchars($_GET['date_depart'] ?? '') ?>" required>

    <label>Nombre de passagers</label>
    <input type="number" name="passagers" min="1" max="5" value="<?= htmlspecialchars($_GET['passagers'] ?? 1) ?>" required>

    <label class="checkbox">
      <input type="checkbox" name="ecologique" <?= isset($_GET['ecologique']) ? 'checked' : '' ?>>
      Voyage écologique seulement
    </label>

    <label>Prix maximum (€)</label>
    <input type="number" name="prix_max" value="<?= htmlspecialchars($_GET['prix_max'] ?? '') ?>">

    <label>Durée maximale (minutes)</label>
    <input type="number" name="duree_max" value="<?= htmlspecialchars($_GET['duree_max'] ?? '') ?>">

    <label>Note minimale du conducteur (0 à 5)</label>
    <input type="number" name="note_min" min="0" max="5" value="<?= htmlspecialchars($_GET['note_min'] ?? '') ?>">

    <button class="btn btn-wide">Rechercher</button>
  </form>

  <!-- RÉSULTATS -->
  <?php if ($hasSearch): ?>

    <?php if (!empty($resultats)): ?>
      <h2 class="section-title">✅ Trajets disponibles</h2>

      <div class="trajets-grid">
        <?php foreach ($resultats as $trajet): ?>
          <div class="trajet-card">
            <p><strong><?= htmlspecialchars($trajet['ville_depart']) ?></strong> → <strong><?= htmlspecialchars($trajet['ville_arrivee']) ?></strong></p>
            <p>📅 <?= htmlspecialchars($trajet['date_depart']) ?> à <?= htmlspecialchars($trajet['heure_depart']) ?></p>
            <p>💳 <?= (int)$trajet['prix'] ?> crédits</p>
            <p>👥 <?= (int)$trajet['places_restantes'] ?> places restantes</p>

            <a class="btn btn-wide" href="/ecoridestudi/ecoride/public/index.php?url=details&id=<?= (int)$trajet['id'] ?>">
              Détails
            </a>
          </div>
        <?php endforeach; ?>
      </div>

    <?php else: ?>
      <div class="msg-warning">😕 Aucun trajet trouvé.</div>

      <?php if (!empty($prochain)): ?>
        <div class="msg-info">
          Prochain trajet disponible le <strong><?= htmlspecialchars($prochain['date_depart']) ?></strong>
          à <?= htmlspecialchars($prochain['heure_depart']) ?> —
          <a href="/ecoridestudi/ecoride/public/index.php?url=details&id=<?= (int)$prochain['id'] ?>">Voir</a>
        </div>
      <?php endif; ?>
    <?php endif; ?>

  <?php endif; ?>

</section>
                                                      