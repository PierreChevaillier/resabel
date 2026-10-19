-- Suppression puis creation de la table 'site'
-- site web du club
-- creation : 15-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------

--
-- Structure de la table
--

DROP TABLE IF EXISTS `rsbl_site`;

CREATE TABLE `rsbl_site` (
  `code` tinyint UNSIGNED NOT NULL,
  `code_club` tinyint UNSIGNED NOT NULL,
  `sigle` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse_racine` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `courriel_contact` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fuseau_horaire` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes
--

ALTER TABLE `rsbl_site`
  ADD PRIMARY KEY (`code`);
