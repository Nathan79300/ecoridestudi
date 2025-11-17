<?php
require __DIR__ . '/../includes/db.php';

$email = "admin@ecoride.fr";
$mdp = "admin123";

$stmt = $pdo->prepare("SELECT id, email, mot_de_passe, role FROM utilisateurs WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<pre>";
echo "Résultat de la requête SQL :\n";
var_dump($user);

if ($user) {
    echo "\nTest password_verify('admin123', hash en base) :\n";
    var_dump(password_verify($mdp, $user['mot_de_passe']));
}
echo "</pre>";
