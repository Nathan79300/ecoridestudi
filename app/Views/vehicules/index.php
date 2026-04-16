<h2 style="text-align:center; margin-top:2rem; color:#2e7d32;">
  🚗 Mes véhicules
</h2>

<div style="max-width:980px;margin:2rem auto;background:#fff;
            border-radius:16px;padding:2rem;
            box-shadow:0 18px 40px rgba(0,0,0,.08);">

  <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:1.5rem;">
    <p style="margin:0;color:#5f6b61;">
      Retrouvez ici vos véhicules enregistrés (pour proposer un trajet).
    </p>

    <a href="index.php?url=ajouterVehicule"
       style="background:#2e7d32;color:white;padding:10px 16px;
              border-radius:10px;text-decoration:none;font-weight:800;">
      ➕ Ajouter un véhicule
    </a>
  </div>

  <?php if (!empty($vehicules)): ?>

    <div style="overflow:auto;">
      <table style="width:100%;border-collapse:collapse;min-width:820px;">
        <thead>
          <tr style="background:#f5f7f6;">
            <th style="padding:12px;text-align:left;">Immat.</th>
            <th style="padding:12px;text-align:left;">Marque</th>
            <th style="padding:12px;text-align:left;">Modèle</th>
            <th style="padding:12px;text-align:left;">Énergie</th>
            <th style="padding:12px;text-align:left;">Couleur</th>
            <th style="padding:12px;text-align:center;">Places</th>
            <th style="padding:12px;text-align:center;">Fumeurs</th>
            <th style="padding:12px;text-align:right;">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($vehicules as $v): ?>
            <tr style="border-bottom:1px solid #eef1ef;">
              <td style="padding:12px;font-weight:700;"><?= htmlspecialchars($v['immatriculation'] ?? '') ?></td>
              <td style="padding:12px;"><?= htmlspecialchars($v['marque'] ?? '') ?></td>
              <td style="padding:12px;"><?= htmlspecialchars($v['modele'] ?? '') ?></td>
              <td style="padding:12px;"><?= htmlspecialchars($v['energie'] ?? '') ?></td>
              <td style="padding:12px;"><?= htmlspecialchars($v['couleur'] ?? '') ?></td>
              <td style="padding:12px;text-align:center;"><?= (int)($v['places'] ?? 0) ?></td>
              <td style="padding:12px;text-align:center;">
                <?= ((int)($v['fumeurs'] ?? 0) === 1) ? "Oui" : "Non" ?>
              </td>
              <td style="padding:12px;text-align:right;">
                <a href="index.php?url=supprimerVehicule&id=<?= (int)$v['id'] ?>"
                   style="color:#c62828;font-weight:800;text-decoration:none;"
                   onclick="return confirm('Supprimer ce véhicule ?');">
                  ❌ Supprimer
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

  <?php else: ?>

    <div style="padding:1.2rem;border-radius:12px;background:#f6fbf7;border:1px solid rgba(46,125,50,.12);color:#1b5e20;">
      Aucun véhicule enregistré pour le moment.
    </div>

  <?php endif; ?>

</div>
