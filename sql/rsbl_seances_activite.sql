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
DROP TABLE IF EXISTS `rsbl_seances_activite`;

--
-- Structure de la table `rsbl_seances_activite`
--

CREATE TABLE `rsbl_seances_activite` (
  `code` int(11) NOT NULL,
  `code_site` tinyint(4) NOT NULL,
  `code_support` tinyint(4) NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `code_responsable` smallint(6) DEFAULT NULL,
  `information` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `rsbl_seances_activite`
--

INSERT INTO `rsbl_seances_activite` (`code`, `code_site`, `code_support`, `date_debut`, `date_fin`, `code_responsable`, `information`) VALUES
(1, 1, 5, '2020-01-19 10:30:00', '2020-01-20 11:30:00', NULL, ''),
(2, 1, 16, '2020-01-19 09:30:00', '2020-01-19 10:30:00', 27, ''),
(3, 1, 22, '2020-01-19 10:30:00', '2020-01-19 11:30:00', 60, ''),
(4, 1, 16, '2020-03-28 08:30:00', '2020-03-28 09:30:00', 101, ''),
(6, 1, 15, '2020-03-29 08:30:00', '2020-03-29 09:30:00', NULL, ''),
(7, 1, 17, '2020-03-29 09:30:00', '2020-03-29 10:30:00', 101, ''),
(8, 1, 5, '2020-03-28 09:30:00', '2020-03-28 10:30:00', NULL, ''),
(9, 2, 20, '2020-03-29 09:30:00', '2020-03-29 10:30:00', NULL, ''),
(10, 1, 1, '2020-03-29 08:30:00', '2020-03-29 09:30:00', NULL, ''),
(12, 1, 1, '2020-03-29 10:30:00', '2020-03-29 11:30:00', NULL, ''),
(13, 1, 1, '2020-03-31 09:00:00', '2020-03-31 10:00:00', 60, ''),
(14, 1, 15, '2020-03-29 09:30:00', '2020-03-29 10:30:00', NULL, ''),
(15, 1, 22, '2020-03-29 10:30:00', '2020-03-29 11:30:00', 60, ''),
(16, 1, 8, '2020-03-30 08:00:00', '2020-03-30 09:00:00', NULL, ''),
(17, 1, 1, '2020-04-04 08:00:00', '2020-04-04 09:00:00', 101, ''),
(18, 1, 1, '2020-08-29 08:00:00', '2020-08-29 09:00:00', 60, '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rsbl_seances_activite`
--
ALTER TABLE `rsbl_seances_activite`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `rsbl_seances_activite`
--
ALTER TABLE `rsbl_seances_activite`
  MODIFY `code` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
