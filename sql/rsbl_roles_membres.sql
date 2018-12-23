-- phpMyAdmin SQL Dump
-- version 3.1.5
-- http://www.phpmyadmin.net
--
-- Serveur: plougonvelin.mer.sql.free.fr
-- Généré le : Dim 23 Décembre 2018 à 18:19
-- Version du serveur: 5.0.83
-- Version de PHP: 5.3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;

--
-- Base de données: `resabel`
--

-- --------------------------------------------------------

--
-- Structure de la table `roles_membres`
--

DROP TABLE IF EXISTS `rsbl_roles_membres`;
CREATE TABLE IF NOT EXISTS `rsbl_roles_membres` (
  `code_membre` int(6) NOT NULL,
  `code_role` varchar(10) collate utf8_unicode_ci NOT NULL,
  `code_composante` varchar(10) collate utf8_unicode_ci NOT NULL,
  `rang` tinyint(4) NOT NULL default '1',
  UNIQUE KEY `role_membre_composante` (`code_membre`,`code_role`,`code_composante`,`rang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='fonctions assurees par membres dans composantes club';
COMMIT;
