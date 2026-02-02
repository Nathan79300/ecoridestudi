<section class="recherche-section">

    <form method="GET" action="/ecoridestudi/ecoride/public/recherche">
        <h2 class="titre-page">🔍 Rechercher un trajet</h2>

        <label>Ville de départ :</label>
        <input type="text" name="ville_depart" required>

        <label>Ville d’arrivée :</label>
        <input type="text" name="ville_arrivee" required>

        <label>Date de départ :</label>
        <input type="date" name="date_depart" required>

        <label>Nombre de passagers :</label>
        <input type="number" name="passagers" min="1" max="10" value="1" required>

        <label><input type="checkbox" name="ecologique" value="1"> Voyage écologique seulement</label>

        <label>Prix maximum (€) :</label>
        <input type="number" name="prix_max" min="0">

        <label>Durée maximale (minutes) :</label>
        <input type="number" name="duree_max" min="1">

        <label>Note minimale du conducteur (0 à 5) :</label>
        <input type="number" name="note_min" min="0" max="5" step="0.1">

        <button type="submit">Rechercher</button>
    </form>
</section>

<hr>

<section class="resultats-section">

    <?php if (!empty($resultats)): ?>

        <?php foreach ($resultats as $row): ?>
        
            <div class="trajet-card">

                <img src="/ecoridestudi/ecoride/public/images/<?= htmlspecialchars($row['photo']) ?>" 
                     alt="photo chauffeur" class="photo-chauffeur">

                <p><strong><?= htmlspecialchars($row['pseudo']) ?></strong>
                    — Note : <?= number_format($row['note_moyenne'], 1) ?>/5
                </p>

                <p><strong>Départ :</strong> <?= htmlspecialchars($row['ville_depart']) ?> |
                   <strong>Arrivée :</strong> <?= htmlspecialchars($row['ville_arrivee']) ?>
                </p>

                <p><strong>Date :</strong> <?= $row['date_depart'] ?> à <?= $row['heure_depart'] ?></p>

                <p><strong>Places restantes :</strong> <?= $row['places_restantes'] ?></p>

                <p><strong>Prix :</strong> <?= $row['prix'] ?> €</p>

                <p><strong>Écologique :</strong> <?= $row['ecologique'] ? "✅ Oui" : "❌ Non" ?></p>

                <a href="/ecoridestudi/ecoride/public/details?id=<?= $row['id'] ?>" class="btn-detail">
                    Voir le trajet
                </a>

            </div>

        <?php endforeach; ?>

    <?php elseif (isset($noResults)): ?>

        <div class="no-results">
            <p>😢 Aucun trajet trouvé à cette date.</p>

            <?php if (!empty($prochain)): ?>
                <p>💡 Prochaine disponibilité le 
                    <?= date("d/m/Y", strtotime($prochain['date_depart'])) ?>
                </p>
            <?php else: ?>
                <p>🕒 Aucun trajet futur disponible.</p>
            <?php endif; ?>
        </div>

    <?php endif; ?>
</section>
