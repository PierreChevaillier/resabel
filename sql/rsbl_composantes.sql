-- phpMyAdmin SQL Dump
-- version OVH
-- https://www.phpmyadmin.net/
--
-- Hôte : avironplsi183.mysql.db
-- Généré le : mer. 09 mars 2022 à 17:57
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

--
-- Structure de la table `rsbl_composantes`
--

DROP TABLE IF EXISTS `rsbl_composantes`;
CREATE TABLE `rsbl_composantes` (
  `code` tinyint(4) UNSIGNED NOT NULL,
  `code_club` int(11) NOT NULL,
  `genre` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'F',
  `nom_court` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `nom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `courriel_contact` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `liste_diffusion` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `rsbl_composantes`
--

INSERT INTO `rsbl_composantes` (`code`, `code_club`, `genre`, `nom_court`, `nom`, `courriel_contact`, `liste_diffusion`) VALUES
(1, 1, 'M', 'Club', 'Club', 'aviron.plougonvelin@gmail.com', ''),
(2, 1, 'N', 'Resabel', 'Système de gestion Resabel', 'pchevaillier@gmail.com', ''),
(3, 1, 'M', 'CA', 'Conseil d\'Administration', '', ''),
(4, 1, 'M', 'Bureau', 'Bureau', 'aviron.plougonvelin@gmail.com', ''),
(5, 1, 'F', 'Informatique', 'Commission Numérique', '', ''),
(6, 1, 'F', 'Formation', 'Commission formation et sécurité', '', ''),
(7, 1, 'F', 'Compet', 'Commission compétition', 'amp.competition@gmail.com', ''),
(8, 1, 'F', 'Entretien', 'Commission entretien', '', ''),
(9, 1, 'F', 'Festivites', 'Commission festivités', 'Amp.festivites@gmail.com', ''),
(10, 1, 'F', 'Rando', 'Commission randonnées', 'amp.randos@gmail.com', ''),
(11, 1, 'F', 'Permanence', 'Equipe des responsables des permanences', '', ''),
(12, 1, 'F', 'Jeunes', 'Commission jeunes', '', '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rsbl_composantes`
--
ALTER TABLE `rsbl_composantes`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `rsbl_composantes`
--
ALTER TABLE `rsbl_composantes`
  MODIFY `code` tinyint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
