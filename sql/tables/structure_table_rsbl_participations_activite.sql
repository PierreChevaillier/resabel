-- Suppression puis creation de la table 'participations_activite'
-- creation : 15-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------

--
-- Structure de la table
--

DROP TABLE IF EXISTS `rsbl_participations_activite`;

CREATE TABLE `rsbl_participations_activite` (
  `code_seance` int UNSIGNED NOT NULL,
  `code_membre` int UNSIGNED NOT NULL,
  `information` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes
--

ALTER TABLE `rsbl_participations_activite`
  ADD UNIQUE KEY `membre_seance` (`code_seance`,`code_membre`);
