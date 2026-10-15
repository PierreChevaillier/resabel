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
DROP TABLE IF EXISTS `rsbl_membres`;

--
-- Structure de la table `rsbl_membres`
--

CREATE TABLE `rsbl_membres` (
  `code` int(5) NOT NULL,
  `identifiant` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `connexion` tinyint(1) NOT NULL DEFAULT '1',
  `niveau` int(5) NOT NULL DEFAULT '0',
  `genre` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'F',
  `mot_passe` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `prenom` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `nom` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `code_commune` int(5) NOT NULL,
  `rue` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `telephone` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `telephone2` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `courriel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `cdb` tinyint(1) NOT NULL DEFAULT '0',
  `derniere_connexion` timestamp NULL DEFAULT NULL,
  `num_licence` varchar(10) COLLATE utf8_unicode_ci DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `rsbl_membres`
--

INSERT INTO `rsbl_membres` (`code`, `identifiant`, `actif`, `connexion`, `niveau`, `genre`, `mot_passe`, `prenom`, `nom`, `date_naissance`, `code_commune`, `rue`, `telephone`, `telephone2`, `courriel`, `cdb`, `derniere_connexion`, `num_licence`) VALUES
(1, 'admin.resabel', 1, 1, 2, 'F', '657af3f0d2f49a560816e472c2164294', 'Admin', 'Resabel', '0000-00-00', 29130, '', '0605040302', '', 'pchevaillier@gmail.com', 0, '2018-12-17 21:11:56', ''),
(101, 'pierre.chevaillier', 1, 1, 2, 'H', '657af3f0d2f49a560816e472c2164294', 'Pierre', 'Chevaillier', '1960-10-08', 29019, '', '0908070605', '', 'pchevaillier@gmail.com', 0, '2022-03-04 12:31:49', '433888'),
(9021, 'visiteur01', 1, 0, 1, 'F', '657af3f0d2f49a560816e472c2164294', 'z', 'visiteur01', '0000-00-00', 29190, '', '', '', '', 0, '2016-11-12 15:21:55', ''),
(9050, 'visiteur02', 1, 0, 0, 'F', '657af3f0d2f49a560816e472c2164294', 'z', 'visiteur02', '0000-00-00', 29190, '', '', '', '', 0, '0000-00-00 00:00:00', '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rsbl_membres`
--
ALTER TABLE `rsbl_membres`
  ADD PRIMARY KEY (`code`),
  ADD UNIQUE KEY `identifiant` (`identifiant`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
