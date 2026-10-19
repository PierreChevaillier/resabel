-- Suppression puis creation de la table 'roles_composantes'
-- creation : 15-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------

--
-- Structure de la table
--

DROP TABLE IF EXISTS `rsbl_roles_composantes`;

CREATE TABLE `rsbl_roles_composantes` (
  `code_role` tinyint UNSIGNED NOT NULL,
  `code_composante` tinyint UNSIGNED NOT NULL,
  `rang` tinyint UNSIGNED NOT NULL,
  `principal` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='association roles - composantes';


--
-- Indexes
--

ALTER TABLE `rsbl_roles_composantes`
  ADD UNIQUE KEY `composante_role` (`code_composante`,`code_role`),
  ADD UNIQUE KEY `composante_role_rang` (`code_composante`,`code_role`, `rang`);
