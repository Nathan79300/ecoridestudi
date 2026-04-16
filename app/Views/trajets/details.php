<section class="page page-details">

  <header class="page-head">
    <div class="page-title">
      <div class="page-icon">🚗</div>
      <div>
        <h1>Détails du trajet</h1>
        <p>Consultez les informations et réservez votre place en 1 clic.</p>
      </div>
    </div>

    <a class="btn btn-outline" href="/ecoridestudi/ecoride/public/index.php?url=recherche">← Retour aux covoiturages</a>
  </header>

  <div class="details-layout">

    <div class="card details-card">

      <div class="details-top">
        <div class="route">
          <div class="route-city">
            <span class="label">Départ</span>
            <strong><?= htmlspecialchars($trajet['ville_depart'] ?? '—', ENT_QUOTES, 'UTF-8') ?></strong>
          </div>

          <div class="route-arrow">➡️</div>

          <div class="route-city">
            <span class="label">Arrivée</span>
            <strong><?= htmlspecialchars($trajet['ville_arrivee'] ?? '—', ENT_QUOTES, 'UTF-8') ?></strong>
          </div>
        </div>

        <div class="badges">
          <span class="badge badge-price">💳 <?= (int)($trajet['prix'] ?? 0) ?> crédits</span>
          <span class="badge badge-places">🪑 <?= (int)($trajet['places_restantes'] ?? 0) ?> place(s)</span>
        </div>
      </div>

      <div class="details-grid">
        <div class="info">
          <span class="label">Conducteur</span>
          <strong><?= htmlspecialchars($conducteur['username'] ?? '—', ENT_QUOTES, 'UTF-8') ?></strong>
        </div>

        <div class="info">
          <span class="label">Date</span>
          <strong><?= htmlspecialchars($trajet['date_depart'] ?? '—', ENT_QUOTES, 'UTF-8') ?></strong>
        </div>

        <div class="info">
          <span class="label">Heure</span>
          <strong><?= htmlspecialchars($trajet['heure_depart'] ?? '—', ENT_QUOTES, 'UTF-8') ?></strong>
        </div>

        <div class="info">
          <span class="label">Places restantes</span>
          <strong><?= (int)($trajet['places_restantes'] ?? 0) ?></strong>
        </div>
      </div>

      <?php
        $photoFile = $conducteur['photo'] ?? 'conducteur_ecoride.jpg';
        $photoUrl  = "/ecoridestudi/ecoride/public/assets/images/" . rawurlencode($photoFile);

        $noteAffiche   = number_format((float)($moyenneAvis ?? 0), 1);
        $nbAvisAffiche = (int)($nbAvis ?? 0);
      ?>

      <div class="card conducteur-card" style="margin-top:16px;">
        <div class="conducteur-head">
          <img class="conducteur-avatar"
               src="<?= $photoUrl ?>"
               alt="Photo du conducteur"
               onerror="this.src='/ecoridestudi/ecoride/public/assets/images/conducteur_ecoride.jpg'">

          <div>
            <div class="conducteur-name">
              Conducteur : <strong><?= htmlspecialchars($conducteur['username'] ?? '—', ENT_QUOTES, 'UTF-8') ?></strong>
            </div>

            <div class="conducteur-rating">
              ⭐ <?= $noteAffiche ?>/5 <span class="muted">(<?= $nbAvisAffiche ?> avis)</span>
            </div>
          </div>
        </div>

        <div class="conducteur-avis">
          <h2 class="card-title" style="margin-top:12px;">🗣️ Avis sur le conducteur</h2>

          <?php if (empty($avisList)): ?>
            <p class="muted">Aucun avis validé pour le moment.</p>
          <?php else: ?>
            <?php foreach ($avisList as $a): ?>
              <div class="avis-item">
                <div class="avis-top">
                  <strong><?= htmlspecialchars($a['auteur'] ?? 'Anonyme', ENT_QUOTES, 'UTF-8') ?></strong>
                  <span>— ⭐ <?= (int)($a['note'] ?? 0) ?>/5</span>
                </div>
                <div class="avis-text">
                  <?= nl2br(htmlspecialchars($a['commentaire'] ?? '', ENT_QUOTES, 'UTF-8')) ?>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>

      <div class="details-actions" style="margin-top:18px; padding-top:10px;">

        <?php
          $places   = (int)($trajet['places_restantes'] ?? 0);
          $trajetId = (int)($trajet['id'] ?? 0);
          $isLogged = !empty($_SESSION['utilisateur_id']);
          $already  = !empty($alreadyReserved);
        ?>

        <?php if (!empty($_SESSION['flash_success'])): ?>
          <div class="msg-success"><?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8') ?></div>
          <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['flash_error'])): ?>
          <div class="msg-error"><?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?></div>
          <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <?php if (!$isLogged): ?>
          <div class="msg-info">🔒 Connectez-vous pour réserver.</div>
          <a class="btn btn-wide" href="/ecoridestudi/ecoride/public/index.php?url=connexion">Se connecter</a>

        <?php elseif ($already): ?>
          <div class="msg-info">✅ Vous avez déjà réservé ce trajet.</div>

          <form method="POST" action="/ecoridestudi/ecoride/public/index.php?url=annulerReservation" class="reserve-form">
            <input type="hidden" name="trajet_id" value="<?= $trajetId ?>">
            <button type="submit" class="btn btn-wide btn-danger">❌ Annuler ma réservation</button>
          </form>

        <?php elseif ($places <= 0): ?>
          <div class="msg-warning">⚠️ Ce trajet est complet.</div>

        <?php else: ?>
          <form method="POST" action="/ecoridestudi/ecoride/public/index.php?url=reserver" class="reserve-form">
            <input type="hidden" name="trajet_id" value="<?= $trajetId ?>">
            <button type="submit" class="btn btn-wide">✅ Réserver</button>
          </form>
          <p class="hint" style="margin-top:10px; opacity:.75;">
            Le nombre de places restantes sera mis à jour automatiquement.
          </p>
        <?php endif; ?>

      </div>

    </div>

    <aside class="card details-aside">
      <h2 class="card-title">ℹ️ Infos utiles</h2>

      <ul class="details-list">
        <li>Arrivez 5 minutes avant l’heure de départ.</li>
        <li>Respectez le conducteur et les passagers.</li>
        <li>En cas d’imprévu, prévenez au plus tôt.</li>
      </ul>

      <div class="aside-note">
        ✅ Réservation sécurisée (contrôle des places, anti double réservation).
      </div>
    </aside>

  </div>

</section>
