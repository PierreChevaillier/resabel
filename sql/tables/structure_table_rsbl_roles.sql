-- Suppression puis creation de la table 'roles'
-- roles definis : fonction ou responsabilite que l'on peut endosser
-- creation : 15-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------

--
-- Structure de la table
--

DROP TABLE IF EXISTS `rsbl_roles`;

CREATE TABLE `rsbl_roles` (
  `code` tinyint UNSIGNED NOT NULL,
  `nom_masculin` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_feminin` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='fonctions que les membres peuvent assurer';

--
-- Indexes
--
ALTER TABLE `rsbl_roles`
  ADD PRIMARY KEY (`code`);

--
-- Auto increment
--

ALTER TABLE `rsbl_roles`
  MODIFY `code` tinyint UNSIGNED NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
