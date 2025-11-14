<?php
include_once __DIR__ . '/../includes/protect.php';
require_once __DIR__ . '/../includes/db.php';
?>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<main>
    <section class="recherche-section">
        <form method="GET" action="index.php">
            <input type="hidden" name="page" value="recherche">
            <h2 class="titre-page">🔍 Rechercher un trajet</h2>

            <label>Ville de départ :</label>
            <input type="text" name="ville_depart" required><br><br>

            <label>Ville d’arrivée :</label>
            <input type="text" name="ville_arrivee" required><br><br>

            <label>Date de départ :</label>
            <input type="date" name="date_depart" required><br><br>

            <label>Nombre de passagers :</label>
            <input type="number" name="passagers" min="1" max="10" value="1" required><br><br>

            <label><input type="checkbox" name="ecologique" value="1"> Voyage écologique seulement</label><br><br>

            <label>Prix maximum (€) :</label>
            <input type="number" name="prix_max" min="0"><br><br>

            <label>Durée maximale (minutes) :</label>
            <input type="number" name="duree_max" min="1"><br><br>

            <label>Note minimale du conducteur (0 à 5) :</label>
            <input type="number" name="note_min" min="0" max="5" step="0.1"><br><br>

            <button type="submit">Rechercher</button>
        </form>
    </section>

    <hr>

    <section class="resultats-section">
    <?php

    if (isset($_GET['ville_depart'], $_GET['ville_arrivee'], $_GET['date_depart'], $_GET['passagers'])) {

        $ville_depart = $_GET['ville_depart'];
        $ville_arrivee = $_GET['ville_arrivee'];
        $date_depart = $_GET['date_depart'];
        $passagers = (int) $_GET['passagers'];

        try {

            /** ---------------------------------------------
             *  REQUÊTE PRINCIPALE (corrigée)
             * --------------------------------------------- */
            $sql = "SELECT 
                        t.*, 
                        u.username AS pseudo, 
                        u.photo,
                        u.note_moyenne
                    FROM trajets t
                    JOIN utilisateurs u ON t.conducteur_id = u.id
                    WHERE t.ville_depart = :ville_depart 
                      AND t.ville_arrivee = :ville_arrivee 
                      AND DATE(t.date_depart) = :date_depart
                      AND t.places_restantes >= :passagers";

            if (!empty($_GET['ecologique'])) {
                $sql .= " AND t.ecologique = 1";
            }
            if (!empty($_GET['prix_max'])) {
                $sql .= " AND t.prix <= :prix_max";
            }
            if (!empty($_GET['duree_max'])) {
                $sql .= " AND TIME_TO_SEC(TIMEDIFF(t.heure_arrivee, t.heure_depart)) / 60 <= :duree_max";
            }
            if (!empty($_GET['note_min'])) {
                $sql .= " AND u.note_moyenne >= :note_min";
            }

            $stmt = $pdo->prepare($sql);

            $params = [
                ':ville_depart' => $ville_depart,
                ':ville_arrivee' => $ville_arrivee,
                ':date_depart' => $date_depart,
                ':passagers' => $passagers
            ];

            if (!empty($_GET['prix_max'])) {
                $params[':prix_max'] = $_GET['prix_max'];
            }
            if (!empty($_GET['duree_max'])) {
                $params[':duree_max'] = $_GET['duree_max'];
            }
            if (!empty($_GET['note_min'])) {
                $params[':note_min'] = $_GET['note_min'];
            }

            $stmt->execute($params);
            $resultats = $stmt->fetchAll();

            /** ---------------------------------------------
             *  SI AUCUN RESULTAT => PROCHAINE DATE
             * --------------------------------------------- */
            if (!$resultats) {
                echo '<div class="no-results">';
                echo "<p>😢 Aucun trajet trouvé à cette date.</p>";

                $sql_alt = "SELECT 
                                t.*, 
                                u.username AS pseudo, 
                                u.photo,
                                u.note_moyenne
                            FROM trajets t
                            JOIN utilisateurs u ON t.conducteur_id = u.id
                            WHERE t.ville_depart = :ville_depart 
                              AND t.ville_arrivee = :ville_arrivee 
                              AND DATE(t.date_depart) > :date_depart
                              AND t.places_restantes >= :passagers
                            ORDER BY t.date_depart ASC
                            LIMIT 1";

                $stmt_alt = $pdo->prepare($sql_alt);

                $stmt_alt->execute([
                    ':ville_depart' => $ville_depart,
                    ':ville_arrivee' => $ville_arrivee,
                    ':date_depart' => $date_depart,
                    ':passagers' => $passagers
                ]);

                $prochain = $stmt_alt->fetch();

                if ($prochain) {
                    echo "<p>💡 Prochaine disponibilité le " . date("d/m/Y", strtotime($prochain['date_depart'])) . "</p>";
                    $resultats[] = $prochain;
                } else {
                    echo "<p>🕒 Aucun trajet futur disponible.</p>";
                }

                echo '</div>';
            }

            /** ---------------------------------------------
             *  AFFICHAGE DES RÉSULTATS
             * --------------------------------------------- */
            foreach ($resultats as $row) {

                echo "<div class='trajet-card'>";

                echo "<img src='images/" . htmlspecialchars($row['photo']) . "' 
                        alt='photo chauffeur' class='photo-chauffeur'>";

                echo "<p><strong>" . htmlspecialchars($row['pseudo']) . "</strong> 
                        — Note : " . number_format($row['note_moyenne'], 1) . "/5</p>";

                echo "<p><strong>Départ :</strong> " . htmlspecialchars($row['ville_depart']) .
                     " | <strong>Arrivée :</strong> " . htmlspecialchars($row['ville_arrivee']) . "</p>";

                echo "<p><strong>Date :</strong> " . $row['date_depart'] . 
                     " à " . $row['heure_depart'] . "</p>";

                echo "<p><strong>Places restantes :</strong> " . $row['places_restantes'] . "</p>";

                echo "<p><strong>Prix :</strong> " . $row['prix'] . " €</p>";

                echo "<p><strong>Écologique :</strong> " . ($row['ecologique'] ? "✅ Oui" : "❌ Non") . "</p>";

                echo '<a href="index.php?page=details_trajet&id=' . $row['id'] . '" class="btn-detail">Détail</a>';

                echo "</div>";
            }

        } catch (PDOException $e) {
            echo "<p>Erreur lors de la recherche : " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }

    ?>
    </section>
</main>
