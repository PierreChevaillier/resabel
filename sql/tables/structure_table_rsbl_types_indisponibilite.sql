-- Suppression puis creation de la table 'types_indisponibilite'
-- creation : 15-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------

--
-- Structure de la table
--

DROP TABLE IF EXISTS `rsbl_types_indisponibilite`;

CREATE TABLE `rsbl_types_indisponibilite` (
  `code` tinyint UNSIGNED NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes
--

ALTER TABLE `rsbl_types_indisponibilite`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT
--

ALTER TABLE `rsbl_types_indisponibilite`
  MODIFY `code` tinyint UNSIGNED NOT NULL AUTO_INCREMENT;
