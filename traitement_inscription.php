<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $pseudo = trim($_POST['pseudo']);
    $email = trim($_POST['email']);
    $motdepasse_plain = $_POST['password']; 

    // Vérification du mot de passe sécurisé
    $longueurOK = strlen($motdepasse_plain) >= 8;
    $majusculeOK = preg_match('/[A-Z]/', $motdepasse_plain);
    $minusculeOK = preg_match('/[a-z]/', $motdepasse_plain);
    $chiffreOK   = preg_match('/[0-9]/', $motdepasse_plain);
    $specialOK   = preg_match('/[\W_]/', $motdepasse_plain); 

    if (!$longueurOK || !$majusculeOK || !$minusculeOK || !$chiffreOK || !$specialOK) {
        header('Location: index.php?page=inscription&error=mdpfaible');
        exit;
    }

    
    $motdepasse = password_hash($motdepasse_plain, PASSWORD_DEFAULT);

    
    require_once __DIR__ . '/includes/db.php';

    
    $check = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
        header('Location: index.php?page=inscription&error=existe');
        exit;
    }

    
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (username, email, mot_de_passe, credits, role) VALUES (?, ?, ?, 20, 'utilisateur')");

    if ($stmt->execute([$pseudo, $email, $motdepasse])) {
        header('Location: index.php?page=inscription&success=1');
        exit;
    } else {
        header('Location: index.php?page=inscription&error=1');
        exit;
    }
}
?>
