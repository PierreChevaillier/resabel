-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  Dim 16 déc. 2018 à 15:21
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
-- Structure de la table `rsbl_composantes`
--
DROP TABLE IF EXISTS `rsbl_composantes`;
CREATE TABLE `rsbl_composantes` (
  `code` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `nom` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `courriel_contact` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `rsbl_composantes`
--

INSERT INTO `rsbl_composantes` (`code`, `nom`, `courriel_contact`) VALUES
('bureau', 'Bureau', 'aviron.plougonvelin@gmail.com'),
('festivites', 'Commission festivités', 'Amp.festivites@gmail.com	'),
('entretien', 'Commission entretien', ''),
('rando', 'Commission randonnées', 'amp.randos@gmail.com'),
('formation', 'Commission formation et sécurité', ''),
('permanence', 'Equipe des responsables des permanence', ''),
('compet', 'Commission compétition', 'amp.competition@gmail.com'),
('jeunes', 'Commission jeunes', ''),
('info', 'Informatique', ''),
('resabel', 'Resabel', 'pchevaillier@gmail.com'),
('ca', 'Conseil d\'Administration', '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rsbl_composantes`
--
ALTER TABLE `rsbl_composantes`
  ADD PRIMARY KEY (`code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
