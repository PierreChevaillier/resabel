-- phpMyAdmin SQL Dump
-- version OVH
-- https://www.phpmyadmin.net/
--
-- Hôte : avironplsi183.mysql.db
-- Généré le : mer. 09 mars 2022 à 18:00
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
DROP TABLE IF EXISTS `rsbl_roles_membres`;

--
-- Structure de la table `rsbl_roles_membres`
--

CREATE TABLE `rsbl_roles_membres` (
  `code_membre` int(6) NOT NULL,
  `code_role` tinyint(3) UNSIGNED NOT NULL,
  `code_composante` tinyint(3) UNSIGNED NOT NULL,
  `rang` tinyint(4) UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='fonctions assurees par membres dans composantes club';

--
-- Déchargement des données de la table `rsbl_roles_membres`
--

INSERT INTO `rsbl_roles_membres` (`code_membre`, `code_role`, `code_composante`, `rang`) VALUES
(3, 7, 8, 1),
(6, 13, 11, 2),
(11, 7, 8, 1),
(11, 13, 11, 3),
(12, 13, 11, 1),
(13, 7, 8, 1),
(15, 1, 7, 1),
(16, 7, 9, 1),
(18, 4, 4, 1),
(27, 1, 8, 1),
(27, 13, 11, 14),
(28, 7, 10, 1),
(29, 7, 8, 1),
(31, 2, 4, 1),
(31, 7, 7, 1),
(31, 13, 11, 10),
(33, 7, 7, 1),
(33, 13, 11, 9),
(37, 1, 5, 1),
(37, 6, 4, 1),
(44, 7, 9, 1),
(48, 7, 8, 1),
(48, 13, 11, 12),
(50, 13, 11, 15),
(56, 1, 9, 1),
(56, 7, 6, 1),
(58, 3, 4, 1),
(60, 1, 4, 1),
(60, 1, 12, 1),
(60, 13, 11, 4),
(67, 7, 9, 1),
(68, 7, 7, 1),
(68, 7, 10, 1),
(68, 13, 11, 6),
(71, 7, 12, 1),
(72, 7, 12, 1),
(74, 1, 6, 1),
(74, 13, 11, 5),
(82, 7, 8, 1),
(82, 13, 11, 7),
(83, 7, 8, 1),
(87, 7, 6, 1),
(87, 7, 10, 1),
(95, 8, 2, 2),
(95, 8, 3, 1),
(97, 7, 6, 1),
(101, 7, 5, 1),
(101, 8, 2, 1),
(101, 13, 11, 8),
(102, 7, 6, 1),
(102, 8, 3, 1),
(102, 13, 11, 11),
(104, 7, 9, 1),
(104, 8, 3, 1),
(106, 7, 12, 1),
(111, 7, 10, 1),
(118, 7, 12, 1),
(130, 7, 9, 1),
(130, 7, 10, 1),
(143, 8, 3, 1),
(143, 13, 11, 13),
(148, 7, 9, 1),
(1521, 7, 10, 1),
(1535, 7, 10, 1),
(16058, 3, 4, 1),
(17007, 5, 4, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rsbl_roles_membres`
--
ALTER TABLE `rsbl_roles_membres`
  ADD UNIQUE KEY `role_membre_composante` (`code_membre`,`code_role`,`code_composante`) USING BTREE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
