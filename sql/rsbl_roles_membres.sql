-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  Dim 31 mars 2019 à 17:52
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
-- Structure de la table `rsbl_roles_membres`
--

DROP TABLE IF EXISTS `rsbl_roles_membres`;
CREATE TABLE `rsbl_roles_membres` (
  `code_membre` int(6) NOT NULL,
  `code_role` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `code_composante` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `rang` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='fonctions assurees par membres dans composantes club';

--
-- Déchargement des données de la table `rsbl_roles_membres`
--

INSERT INTO `rsbl_roles_membres` (`code_membre`, `code_role`, `code_composante`, `rang`) VALUES
(3, 'membre', 'entretien', 1),
(6, 'resp', 'permanence', 2),
(11, 'membre', 'entretien', 1),
(11, 'resp', 'permanence', 3),
(12, 'resp', 'permanence', 1),
(13, 'membre', 'entretien', 1),
(15, 'president', 'compet', 1),
(16, 'membre', 'festivites', 1),
(18, 'tresor-adj', 'bureau', 1),
(27, 'president', 'entretien', 1),
(27, 'resp', 'permanence', 14),
(28, 'membre', 'rando', 1),
(29, 'membre', 'entretien', 1),
(31, 'membre', 'compet', 1),
(31, 'resp', 'permanence', 10),
(31, 'vice-pres', 'bureau', 1),
(33, 'membre', 'compet', 1),
(33, 'resp', 'permanence', 9),
(37, 'president', 'info', 1),
(37, 'secret-adj', 'bureau', 1),
(44, 'membre', 'festivites', 1),
(48, 'membre', 'entretien', 1),
(48, 'resp', 'permanence', 12),
(50, 'resp', 'permanence', 15),
(56, 'membre', 'formation', 1),
(56, 'president', 'festivites', 1),
(58, 'tresorier', 'bureau', 1),
(60, 'president', 'bureau', 1),
(60, 'president', 'jeunes', 1),
(60, 'resp', 'permanence', 4),
(67, 'membre', 'festivites', 1),
(68, 'membre', 'compet', 1),
(68, 'membre', 'rando', 1),
(68, 'resp', 'permanence', 6),
(71, 'membre', 'jeunes', 1),
(72, 'membre', 'jeunes', 1),
(74, 'president', 'formation', 1),
(74, 'resp', 'permanence', 5),
(82, 'membre', 'entretien', 1),
(82, 'resp', 'permanence', 7),
(83, 'membre', 'entretien', 1),
(87, 'membre', 'formation', 1),
(87, 'membre', 'rando', 1),
(95, 'admin', 'ca', 1),
(97, 'membre', 'formation', 1),
(101, 'admin', 'resabel', 1),
(101, 'membre', 'info', 1),
(101, 'resp', 'permanence', 8),
(102, 'admin', 'ca', 1),
(102, 'membre', 'formation', 1),
(102, 'resp', 'permanence', 11),
(104, 'admin', 'ca', 1),
(104, 'membre', 'festivites', 1),
(106, 'membre', 'jeunes', 1),
(111, 'membre', 'rando', 1),
(118, 'membre', 'jeunes', 1),
(130, 'membre', 'festivites', 1),
(130, 'membre', 'rando', 1),
(143, 'admin', 'ca', 1),
(143, 'resp', 'permanence', 13),
(148, 'membre', 'festivites', 1),
(1521, 'membre', 'rando', 1),
(1535, 'membre', 'rando', 1),
(16058, 'tresorier', 'bureau', 1),
(17007, 'secretaire', 'bureau', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rsbl_roles_membres`
--
ALTER TABLE `rsbl_roles_membres`
  ADD UNIQUE KEY `role_membre_composante` (`code_membre`,`code_role`,`code_composante`,`rang`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
