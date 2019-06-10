-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  lun. 10 juin 2019 à 13:15
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
