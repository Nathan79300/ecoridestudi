<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: index.php?page=connexion");
    exit;
}

$uid = $_SESSION['utilisateur_id'];

$message_success = "";
$message_error = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $plaque = trim($_POST['plaque']);
    $date_immat = $_POST['date_immat'];
    $marque = trim($_POST['marque']);
    $modele = trim($_POST['modele']);
    $energie = trim($_POST['energie']);
    $couleur = trim($_POST['couleur']);
    $places = intval($_POST['places']);
    $fumeurs = isset($_POST['fumeurs']) ? 1 : 0;

    // Requête conforme à ta table
    $sql = "INSERT INTO vehicules 
            (id_utilisateur, immatriculation, date_immat, marque, modele, energie, couleur, places, fumeurs)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$uid, $plaque, $date_immat, $marque, $modele, $energie, $couleur, $places, $fumeurs])) {

        // Passage au rôle chauffeur
        $pdo->prepare("UPDATE utilisateurs SET role = 'chauffeur' WHERE id = ?")->execute([$uid]);

        $message_success = "🚗 Votre véhicule a été ajouté avec succès !";
    } 
    else {
        $message_error = "❌ Une erreur est survenue lors de l’ajout du véhicule.";
    }
}
?>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>


<?php if (!empty($message_success)): ?>
    <div style="
        max-width: 650px;
        margin: 20px auto;
        padding: 15px 20px;
        background: #e8f5e9;
        border-left: 6px solid #4CAF50;
        color:#2e7d32;
        border-radius: 8px;
        font-size: 1.1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    ">
        <span><?= $message_success ?></span>

        <a href="index.php?page=saisir_trajet" 
           style="
                background:#2e7d32;
                color:white;
                padding:8px 16px;
                border-radius:6px;
                text-decoration:none;
                font-weight:bold;
                transition:0.2s;
           "
           onmouseover="this.style.background='#276b2b'"
           onmouseout="this.style.background='#2e7d32'">
            ➕ Proposer un trajet
        </a>
    </div>
<?php endif; ?>

<?php if (!empty($message_error)): ?>
    <div style="
        max-width: 600px; margin: 20px auto; padding: 15px;
        background: #ffebee; border-left: 6px solid #d32f2f;
        color:#b71c1c; border-radius: 8px; font-size: 1.1rem;">
        <?= $message_error ?>
    </div>
<?php endif; ?>


<div style="
    max-width: 550px;
    margin: 3rem auto;
    background: #ffffff;
    padding: 2.5rem;
    border-radius: 18px;
    box-shadow: 0 6px 25px rgba(0,0,0,0.08);
    border-left: 6px solid #4CAF50;
">

    <h2 style="
        text-align:center;
        color:#2e7d32;
        font-size:2rem;
        margin-bottom:2rem;
    ">🚗 Devenir Chauffeur</h2>

    <form method="POST" style="display:flex; flex-direction:column; gap:1.2rem;">

        <div>
            <label>Plaque d'immatriculation</label>
            <input type="text" name="plaque" required
                style="width:100%; padding:0.7rem; border:1px solid #ddd; border-radius:8px;">
        </div>

        <div>
            <label>Date de première immatriculation</label>
            <input type="date" name="date_immat" required
                style="width:100%; padding:0.7rem; border:1px solid #ddd; border-radius:8px;">
        </div>

        <div>
            <label>Marque</label>
            <input type="text" name="marque" required
                style="width:100%; padding:0.7rem; border:1px solid #ddd; border-radius:8px;">
        </div>

        <div>
            <label>Modèle</label>
            <input type="text" name="modele" required
                style="width:100%; padding:0.7rem; border:1px solid #ddd; border-radius:8px;">
        </div>

        <div>
            <label>Énergie</label>
            <select name="energie" required
                style="width:100%; padding:0.7rem; border:1px solid #ddd; border-radius:8px;">
                <option value="essence">Essence</option>
                <option value="diesel">Diesel</option>
                <option value="electrique">Électrique</option>
                <option value="hybride">Hybride</option>
            </select>
        </div>

        <div>
            <label>Couleur</label>
            <input type="text" name="couleur" required
                style="width:100%; padding:0.7rem; border:1px solid #ddd; border-radius:8px;">
        </div>

        <div>
            <label>Nombre de places disponibles</label>
            <input type="number" name="places" min="1" max="8" required
                style="width:100%; padding:0.7rem; border:1px solid #ddd; border-radius:8px;">
        </div>

        <div style="display:flex; align-items:center; gap:0.5rem;">
            <input type="checkbox" name="fumeurs">
            <label>Accepte les fumeurs</label>
        </div>

        <button type="submit" style="
            background:#2e7d32;
            color:white;
            padding:0.9rem;
            border:none;
            border-radius:10px;
            font-size:1.1rem;
            cursor:pointer;
            transition:0.2s;
        "
        onmouseover="this.style.background='#276b2b'"
        onmouseout="this.style.background='#2e7d32'">
            Enregistrer mon véhicule
        </button>

    </form>
</div>
