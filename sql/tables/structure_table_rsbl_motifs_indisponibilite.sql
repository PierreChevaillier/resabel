-- Suppression puis creation de la table 'motifs_indisponibilite'
-- creation : 15-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------

--
-- Structure de la table
--

DROP TABLE IF EXISTS `rsbl_motifs_indisponibilite`;

CREATE TABLE `rsbl_motifs_indisponibilite` (
  `code` tinyint UNSIGNED NOT NULL,
  `code_type_indisponibilite` tinyint UNSIGNED NOT NULL,
  `code_composante` tinyint UNSIGNED NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_court` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes
--

ALTER TABLE `rsbl_motifs_indisponibilite`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT
--
ALTER TABLE `rsbl_motifs_indisponibilite`
  MODIFY `code` tinyint UNSIGNED NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
