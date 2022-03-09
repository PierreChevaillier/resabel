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
DROP TABLE IF EXISTS `rsbl_indisponibilites`;

--
-- Structure de la table `rsbl_indisponibilites`
--

CREATE TABLE `rsbl_indisponibilites` (
  `code` int(11) NOT NULL,
  `code_type` smallint(4) NOT NULL,
  `date_creation` datetime NOT NULL,
  `code_createur` smallint(4) NOT NULL,
  `code_motif` smallint(4) NOT NULL,
  `code_objet` smallint(4) NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `information` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `rsbl_indisponibilites`
--

INSERT INTO `rsbl_indisponibilites` (`code`, `code_type`, `date_creation`, `code_createur`, `code_motif`, `code_objet`, `date_debut`, `date_fin`, `information`) VALUES
(1, 1, '2019-06-10 14:19:00', 101, 1, 5, '2019-01-08 12:00:00', '2020-01-22 00:00:00', 'Ceci est un essai. ça marche hélène ?  '),
(2, 2, '2019-06-10 21:38:00', 101, 14, 1, '2020-04-04 11:00:00', '2020-04-04 13:00:00', 'Avis de grosse tempête'),
(3, 2, '2020-01-19 17:00:00', 101, 2, 1, '2020-06-13 00:00:00', '2020-06-14 00:00:00', 'Manche Championnat Grand Ouest 2020 à Plougonvelin'),
(4, 1, '2020-01-19 17:00:00', 101, 8, 5, '2020-01-25 12:00:00', '2020-01-25 14:00:00', 'Essai créneau infra journée'),
(5, 1, '2020-03-29 17:00:00', 101, 6, 14, '2020-04-04 00:00:00', '2020-04-05 00:00:00', 'Petites réparations à faire');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rsbl_indisponibilites`
--
ALTER TABLE `rsbl_indisponibilites`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `rsbl_indisponibilites`
--
ALTER TABLE `rsbl_indisponibilites`
  MODIFY `code` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
