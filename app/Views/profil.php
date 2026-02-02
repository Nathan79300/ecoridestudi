<h1>👤 Mon profil</h1>

<?php if (!empty($message)): ?>
    <div class="msg-success">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<form method="POST">

    <div class="form-group">
        <label>Prénom :</label>
        <input type="text" name="prenom" value="<?= htmlspecialchars($utilisateur['prenom']) ?>" required>
    </div>

    <div class="form-group">
        <label>Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($utilisateur['nom']) ?>" required>
    </div>

    <div class="form-group">
        <label>Je souhaite être :</label>
        <select name="role">
            <option value="utilisateur" <?= $utilisateur['role']=='utilisateur'?'selected':'' ?>>Passager</option>
            <option value="chauffeur" <?= $utilisateur['role']=='chauffeur'?'selected':'' ?>>Chauffeur</option>
            <option value="passager_chauffeur" <?= $utilisateur['role']=='passager_chauffeur'?'selected':'' ?>>Les deux</option>
        </select>
    </div>

    <button type="submit" class="btn-submit">💾 Mettre à jour</button>

</form>

<hr>

<?php if (!empty($trajets)): ?>
    <h2>🚗 Mes trajets proposés</h2>

    <?php foreach ($trajets as $trajet): ?>
        <div class="trajet-card">
            <p><strong>Départ :</strong> <?= htmlspecialchars($trajet['ville_depart']) ?></p>
            <p><strong>Arrivée :</strong> <?= htmlspecialchars($trajet['ville_arrivee']) ?></p>
            <p><strong>Date :</strong> <?= htmlspecialchars($trajet['date_depart']) ?> à <?= htmlspecialchars($trajet['heure_depart']) ?></p>
            <p><strong>Prix :</strong> <?= htmlspecialchars($trajet['prix']) ?> crédits</p>
            <p><strong>Places restantes :</strong> <?= htmlspecialchars($trajet['places_restantes']) ?></p>

            <a href="/ecoridestudi/ecoride/public/details?id=<?= $trajet['id'] ?>">
                <button>Détails</button>
            </a>
        </div>
    <?php endforeach; ?>

<?php else: ?>
    <p>Aucun trajet proposé pour le moment.</p>
<?php endif; ?>
