<h1>Inscription</h1>

<?php if (!empty($error)): ?>
    <div class="msg-error">
        <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form method="POST">

    <div class="form-group">
        <label>Prénom :</label>
        <input type="text" name="prenom" required>
    </div>

    <div class="form-group">
        <label>Nom :</label>
        <input type="text" name="nom" required>
    </div>

    <div class="form-group">
        <label>Email :</label>
        <input type="email" name="email" required>
    </div>

    <div class="form-group">
        <label>Mot de passe :</label>
        <input type="password" name="password" required>
    </div>

    <button type="submit" class="btn-submit">Créer mon compte</button>

</form>
