-- Suppression puis creation de la table 'types_site_activite'
-- creation : 15-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------

--
-- Structure de la table
--

DROP TABLE IF EXISTS `rsbl_types_site_activite`;

CREATE TABLE `rsbl_types_site_activite` (
  `code` tinyint UNSIGNED NOT NULL,
  `nom` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes
--

ALTER TABLE `rsbl_types_site_activite`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT
--

ALTER TABLE `rsbl_sites_activite`
  MODIFY `code` tinyint UNSIGNED NOT NULL AUTO_INCREMENT;

