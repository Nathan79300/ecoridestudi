<?php

$val = function(string $k): string {
  return htmlspecialchars($_POST[$k] ?? '');
};
?>

<h2 style="text-align:center; margin-top:2rem; color:#2e7d32;">
  ➕ Ajouter un véhicule
</h2>

<div style="max-width:560px;margin:2rem auto;background:#fff;
            border-radius:16px;padding:2rem;
            box-shadow:0 18px 40px rgba(0,0,0,.08);">

  <p style="margin-top:0;color:#5f6b61;">
    Ces informations servent à proposer un trajet et à afficher les préférences.
  </p>

  <form method="POST" action="">

    <label style="font-weight:700;">Immatriculation :</label>
    <input type="text" name="immatriculation" required value="<?= $val('immatriculation') ?>"
           placeholder="Ex : AB-123-CD"
           style="width:100%;padding:10px;margin:.4rem 0 1rem;border-radius:10px;border:1px solid #dfe7e1;">

    <label style="font-weight:700;">Date d’immatriculation :</label>
    <input type="date" name="date_immat" required value="<?= $val('date_immat') ?>"
           style="width:100%;padding:10px;margin:.4rem 0 1rem;border-radius:10px;border:1px solid #dfe7e1;">

    <label style="font-weight:700;">Marque :</label>
    <input type="text" name="marque" required value="<?= $val('marque') ?>"
           style="width:100%;padding:10px;margin:.4rem 0 1rem;border-radius:10px;border:1px solid #dfe7e1;">

    <label style="font-weight:700;">Modèle :</label>
    <input type="text" name="modele" required value="<?= $val('modele') ?>"
           style="width:100%;padding:10px;margin:.4rem 0 1rem;border-radius:10px;border:1px solid #dfe7e1;">

    <label style="font-weight:700;">Énergie :</label>
    <select name="energie" required
            style="width:100%;padding:10px;margin:.4rem 0 1rem;border-radius:10px;border:1px solid #dfe7e1;">
      <?php $e = $_POST['energie'] ?? ''; ?>
      <option value="essence" <?= ($e==='essence')?'selected':''; ?>>Essence</option>
      <option value="diesel" <?= ($e==='diesel')?'selected':''; ?>>Diesel</option>
      <option value="électrique" <?= ($e==='électrique')?'selected':''; ?>>Électrique</option>
      <option value="hybride" <?= ($e==='hybride')?'selected':''; ?>>Hybride</option>
    </select>

    <label style="font-weight:700;">Couleur :</label>
    <input type="text" name="couleur" required value="<?= $val('couleur') ?>"
           style="width:100%;padding:10px;margin:.4rem 0 1rem;border-radius:10px;border:1px solid #dfe7e1;">

    <label style="font-weight:700;">Nombre de places :</label>
    <input type="number" name="places" min="1" max="8" required value="<?= $val('places') ?: '3' ?>"
           style="width:100%;padding:10px;margin:.4rem 0 1rem;border-radius:10px;border:1px solid #dfe7e1;">

    <label style="display:flex;align-items:center;gap:.6rem;font-weight:800;margin:.4rem 0 1.2rem;">
      <input type="checkbox" name="fumeurs" value="1" <?= !empty($_POST['fumeurs']) ? 'checked' : '' ?>>
      Autoriser les fumeurs
    </label>

    <button type="submit"
            style="width:100%;padding:12px;background:#2e7d32;color:white;
                   border:none;border-radius:12px;font-weight:900;cursor:pointer;">
      ✅ Enregistrer
    </button>
  </form>

  <div style="text-align:center;margin-top:1rem;">
    <a href="index.php?url=vehicules"
       style="color:#2e7d32;text-decoration:none;font-weight:900;">
      ← Retour à mes véhicules
    </a>
  </div>

</div>
