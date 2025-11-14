<?php
try {
    $pdo = new PDO('mysql:host=localhost;port=3308;dbname=ecoride', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion réussie à la base de données !";
} catch (PDOException $e) {
    echo "❌ Erreur : " . $e->getMessage();
}
?>