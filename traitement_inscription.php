<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sécurisation des entrées
    $pseudo = trim($_POST['pseudo'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $motdepasse_plain = $_POST['password'] ?? '';

    // Vérification champs vides
    if ($pseudo === '' || $email === '' || $motdepasse_plain === '') {
        header('Location: index.php?page=inscription&error=vide');
        exit;
    }

    // Vérification email valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: index.php?page=inscription&error=emailinvalide');
        exit;
    }

    // Vérification longueur mot de passe
    if (strlen($motdepasse_plain) < 8) {
        header('Location: index.php?page=inscription&error=motdepassecourt');
        exit;
    }

    // Vérification mot de passe fort
    if (
        !preg_match('/[A-Z]/', $motdepasse_plain) || 
        !preg_match('/[a-z]/', $motdepasse_plain) ||
        !preg_match('/[0-9]/', $motdepasse_plain) ||
        !preg_match('/[\W]/', $motdepasse_plain)
    ) {
        header('Location: index.php?page=inscription&error=mdpfaible');
        exit;
    }

    // Hash du mot de passe
    $motdepasse = password_hash($motdepasse_plain, PASSWORD_DEFAULT);

    require_once __DIR__ . '/includes/db.php';

    // Vérifier si email existe déjà
    $check = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
        header('Location: index.php?page=inscription&error=existe');
        exit;
    }

    // Insertion
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
