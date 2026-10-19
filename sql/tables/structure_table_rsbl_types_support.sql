-- Suppression puis creation de la table 'types_support'
-- creation : 15-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------

--
-- Structure de la table
--

DROP TABLE IF EXISTS `rsbl_types_support`;

CREATE TABLE `rsbl_types_support` (
  `code` tinyint UNSIGNED NOT NULL,
  `code_type` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `nom` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom_court` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_type_site` tinyint UNSIGNED NOT NULL,
  `nb_pers_min` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `nb_pers_max` tinyint UNSIGNED DEFAULT NULL,
  `cdb_requis` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Types de support d''activite';

--
-- Indexes
--

ALTER TABLE `rsbl_types_support`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT
--

ALTER TABLE `rsbl_types_support`
  MODIFY `code` tinyint UNSIGNED NOT NULL AUTO_INCREMENT;
