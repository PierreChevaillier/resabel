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
DROP TABLE IF EXISTS `rsbl_roles`;
--
-- Structure de la table `rsbl_roles`
--

CREATE TABLE `rsbl_roles` (
  `code` tinyint(3) UNSIGNED NOT NULL,
  `nom_masculin` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `nom_feminin` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='fonctions que les membres peuvent assurer';

--
-- Déchargement des données de la table `rsbl_roles`
--

INSERT INTO `rsbl_roles` (`code`, `nom_masculin`, `nom_feminin`) VALUES
(1, 'président', 'présidente'),
(2, 'vice-président', 'vice-présidente'),
(3, 'trésorier', 'trésorière'),
(4, 'trésorier adjoint', 'trésorière adjointe'),
(5, 'secrétaire', 'secrétaire'),
(6, 'secrétaire adjoint', 'scrétaire adjointe'),
(7, 'membre actif', 'membre active'),
(8, 'administrateur', 'administratrice'),
(9, 'chef de bord', 'cheffe de bord'),
(10, 'initiateur', 'initiatrice'),
(11, 'éducateur', 'éducatrice'),
(12, 'entraineur', 'entraineuse'),
(13, 'responsable', 'responsable');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rsbl_roles`
--
ALTER TABLE `rsbl_roles`
  ADD PRIMARY KEY (`code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
