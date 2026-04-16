<?php
require_once './includes/db.php';


$check = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = :email");
$check->execute(['email' => 'test@ecoride.fr']);
$existingUser = $check->fetch();

if ($existingUser) {
    echo "✅ Utilisateur test déjà présent avec l'ID : " . $existingUser['id'];
    $conducteur_id = $existingUser['id'];
} else {
    
    $insertUser = $pdo->prepare("INSERT INTO utilisateurs 
        (username, email, mot_de_passe, photo, note) 
        VALUES (:username, :email, :mot_de_passe, :photo, :note)");

    $insertUser->execute([
        'username' => 'JeanEco',
        'email' => 'test@ecoride.fr',
        'mot_de_passe' => password_hash('motdepasse123', PASSWORD_DEFAULT),
        'photo' => 'jean.jpg', 
        'note' => 4.8
    ]);

    $conducteur_id = $pdo->lastInsertId();
    echo "✅ Utilisateur test inséré avec l'ID : $conducteur_id";
}


$insertTrajet = $pdo->prepare("INSERT INTO trajets 
    (ville_depart, ville_arrivee, date_depart, heure_depart, places_restantes, prix, ecologique, conducteur_id) 
    VALUES (:vd, :va, :dd, :hd, :places, :prix, :eco, :cond)");

$insertTrajet->execute([
    'vd' => 'Paris',
    'va' => 'Lyon',
    'dd' => date('Y-m-d', strtotime('+1 day')), 
    'hd' => '10:00',
    'places' => 3,
    'prix' => 12.00,
    'eco' => 1,
    'cond' => $conducteur_id
]);

echo "<br>🚗 Trajet test inséré avec succès.";
?>