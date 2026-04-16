<?php

?>

<section class="page-wrap" style="max-width:1100px;margin:2rem auto;padding:0 1rem;">
  <div class="card" style="background:#fff;border-radius:16px;box-shadow:0 18px 40px rgba(0,0,0,.06);border:1px solid rgba(46,125,50,.10);overflow:hidden;">
    <div style="padding:1.4rem 1.4rem 1rem;background:linear-gradient(180deg, rgba(46,125,50,.10), rgba(46,125,50,0));">
      <h1 style="margin:0;color:#1b5e20;font-size:1.6rem;">🚙 Mes trajets</h1>
      <p style="margin:.35rem 0 0;color:#5f6b61;">Retrouve ici tous les trajets que tu as publiés.</p>
    </div>

    <div style="padding:1.2rem 1.4rem 1.4rem;">
      <?php if (empty($trajets ?? [])): ?>
        <div style="padding:1rem;border-radius:12px;background:rgba(25,118,210,.07);border:1px solid rgba(25,118,210,.15);color:#0d47a1;">
          ℹ️ Tu n’as pas encore publié de trajet.
          <div style="margin-top:.8rem;">
            <a href="index.php?url=proposer" class="btn" style="display:inline-block;padding:.75rem 1rem;border-radius:12px;background:#2e7d32;color:#fff;text-decoration:none;font-weight:800;">
              ➕ Proposer un trajet
            </a>
          </div>
        </div>
      <?php else: ?>

        <div style="display:grid;gap:1rem;">
          <?php foreach ($trajets as $t): ?>
            <div style="padding:1rem;border-radius:14px;background:#fbfcfb;border:1px solid #e6efe7;">
              <div style="display:flex;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
                <div>
                  <div style="font-weight:900;color:#1b5e20;">
                    <?= htmlspecialchars($t['ville_depart'] ?? '—') ?> → <?= htmlspecialchars($t['ville_arrivee'] ?? '—') ?>
                  </div>
                  <div style="margin-top:.25rem;color:#5f6b61;">
                    📅 <?= htmlspecialchars($t['date_depart'] ?? '—') ?>
                    <?php if (!empty($t['heure_depart'])): ?> · ⏰ <?= htmlspecialchars($t['heure_depart']) ?><?php endif; ?>
                  </div>
                </div>

                <div style="display:flex;gap:.6rem;align-items:center;flex-wrap:wrap;">
                  <?php if (isset($t['prix'])): ?>
                    <span style="padding:.35rem .6rem;border-radius:999px;background:rgba(46,125,50,.10);color:#1b5e20;font-weight:800;">
                      💳 <?= (int)$t['prix'] ?> crédits
                    </span>
                  <?php endif; ?>

                  <?php if (isset($t['places_restantes'])): ?>
                    <span style="padding:.35rem .6rem;border-radius:999px;background:rgba(13,71,161,.08);color:#0d47a1;font-weight:800;">
                      👥 <?= (int)$t['places_restantes'] ?> places
                    </span>
                  <?php endif; ?>

                  <?php if (!empty($t['etat'])): ?>
                    <span style="padding:.35rem .6rem;border-radius:999px;background:rgba(0,0,0,.06);color:#2c2c2c;font-weight:800;">
                      <?= htmlspecialchars($t['etat']) ?>
                    </span>
                  <?php endif; ?>
                </div>
              </div>

              <div style="margin-top:.9rem;display:flex;gap:.6rem;flex-wrap:wrap;">
                <a href="index.php?url=details&id=<?= (int)($t['id'] ?? 0) ?>"
                   style="text-decoration:none;padding:.65rem .9rem;border-radius:12px;border:1px solid #dfe7e1;background:#fff;color:#1b5e20;font-weight:800;">
                  🔎 Détails
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

      <?php endif; ?>
    </div>
  </div>
</section>

