-- Suppression puis creation de la table 'heures_marees'
-- creation : 15-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------

--
-- Structure de la table
--

DROP TABLE IF EXISTS `rsbl_heures_marees`;

CREATE TABLE `rsbl_heures_marees` (
  `code_lieu` int UNSIGNED NOT NULL DEFAULT '1',
  `date_heure` datetime NOT NULL,
  `etat` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'BM ou PM',
  `heure` int UNSIGNED NOT NULL COMMENT 'valeur donnee par mktime',
  `hauteur` float NOT NULL,
  `coefficient` tinyint UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Horaires des marees';

--
-- Indexes 
--
ALTER TABLE `rsbl_heures_marees`
  ADD PRIMARY KEY (`code_lieu`,`date_heure`);

