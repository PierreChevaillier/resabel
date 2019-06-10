-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  lun. 10 juin 2019 à 13:10
-- Version du serveur :  5.7.21
-- Version de PHP :  7.1.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `resabel`
--

-- --------------------------------------------------------

--
-- Structure de la table `rsbl_sites_activite`
--

DROP TABLE IF EXISTS `rsbl_sites_activite`;
CREATE TABLE `rsbl_sites_activite` (
  `code` smallint(4) NOT NULL,
  `code_type_site` smallint(4) NOT NULL,
  `nom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nom_court` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `hauteur_maree_min` float DEFAULT NULL,
  `hauteur_maree_max` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `rsbl_sites_activite`
--

INSERT INTO `rsbl_sites_activite` (`code`, `code_type_site`, `nom`, `nom_court`, `latitude`, `longitude`, `hauteur_maree_min`, `hauteur_maree_max`) VALUES
(1, 1, 'Plage du Trez Hir', 'Trez Hir', 48.3489, -4.68248, 0.95, NULL),
(2, 2, 'Installations à terre', 'A terre', NULL, NULL, NULL, NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rsbl_sites_activite`
--
ALTER TABLE `rsbl_sites_activite`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `rsbl_sites_activite`
--
ALTER TABLE `rsbl_sites_activite`
  MODIFY `code` smallint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
