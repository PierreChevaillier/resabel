-- Suppression puis creation de la table 'club'
-- creation : 15-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------

--
-- Structure de la table
--

DROP TABLE IF EXISTS `rsbl_club`;

CREATE TABLE `rsbl_club` (
  `code` tinyint UNSIGNED NOT NULL,
  `identifiant` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mot_passe` varbinary(255) NOT NULL,
  `nom` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_commune` int UNSIGNED NOT NULL,
  `telephone` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `courriel` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fuseau_horaire` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Europe/Paris'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Identite du club';

--
-- Indexes
--

ALTER TABLE `rsbl_club`
  ADD PRIMARY KEY (`identifiant`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Auto increment
--

ALTER TABLE `rsbl_club`
  MODIFY `code` smallint UNSIGNED NOT NULL AUTO_INCREMENT;
