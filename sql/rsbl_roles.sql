-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  Dim 16 déc. 2018 à 15:17
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
-- Structure de la table `rsbl_roles`
--
DROP TABLE IF EXISTS `rsbl_roles`;
CREATE TABLE `rsbl_roles` (
  `code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `nom_masculin` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `nom_feminin` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `rang` int(6) NOT NULL,
  `principal` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='fonctions que les membres peuvent assurer';

--
-- Déchargement des données de la table `rsbl_roles`
--

INSERT INTO `rsbl_roles` (`code`, `nom_masculin`, `nom_feminin`, `rang`, `principal`) VALUES
('president', 'président', 'présidente', 1, 1),
('vice-pres', 'vice-président', 'vice-présidente', 2, 1),
('tresorier', 'trésorier', 'trésorière', 3, 0),
('tresor-adj', 'trésorier adjoint', 'trésorière adjointe', 4, 0),
('secretaire', 'secrétaire', 'secrétaire', 5, 0),
('secret-adj', 'secrétaire adjoint', 'scrétaire adjointe', 6, 0),
('membre', 'membre actif', 'membre active', 10, 0),
('cdb', 'chef de bord', 'cheffe de bord', 1, 1),
('initiateur', 'initiateur', 'initiatrice', 2, 1),
('educateur', 'éducateur', 'éducatrice', 10, 0),
('entraineur', 'entraineur', 'entraineuse', 5, 0),
('resp', 'responsable', 'responsable', 1, 0),
('admin', 'administrateur', 'administratrice', 1, 1);

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
