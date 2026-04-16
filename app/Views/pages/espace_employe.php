<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? null) !== 'employe') {
    header('Location: index.php?url=connexionEmploye');
    exit;
}

require_once(__DIR__ . '/../../../includes/db.php');

// Avis en attente
$stmt_avis = $pdo->query("
    SELECT a.id, a.note, a.commentaire, 
           u.username AS pseudo,
           t.ville_depart, t.ville_arrivee
    FROM avis a
    JOIN utilisateurs u ON a.id_utilisateur = u.id
    JOIN trajets t ON a.id_trajet = t.id
    WHERE a.valide = 0
");
$avis_attente = $stmt_avis->fetchAll(PDO::FETCH_ASSOC);

// Trajets signalés
$stmt_signales = $pdo->query("
    SELECT a.id AS avis_id, a.commentaire, a.traite,
           u.username AS pseudo, u.email,
           t.id AS trajet_id, t.ville_depart, 
           t.ville_arrivee, t.date_depart
    FROM avis a
    JOIN utilisateurs u ON a.id_utilisateur = u.id
    JOIN trajets t ON a.id_trajet = t.id
    WHERE a.probleme = 1
");
$trajets_signales = $stmt_signales->fetchAll(PDO::FETCH_ASSOC);

$prenom = trim($_SESSION['user']['prenom'] ?? '');
$nom = trim($_SESSION['user']['nom'] ?? '');
$fullName = trim($prenom . ' ' . $nom);
$fullName = $fullName !== '' ? $fullName : 'Employé';

$nbAvis = count($avis_attente);
$nbSignal = count($trajets_signales);
?>

<style>
  .emp-wrap{ max-width:1100px; margin:2.2rem auto; padding:0 1rem 2.5rem; }
  .emp-hero{
    background:linear-gradient(180deg, rgba(46,125,50,.10), rgba(46,125,50,0));
    border:1px solid rgba(46,125,50,.14);
    border-radius:18px;
    padding:1.6rem 1.6rem 1.2rem;
    box-shadow:0 18px 40px rgba(0,0,0,.06);
  }
  .emp-title{ margin:0; font-size:1.8rem; font-weight:900; color:#1b5e20; display:flex; align-items:center; gap:.7rem; }
  .emp-sub{ margin:.35rem 0 0; color:#516056; font-size:.98rem; line-height:1.4; }
  .chips{ margin-top:.9rem; display:flex; flex-wrap:wrap; gap:.55rem; }
  .chip{
    display:inline-flex; align-items:center; gap:.45rem;
    padding:.35rem .65rem; border-radius:999px; font-weight:800; font-size:.86rem;
    border:1px solid rgba(0,0,0,.06); background:#fff;
  }
  .chip.green{ color:#1b5e20; border-color:rgba(46,125,50,.20); background:rgba(46,125,50,.08); }
  .chip.red{ color:#b71c1c; border-color:rgba(198,40,40,.20); background:rgba(198,40,40,.08); }
  .chip.blue{ color:#0d47a1; border-color:rgba(25,118,210,.20); background:rgba(25,118,210,.08); }

  .grid{ margin-top:1.3rem; display:grid; grid-template-columns:1fr 1fr; gap:1.1rem; }
  .card{
    background:#fff; border-radius:18px; border:1px solid rgba(0,0,0,.06);
    box-shadow:0 16px 34px rgba(0,0,0,.06);
    overflow:hidden;
  }
  .card-h{
    padding:1.1rem 1.2rem .9rem;
    display:flex; align-items:flex-start; justify-content:space-between; gap:1rem;
    background:linear-gradient(180deg, rgba(0,0,0,.02), rgba(0,0,0,0));
  }
  .card-title{ margin:0; font-size:1.15rem; font-weight:900; color:#1f2d23; display:flex; align-items:center; gap:.55rem; }
  .card-meta{ margin:.25rem 0 0; color:#6b7a70; font-size:.92rem; }
  .pill{
    padding:.35rem .6rem; border-radius:999px; font-weight:900; font-size:.85rem;
    background:#f3f6f4; border:1px solid rgba(0,0,0,.06); color:#2f3a33;
    white-space:nowrap;
  }

  .list{ padding:1rem 1.2rem 1.2rem; display:flex; flex-direction:column; gap:.9rem; }
  .item{
    border:1px solid rgba(0,0,0,.06);
    border-radius:14px;
    background:#fbfcfb;
    padding:1rem;
    display:flex; flex-direction:column; gap:.55rem;
  }
  .row{ display:flex; flex-wrap:wrap; gap:.5rem .8rem; align-items:center; }
  .who{ font-weight:900; color:#163a1a; }
  .route{ color:#2a3c2f; font-weight:800; }
  .text{ color:#3d4c41; line-height:1.45; }
  .small{ color:#6b7a70; font-size:.92rem; }
  .btns{ display:flex; gap:.55rem; flex-wrap:wrap; margin-top:.25rem; }

  .btn{
    appearance:none; border:none; cursor:pointer;
    padding:.55rem .8rem; border-radius:12px; font-weight:900;
    transition:.18s;
  }
  .btn:hover{ transform:translateY(-1px); }
  .btn:active{ transform:translateY(0); }
  .btn.ok{ background:#2e7d32; color:#fff; }
  .btn.no{ background:#c62828; color:#fff; }
  .btn.done{ background:#1976d2; color:#fff; }

  .empty{
    padding:1.2rem;
    color:#6b7a70;
    font-style:italic;
    background:rgba(0,0,0,.02);
    border:1px dashed rgba(0,0,0,.12);
    border-radius:14px;
  }

  @media (max-width:900px){
    .grid{ grid-template-columns:1fr; }
    .emp-title{ font-size:1.55rem; }
  }
</style>

<div class="emp-wrap">

  <section class="emp-hero">
    <h1 class="emp-title">👨‍💼 Espace Employé</h1>
    <p class="emp-sub">
      Connecté en tant que <strong><?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') ?></strong>.
      Ici tu peux valider les avis et traiter les signalements.
    </p>

    <div class="chips">
      <span class="chip green">📝 Avis à valider : <strong><?= (int)$nbAvis ?></strong></span>
      <span class="chip red">🚨 Signalements : <strong><?= (int)$nbSignal ?></strong></span>
      <span class="chip blue">🔒 Rôle : Employé</span>
    </div>
  </section>

  <section class="grid">

    <!-- AVIS -->
    <div class="card">
      <div class="card-h">
        <div>
          <h2 class="card-title">📝 Avis en attente</h2>
          <p class="card-meta">Valide ou refuse les avis avant publication.</p>
        </div>
        <span class="pill"><?= (int)$nbAvis ?> en attente</span>
      </div>

      <div class="list">
        <?php if (!empty($avis_attente)): ?>
          <?php foreach ($avis_attente as $avis): ?>
            <div class="item">
              <div class="row">
                <span class="who"><?= htmlspecialchars($avis['pseudo'], ENT_QUOTES, 'UTF-8') ?></span>
                <span class="pill">⭐ <?= (int)$avis['note'] ?>/5</span>
              </div>

              <div class="row">
                <span class="route">🚗 <?= htmlspecialchars($avis['ville_depart']) ?> → <?= htmlspecialchars($avis['ville_arrivee']) ?></span>
              </div>

              <div class="text"><?= nl2br(htmlspecialchars($avis['commentaire'], ENT_QUOTES, 'UTF-8')) ?></div>

              <div class="btns">
                <form method="POST" action="index.php?url=validerAvis">
                  <input type="hidden" name="avis_id" value="<?= (int)$avis['id'] ?>">
                  <button class="btn ok" name="action" value="valider" type="submit">Valider</button>
                  <button class="btn no" name="action" value="refuser" type="submit">Refuser</button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="empty">Aucun avis à valider pour le moment.</div>
        <?php endif; ?>
      </div>
    </div>

    <!-- SIGNALEMENTS -->
    <div class="card">
      <div class="card-h">
        <div>
          <h2 class="card-title">🚨 Covoiturages signalés</h2>
          <p class="card-meta">Traite les signalements et marque-les comme résolus.</p>
        </div>
        <span class="pill"><?= (int)$nbSignal ?> signalement(s)</span>
      </div>

      <div class="list">
        <?php if (!empty($trajets_signales)): ?>
          <?php foreach ($trajets_signales as $t): ?>
            <div class="item">
              <div class="row">
                <span class="pill">Trajet #<?= (int)$t['trajet_id'] ?></span>
                <span class="route">🚗 <?= htmlspecialchars($t['ville_depart']) ?> → <?= htmlspecialchars($t['ville_arrivee']) ?></span>
              </div>

              <div class="row small">
                <span><strong>Départ :</strong> <?= htmlspecialchars($t['date_depart']) ?></span>
              </div>

              <div class="row small">
                <span><strong>Participant :</strong> <?= htmlspecialchars($t['pseudo']) ?></span>
                <span>— <?= htmlspecialchars($t['email']) ?></span>
              </div>

              <div class="text"><strong>Problème :</strong> <?= nl2br(htmlspecialchars($t['commentaire'], ENT_QUOTES, 'UTF-8')) ?></div>

              <?php if ((int)$t['traite'] === 0): ?>
                <form method="POST" action="index.php?url=marquerSignaleTraite">
                  <input type="hidden" name="avis_id" value="<?= (int)$t['avis_id'] ?>">
                  <button class="btn done" type="submit">Marquer comme traité ✔️</button>
                </form>
              <?php else: ?>
                <span class="chip green">✔ Signalement traité</span>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="empty">Aucun trajet signalé.</div>
        <?php endif; ?>
      </div>
    </div>

  </section>

</div>
