-- Suppression si les tables existent déjà
DROP TABLE IF EXISTS participations, avis, preferences, trajets, vehicules, utilisateurs;

-- Création de la table utilisateurs
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(150) UNIQUE,
    mot_de_passe VARCHAR(255),
    role ENUM('utilisateur', 'chauffeur', 'passager_chauffeur', 'employe', 'admin') DEFAULT 'utilisateur',
    credits INT DEFAULT 0
);

-- Création de la table véhicules
CREATE TABLE vehicules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    marque VARCHAR(100),
    modele VARCHAR(100),
    energie VARCHAR(50),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Préférences des conducteurs
CREATE TABLE preferences (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    musique BOOLEAN,
    animaux BOOLEAN,
    discussions BOOLEAN,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Création de la table trajets
CREATE TABLE trajets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conducteur_id INT,
    ville_depart VARCHAR(100),
    ville_arrivee VARCHAR(100),
    date_depart DATE,
    heure_depart TIME,
    prix INT,
    places_restantes INT,
    ecologique BOOLEAN,
    etat ENUM('en_attente', 'prévu', 'en_cours', 'termine', 'annule') DEFAULT 'en_attente',
    FOREIGN KEY (conducteur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table de participation au trajet
CREATE TABLE participations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_trajet INT,
    id_utilisateur INT,
    FOREIGN KEY (id_trajet) REFERENCES trajets(id) ON DELETE CASCADE,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id) ON DELETE CASCADE
);

-- Table des avis
CREATE TABLE avis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_conducteur INT,
    id_utilisateur INT,
    auteur VARCHAR(100),
    note INT CHECK(note BETWEEN 1 AND 5),
    commentaire TEXT,
    FOREIGN KEY (id_conducteur) REFERENCES utilisateurs(id),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id)
);

-- Insertion des utilisateurs test
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, credits) VALUES
('Eco', 'Jean', 'alice@example.com', 'alice123', 'chauffeur', 14),
('User', 'Marie', 'utilisateur@example.co', 'Util123**', 'utilisateur', 20),
('Employe', 'Paul', 'employe@ecoride.fr', 'paul123', 'employe', 0),
('Admin', 'Admin', 'admin@ecoride.fr', 'admin123', 'admin', 0);

-- Insertion de véhicules
INSERT INTO vehicules (id_utilisateur, marque, modele, energie) VALUES
(1, 'Peugeot', '208', 'essence'),
(1, 'Citroën', 'C3', 'électrique');

-- Préférences
INSERT INTO preferences (id_utilisateur, musique, animaux, discussions) VALUES
(1, TRUE, FALSE, TRUE);

-- Insertion de trajets
INSERT INTO trajets (conducteur_id, ville_depart, ville_arrivee, date_depart, heure_depart, prix, places_restantes, ecologique, etat) VALUES
(64, 'Paris', 'Londres', '2025-07-10', '14:00:00', 25, 4, TRUE, 'prévu'),
(1, 'Paris', 'Lyon', '2025-06-10', '14:00:00', 20, 1, FALSE, 'prévu');

-- Insertion de participations
INSERT INTO participations (id_trajet, id_utilisateur) VALUES
(64, 5),
(1, 1);

-- Insertion d'avis
INSERT INTO avis (id_conducteur, id_utilisateur, auteur, note, commentaire) VALUES
(1, 2, 'Marie', 5, 'Super trajet, merci !');