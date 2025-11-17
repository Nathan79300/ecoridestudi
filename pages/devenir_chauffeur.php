<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';

// Vérification connexion
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: index.php?page=connexion");
    exit;
}

$uid = $_SESSION['utilisateur_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Récupération des données du formulaire
    $immatriculation = trim($_POST['plaque']);
    $date_immat      = $_POST['date_immat'];
    $marque          = trim($_POST['marque']);
    $modele          = trim($_POST['modele']);
    $energie         = trim($_POST['energie']);
    $couleur         = trim($_POST['couleur']);
    $places          = intval($_POST['places']);
    $fumeurs         = isset($_POST['fumeurs']) ? 1 : 0;

    // Requête adaptée à ta table 'vehicules'
    $sql = "INSERT INTO vehicules 
            (id_utilisateur, immatriculation, date_immat, marque, modele, energie, couleur, places, fumeurs)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([
        $uid,
        $immatriculation,
        $date_immat,
        $marque,
        $modele,
        $energie,
        $couleur,
        $places,
        $fumeurs
    ])) {

        // Mise à jour du rôle utilisateur
        $pdo->prepare("UPDATE utilisateurs SET role = 'chauffeur' WHERE id = ?")
            ->execute([$uid]);

        // Redirection profil
        header("Location: index.php?page=profil&vehicule=ok");
        exit;

    } else {
        echo "<p style='color:red; font-size:18px;'>❌ ERREUR SQL : Impossible d'ajouter le véhicule.</p>";
    }
}

?>

<!-- 🎨 FORMULAIRE HTML -->
<div class="form-container">
    <h2>Devenir Chauffeur</h2>

    <form action="" method="POST">

        <label>Plaque d'immatriculation</label>
        <input type="text" name="plaque" required>

        <label>Date de première immatriculation</label>
        <input type="date" name="date_immat" required>

        <label>Marque</label>
        <input type="text" name="marque" required>

        <label>Modèle</label>
        <input type="text" name="modele" required>

        <label>Énergie</label>
        <select name="energie" required>
            <option value="essence">Essence</option>
            <option value="diesel">Diesel</option>
            <option value="electrique">Électrique</option>
            <option value="hybride">Hybride</option>
        </select>

        <label>Couleur</label>
        <input type="text" name="couleur" required>

        <label>Nombre de places disponibles</label>
        <input type="number" name="places" min="1" max="8" required>

        <label>
            <input type="checkbox" name="fumeurs"> Accepte les fumeurs
        </label>

        <button type="submit">Enregistrer mon véhicule</button>

    </form>
</div>
