<?php
require_once(__DIR__ . '/../includes/db.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: index.php?page=connexion');
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];


if (!in_array($_SESSION['role'], ['chauffeur', 'passager_chauffeur'])) {
    echo "<p style='color: red;'>🚫 Vous devez être chauffeur pour proposer un trajet.</p>";
    exit;
}


if ($_SESSION['credits'] < 2) {
    echo "<p style='color: red;'>⚠️ Vous devez avoir au moins 2 crédits pour proposer un trajet.</p>";
    exit;
}


$stmt = $pdo->prepare("SELECT id, marque, modele FROM vehicules WHERE id_utilisateur = ?");
$stmt->execute([$utilisateur_id]);
$vehicules = $stmt->fetchAll();

if (count($vehicules) === 0) {
    ?>
    <div style="
        max-width: 600px;
        margin: 4rem auto;
        background: #ffffff;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border-left: 6px solid #4CAF50;
    ">

        <div style="font-size: 50px; margin-bottom: 1rem;">🚗</div>

        <h2 style="color:#2e7d32; margin-bottom: 1rem;">
            Aucun véhicule détecté
        </h2>

        <p style="font-size: 1.1rem; color:#444; margin-bottom: 1.5rem;">
            Pour proposer un trajet, vous devez d'abord enregistrer un véhicule sur votre compte.
        </p>

        <a href="index.php?page=devenir_chauffeur"
        style="
            display:inline-block;
            background:#2e7d32;
            color:white;
            padding:0.8rem 1.4rem;
            font-size:1rem;
            border-radius:8px;
            text-decoration:none;
            transition:0.2s;
        "
        onmouseover="this.style.background='#256628'"
        onmouseout="this.style.background='#2e7d32'">
            ➕ Ajouter un véhicule
        </a>

    </div>
    <?php
    exit;
}

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ville_depart = $_POST['ville_depart'];
    $ville_arrivee = $_POST['ville_arrivee'];
    $date_depart = $_POST['date_depart'];
    $heure_depart = $_POST['heure_depart'];
    $prix = floatval($_POST['prix']);
    $places = (int) $_POST['places'];
    $vehicule_id = (int) $_POST['vehicule'];
    $ecologique = isset($_POST['ecologique']) ? 1 : 0;

   
    $stmt = $pdo->prepare("INSERT INTO trajets (conducteur_id, ville_depart, ville_arrivee, date_depart, heure_depart, prix, places_restantes, vehicule_id, ecologique) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$utilisateur_id, $ville_depart, $ville_arrivee, $date_depart, $heure_depart, $prix, $places, $vehicule_id, $ecologique])) {
        
        $stmt = $pdo->prepare("UPDATE utilisateurs SET credits = credits - 2 WHERE id = ?");
        $stmt->execute([$utilisateur_id]);
        $_SESSION['credits'] -= 2;
        $success = true;
    }
}
?>
<head><meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<div class="inscription-section">
    <h2>🛣️ Saisir un trajet</h2>

    <?php if ($success): ?>
        <p style="color: green; font-weight: bold;">✅ Trajet enregistré avec succès ! 2 crédits ont été déduits.</p>
    <?php endif; ?>

    <p style="margin-bottom: 1em; color: #444;">
        ℹ️ <strong>Publier un trajet coûte 2 crédits</strong> (commission pour garantir le bon fonctionnement de la plateforme).
    </p>

    <form method="POST" class="inscription-form">
        <div class="form-group">
            <label for="ville_depart">Ville de départ</label>
            <input type="text" name="ville_depart" id="ville_depart" required>
        </div>

        <div class="form-group">
            <label for="ville_arrivee">Ville d'arrivée</label>
            <input type="text" name="ville_arrivee" id="ville_arrivee" required>
        </div>

        <div class="form-group">
            <label for="date_depart">Date de départ</label>
            <input type="date" name="date_depart" id="date_depart" required>
        </div>

        <div class="form-group">
            <label for="heure_depart">Heure de départ</label>
            <input type="time" name="heure_depart" id="heure_depart" required>
        </div>

        <div class="form-group">
            <label for="prix">Prix du trajet (€)</label>
            <input type="number" step="0.01" name="prix" id="prix" required>
        </div>

        <div class="form-group">
            <label for="places">Places disponibles</label>
            <input type="number" name="places" id="places" min="1" max="8" required>
        </div>

        <div class="form-group">
            <label for="vehicule">Véhicule utilisé</label>
            <select name="vehicule" id="vehicule" required>
                <?php foreach ($vehicules as $v): ?>
                    <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['marque'] . ' ' . $v['modele']) ?></option>
                <?php endforeach; ?>
            </select>
            <a href="index.php?page=devenir_chauffeur" class="ajout-vehicule">Ajouter un nouveau véhicule</a>


        </div>

        <div class="form-group">
            <label><input type="checkbox" name="ecologique"> Trajet écologique (véhicule électrique)</label>
        </div>

        <input type="submit" value="Publier le trajet">
    </form>
</div>
