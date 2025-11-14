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
$role = $_SESSION['role'] ?? null;
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $immatriculation = trim($_POST['plaque']);
    $date = $_POST['date'];
    $marque = trim($_POST['marque']);
    $modele = trim($_POST['modele']);
    $couleur = trim($_POST['couleur']);
    $places = max(1, min(8, (int) $_POST['places']));
    $energie = $_POST['energie'];

    $fumeur = isset($_POST['fumeur']) ? 1 : 0;
    $animaux = isset($_POST['animaux']) ? 1 : 0;
    $discussions = isset($_POST['discussions']) ? 1 : 0;
    $autres_preferences = trim($_POST['autres_preferences'] ?? '');

    // Insertion du véhicule
    $stmt = $pdo->prepare("INSERT INTO vehicules (id_utilisateur, marque, modele, energie, immatriculation, couleur, nb_places) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$utilisateur_id, $marque, $modele, $energie, $immatriculation, $couleur, $places]);

    // Insertion ou mise à jour des préférences
    $stmt = $pdo->prepare("INSERT INTO preferences (id_utilisateur, fumeur, animaux, discussions, autres_preferences) 
                           VALUES (?, ?, ?, ?, ?) 
                           ON DUPLICATE KEY UPDATE 
                           fumeur = VALUES(fumeur), 
                           animaux = VALUES(animaux), 
                           discussions = VALUES(discussions), 
                           autres_preferences = VALUES(autres_preferences)");
    $stmt->execute([$utilisateur_id, $fumeur, $animaux, $discussions, $autres_preferences]);

    // Mise à jour du rôle si besoin
    if ($role === 'utilisateur') {
        $stmt = $pdo->prepare("UPDATE utilisateurs SET role = 'chauffeur' WHERE id = ?");
        $stmt->execute([$utilisateur_id]);
        $_SESSION['role'] = 'chauffeur';
    }

    $success = true;
}
?>

<div class="inscription-section">
  <h2> Devenir Chauffeur </h2>

  <?php if ($success): ?>
    <div style="display: flex; align-items: center; justify-content: space-between; background: #e6f9e6; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
      <p style="color: green; font-weight: bold; margin: 0;">✅ Vous êtes maintenant chauffeur !</p>
      <a href="index.php?page=saisir_trajet" style="background-color: #2e7d32; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: bold;">
        ➕ Proposer un trajet
      </a>
    </div>
  <?php endif; ?>

  <?php if (!$success): ?>
    <head><meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
  <form method="POST" class="inscription-form">
    <div class="form-group">
      <label for="plaque">Plaque d'immatriculation</label>
      <input type="text" name="plaque" id="plaque" required>
    </div>

    <div class="form-group">
      <label for="date">Date de première immatriculation</label>
      <input type="date" name="date" id="date" required>
    </div>

    <div class="form-group">
      <label for="marque">Marque</label>
      <input type="text" name="marque" id="marque" required>
    </div>

    <div class="form-group">
      <label for="modele">Modèle</label>
      <input type="text" name="modele" id="modele" required>
    </div>

    <div class="form-group">
      <label for="energie">Énergie</label>
      <select name="energie" id="energie" required>
        <option value="Essence">Essence</option>
        <option value="Diesel">Diesel</option>
        <option value="Électrique">Électrique</option>
        <option value="Hybride">Hybride</option>
      </select>
    </div>

    <div class="form-group">
      <label for="couleur">Couleur</label>
      <input type="text" name="couleur" id="couleur" required>
    </div>

    <div class="form-group">
      <label for="places">Nombre de places disponibles</label>
      <input type="number" name="places" id="places" min="1" max="8" required>
    </div>

    <div class="form-group">
      <label>Préférences :</label><br>
      <label><input type="checkbox" name="fumeur"> Accepte les fumeurs</label><br>
      <label><input type="checkbox" name="animaux"> Accepte les animaux</label><br>
      <label><input type="checkbox" name="discussions"> Accepte les discussions</label>
    </div>

    <div class="form-group">
      <label for="autres_preferences">Autres préférences :</label>
      <textarea name="autres_preferences" id="autres_preferences" rows="3" placeholder="Ex. : musique calme, pas de nourriture dans le véhicule..."></textarea>
    </div>

    <input type="submit" value="Valider">
  </form>
  <?php endif; ?>
</div>
