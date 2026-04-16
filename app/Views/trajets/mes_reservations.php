<?php
// app/Views/trajets/mes_reservations.php
?>

<div class="page-wrap">
  <div class="page-head">
    <h1 class="page-title">📌 Mes réservations</h1>
    <p class="page-subtitle">Retrouvez ici tous les trajets que vous avez réservés.</p>
  </div>

  <?php if (empty($reservations)): ?>
    <div class="empty-state">
      <div class="empty-icon">🧾</div>
      <h2>Aucune réservation</h2>
      <p>Vous n’avez pas encore réservé de trajet.</p>
      <a class="btn btn-primary" href="/ecoridestudi/ecoride/public/index.php?url=recherche">Rechercher un trajet</a>
    </div>
  <?php else: ?>

    <div class="reservations-grid">
      <?php foreach ($reservations as $r): ?>
        <?php
          $idTrajet = (int)($r['id'] ?? 0);
          $prix = (int)($r['prix'] ?? 0);
          $places = (int)($r['places_restantes'] ?? 0);
          $etat = $r['etat'] ?? 'prévu';

          $villeDepart  = $r['ville_depart'] ?? '';
          $villeArrivee = $r['ville_arrivee'] ?? '';

          $date = $r['date_depart'] ?? '';
          $heure = $r['heure_depart'] ?? '';

          $chauffeur = $r['conducteur_username'] ?? '—';
          $note = isset($r['conducteur_note_moyenne']) ? (float)$r['conducteur_note_moyenne'] : null;

          // Badge état
          $etatClass = 'badge';
          if ($etat === 'prévu') $etatClass .= ' badge-info';
          elseif ($etat === 'en cours') $etatClass .= ' badge-warning';
          elseif ($etat === 'terminé') $etatClass .= ' badge-success';
          else $etatClass .= ' badge-neutral';
        ?>

        <article class="reservation-card">
          <header class="reservation-top">
            <div class="route">
              <span class="city"><?= htmlspecialchars($villeDepart) ?></span>
              <span class="arrow">→</span>
              <span class="city"><?= htmlspecialchars($villeArrivee) ?></span>
            </div>

            <span class="<?= $etatClass ?>"><?= htmlspecialchars($etat) ?></span>
          </header>

          <div class="reservation-meta">
            <div class="meta-item">
              <div class="meta-label">Date</div>
              <div class="meta-value"><?= htmlspecialchars($date) ?></div>
            </div>

            <div class="meta-item">
              <div class="meta-label">Heure</div>
              <div class="meta-value"><?= htmlspecialchars($heure) ?></div>
            </div>

            <div class="meta-item">
              <div class="meta-label">Prix</div>
              <div class="meta-value"><?= $prix ?> crédits</div>
            </div>

            <div class="meta-item">
              <div class="meta-label">Places</div>
              <div class="meta-value"><?= $places ?></div>
            </div>
          </div>

          <div class="reservation-driver">
            <div class="driver-left">
              <div class="driver-name">
                <span class="driver-label">Chauffeur</span>
                <strong><?= htmlspecialchars($chauffeur) ?></strong>
              </div>
              <div class="driver-rating">
                <?php if ($note !== null): ?>
                  <span class="star">★</span> <?= htmlspecialchars((string)$note) ?>/5
                <?php else: ?>
                  <span class="muted">Pas encore de note</span>
                <?php endif; ?>
              </div>
            </div>

            <div class="actions">
              <a class="btn btn-primary" href="/ecoridestudi/ecoride/public/index.php?url=details&id=<?= $idTrajet ?>">
                Voir le trajet
              </a>

              <form method="POST" action="/ecoridestudi/ecoride/public/index.php?url=annulerReservation">
                <input type="hidden" name="trajet_id" value="<?= $idTrajet ?>">
                <button class="btn btn-outline-danger" type="submit"
                        onclick="return confirm('Annuler cette réservation ?');">
                  Annuler
                </button>
              </form>
            </div>
          </div>
        </article>

      <?php endforeach; ?>
    </div>

  <?php endif; ?>
</div>
