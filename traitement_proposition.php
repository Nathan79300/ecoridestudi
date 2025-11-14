<?php
session_start();
require_once './includes/db.php';
include_once __DIR__ . '/includes/protect.php'; 


if (!isset($_SESSION['user_id'])) {
    echo "❌ Vous devez être connecté pour proposer un trajet.";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ville_depart = $_POST['ville_depart'];
    $ville_arrivee = $_POST['ville_arrivee'];
    $date_depart = $_POST['date_depart'];
    $heure_depart = $_POST['heure_depart'];
    $prix = $_POST['prix'];

    
    $utilisateur_id = $_SESSION['user_id'];

    try {
        $stmt = $pdo->prepare("INSERT INTO trajets (utilisateur_id, ville_depart, ville_arrivee, date_depart, heure_depart, prix) 
                               VALUES (:utilisateur_id, :ville_depart, :ville_arrivee, :date_depart, :heure_depart, :prix)");

        $stmt->execute([
            ':utilisateur_id' => $utilisateur_id,
            ':ville_depart' => $ville_depart,
            ':ville_arrivee' => $ville_arrivee,
            ':date_depart' => $date_depart,
            ':heure_depart' => $heure_depart,
            ':prix' => $prix
        ]);

        echo "✅ Trajet proposé avec succès !";
        echo '<br><a href="recherche.php">Voir les trajets</a>';
    } catch (PDOException $e) {
        echo "❌ Erreur lors de la proposition du trajet : " . $e->getMessage();
    }
} else {
    echo "Formulaire non soumis correctement.";
}
?>