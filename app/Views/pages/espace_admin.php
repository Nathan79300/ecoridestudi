<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] ?? null) !== 'admin') {
    header('Location: index.php?url=connexionAdmin');
    exit;
}

require_once(__DIR__ . '/../../../includes/db.php');


$days = 14;


$stmtTrajets = $pdo->prepare("
    SELECT DATE(date_depart) as d, COUNT(*) as nb
    FROM trajets
    WHERE date_depart >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
    GROUP BY DATE(date_depart)
    ORDER BY d ASC
");
$stmtTrajets->bindValue(':days', $days, PDO::PARAM_INT);
$stmtTrajets->execute();
$rowsTrajets = $stmtTrajets->fetchAll(PDO::FETCH_ASSOC);


$stmtCredits = $pdo->prepare("
    SELECT DATE(date_depart) as d, COALESCE(SUM(prix),0) as total
    FROM trajets
    WHERE date_depart >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
    GROUP BY DATE(date_depart)
    ORDER BY d ASC
");
$stmtCredits->bindValue(':days', $days, PDO::PARAM_INT);
$stmtCredits->execute();
$rowsCredits = $stmtCredits->fetchAll(PDO::FETCH_ASSOC);


$totalCredits = (int)$pdo->query("SELECT COALESCE(SUM(prix),0) FROM trajets")->fetchColumn();


$totalTrajets = (int)$pdo->query("SELECT COUNT(*) FROM trajets")->fetchColumn();
$totalUsers   = (int)$pdo->query("SELECT COUNT(*) FROM utilisateurs")->fetchColumn();
$totalSuspendus = (int)$pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE suspendu = 1")->fetchColumn();


$labels = [];
$mapTrajets = [];
$mapCredits = [];

foreach ($rowsTrajets as $r) $mapTrajets[$r['d']] = (int)$r['nb'];
foreach ($rowsCredits as $r) $mapCredits[$r['d']] = (int)$r['total'];

for ($i = $days; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $labels[] = $d;
}

$dataTrajets = array_map(fn($d) => $mapTrajets[$d] ?? 0, $labels);
$dataCredits = array_map(fn($d) => $mapCredits[$d] ?? 0, $labels);

$adminName = trim(($_SESSION['user']['prenom'] ?? '') . ' ' . ($_SESSION['user']['nom'] ?? ''));
?>

<style>

.admin-wrap{
    max-width:1100px;
    margin: 2rem auto;
    padding: 0 1rem;
}
.admin-hero{
    background: #fff;
    border-radius: 16px;
    padding: 1.6rem 1.6rem;
    box-shadow: 0 10px 30px rgba(0,0,0,.06);
    border: 1px solid rgba(46,125,50,.12);
}
.admin-hero-top{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:1rem;
    flex-wrap:wrap;
}
.admin-title{
    display:flex;
    align-items:center;
    gap:.8rem;
}
.admin-title h2{
    margin:0;
    color:#2e7d32;
    font-size:1.6rem;
}
.admin-sub{
    margin:.4rem 0 0 0;
    color:#5b6b60;
}
.admin-actions{
    display:flex;
    gap:.6rem;
    flex-wrap:wrap;
}
.btn{
    display:inline-flex;
    align-items:center;
    gap:.5rem;
    padding:.65rem .95rem;
    border-radius: 10px;
    text-decoration:none;
    font-weight:600;
    border:1px solid transparent;
}
.btn-green{ background:#2e7d32; color:#fff; }
.btn-green:hover{ filter:brightness(0.96); }
.btn-soft{
    background:#eaf6ec;
    color:#2e7d32;
    border-color: rgba(46,125,50,.20);
}
.btn-soft:hover{ background:#dff0e2; }

.kpis{
    margin-top:1.2rem;
    display:grid;
    grid-template-columns: repeat(4, minmax(0,1fr));
    gap: .9rem;
}
.kpi{
    background:#fff;
    border-radius:14px;
    padding:1rem;
    border: 1px solid rgba(46,125,50,.10);
    box-shadow: 0 10px 25px rgba(0,0,0,.04);
}
.kpi .label{
    color:#6a7a70;
    font-weight:600;
    font-size:.92rem;
    display:flex;
    justify-content:space-between;
    gap:.5rem;
}
.kpi .value{
    margin-top:.4rem;
    font-size:1.55rem;
    font-weight:800;
    color:#1f2b23;
}
.kpi .hint{
    margin-top:.25rem;
    color:#74867c;
    font-size:.9rem;
}

.grid{
    margin-top:1.2rem;
    display:grid;
    grid-template-columns: 1.4fr 1fr;
    gap: 1rem;
}
.card{
    background:#fff;
    border-radius:16px;
    padding:1.2rem 1.2rem;
    border: 1px solid rgba(46,125,50,.10);
    box-shadow: 0 10px 25px rgba(0,0,0,.04);
}
.card h3{
    margin:0 0 .8rem 0;
    color:#2e7d32;
    font-size:1.1rem;
}
.card canvas{
    width:100% !important;
    height:320px !important;
}

@media (max-width: 980px){
    .kpis{ grid-template-columns: repeat(2, minmax(0,1fr)); }
    .grid{ grid-template-columns: 1fr; }
}
</style>

<div class="admin-wrap">

    <div class="admin-hero">
        <div class="admin-hero-top">
            <div class="admin-title">
                <span style="font-size:1.5rem;">🛠️</span>
                <div>
                    <h2>Espace Administrateur</h2>
                    <p class="admin-sub">Bienvenue <strong><?= htmlspecialchars($adminName ?: ($_SESSION['user']['email'] ?? '')) ?></strong> — pilote EcoRide en un coup d’œil.</p>
                </div>
            </div>

            <div class="admin-actions">
                <a class="btn btn-green" href="index.php?url=ajouterEmploye">➕ Créer employé</a>
                <a class="btn btn-soft" href="index.php?url=suspendreCompte">⛔ Suspendre</a>
                <a class="btn btn-soft" href="index.php?url=reactiverCompte">✅ Réactiver</a>
                <a class="btn btn-soft" href="index.php?url=logout" style="color:#c62828;">🚪 Déconnexion</a>
            </div>
        </div>

        <div class="kpis">
            <div class="kpi">
                <div class="label">Total crédits gagnés <span>💳</span></div>
                <div class="value"><?= (int)$totalCredits ?></div>
                <div class="hint">Somme des prix des trajets</div>
            </div>

            <div class="kpi">
                <div class="label">Total covoiturages <span>🚗</span></div>
                <div class="value"><?= (int)$totalTrajets ?></div>
                <div class="hint">Trajets publiés</div>
            </div>

            <div class="kpi">
                <div class="label">Utilisateurs <span>👥</span></div>
                <div class="value"><?= (int)$totalUsers ?></div>
                <div class="hint">Tous rôles confondus</div>
            </div>

            <div class="kpi">
                <div class="label">Comptes suspendus <span>⛔</span></div>
                <div class="value"><?= (int)$totalSuspendus ?></div>
                <div class="hint">À vérifier / réactiver</div>
            </div>
        </div>
    </div>

    <div class="grid">
        <div class="card">
            <h3>📈 Covoiturages par jour (<?= $days ?> derniers jours)</h3>
            <canvas id="chartTrajets"></canvas>
            <p style="margin:.6rem 0 0;color:#7b8b82;font-size:.9rem;">
                Astuce : si tu vois tout à 0, c’est souvent un souci de champ date (`date_depart`) ou de fuseau.
            </p>
        </div>

        <div class="card">
            <h3>💰 Crédits gagnés par jour (<?= $days ?> derniers jours)</h3>
            <canvas id="chartCredits"></canvas>
            <p style="margin:.6rem 0 0;color:#7b8b82;font-size:.9rem;">
                Si le prix n’est pas `prix`, dis-moi le nom exact de ta colonne et je corrige.
            </p>
        </div>
    </div>

</div>

<!-- Chart.js (CDN) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = <?= json_encode($labels, JSON_UNESCAPED_UNICODE) ?>;
const dataTrajets = <?= json_encode($dataTrajets, JSON_UNESCAPED_UNICODE) ?>;
const dataCredits = <?= json_encode($dataCredits, JSON_UNESCAPED_UNICODE) ?>;

// Graph 1
const ctx1 = document.getElementById('chartTrajets');
if (ctx1 && window.Chart) {
  new Chart(ctx1, {
    type: 'line',
    data: {
      labels,
      datasets: [{
        label: 'Covoiturages',
        data: dataTrajets,
        tension: 0.35,
        fill: true
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
  });
}

// Graph 2
const ctx2 = document.getElementById('chartCredits');
if (ctx2 && window.Chart) {
  new Chart(ctx2, {
    type: 'bar',
    data: {
      labels,
      datasets: [{
        label: 'Crédits',
        data: dataCredits
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
  });
}
</script>
