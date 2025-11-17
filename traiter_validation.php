<?php
require_once(__DIR__ . '/../includes/db.php');

if (session_status() === PHP_SESSION_NONE) session_start();

// SÉCURITÉ
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: index.php?page=connexion");
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];

if (!isset($_POST['trajet_id'])) {
    die("ID manquant.");
}

$trajet_id = (int) $_POST['trajet_id'];
$etat = $_POST['etat'] ?? null;
$commentaire = trim($_POST['commentaire'] ?? '');
$note = $_POST['note'] ?? null;
$avis_texte = trim($_POST['avis'] ?? '');


// Vérifier que l'utilisateur a bien participé
$stmt = $pdo->prepare("
    SELECT t.*, p.id_utilisateur 
    FROM trajets t
    JOIN participations p ON p.id_trajet = t.id
    WHERE t.id = ? AND p.id_utilisateur = ?
");
$stmt->execute([$trajet_id, $utilisateur_id]);
$trajet = $stmt->fetch();

if (!$trajet) {
    die("🚫 Erreur : vous ne faites pas partie de ce trajet.");
}

$conducteur_id = $trajet['conducteur_id'];


// CAS 1 : tout s’est bien passé
if ($etat === 'ok') {

    // Si note fournie → enregistrement d'un avis classique
    if (!empty($note)) {
        $insert = $pdo->prepare("
            INSERT INTO avis (id_utilisateur, id_conducteur, id_trajet, note, commentaire, valide, probleme, statut)
            VALUES (?, ?, ?, ?, ?, 0, 0, 'en attente')
        ");
        $insert->execute([$utilisateur_id, $conducteur_id, $trajet_id, $note, $avis_texte]);
    }

    // Vérifier si tous les passagers ont validé
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM participations p 
        WHERE p.id_trajet = ?
    ");
    $stmt->execute([$trajet_id]);
    $nb_passagers = $stmt->fetchColumn();


    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM validations_passagers 
        WHERE id_trajet = ? AND etat = 'ok'
    ");
    $stmt->execute([$trajet_id]);
    $nb_validations = $stmt->fetchColumn();


    // Enregistrer validation
    $pdo->prepare("
        INSERT INTO validations_passagers (id_trajet, id_utilisateur, etat)
        VALUES (?, ?, 'ok')
    ")->execute([$trajet_id, $utilisateur_id]);


    // Si tous les passagers ont validé → créditer le chauffeur
    if ($nb_validations + 1 == $nb_passagers) {

        // Crédit vers chauffeur = prix - 2
        $gain = max(0, $trajet['prix'] - 2);

        $pdo->prepare("
            UPDATE utilisateurs SET credits = credits + ? WHERE id = ?
        ")->execute([$gain, $conducteur_id]);

        $pdo->prepare("
            UPDATE trajets SET etat = 'termine' WHERE id = ?
        ")->execute([$trajet_id]);
    }

    header("Location: index.php?page=historique&msg=valid_ok");
    exit;
}



// CAS 2 : problème signalé
if ($etat === 'probleme') {

    // Avis problématique envoyé aux employés
    $insert = $pdo->prepare("
        INSERT INTO avis (id_utilisateur, id_conducteur, id_trajet, note, commentaire, valide, probleme, statut)
        VALUES (?, ?, ?, NULL, ?, 0, 1, 'probleme')
    ");
    $insert->execute([$utilisateur_id, $conducteur_id, $trajet_id, $commentaire]);

    // Mettre le trajet en "problème"
    $pdo->prepare("
        UPDATE trajets SET etat = 'probleme' WHERE id = ?
    ")->execute([$trajet_id]);

    header("Location: index.php?page=historique&msg=reported");
    exit;
}

?>
