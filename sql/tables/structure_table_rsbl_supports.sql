-- Suppression puis creation de la table 'supports_activite'
-- creation : 15-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------

--
-- Structure de la table
--

DROP TABLE IF EXISTS `rsbl_supports`;

CREATE TABLE `rsbl_supports` (
  `code` tinyint UNSIGNED NOT NULL,
  `numero` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_type_support` tinyint UNSIGNED NOT NULL,
  `nom` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modele` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `constructeur` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `annee_construction` smallint UNSIGNED DEFAULT NULL,
  `fichier_image` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `code_site_base` tinyint UNSIGNED NOT NULL,
  `nombre_postes` tinyint UNSIGNED DEFAULT NULL,
  `competition` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `loisir` tinyint(1) NOT NULL DEFAULT '1',
  `nb_initiation_min` tinyint UNSIGNED DEFAULT '0',
  `nb_initiation_max` tinyint UNSIGNED DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Supports d''activite';

--
-- Indexes
--

ALTER TABLE `rsbl_supports`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT
--

ALTER TABLE `rsbl_supports`
  MODIFY `code` tinyint UNSIGNED NOT NULL AUTO_INCREMENT;
