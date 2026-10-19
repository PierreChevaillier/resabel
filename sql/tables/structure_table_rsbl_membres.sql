-- Suppression puis creation de la table 'membres'
-- creation : 15-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------
--
-- Structure de la table
--
DROP TABLE IF EXISTS `rsbl_membres`;

CREATE TABLE `rsbl_membres` (
  `code` int UNSIGNED NOT NULL,
  `niveau` smallint NOT NULL DEFAULT '0',
  `genre` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'F',
  `prenom` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `code_commune` int UNSIGNED NOT NULL,
  `rue` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone2` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `courriel` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cdb` tinyint(1) NOT NULL DEFAULT '0',
  `num_licence` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes
--

ALTER TABLE `rsbl_membres`
  ADD PRIMARY KEY (`code`);
