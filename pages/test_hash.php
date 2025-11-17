<?php
echo "<pre>";

$mdp = "admin123";
echo "Mot de passe brut : $mdp\n";

$hash = password_hash($mdp, PASSWORD_DEFAULT);
echo "Hash généré :\n$hash\n";

echo "Longueur : " . strlen($hash) . "\n";

echo "</pre>";
