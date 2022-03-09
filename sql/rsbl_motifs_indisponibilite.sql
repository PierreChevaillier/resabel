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
DROP TABLE IF EXISTS `rsbl_motifs_indisponibilite`;

--
-- Structure de la table `rsbl_motifs_indisponibilite`
--

CREATE TABLE `rsbl_motifs_indisponibilite` (
  `code` smallint(4) NOT NULL,
  `code_composante` smallint(4) NOT NULL,
  `nom` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nom_court` varchar(16) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `rsbl_motifs_indisponibilite`
--

INSERT INTO `rsbl_motifs_indisponibilite` (`code`, `code_composante`, `nom`, `nom_court`) VALUES
(1, 7, 'Réservation pour compétition', 'Compétition'),
(2, 7, 'Compétition sur le site', 'Site compétition'),
(3, 10, 'Réservation pour une randonnée', 'Randonnée'),
(4, 4, 'Prêt de la coque', 'Prêt coque'),
(5, 8, 'Entretien régulier du matériel', 'Entretien'),
(6, 8, 'Réparation du matériel', 'Réparation'),
(7, 8, 'Hors site pour hivernage', 'Hivernage'),
(8, 6, 'Séance de formation des jeunes', 'Formation jeunes'),
(9, 6, 'Séance de formation', 'Séance formation'),
(10, 6, 'Stage de formation/perfectionnement', 'Stage'),
(11, 4, 'Journée portes ouvertes', 'Portes ouvertes'),
(12, 4, 'Découverte de l\'aviron', 'Découverte'),
(13, 4, 'Séance Aviron santé', 'Aviron santé'),
(14, 4, 'Mauvaises conditions de navigation', 'Sécurité'),
(15, 4, 'Basse mer de grandes marées', 'Grande marée');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rsbl_motifs_indisponibilite`
--
ALTER TABLE `rsbl_motifs_indisponibilite`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `rsbl_motifs_indisponibilite`
--
ALTER TABLE `rsbl_motifs_indisponibilite`
  MODIFY `code` smallint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
