<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<div class="inscription-section">
    <h2>✨ Inscription</h2>
    <p>Rejoignez la communauté <strong>EcoRide</strong> et partagez vos trajets en toute simplicité 🌿</p>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="success-message">
            ✅ Inscription réussie ! Vous pouvez maintenant vous connecter.
        </div>

    <?php elseif (isset($_GET['error'])): ?>
        <div class="error-message">
            <?php
            switch ($_GET['error']) {

                case 'vide':
                    echo '❌ Merci de remplir tous les champs.';
                    break;

                case 'emailinvalide':
                    echo '❌ L\'email indiqué n\'est pas valide.';
                    break;

                case 'motdepassecourt':
                    echo '❌ Le mot de passe doit contenir au moins 8 caractères.';
                    break;

                case 'mdpfaible':
                    echo '❌ Le mot de passe doit contenir au minimum 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.';
                    break;

                case 'existe':
                    echo '❌ Cet email est déjà utilisé.';
                    break;

                default:
                    echo '❌ Une erreur est survenue, veuillez réessayer.';
                    break;
            }
            ?>
        </div>
    <?php endif; ?>

    <form method="post" action="traitement_inscription.php" class="inscription-form">
        <div class="form-group">
            <label for="pseudo">Pseudo</label>
            <input type="text" name="pseudo" id="pseudo" placeholder="Entrez votre pseudo" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="votre@email.com" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" placeholder="••••••••" required>
        </div>

        <input type="submit" value="S'inscrire">
    </form>
</div>
