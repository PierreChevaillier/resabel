-- Suppression puis creation de la table 'seances_activite'
-- creation : 15-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------

--
-- Structure de la table
--

DROP TABLE IF EXISTS `rsbl_seances_activite`;

CREATE TABLE `rsbl_seances_activite` (
  `code` int UNSIGNED NOT NULL,
  `code_site` tinyint UNSIGNED NOT NULL,
  `code_support` tinyint UNSIGNED NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `code_responsable` int  UNSIGNED DEFAULT NULL,
  `information` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes
--

ALTER TABLE `rsbl_seances_activite`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT
--
ALTER TABLE `rsbl_seances_activite`
  MODIFY `code` int UNSIGNED NOT NULL AUTO_INCREMENT;
