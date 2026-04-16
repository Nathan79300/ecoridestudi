<?php
// app/Views/trajets/proposer.php
?>

<section class="proposer-page">

  <div class="proposer-hero">
    <div class="proposer-hero-top">
      <h1 class="proposer-title">🚗 Proposer un trajet</h1>
      <p class="proposer-subtitle">Renseignez les informations ci-dessous pour publier votre covoiturage.</p>
    </div>

    <a class="btn btn-outline proposer-back" href="/ecoridestudi/ecoride/public/index.php?url=profil">
      ← Retour au profil
    </a>

    <?php if (!empty($message)): ?>
      <div class="msg-success">
        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
      </div>
    <?php endif; ?>
  </div>

  <div class="proposer-grid">

    <!-- ===== Formulaire ===== -->
    <div class="card proposer-card">
      <div class="card-head">
        <h2 class="card-title">📝 Détails du trajet</h2>
        <p class="card-subtitle">Les informations seront visibles lors de la recherche.</p>
      </div>

      <form method="POST" class="proposer-form">

        <div class="field">
          <label for="ville_depart">Ville de départ</label>
          <input id="ville_depart" type="text" name="ville_depart" placeholder="Ex : Paris" required>
        </div>

        <div class="field">
          <label for="ville_arrivee">Ville d'arrivée</label>
          <input id="ville_arrivee" type="text" name="ville_arrivee" placeholder="Ex : Lyon" required>
        </div>

        <div class="field">
          <label for="date_depart">Date de départ</label>
          <input id="date_depart" type="date" name="date_depart" required>
        </div>

        <div class="field">
          <label for="heure_depart">Heure de départ</label>
          <input id="heure_depart" type="time" name="heure_depart" required>
        </div>

        <div class="field">
          <label for="prix">Prix (crédits)</label>
          <input id="prix" type="number" name="prix" min="0" placeholder="Ex : 20" required>
          <small class="help">Le prix est payé au moment de la réservation.</small>
        </div>

        <div class="field">
          <label for="places">Places proposées</label>
          <input id="places" type="number" name="places" min="1" max="5" value="1" required>
          <small class="help">Maximum 5 places par trajet.</small>
        </div>

        <label class="switch">
          <input type="checkbox" name="ecologique" value="1">
          <span class="switch-ui"></span>
          <span class="switch-text">Voyage écologique seulement</span>
        </label>

        <button type="submit" class="btn btn-wide proposer-submit">✅ Créer le trajet</button>
      </form>
    </div>

    <!-- ===== Aperçu ===== -->
    <div class="card proposer-card preview-card">
      <div class="card-head">
        <h2 class="card-title">👀 Aperçu</h2>
        <p class="card-subtitle">Vérifiez avant de publier (aperçu simple).</p>
      </div>

      <div class="preview-box">
        <div class="preview-row">
          <span class="preview-label">Départ</span>
          <span class="preview-value" id="prev_depart">—</span>
        </div>

        <div class="preview-row">
          <span class="preview-label">Arrivée</span>
          <span class="preview-value" id="prev_arrivee">—</span>
        </div>

        <div class="preview-row">
          <span class="preview-label">Date</span>
          <span class="preview-value" id="prev_date">—</span>
        </div>

        <div class="preview-row">
          <span class="preview-label">Heure</span>
          <span class="preview-value" id="prev_heure">—</span>
        </div>

        <div class="preview-row">
          <span class="preview-label">Prix</span>
          <span class="preview-value" id="prev_prix">—</span>
        </div>

        <div class="preview-row">
          <span class="preview-label">Places</span>
          <span class="preview-value" id="prev_places">—</span>
        </div>

        <div class="preview-row">
          <span class="preview-label">Écologique</span>
          <span class="preview-value" id="prev_eco">—</span>
        </div>

        <div class="preview-tip">
          💡 Astuce : choisis une heure réaliste et un prix cohérent, ça augmente les réservations.
        </div>
      </div>
    </div>

  </div>
</section>

<script>
  // Mini aperçu live (sans dépendance)
  const $ = (id) => document.getElementById(id);

  const inputs = {
    ville_depart: $('ville_depart'),
    ville_arrivee: $('ville_arrivee'),
    date_depart: $('date_depart'),
    heure_depart: $('heure_depart'),
    prix: $('prix'),
    places: $('places'),
    ecologique: document.querySelector('input[name="ecologique"]')
  };

  const prev = {
    depart: $('prev_depart'),
    arrivee: $('prev_arrivee'),
    date: $('prev_date'),
    heure: $('prev_heure'),
    prix: $('prev_prix'),
    places: $('prev_places'),
    eco: $('prev_eco')
  };

  function refreshPreview(){
    prev.depart.textContent = inputs.ville_depart.value.trim() || '—';
    prev.arrivee.textContent = inputs.ville_arrivee.value.trim() || '—';
    prev.date.textContent = inputs.date_depart.value || '—';
    prev.heure.textContent = inputs.heure_depart.value || '—';
    prev.prix.textContent = inputs.prix.value ? (inputs.prix.value + ' crédits') : '—';
    prev.places.textContent = inputs.places.value || '—';
    prev.eco.textContent = inputs.ecologique.checked ? 'Oui 🌿' : 'Non';
  }

  Object.values(inputs).forEach(el => {
    if (!el) return;
    el.addEventListener('input', refreshPreview);
    el.addEventListener('change', refreshPreview);
  });

  refreshPreview();
</script>
