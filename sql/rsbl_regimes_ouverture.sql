-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  lun. 01 juil. 2019 à 13:42
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
-- Structure de la table `rsbl_regimes_ouverture`
--

DROP TABLE IF EXISTS `rsbl_regimes_ouverture`;
CREATE TABLE `rsbl_regimes_ouverture` (
  `code` smallint(6) NOT NULL,
  `code_type` smallint(6) NOT NULL,
  `nom` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `jour_semaine` smallint(6) NOT NULL DEFAULT '0',
  `heure_ouverture` time NOT NULL,
  `heure_fermeture` time NOT NULL,
  `duree_seance` time NOT NULL,
  `de_jour_uniquement` tinyint(4) NOT NULL,
  `decalage_heure_hiver` time NOT NULL DEFAULT '00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `rsbl_regimes_ouverture`
--

INSERT INTO `rsbl_regimes_ouverture` (`code`, `code_type`, `nom`, `description`, `jour_semaine`, `heure_ouverture`, `heure_fermeture`, `duree_seance`, `de_jour_uniquement`, `decalage_heure_hiver`) VALUES
(1, 1, 'Sorties en mer', 'Séances d\'une heure entre le levée et le coucher du soleil. Tous les jours identiques', 0, '08:00:00', '20:00:00', '01:00:00', 1, '00:30:00');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rsbl_regimes_ouverture`
--
ALTER TABLE `rsbl_regimes_ouverture`
  ADD PRIMARY KEY (`code`,`jour_semaine`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `rsbl_regimes_ouverture`
--
ALTER TABLE `rsbl_regimes_ouverture`
  MODIFY `code` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
