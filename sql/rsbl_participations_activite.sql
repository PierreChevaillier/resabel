SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;

-- --------------------------------------------------------
--
-- Structure de la table `rsbl_participations_activite`
--

DROP TABLE IF EXISTS `rsbl_participations_activite`;
CREATE TABLE `rsbl_participations_activite` (
  `code_seance` bigint(20) NOT NULL,
  `code_membre` smallint(6) NOT NULL,
  `information` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `rsbl_participations_activite`
--

INSERT INTO `rsbl_participations_activite` (`code_seance`, `code_membre`, `information`) VALUES
(1, 1, ''),
(1, 17, ''),
(1, 1521, ''),
(2, 27, ''),
(2, 37, ''),
(3, 60, '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rsbl_participations_activite`
--
ALTER TABLE `rsbl_participations_activite`
  ADD UNIQUE KEY `code_seance` (`code_seance`,`code_membre`);
COMMIT;
