<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pseudo = trim($_POST['pseudo']);
    $email = trim($_POST['email']);
    $motdepasse_plain = $_POST['password'];

    

    
    $motdepasse = password_hash($motdepasse_plain, PASSWORD_DEFAULT);

    require_once __DIR__ . '/includes/db.php';

    
    $check = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
        header('Location: index.php?page=inscription&error=existe');
        exit;
    }

    
    $stmt = $pdo->prepare("
        INSERT INTO utilisateurs (username, email, mot_de_passe, credits, role)
        VALUES (?, ?, ?, 20, 'utilisateur')
    ");

    if ($stmt->execute([$pseudo, $email, $motdepasse])) {
        header('Location: index.php?page=inscription&success=1');
        exit;
    } else {
        header('Location: index.php?page=inscription&error=1');
        exit;
    }
}
?>
