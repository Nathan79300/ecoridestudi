<h1>🚗 Détails du trajet</h1>
<?php if (isset($_GET['success']) && $_GET['success'] === 'reserved'): ?>
    <div class="alert success">🎉 Réservation effectuée avec succès !</div>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] === 'already_reserved'): ?>
    <div class="alert error">⚠ Vous avez déjà réservé ce trajet.</div>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] === 'no_places'): ?>
    <div class="alert error">❌ Il n’y a plus de places disponibles.</div>
<?php endif; ?>


<div class="trajet-card">

    <p><strong>Conducteur :</strong>
        <?= htmlspecialchars($trajet['prenom'] . " " . $trajet['nom']) ?>
    </p>

    <p><strong>Départ :</strong> <?= htmlspecialchars($trajet['ville_depart']) ?></p>
    <p><strong>Arrivée :</strong> <?= htmlspecialchars($trajet['ville_arrivee']) ?></p>

    <p>
        <strong>Date :</strong>
        <?= htmlspecialchars($trajet['date_depart']) ?> à
        <?= htmlspecialchars($trajet['heure_depart']) ?>
    </p>

    <p><strong>Prix :</strong>
        <?= htmlspecialchars($trajet['prix']) ?> crédits
    </p>

    <p><strong>Places restantes :</strong>
        <?= htmlspecialchars($trajet['places_restantes']) ?>
    </p>

    <form method="POST" action="/ecoridestudi/ecoride/public/reserver">
        <input type="hidden" name="trajet_id" value="<?= $trajet['id'] ?>">
        <button type="submit">Réserver</button>
    </form>

</div>
