<h1>🚗 Proposer un trajet</h1>

<?php if (!empty($message)): ?>
    <div class="msg-success">
        <?= htmlspecialchars($message) ?>
    </div>
<?php endif; ?>

<form method="POST">

    <label>Ville de départ :</label>
    <input type="text" name="ville_depart" required>

    <label>Ville d'arrivée :</label>
    <input type="text" name="ville_arrivee" required>

    <label>Date de départ :</label>
    <input type="date" name="date_depart" required>

    <label>Heure de départ :</label>
    <input type="time" name="heure_depart" required>

    <label>Prix (crédits) :</label>
    <input type="number" name="prix" required>

    <button type="submit">Créer le trajet</button>

</form>
