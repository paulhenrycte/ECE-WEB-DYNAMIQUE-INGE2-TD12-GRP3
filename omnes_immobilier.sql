SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id_user` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(100) NOT NULL,
  `prenom` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `mot_de_passe` VARCHAR(255) NOT NULL,
  `type_utilisateur` ENUM('admin','agent','client') NOT NULL,
  `adresse` TEXT,
  `info_financieres` TEXT,
  `photo_profil` VARCHAR(255),
  PRIMARY KEY (`id_user`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;



INSERT INTO `utilisateurs` (`id_user`, `nom`, `prenom`, `email`, `mot_de_passe`, `type_utilisateur`, `adresse`, `info_financieres`, `photo_profil`) VALUES
(1, 'Martin', 'Lucie', 'lucie.martin@omnes.fr', '$2y$10$motdepasseadminhashé', 'admin', NULL, NULL, 'images/admin_lucie.jpg'),
(2, 'Durand', 'Paul', 'paul.durand@omnes.fr', '$2y$10$motdepasseagenthashé', 'agent', NULL, NULL, 'images/agent_paul.jpg'),
(3, 'Bernard', 'Emma', 'emma.bernard@omnes.fr', '$2y$10$motdepasseclienthashé', 'client', '18 rue des Cerisiers, Lyon', 'Carte Visa x-1234', 'images/client_emma.jpg');

COMMIT;



DROP TABLE IF EXISTS `biens_immobiliers`;
CREATE TABLE IF NOT EXISTS `biens_immobiliers` (
  `id_bien` INT NOT NULL AUTO_INCREMENT,
  `type_bien` ENUM('résidentiel', 'commercial', 'terrain', 'appartement', 'enchere') NOT NULL,
  `titre` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `ville` VARCHAR(100),
  `adresse` TEXT,
  `superficie` FLOAT,
  `nb_pieces` INT,
  `nb_chambres` INT,
  `etage` INT,
  `balcon` BOOLEAN,
  `parking` BOOLEAN,
  `prix` INT,
  `photos` VARCHAR(255),
  `video` VARCHAR(255),
  `vendu` BOOLEAN DEFAULT FALSE,
  `id_agent` INT,
  PRIMARY KEY (`id_bien`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;



INSERT INTO `biens_immobiliers` (`id_bien`, `type_bien`, `titre`, `description`, `ville`, `adresse`, `superficie`, `nb_pieces`, `nb_chambres`, `etage`, `balcon`, `parking`, `photos`, `video`, `vendu`, `id_agent`) VALUES
(1, 'résidentiel', 'Maison avec jardin', 'Belle maison 4 pièces avec grand jardin', 'Paris', '12 rue Verte', 120.5, 4, 3, 0, TRUE, TRUE, 'images/maison1.jpg', 'videos/maison1.mp4', FALSE, 2),
(2, 'appartement', 'Studio meublé', 'Studio meublé proche université', 'Lyon', '45 avenue des Fleurs', 28.0, 1, 0, 3, FALSE, FALSE, 'images/studio1.jpg', NULL, FALSE, 2),
(3, 'terrain', 'Terrain constructible', 'Terrain à bâtir dans quartier résidentiel', 'Marseille', 'Chemin des Vignes', 500.0, 0, 0, 0, FALSE, FALSE, 'images/terrain1.jpg', NULL, FALSE, 2);
""
