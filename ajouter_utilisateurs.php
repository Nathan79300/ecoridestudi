<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/db.php';

$utilisateurs = [
    ['username' => 'alice', 'email' => 'alice@example.com', 'password' => 'alice123'],
    ['username' => 'bob', 'email' => 'bob@example.com', 'password' => 'bob123'],
    ['username' => 'charlie', 'email' => 'charlie@example.com', 'password' => 'charlie123']
];


$sql = "INSERT INTO utilisateurs (username, email, mot_de_passe, credits, role) 
        VALUES (:username, :email, :password, 20, 'visiteur')";
$stmt = $pdo->prepare($sql);

foreach ($utilisateurs as $u) {
    
    $check = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = :email");
    $check->execute([':email' => $u['email']]);

    if ($check->rowCount() == 0) {
        $stmt->execute([
            ':username' => $u['username'],
            ':email' => $u['email'],
            ':password' => password_hash($u['password'], PASSWORD_DEFAULT)
        ]);
        echo "✅ Utilisateur ajouté : {$u['username']}<br>";
    } else {
        echo "⚠️ Utilisateur déjà existant : {$u['email']}<br>";
    }
}
?>
