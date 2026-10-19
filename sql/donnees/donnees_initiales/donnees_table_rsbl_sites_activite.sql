
-- --------------------------------------------------------

--
-- Structure de la table `rsbl_test`.`rsbl_sites_activite`
--

TRUNCATE TABLE `rsbl_sites_activite`;

INSERT INTO `rsbl_sites_activite` (`actif`, `code_type`, `nom`, `nom_court`, `latitude`, `longitude`, `code_regime`, `hauteur_maree_min`, `hauteur_maree_max`) VALUES(1, 1, 'Plage du Trez Hir', 'Trez Hir', 48.3489, -4.68248, 1, 0.95, NULL);
INSERT INTO `rsbl_sites_activite` (`actif`, `code_type`, `nom`, `nom_court`, `latitude`, `longitude`, `code_regime`, `hauteur_maree_min`, `hauteur_maree_max`) VALUES(1, 2, 'Ergomètres', 'Ergos', 48.3489, -4.68248, 2, NULL, NULL);
