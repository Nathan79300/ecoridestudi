<?php
require_once(__DIR__ . '/../includes/db.php');
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: index.php?page=connexion');
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];
$role = $_SESSION['role'] ?? null;

$stmt = $pdo->prepare("
    SELECT DISTINCT t.id, t.ville_depart, t.ville_arrivee, t.date_depart, t.heure_depart, t.prix, t.etat, t.conducteur_id
    FROM trajets t
    LEFT JOIN participations p ON t.id = p.id_trajet
    WHERE t.conducteur_id = :id OR p.id_utilisateur = :id
    ORDER BY t.date_depart DESC
");
$stmt->execute(['id' => $utilisateur_id]);
$trajets = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT id_trajet FROM avis WHERE id_utilisateur = ?");
$stmt->execute([$utilisateur_id]);
$trajets_avis_donne = array_column($stmt->fetchAll(), 'id_trajet');

function envoyer_email_annulation($email, $trajet) {
    $sujet = "Annulation du covoiturage";
    $message = "Trajet annulé de {$trajet['ville_depart']} à {$trajet['ville_arrivee']} le {$trajet['date_depart']}.";
    echo "<!-- Mail envoyé à $email -->";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>Historique des trajets - EcoRide</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #e6f2e6; padding: 2rem; }
        .container { max-width: 1000px; margin: 0 auto; background: #fff; padding: 2rem; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.05); }
        h2 { text-align: center; color: #2e7d32; }
        table { width: 100%; border-collapse: collapse; margin-top: 2rem; }
        th, td { padding: 0.8rem; border: 1px solid #ccc; text-align: center; }
        th { background-color: #e8f5e9; color: #2e7d32; }
        button { padding: 0.4rem 0.8rem; border: none; border-radius: 5px; cursor: pointer; }
        .btn-green { background-color: #4CAF50; color: white; }
        .btn-red { background-color: #e53935; color: white; }
        .btn-blue { background-color: #1976d2; color: white; }
        .btn-grey { background-color: #9e9e9e; color: white; }

 
    </style>
</head>
<body>

<div class="container">
    <h2>📜 Historique de vos trajets</h2>

    <?php if (!empty($trajets)): ?>
        <table>
            <thead>
                <tr>
                    <th>Départ</th>
                    <th>Arrivée</th>
                    <th>Date & Heure</th>
                    <th>Prix</th>
                    <th>État</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($trajets as $trajet): ?>
                <tr>
                    <td data-label="Départ"><?= htmlspecialchars($trajet['ville_depart']) ?></td>
                    <td data-label="Arrivée"><?= htmlspecialchars($trajet['ville_arrivee']) ?></td>
                    <td data-label="Date & Heure"><?= htmlspecialchars($trajet['date_depart']) ?> à <?= htmlspecialchars($trajet['heure_depart']) ?></td>
                    <td data-label="Prix"><?= htmlspecialchars($trajet['prix']) ?> crédits</td>
                    <td data-label="État">
                        <?= match ($trajet['etat']) {
                            'en_attente' => '🟡 En attente',
                            'prévu' => '🟡 Prévu',
                            'en_cours' => '🟠 En cours',
                            'termine' => '✅ Terminé',
                            'annule' => '❌ Annulé',
                            default => '🔍 ' . htmlspecialchars($trajet['etat'])
                        } ?>
                    </td>
                    <td data-label="Actions">
                        <?php if (
                            $trajet['conducteur_id'] == $utilisateur_id &&
                            in_array($_SESSION['role'], ['chauffeur', 'passager_chauffeur'])
                        ): ?>
                            <?php if (in_array($trajet['etat'], ['en_attente', 'prévu'])): ?>
                                <form method="POST" action="index.php?page=action_trajet" style="margin-bottom:5px;">
                                    <input type="hidden" name="trajet_id" value="<?= $trajet['id'] ?>">
                                    <button type="submit" name="action" value="annuler" class="btn-red">❌ Annuler</button>
                                    <button type="submit" name="action" value="demarrer" class="btn-green">🚗 Démarrer</button>
                                </form>
                            <?php elseif ($trajet['etat'] === 'en_cours'): ?>
                                <form method="POST" action="index.php?page=action_trajet">
                                    <input type="hidden" name="trajet_id" value="<?= $trajet['id'] ?>">
                                    <button type="submit" name="action" value="terminer" class="btn-blue">🏁 Terminer</button>
                                </form>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if (
                                $trajet['etat'] === 'termine'
                                && !in_array($trajet['id'], $trajets_avis_donne)
                                && $trajet['conducteur_id'] !== $utilisateur_id
                            ): ?>
                                <form method="GET" action="index.php">
                                    <input type="hidden" name="page" value="laisser_avis">
                                    <input type="hidden" name="trajet_id" value="<?= $trajet['id'] ?>">
                                    <button type="submit" class="btn-grey">📝 Laisser un avis</button>
                                </form>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align:center; font-weight:bold;">🚫 Aucun trajet trouvé.</p>
    <?php endif; ?>
</div>

</body>
</html>
