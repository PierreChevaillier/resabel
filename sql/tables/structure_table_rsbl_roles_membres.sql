-- Suppression puis creation de la table 'roles_membres'
-- roles joues par un membre dans une composante
-- Association ternaire role - Membre - Composante
-- creation : 15-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------

--
-- Structure de la table
--

DROP TABLE IF EXISTS `rsbl_roles_membres`;

CREATE TABLE `rsbl_roles_membres` (
  `code_membre` int UNSIGNED NOT NULL,
  `code_role` tinyint UNSIGNED NOT NULL,
  `code_composante` tinyint UNSIGNED NOT NULL,
  `rang` tinyint UNSIGNED NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='fonctions assurees par membres dans composantes club';

--
-- Indexes
--

ALTER TABLE `rsbl_roles_membres`
  ADD UNIQUE KEY `role_membre_composante` (`code_composante`,`code_membre`,`code_role`) USING BTREE;

-- --------------------------------------------------------
