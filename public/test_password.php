<?php
require_once __DIR__ . "/../vendor/autoload.php";

use Natom\Ecoride\Core\Database;

$user = null;
$verify = null;
$sentPassword = null;

if (!empty($_POST)) {

    // Ce que TON FORMULAIRE ENVOIE réellement
    $sentPassword = $_POST['password'] ?? '';

    $pdo = Database::getConnection();
    var_dump("Email reçu :", $_POST['email']);

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$_POST['email']]);
    $user = $stmt->fetch();

    if ($user) {
        // Test password_verify
        $verify = password_verify($sentPassword, $user['mot_de_passe']);
    }
}
?>
<h2>🔍 Test de mot de passe</h2>

<form method="post">
    Email :<br>
    <input type="text" name="email"><br><br>

    Mot de passe :<br>
    <input type="password" name="password"><br><br>

    <button type="submit">Tester</button>
</form>

<?php if ($user): ?>
    <hr>
    <h3>Résultat :</h3>

    <p><strong>Mot de passe envoyé :</strong>  
    <?php var_dump($sentPassword); ?></p>

    <p><strong>Hash enregistré :</strong><br>
    <?= $user['mot_de_passe'] ?></p>

    <p><strong>password_verify :</strong>  
    <?php var_dump($verify); ?></p>
<?php endif; ?>
