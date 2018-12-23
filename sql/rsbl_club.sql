-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  Dim 16 déc. 2018 à 15:13
-- Version du serveur :  5.6.23
-- Version de PHP :  7.1.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;

--
-- Resabel
--

-- --------------------------------------------------------

--
-- Structure de la table `rsbl_club`
--

CREATE TABLE `rsbl_club` (
  `code` tinyint(3) UNSIGNED NOT NULL,
  `identifiant` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `mot_passe` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nom` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `code_commune` int(5) NOT NULL,
  `telephone` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `courriel` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Chargement des données de la table `rsbl_club`
--

INSERT INTO `rsbl_club` (`code`, `identifiant`, `mot_passe`, `nom`, `code_commune`, `telephone`, `courriel`) VALUES
(1, 'AMP', '', 'Aviron de Mer de Plougonvelin', 29190, '', '');

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
  MODIFY `code` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
