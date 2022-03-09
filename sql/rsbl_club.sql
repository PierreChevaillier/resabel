-- phpMyAdmin SQL Dump
-- version OVH
-- https://www.phpmyadmin.net/
--
-- Hôte : avironplsi183.mysql.db
-- Généré le : dim. 06 mars 2022 à 18:46
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
-- Structure de la table `rsbl_club`
--

DROP TABLE IF EXISTS `rsbl_club`;
CREATE TABLE `rsbl_club` (
  `code` tinyint(4) UNSIGNED NOT NULL,
  `identifiant` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `mot_passe` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nom` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `code_commune` int(5) NOT NULL,
  `telephone` varchar(14) COLLATE utf8_unicode_ci NOT NULL,
  `courriel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `fuseau_horaire` varchar(30) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Europe/Paris'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Identite du club';

--
-- Déchargement des données de la table `rsbl_club`
--

INSERT INTO `rsbl_club` (`code`, `identifiant`, `mot_passe`, `nom`, `code_commune`, `telephone`, `courriel`, `fuseau_horaire`) VALUES
(1, 'AMP', '657af3f0d2f49a560816e472c2164294', 'Aviron de Mer de Plougonvelin', 29190, '', '', 'Europe/Paris');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rsbl_club`
--
ALTER TABLE `rsbl_club`
  ADD PRIMARY KEY (`identifiant`),
  ADD UNIQUE KEY `code` (`code`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `rsbl_club`
--
ALTER TABLE `rsbl_club`
  MODIFY `code` tinyint(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
