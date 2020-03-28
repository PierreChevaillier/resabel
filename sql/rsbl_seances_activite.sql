SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;

-- --------------------------------------------------------
--
-- Structure de la table `rsbl_seances_activite`
--

DROP TABLE IF EXISTS `rsbl_seances_activite`;
CREATE TABLE `rsbl_seances_activite` (
  `code` int(11) NOT NULL,
  `code_site` tinyint(4) NOT NULL,
  `code_support` tinyint(4) NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `code_responsable` tinyint(4) DEFAULT NULL,
  `information` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


INSERT INTO `rsbl_seances_activite` (`code`, `code_site`, `code_support`, `date_debut`, `date_fin`, `code_responsable`, `information`) VALUES
(1, 1, 5, '2020-01-19 10:30:00', '2020-01-20 11:30:00', NULL, ''),
(2, 1, 16, '2020-01-19 09:30:00', '2020-01-19 10:30:00', 27, ''),
(3, 1, 22, '2020-01-19 10:30:00', '2020-01-19 11:30:00', 60, '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `rsbl_seances_activite`
--
ALTER TABLE `rsbl_seances_activite`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `rsbl_seances_activite`
--
ALTER TABLE `rsbl_seances_activite`
  MODIFY `code` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;
