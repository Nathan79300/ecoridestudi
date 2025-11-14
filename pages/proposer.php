<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/protect.php';



if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: ../index.php?page=connexion');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta charset="UTF-8">
    <title>Proposer un trajet - EcoRide</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <section class="proposer-section">
        <h2>🚘 Proposer un trajet</h2>

        <form method="POST" action="">
            <label>Ville de départ :</label>
            <input type="text" name="ville_depart" required><br><br>

            <label>Ville d’arrivée :</label>
            <input type="text" name="ville_arrivee" required><br><br>

            <label>Date de départ :</label>
            <input type="date" name="date_depart" required><br><br>

            <label>Heure de départ :</label>
            <input type="time" name="heure_depart" required><br><br>

            <label>Prix (€) :</label>
            <input type="number" step="0.01" name="prix" required><br><br>

            <button type="submit">Publier le trajet</button>
        </form>

        <hr>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ville_depart = $_POST['ville_depart'];
            $ville_arrivee = $_POST['ville_arrivee'];
            $date_depart = $_POST['date_depart'];
            $heure_depart = $_POST['heure_depart'];
            $prix = $_POST['prix'];
            $utilisateur_id = $_SESSION['utilisateur_id'];

            try {
                $sql = "INSERT INTO trajets (ville_depart, ville_arrivee, date_depart, heure_depart, prix, utilisateur_id) 
                        VALUES (:ville_depart, :ville_arrivee, :date_depart, :heure_depart, :prix, :utilisateur_id)";

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':ville_depart' => $ville_depart,
                    ':ville_arrivee' => $ville_arrivee,
                    ':date_depart' => $date_depart,
                    ':heure_depart' => $heure_depart,
                    ':prix' => $prix,
                    ':utilisateur_id' => $utilisateur_id
                ]);

                echo "<p style='color:green;'>✅ Trajet publié avec succès !</p>";
            } catch (PDOException $e) {
                echo "<p style='color:red;'>❌ Erreur : " . $e->getMessage() . "</p>";
            }
        }
        ?>
    </section>

    
</body>
</html>
