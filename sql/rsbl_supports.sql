-- phpMyAdmin SQL Dump
-- version OVH
-- https://www.phpmyadmin.net/
--
-- Hôte : avironplsi183.mysql.db
-- Généré le : mer. 09 mars 2022 à 18:01
-- Version du serveur : 5.6.50-log
-- Version de PHP : 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `avironplsi183` (serveur)
-- Base de données : `resabel` (local dev.)
--

-- --------------------------------------------------------
DROP TABLE IF EXISTS `rsbl_supports`;

--
-- Structure de la table `rsbl_supports`
--

CREATE TABLE `rsbl_supports` (
  `code` smallint(4) NOT NULL,
  `numero` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `code_type_support` smallint(4) NOT NULL,
  `nom` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `modele` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `constructeur` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `annee_construction` smallint(4) DEFAULT NULL,
  `fichier_image` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `code_site_base` smallint(4) NOT NULL,
  `nombre_postes` smallint(4) DEFAULT NULL,
  `competition` tinyint(4) NOT NULL DEFAULT '1',
  `loisir` tinyint(1) NOT NULL DEFAULT '1',
  `nb_initiation_min` smallint(4) DEFAULT '0',
  `nb_initiation_max` smallint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Supports d''activite';

--
-- Déchargement des données de la table `rsbl_supports`
--

INSERT INTO `rsbl_supports` (`code`, `numero`, `code_type_support`, `nom`, `modele`, `constructeur`, `annee_construction`, `fichier_image`, `actif`, `code_site_base`, `nombre_postes`, `competition`, `loisir`, `nb_initiation_min`, `nb_initiation_max`) VALUES
(1, '57', 4, 'Mouette', 'Yole 32', 'Eurodiffusion', 1999, 'b_286.jpg', 1, 1, 0, 0, 1, 0, 0),
(2, '58', 4, 'Fou de Bassan', 'Iroise 34.5', 'Latitude Composite', 2003, 'b_287.jpg', 1, 1, 0, 0, 1, 0, 0),
(3, '59', 4, 'Cormoran', 'Iroise 34.5', 'Latitude Composite', 2005, 'b_288.jpg', 1, 1, 0, 0, 1, 0, 0),
(4, '60', 4, 'Pétrel', 'Safran 33', 'Safran', 2007, 'b_289.jpg', 1, 1, 0, 0, 1, 0, 0),
(5, '61', 4, 'Sterne', 'Safran 33', 'Safran', 2009, 'b_2810.jpg', 1, 1, 0, 1, 0, 0, 0),
(6, 'B282', 2, 'Litiry', 'Yole 25', 'Safran', 1996, 'b_282.jpg', 0, 1, 0, 0, 0, 0, 0),
(7, 'B284', 2, 'Lédénes', 'Yole 25', 'Safran', 1996, 'b_284.jpg', 0, 1, 0, 0, 0, 0, 0),
(8, '62', 2, 'Bannec', 'Yole 25', 'Safran', 2012, 'b_2812.JPG', 1, 1, 0, 0, 1, 0, 0),
(9, '628', 1, 'Swansea Vale', 'Solo', 'Latitude Composite', 2005, 'b_285.jpg', 1, 1, 0, 0, 1, 0, 0),
(10, '356', 1, 'Le Coq Compet', 'Solo Eurodiff', 'Eurodiffusions', 2007, 'b_2811.jpg', 1, 1, 0, 1, 0, 0, 0),
(11, '627', 1, 'Charles Martel', 'Solo Lite Boat', 'Lite Boat', 2012, 'b_2815.jpg', 1, 1, 0, 0, 1, 0, 0),
(12, 'B280', 3, 'Brug 24', '', 'Brug', 1994, 'b_280.jpg', 0, 1, 0, 0, 1, 0, 0),
(13, 'B283', 3, 'Jument', 'Brug 24', 'Brug', 1996, 'b_283.jpg', 0, 1, 0, 0, 1, 0, 0),
(14, 'B186', 2, 'Double Thales', '', '', 1996, 'B186.jpg', 1, 1, 0, 0, 1, 0, 0),
(15, '63', 4, 'Guillemot Compet', 'Safran 33', 'Safran', 2012, 'b_2813.jpg', 1, 1, 0, 1, 0, 0, 0),
(16, '72', 2, 'Quéménes Compet', 'Yole 25', 'Safran', 2012, 'b_2814.jpg', 1, 1, 0, 1, 0, 0, 0),
(17, '630', 2, 'X25 Beniguet Compet', 'X25', 'Euro Diffusion\'s', 2016, 'X25.jpg', 1, 1, 0, 1, 0, 0, 0),
(18, 'ErgoG', 5, 'Ergos gymnase ', '', '', 1994, '', 1, 2, 4, 1, 1, 0, 0),
(19, 'ErgoP', 5, 'Ergos piscine', '', '', 2017, '', 1, 2, 4, 1, 1, 0, 0),
(20, 'ErgoC', 5, 'Ergos Club', '', '', 1994, '', 1, 2, 2, 1, 1, 0, 0),
(21, '719', 2, 'Lite Boat Compet', '', 'Lite Boat', 2018, '', 1, 1, 0, 1, 0, 0, 0),
(22, '629', 1, 'X17 Vandrée Compet', 'X17', 'Euro Diffusion\'s', 2017, '', 1, 1, 0, 1, 1, 0, 0),
(23, '53', 4, 'Bateau ligue Compet', '', '', 2017, '', 0, 1, 0, 1, 0, 0, 0),
(24, '298', 4, '298 Compet', '', '', 2018, '', 0, 1, 0, 1, 0, 0, 0),
(25, '778', 1, 'Solo 778', '', 'Euro Diffusion\'s', 2019, '', 1, 1, 0, 1, 1, 0, 0),
(26, '999', 6, 'Avirone Sécu', 'Sécu', NULL, NULL, NULL, 1, 1, 1, 1, 0, 0, 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rsbl_supports`
--
ALTER TABLE `rsbl_supports`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `rsbl_supports`
--
ALTER TABLE `rsbl_supports`
  MODIFY `code` smallint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
