-- Suppression puis creation de la table 'connexions'
-- creation : 15-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------

--
-- Structure de la table
--
DROP TABLE IF EXISTS `rsbl_connexions`;

CREATE TABLE `rsbl_connexions` (
  `code_membre` int UNSIGNED NOT NULL,
  `identifiant` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `mot_passe` varbinary(255) DEFAULT NULL,
  `date_mot_passe` datetime DEFAULT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `date_actif` datetime DEFAULT NULL,
  `connexion` tinyint(1) NOT NULL DEFAULT '1',
  `date_connexion` datetime DEFAULT NULL,
  `date_creation` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes
--

ALTER TABLE `rsbl_connexions`
  ADD UNIQUE KEY `code_membre` (`code_membre`),
  ADD UNIQUE KEY `id` (`identifiant`);

