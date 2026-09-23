-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3307
-- Généré le : mer. 18 fév. 2026 à 16:37
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ecoride`
--

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id` int(11) NOT NULL,
  `id_conducteur` int(11) DEFAULT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `id_trajet` int(11) NOT NULL,
  `auteur` varchar(100) DEFAULT NULL,
  `note` int(11) DEFAULT NULL CHECK (`note` between 1 and 5),
  `commentaire` text DEFAULT NULL,
  `valide` tinyint(1) NOT NULL DEFAULT 0,
  `probleme` tinyint(1) NOT NULL DEFAULT 0,
  `traite` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `avis`
--

INSERT INTO `avis` (`id`, `id_conducteur`, `id_utilisateur`, `id_trajet`, `auteur`, `note`, `commentaire`, `valide`, `probleme`, `traite`) VALUES
(1, 1, 2, 0, 'Marie', 5, 'Super trajet, merci !', 0, 1, 0),
(2, 1, 2, 1, 'Marie', 5, 'Très bon trajet', 1, 0, 0),
(4, 1, 3, 1, 'Boris', 1, 'Le chauffeur roulait trop vite', 1, 0, 0),
(6, 1, 2, 1, 'Marie', 5, 'Très bon trajet', -1, 0, 0),
(19, NULL, 1, 1, NULL, 5, 'Avis test : trajet nickel ✅', 1, 0, 0),
(20, NULL, 1, 1, NULL, 5, 'Avis test : trajet nickel ✅', -1, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `participations`
--

CREATE TABLE `participations` (
  `id` int(11) NOT NULL,
  `id_trajet` int(11) DEFAULT NULL,
  `id_utilisateur` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `participations`
--

INSERT INTO `participations` (`id`, `id_trajet`, `id_utilisateur`) VALUES
(1, 1, 2),
(3, 1, 8),
(4, 1, 8),
(12, 1, 9);

-- --------------------------------------------------------

--
-- Structure de la table `preferences`
--

CREATE TABLE `preferences` (
  `id` int(11) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `musique` tinyint(1) DEFAULT NULL,
  `animaux` tinyint(1) DEFAULT NULL,
  `discussions` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `preferences`
--

INSERT INTO `preferences` (`id`, `id_utilisateur`, `musique`, `animaux`, `discussions`) VALUES
(1, 1, 1, 0, 1);

-- --------------------------------------------------------

--
-- Structure de la table `trajets`
--

CREATE TABLE `trajets` (
  `id` int(11) NOT NULL,
  `conducteur_id` int(11) DEFAULT NULL,
  `vehicule_id` int(11) NOT NULL,
  `ville_depart` varchar(100) DEFAULT NULL,
  `ville_arrivee` varchar(100) DEFAULT NULL,
  `date_depart` date DEFAULT NULL,
  `heure_depart` time DEFAULT NULL,
  `prix` int(11) DEFAULT NULL,
  `places_restantes` int(11) DEFAULT NULL,
  `ecologique` tinyint(1) DEFAULT NULL,
  `etat` enum('en_attente','prévu','en_cours','termine','annule') DEFAULT 'en_attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `trajets`
--

INSERT INTO `trajets` (`id`, `conducteur_id`, `vehicule_id`, `ville_depart`, `ville_arrivee`, `date_depart`, `heure_depart`, `prix`, `places_restantes`, `ecologique`, `etat`) VALUES
(1, 1, 0, 'Paris', 'Londres', '2026-03-05', '14:00:00', 2, 1, 1, 'prévu'),
(2, 1, 0, 'Paris', 'Lyon', '2026-12-05', '14:00:00', 3, 2, 0, 'prévu'),
(3, 7, 3, 'Paris', 'Bressuire', '2026-06-28', '18:49:00', 4, 5, 0, 'termine'),
(12, 1, 0, 'Paris', 'londres', '2026-02-06', '13:13:00', 2, 1, 0, 'prévu');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `role` enum('utilisateur','chauffeur','passager_chauffeur','employe','admin') DEFAULT 'utilisateur',
  `credits` int(11) DEFAULT 0,
  `photo` varchar(255) DEFAULT 'default.jpg',
  `pseudo` varchar(100) DEFAULT NULL,
  `note_moyenne` float DEFAULT 0,
  `suspendu` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `username`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`, `credits`, `photo`, `pseudo`, `note_moyenne`, `suspendu`) VALUES
(1, 'Eco Jean', 'Eco', 'Jean', 'alice@example.com', '$2y$10$FXSRfSZ.TBF7tveHFcihLuGUObYFJm/2cCj71RidUPlANZ59R/lX.', 'passager_chauffeur', 19, 'jean.jpg', NULL, 0, 0),
(2, 'User Marie', 'User', 'Marie', 'utilisateur@example.com', '$2y$10$FXSRfSZ.TBF7tveHFcihLuGUObYFJm/2cCj71RidUPlANZ59R/lX.', 'utilisateur', 20, 'marie.jpg', 'Marie', 0, 0),
(3, 'Employe Paul', 'Employe', 'Paul', 'employe@ecoride.fr', '$2y$10$jYF2Xwl9thyboerGN7YzIOUNVee69X62IW0itD7wUlA1WMzCciP12', 'employe', 0, 'default.jpg', NULL, 0, 0),
(4, 'Admin Admin', 'Admin', 'Admin', 'admin@ecoride.fr', '$2y$10$.tL0U5u5tY4Qbxm.smoEgeqQy4VOJAVBJqHZm2/N.xOET9GL0/P7O', 'admin', 0, 'default.jpg', NULL, 0, 0),
(5, 'Boris', NULL, NULL, 'Boris@gmail.com', '$2y$10$FXSRfSZ.TBF7tveHFcihLuGUObYFJm/2cCj71RidUPlANZ59R/lX.', 'utilisateur', 20, 'default.jpg', NULL, 0, 0),
(6, 'pierre', '', '', 'pierre@example.com', '$2y$10$P3IvbeoLndYAKPMm.kP8feLabZgpzSJ8q.ol7lVND7wg1X8h49c8C', 'chauffeur', 20, 'default.jpg', NULL, 0, 0),
(7, 'seb', 'seb', 'seb', 'seb@example.com', '$2y$10$P3IvbeoLndYAKPMm.kP8feLabZgpzSJ8q.ol7lVND7wg1X8h49c8C', 'chauffeur', 14, 'default.jpg', NULL, 0, 0),
(8, 'yohann', 'Mathieu', 'Yohan', 'yohan@example.com', '$2y$10$P3IvbeoLndYAKPMm.kP8feLabZgpzSJ8q.ol7lVND7wg1X8h49c8C', 'chauffeur', 18, 'default.jpg', NULL, 0, 0),
(9, 'popo54', 'popo', 'popo', 'popo@gmail.com', '$2y$10$w8V6TcBWacU7gzkK7JH3Qu8yRhPDCaRe1GqjwoYGHYM2y/HQ58fXC', 'utilisateur', 18, 'default.jpg', NULL, 0, 0),
(10, 'loupie', 'Sabrina', 'Loupie', 'sabrina@ecoride.fr', '$2y$10$Y4no4Bm5geucQMm1NZ5vPO4S5m/g3b3iojjiu.K30HMO5c4AfUxxu', 'employe', 0, 'default.jpg', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `vehicules`
--

CREATE TABLE `vehicules` (
  `id` int(11) NOT NULL,
  `id_utilisateur` int(11) DEFAULT NULL,
  `immatriculation` varchar(20) NOT NULL,
  `date_immat` date NOT NULL DEFAULT '2025-01-01',
  `marque` varchar(100) DEFAULT NULL,
  `modele` varchar(100) DEFAULT NULL,
  `energie` varchar(50) DEFAULT NULL,
  `couleur` varchar(50) NOT NULL DEFAULT 'inconnu',
  `places` int(11) NOT NULL DEFAULT 1,
  `fumeurs` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `vehicules`
--

INSERT INTO `vehicules` (`id`, `id_utilisateur`, `immatriculation`, `date_immat`, `marque`, `modele`, `energie`, `couleur`, `places`, `fumeurs`) VALUES
(1, 1, '', '2025-01-01', 'Peugeot', '208', 'essence', 'inconnu', 1, 0),
(2, 1, '', '2025-01-01', 'Citroën', 'C3', 'électrique', 'inconnu', 1, 0),
(3, 7, 'ABCDEF', '2025-11-06', 'Alfa Romeo', 'Mito', 'essence', 'noir', 3, 0),
(6, 1, 'ADB-DFG-79', '2026-02-20', 'Ferrari', 'dfdfd', 'électrique', 'noir', 4, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_conducteur` (`id_conducteur`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `participations`
--
ALTER TABLE `participations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_trajet` (`id_trajet`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `preferences`
--
ALTER TABLE `preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `trajets`
--
ALTER TABLE `trajets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `conducteur_id` (`conducteur_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `vehicules`
--
ALTER TABLE `vehicules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `participations`
--
ALTER TABLE `participations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `preferences`
--
ALTER TABLE `preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `trajets`
--
ALTER TABLE `trajets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `vehicules`
--
ALTER TABLE `vehicules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`id_conducteur`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `participations`
--
ALTER TABLE `participations`
  ADD CONSTRAINT `participations_ibfk_1` FOREIGN KEY (`id_trajet`) REFERENCES `trajets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `participations_ibfk_2` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `preferences`
--
ALTER TABLE `preferences`
  ADD CONSTRAINT `preferences_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `trajets`
--
ALTER TABLE `trajets`
  ADD CONSTRAINT `trajets_ibfk_1` FOREIGN KEY (`conducteur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `vehicules`
--
ALTER TABLE `vehicules`
  ADD CONSTRAINT `vehicules_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
