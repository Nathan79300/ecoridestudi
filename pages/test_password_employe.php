<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';

$email = 'employe@ecoride.fr';
$mot_clair = 'employe123';

$stmt = $pdo->prepare("SELECT id, email, role, suspendu, mot_de_passe 
                       FROM utilisateurs 
                       WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo "<pre>";
echo "Résultat SQL :\n";
var_dump($user);

if ($user) {
    echo "\nTest password_verify('{$mot_clair}', hash en base) :\n";
    var_dump(password_verify($mot_clair, $user['mot_de_passe']));
} else {
    echo "\nAUCUN utilisateur trouvé avec cet email.\n";
}
echo "</pre>";
