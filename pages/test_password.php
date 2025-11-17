<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../includes/db.php';

$resultat = "";
$hashBase = "";
$verify = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $motdepasse = $_POST['motdepasse'];

    // On cherche l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$u) {
        $resultat = "❌ Aucun utilisateur trouvé avec cet email.";
    } else {
        $hashBase = $u['mot_de_passe'];

      
        if (password_verify($motdepasse, $hashBase)) {
            $verify = "✔ Mot de passe CORRECT !";
        } 
        else {
            $verify = "❌ Mot de passe INCORRECT.";
        }

        
        $resultat = "
            <div style='padding:10px;background:#eef;border-radius:6px;margin-top:10px;'>
                <strong>ID :</strong> {$u['id']}<br>
                <strong>Email :</strong> {$u['email']}<br>
                <strong>Rôle :</strong> {$u['role']}<br>
                <strong>Hash enregistré :</strong><br>
                <code>{$hashBase}</code>
            </div>
        ";
    }
}
?>

<h2 style="text-align:center;margin-top:20px;">🔍 Test de mot de passe</h2>

<form method="POST" 
      style="max-width:400px;margin:20px auto;padding:20px;background:white;border-radius:10px;box-shadow:0 0 10px rgba(0,0,0,0.1);">

    <label>Email à tester :</label>
    <input type="email" name="email" required
           style="width:100%;padding:8px;margin-bottom:10px;border-radius:5px;border:1px solid #ccc;">

    <label>Mot de passe à tester :</label>
    <input type="password" name="motdepasse" required
           style="width:100%;padding:8px;margin-bottom:10px;border-radius:5px;border:1px solid #ccc;">

    <button type="submit"
            style="width:100%;padding:10px;background:#2e7d32;color:white;border:none;border-radius:6px;">
        Tester
    </button>
</form>

<?php if ($resultat): ?>
<div style="max-width:600px;margin:20px auto;">
    <?= $resultat ?>
</div>
<?php endif; ?>

<?php if ($verify): ?>
<div style="max-width:600px;margin:10px auto;padding:10px;background:#ffe;border-left:5px solid red;">
    <strong><?= $verify ?></strong>
</div>
<?php endif; ?>
