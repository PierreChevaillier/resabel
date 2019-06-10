-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  lun. 10 juin 2019 à 13:22
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
-- Structure de la table `rsbl_types_support`
--

DROP TABLE IF EXISTS `rsbl_types_support`;
CREATE TABLE `rsbl_types_support` (
  `code` smallint(4) NOT NULL,
  `nom` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nom_court` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code_type_site` smallint(4) NOT NULL,
  `nb_pers_min` smallint(4) NOT NULL DEFAULT '0',
  `nb_pers_max` smallint(4) DEFAULT NULL,
  `cdb_requis` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Types de support d''activite';

--
-- Déchargement des données de la table `rsbl_types_support`
--

INSERT INTO `rsbl_types_support` (`code`, `nom`, `nom_court`, `code_type_site`, `nb_pers_min`, `nb_pers_max`, `cdb_requis`) VALUES
(1, 'Solo', 'Solo', 1, 1, 1, 1),
(2, 'Double', 'Double', 1, 2, 2, 1),
(3, 'Quatre de pointe avec barreur', 'Quatre de pointe', 1, 5, 5, 1),
(4, 'Quatre de couple avec barreur', 'Quatre barrée', 1, 5, 5, 1),
(5, 'Plateau ergos', 'Plateau ergos', 2, 0, NULL, 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rsbl_types_support`
--
ALTER TABLE `rsbl_types_support`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `rsbl_types_support`
--
ALTER TABLE `rsbl_types_support`
  MODIFY `code` smallint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
