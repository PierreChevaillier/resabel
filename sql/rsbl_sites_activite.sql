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
DROP TABLE IF EXISTS `rsbl_sites_activite`;

--
-- Structure de la table `rsbl_sites_activite`
--

CREATE TABLE `rsbl_sites_activite` (
  `code` smallint(4) NOT NULL,
  `actif` tinyint(4) NOT NULL DEFAULT '1',
  `code_type` smallint(4) NOT NULL,
  `nom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nom_court` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `code_regime` smallint(4) NOT NULL,
  `hauteur_maree_min` float DEFAULT NULL,
  `hauteur_maree_max` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `rsbl_sites_activite`
--

INSERT INTO `rsbl_sites_activite` (`code`, `actif`, `code_type`, `nom`, `nom_court`, `latitude`, `longitude`, `code_regime`, `hauteur_maree_min`, `hauteur_maree_max`) VALUES
(1, 1, 1, 'Plage du Trez Hir', 'Trez Hir', 48.3489, -4.68248, 1, 0.95, NULL),
(2, 1, 2, 'Installations à terre', 'A terre', 48.3489, -4.68248, 1, NULL, NULL);

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
