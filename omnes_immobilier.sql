-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 27 mai 2025 à 14:54
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `omnes_immobilier`
--

-- --------------------------------------------------------

--
-- Structure de la table `biens_immobiliers`
--

DROP TABLE IF EXISTS `biens_immobiliers`;
CREATE TABLE IF NOT EXISTS `biens_immobiliers` (
  `id_bien` int NOT NULL AUTO_INCREMENT,
  `type_bien` enum('résidentiel','commercial','terrain','appartement','enchere') NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text,
  `ville` varchar(100) DEFAULT NULL,
  `adresse` text,
  `superficie` float DEFAULT NULL,
  `nb_pieces` int DEFAULT NULL,
  `nb_chambres` int DEFAULT NULL,
  `etage` int DEFAULT NULL,
  `balcon` tinyint(1) DEFAULT NULL,
  `parking` tinyint(1) DEFAULT NULL,
  `prix` int DEFAULT NULL,
  `photos` varchar(255) DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `vendu` tinyint(1) DEFAULT '0',
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `id_agent` int DEFAULT NULL,
  PRIMARY KEY (`id_bien`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `biens_immobiliers`
--

INSERT INTO `biens_immobiliers` (`id_bien`, `type_bien`, `titre`, `description`, `ville`, `adresse`, `superficie`, `nb_pieces`, `nb_chambres`, `etage`, `balcon`, `parking`, `prix`, `photos`, `video`, `vendu`, `latitude`, `longitude`, `id_agent`) VALUES
(1, 'résidentiel', 'Maison avec jardin', 'Belle maison 4 pièces avec grand jardin', 'Paris', '12 rue Verte', 120.5, 4, 3, 0, 1, 1, 1000000, 'images/maison1.jpg', 'videos/maison1.mp4', 0, 48.8566, 2.3522, 2),
(2, 'appartement', 'Studio meublé', 'Studio meublé proche université', 'Lyon', '45 avenue des Fleurs', 28, 1, 0, 3, 0, 0, 200000, 'images/studio1.jpg', NULL, 0, 45.764, 4.8357, 2),
(3, 'terrain', 'Terrain constructible', 'Terrain à bâtir dans quartier résidentiel', 'Marseille', 'Chemin des Vignes', 500, 0, 0, 0, 0, 0, 50000, 'images/terrain1.jpg', NULL, 0, 43.2965, 5.3698, 2),
(4, 'résidentiel', 'Château ', 'Magnifique propriété avec architecture classique', 'Caen', 'Rue des Ducs', 320, 10, 6, 2, 1, 1, 950000, 'images/maison2.jpg', NULL, 0, 49.1829, -0.37, 2),
(5, 'résidentiel', 'Villa familiale ', 'Grande maison avec jardin fleuri et terrasse', 'Étretat', 'Rue des Rosiers', 180, 6, 4, 1, 1, 1, 670000, 'images/maison3.jpg', NULL, 0, 49.7061, 0.204, 2),
(6, 'appartement', 'Studio lumineux \r\n', 'Studio cosy avec poutres apparentes', 'Paris', '9e arrondissement', 35, 1, 0, 5, 0, 0, 320000, 'images/studio2.jpg', NULL, 0, 48.8821, 2.3371, 2),
(7, 'appartement', 'Studio meublé ', 'Studio fonctionnel idéal étudiant ou jeune actif', 'Lille', 'Boulevard de la Liberté', 28, 1, 0, 2, 0, 0, 210000, 'images/studio3.jpg', NULL, 0, 50.6306, 3.046, 2),
(8, 'terrain', 'Terrain agricole ', 'Grand terrain avec vue sur campagne', 'Fleurie', 'Chemin rural', 800, 0, 0, 0, 0, 0, 90000, 'images/terrain2.jpg', NULL, 0, 46.2021, 4.6892, 2),
(9, 'terrain', 'Terrain constructible', 'Terrain plat viabilisé en zone résidentielle', 'Marseille', 'Rue des Tamaris', 600, 0, 0, 0, 0, 0, 120000, 'images/terrain3.jpg', NULL, 0, 43.2965, 5.3698, 2),
(10, 'commercial', 'Locaux modernes ', 'Bureaux neufs RDC dans immeuble résidentiel', 'Bordeaux', 'Avenue Victor Hugo', 145, 4, 0, 0, 0, 1, 480000, 'images/commercial1.jpg', NULL, 0, 44.8378, -0.5792, 2),
(11, 'commercial', 'Commerce en centre-ville ', 'Idéal profession médicale ou libérale', 'Ajaccio', 'Rue Fesch', 100, 3, 0, 0, 0, 0, 375000, 'images/commercial2.jpg', NULL, 0, 41.9265, 8.7369, 2),
(12, 'commercial', 'Local commercial ', 'Commerce lumineux avec vitrines en centre-ville', 'Lyon', 'Rue Victor Hugo', 110, 3, 0, 0, 0, 1, 390000, 'images/commercial3.jpg', NULL, 0, 45.7578, 4.832, 2);

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id_message` int NOT NULL AUTO_INCREMENT,
  `id_expediteur` int NOT NULL,
  `id_destinataire` int NOT NULL,
  `id_bien` int NOT NULL,
  `contenu` text NOT NULL,
  `date_envoi` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_message`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id_message`, `id_expediteur`, `id_destinataire`, `id_bien`, `contenu`, `date_envoi`) VALUES
(1, 3, 2, 1, 'Bonjour, je suis intéressée par l’appartement à Lyon.', '2025-05-01 09:00:00'),
(2, 2, 3, 1, 'Bonjour Emma, je peux vous proposer une visite mercredi.', '2025-05-01 09:15:00'),
(3, 3, 2, 1, 'Parfait, mercredi 15h me va très bien.', '2025-05-01 09:18:00'),
(4, 2, 3, 1, 'Super, je vous envoie l’adresse par email.', '2025-05-01 09:20:00');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `portable` int NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `type_utilisateur` enum('admin','agent','client') NOT NULL,
  `adresse` text,
  `info_financieres` text,
  `photo_profil` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_user`, `nom`, `prenom`, `email`, `portable`, `mot_de_passe`, `type_utilisateur`, `adresse`, `info_financieres`, `photo_profil`) VALUES
(1, 'Martin', 'Lucie', 'lucie.martin@omnes.fr', 0, 'a', 'admin', NULL, NULL, 'images/admin_lucie.jpg'),
(2, 'Durand', 'Paul', 'paul.durand@omnes.fr', 0, 'b', 'agent', NULL, NULL, 'images/agent_paul.jpg'),
(3, 'Bernard', 'Emma', 'emma.bernard@omnes.fr', 0, 'c', 'client', '18 rue des Cerisiers, Lyon', 'Carte Visa x-1234', 'images/client_emma.jpg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
