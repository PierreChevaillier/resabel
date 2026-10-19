-- Suppression puis creation de la table 'composantes'
-- Une composante est une structure organisationnelle d'un club
-- creation : 15-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------

--
-- Structure de la table
--

DROP TABLE IF EXISTS `rsbl_composantes`;

CREATE TABLE `rsbl_composantes` (
  `code` tinyint UNSIGNED NOT NULL,
  `code_club` tinyint UNSIGNED NOT NULL,
  `genre` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'F',
  `nom_court` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `courriel_contact` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `liste_diffusion` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes
--
ALTER TABLE `rsbl_composantes`
  ADD PRIMARY KEY (`code`);

--
-- Auto increment
--

ALTER TABLE `rsbl_composantes`
  MODIFY `code` tinyint UNSIGNED NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
