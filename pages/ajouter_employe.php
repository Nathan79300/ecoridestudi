<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../includes/db.php');

// Seul l’admin peut ajouter un employé
if (!isset($_SESSION['admin_id'])) {
    header('Location: index.php?page=connexion_admin');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $motdepasse = password_hash($_POST['motdepasse'], PASSWORD_DEFAULT);

    // Vérifier si l'email existe déjà
    $verif = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $verif->execute([$email]);

    if ($verif->fetch()) {
        echo "❌ Cet email existe déjà.";
        exit;
    }

    // Insertion dans utilisateurs
    $stmt = $pdo->prepare("
        INSERT INTO utilisateurs (username, nom, prenom, email, mot_de_passe, role, credits, photo, suspendu) 
        VALUES (?, ?, ?, ?, ?, 'employe', 0, 'default.jpg', 0)
    ");

    $stmt->execute([
        strtolower($prenom),
        $nom,
        $prenom,
        $email,
        $motdepasse
    ]);

    header('Location: index.php?page=espace_admin');
    exit;
}
?>
