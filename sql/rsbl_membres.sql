-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  Dim 23 déc. 2018 à 11:21
-- Version du serveur :  5.6.23
-- Version de PHP :  7.1.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Resabel
--

-- --------------------------------------------------------

--
-- Structure de la table `rsbl_membres`
--

DROP TABLE IF EXISTS `rsbl_membres`;
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
