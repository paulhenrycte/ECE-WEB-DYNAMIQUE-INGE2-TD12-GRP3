-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 27 mai 2025 à 10:14
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
  `id_agent` int DEFAULT NULL,
  PRIMARY KEY (`id_bien`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `biens_immobiliers`
--

INSERT INTO `biens_immobiliers` (`id_bien`, `type_bien`, `titre`, `description`, `ville`, `adresse`, `superficie`, `nb_pieces`, `nb_chambres`, `etage`, `balcon`, `parking`, `prix`, `photos`, `video`, `vendu`, `id_agent`) VALUES
(1, 'résidentiel', 'Maison avec jardin', 'Belle maison 4 pièces avec grand jardin', 'Paris', '12 rue Verte', 120.5, 4, 3, 0, 1, 1, 1000000, 'images/maison1.jpg', 'videos/maison1.mp4', 0, 2),
(2, 'appartement', 'Studio meublé', 'Studio meublé proche université', 'Lyon', '45 avenue des Fleurs', 28, 1, 0, 3, 0, 0, 200000, 'images/studio1.jpg', NULL, 0, 2),
(3, 'terrain', 'Terrain constructible', 'Terrain à bâtir dans quartier résidentiel', 'Marseille', 'Chemin des Vignes', 500, 0, 0, 0, 0, 0, 50000, 'images/terrain1.jpg', NULL, 0, 2);

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
(1, 'Martin', 'Lucie', 'lucie.martin@omnes.fr', 0, '$2y$10$motdepasseadminhashé', 'admin', NULL, NULL, 'images/admin_lucie.jpg'),
(2, 'Durand', 'Paul', 'paul.durand@omnes.fr', 0, '$2y$10$motdepasseagenthashé', 'agent', NULL, NULL, 'images/agent_paul.jpg'),
(3, 'Bernard', 'Emma', 'emma.bernard@omnes.fr', 0, '$2y$10$motdepasseclienthashé', 'client', '18 rue des Cerisiers, Lyon', 'Carte Visa x-1234', 'images/client_emma.jpg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
