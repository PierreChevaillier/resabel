-- Suppression puis creation de la table 'permanences'
-- creation : 15-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------

--
-- Structure de la table
--

DROP TABLE IF EXISTS `rsbl_permanences`;

CREATE TABLE `rsbl_permanences` (
  `annee` year NOT NULL,
  `semaine` tinyint UNSIGNED NOT NULL,
  `code_membre` int UNSIGNED NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes
--
ALTER TABLE `rsbl_permanences`
  ADD UNIQUE KEY `semaine` (`annee`,`semaine`),
  ADD UNIQUE KEY `semaine_membre` (`annee`,`semaine`,`code_membre`);
