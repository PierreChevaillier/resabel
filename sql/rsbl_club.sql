-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  Dim 21 oct. 2018 à 20:07
-- Version du serveur :  5.6.23
-- Version de PHP :  7.1.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `plougonvelin.mer`
--

-- --------------------------------------------------------

--
-- Structure de la table `rsbl_club`
--

CREATE TABLE `rsbl_club` (
  `code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `mot_passe` varchar(100) NOT NULL,
  `nom` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `code_commune` int(5) NOT NULL,
  `telephone` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `courriel` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rsbl_club`
--
ALTER TABLE `rsbl_club`
  ADD PRIMARY KEY (`code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
