-- Suppression puis creation de la table 'communes'
-- creation : 15-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------

--
-- Structure de la table
--

DROP TABLE IF EXISTS `rsbl_communes`;

CREATE TABLE `rsbl_communes` (
  `code` int UNSIGNED NOT NULL,
  `code_postal` int UNSIGNED NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `acces` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes
--

ALTER TABLE `rsbl_communes`
  ADD PRIMARY KEY (`code`);
