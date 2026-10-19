-- Suppression puis creation de la table 'regimes_ouverture'
-- creation : 15-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------

--
-- Structure de la table
--

DROP TABLE IF EXISTS `rsbl_regimes_ouverture`;

CREATE TABLE `rsbl_regimes_ouverture` (
  `code` tinyint UNSIGNED NOT NULL,
  `code_type` tinyint UNSIGNED NOT NULL,
  `nom` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `jour_semaine` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `heure_ouverture` time NOT NULL,
  `heure_fermeture` time NOT NULL,
  `duree_seance` time NOT NULL DEFAULT '01:00:00',
  `de_jour_uniquement` tinyint(1) UNSIGNED NOT NULL,
  `decalage_heure_hiver` time NOT NULL DEFAULT '00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes
--

ALTER TABLE `rsbl_regimes_ouverture`
  ADD PRIMARY KEY (`code`,`jour_semaine`);

--
-- AUTO_INCREMENT
--

ALTER TABLE `rsbl_regimes_ouverture`
  MODIFY `code` tinyint UNSIGNED NOT NULL AUTO_INCREMENT;
