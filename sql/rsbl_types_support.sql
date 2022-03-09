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
DROP TABLE IF EXISTS `rsbl_types_support`;

--
-- Structure de la table `rsbl_types_support`
--

CREATE TABLE `rsbl_types_support` (
  `code` smallint(4) NOT NULL,
  `code_type` smallint(4) NOT NULL DEFAULT '1',
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

INSERT INTO `rsbl_types_support` (`code`, `code_type`, `nom`, `nom_court`, `code_type_site`, `nb_pers_min`, `nb_pers_max`, `cdb_requis`) VALUES
(1, 1, 'Solo', 'Solo', 1, 1, 1, 1),
(2, 1, 'Double', 'Double', 1, 2, 2, 1),
(3, 1, 'Quatre de pointe avec barreur', 'Quatre de pointe', 1, 5, 5, 1),
(4, 1, 'Quatre de couple avec barreur', 'Quatre barrée', 1, 5, 5, 1),
(5, 2, 'Plateau ergos', 'Plateau ergos', 2, 0, NULL, 0),
(6, 1, 'Bateau sécurité', 'Sécu', 1, 1, 1, 1);

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
  MODIFY `code` smallint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
