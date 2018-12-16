-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  Dim 16 déc. 2018 à 15:14
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
-- Structure de la table `rsbl_site`
--

CREATE TABLE `rsbl_site` (
  `code` tinyint(4) NOT NULL,
  `code_club` tinyint(4) NOT NULL,
  `sigle` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `adresse_racine` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `courriel_contact` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `fuseau_horaire` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `rsbl_site`
--

INSERT INTO `rsbl_site` (`code`, `code_club`, `sigle`, `adresse_racine`, `courriel_contact`, `fuseau_horaire`) VALUES
(1, 1, 'AMP', 'https://avironplougonvelin.fr', 'contact@avironplougonvelin.fr', 'Europe/Paris');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rsbl_site`
--
ALTER TABLE `rsbl_site`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `rsbl_site`
--
ALTER TABLE `rsbl_site`
  MODIFY `code` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
