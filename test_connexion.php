<?php
$mysqli = new mysqli("localhost", "root", "", "ecoride", 3308);


if ($mysqli->connect_error) {
    die("Échec de la connexion : " . $mysqli->connect_error);
}
echo "Connexion réussie ! ✅";
?>