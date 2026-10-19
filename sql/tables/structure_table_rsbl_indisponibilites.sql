-- Suppression puis creation de la table 'indisponibilites'
-- Periode d'indisponibilite d'un support d'activite ou d'un site d'activite
-- creation : 15-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------
--
-- Structure de la table
--

DROP TABLE IF EXISTS `rsbl_indisponibilites`;

CREATE TABLE `rsbl_indisponibilites` (
  `code` int UNSIGNED NOT NULL,
  `code_type` tinyint UNSIGNED NOT NULL,
  `nom_classe` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_creation` datetime NOT NULL,
  `code_createur` int UNSIGNED DEFAULT NULL,
  `code_motif` tinyint UNSIGNED NOT NULL,
  `code_objet` int UNSIGNED NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `information` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes
--

ALTER TABLE `rsbl_indisponibilites`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT
--

ALTER TABLE `rsbl_indisponibilites`
  MODIFY `code` int UNSIGNED NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
