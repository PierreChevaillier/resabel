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
DROP TABLE IF EXISTS `rsbl_types_site_activite`;

--
-- Structure de la table `rsbl_types_site_activite`
--

CREATE TABLE `rsbl_types_site_activite` (
  `code` smallint(4) NOT NULL,
  `nom` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `rsbl_types_site_activite`
--

INSERT INTO `rsbl_types_site_activite` (`code`, `nom`) VALUES
(1, 'Mer'),
(2, 'Salle de sport');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rsbl_types_site_activite`
--
ALTER TABLE `rsbl_types_site_activite`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `rsbl_types_site_activite`
--
ALTER TABLE `rsbl_types_site_activite`
  MODIFY `code` smallint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
