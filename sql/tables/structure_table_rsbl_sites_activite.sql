-- Suppression puis creation de la table 'sites_activite'
-- creation : 15-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------

--
-- Structure de la table
--

DROP TABLE IF EXISTS `rsbl_sites_activite`;

CREATE TABLE `rsbl_sites_activite` (
  `code` tinyint UNSIGNED NOT NULL,
  `actif` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `code_type` tinyint UNSIGNED NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_court` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `code_regime` smallint NOT NULL,
  `hauteur_maree_min` float DEFAULT NULL,
  `hauteur_maree_max` float DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes
--

ALTER TABLE `rsbl_sites_activite`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT
--
ALTER TABLE `rsbl_types_site_activite`
  MODIFY `code` tinyint UNSIGNED NOT NULL;
