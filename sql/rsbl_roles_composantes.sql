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
DROP TABLE IF EXISTS `rsbl_roles_composantes`;

--
-- Structure de la table `rsbl_roles_composantes`
--

CREATE TABLE `rsbl_roles_composantes` (
  `code_role` tinyint(3) UNSIGNED NOT NULL,
  `code_composante` tinyint(3) UNSIGNED NOT NULL,
  `rang` tinyint(3) NOT NULL,
  `principal` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='association roles - composantes';

--
-- Déchargement des données de la table `rsbl_roles_composantes`
--

INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES
(1, 1, 1, 1),
(1, 3, 1, 1),
(2, 3, 2, 1),
(3, 3, 3, 0),
(4, 3, 4, 0),
(5, 3, 5, 0),
(6, 3, 6, 0),
(7, 3, 10, 0),
(8, 2, 1, 1),
(9, 1, 1, 1),
(10, 1, 2, 1),
(11, 1, 3, 0),
(12, 1, 4, 0),
(13, 11, 1, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
