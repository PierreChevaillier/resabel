-- phpMyAdmin SQL Dump
-- version OVH
-- https://www.phpmyadmin.net/
--
-- Hôte : avironplsi183.mysql.db
-- Généré le : mer. 09 mars 2022 à 17:59
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
DROP TABLE IF EXISTS `rsbl_participations_activite`;

--
-- Structure de la table `rsbl_participations_activite`
--

CREATE TABLE `rsbl_participations_activite` (
  `code_seance` bigint(20) NOT NULL,
  `code_membre` smallint(6) NOT NULL,
  `information` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `rsbl_participations_activite`
--

INSERT INTO `rsbl_participations_activite` (`code_seance`, `code_membre`, `information`) VALUES
(1, 1, ''),
(1, 17, ''),
(1, 1521, ''),
(2, 27, ''),
(2, 37, ''),
(3, 60, ''),
(4, 101, ''),
(6, 101, ''),
(7, 101, ''),
(8, 101, ''),
(9, 101, ''),
(10, 60, ''),
(12, 101, ''),
(13, 60, ''),
(14, 60, ''),
(15, 60, ''),
(16, 101, ''),
(17, 101, ''),
(18, 60, ''),
(18, 101, '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rsbl_participations_activite`
--
ALTER TABLE `rsbl_participations_activite`
  ADD UNIQUE KEY `code_seance` (`code_seance`,`code_membre`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
