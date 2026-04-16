<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars($_POST["nom"]);
    $email = htmlspecialchars($_POST["email"]);
    $message = htmlspecialchars($_POST["message"]);

    

    echo "<p>Merci pour votre message, $nom ! Nous vous répondrons bientôt. 🌱</p>";
} else {
    header("Location: contact.php");
    exit();
}

require_once("includes/db.php"); 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = htmlspecialchars(trim($_POST["nom"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    if (!empty($nom) && !empty($email) && !empty($message)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO messages_contact (nom, email, message) VALUES (?, ?, ?)");
            $stmt->execute([$nom, $email, $message]);

            
            header("Location: contact.php?success=1");
            exit();
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    } else {
        header("Location: contact.php?error=1");
        exit();
    }
} else {
    header("Location: contact.php");
    exit();
}
?>
