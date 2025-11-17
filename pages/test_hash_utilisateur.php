<?php
// -----------------------------------------------------
// Générateur de hash pour utilisateur
// -----------------------------------------------------

// Mot de passe à hasher
$motdepasse = "utilisateur123";

// Génération du hash sécurisé
$hash = password_hash($motdepasse, PASSWORD_DEFAULT);

// Affichage du résultat
echo "<h2>Hash généré pour utilisateur123 :</h2>";
echo "<pre style='font-size:18px; color:green;'>$hash</pre>";

?>
