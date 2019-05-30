
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;

--
-- Resabel
--

-- --------------------------------------------------------

--
-- Structure de la table `rsbl_roles`
--
DROP TABLE IF EXISTS `rsbl_roles`;
CREATE TABLE `rsbl_roles` (
  `code` TINYINT(3) UNSIGNED NOT NULL,
  `nom_masculin` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `nom_feminin` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `rang` TINYINT(3) NOT NULL,
  `principal` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='fonctions que les membres peuvent assurer';

--
-- Déchargement des données de la table `rsbl_roles`
--

INSERT INTO `rsbl_roles` (`code`, `nom_masculin`, `nom_feminin`, `rang`, `principal`) VALUES
(1, 'président', 'présidente', 1, 1),
(2, 'vice-président', 'vice-présidente', 2, 1),
(3, 'trésorier', 'trésorière', 3, 0),
(4, 'trésorier adjoint', 'trésorière adjointe', 4, 0),
(5, 'secrétaire', 'secrétaire', 5, 0),
(6, 'secrétaire adjoint', 'scrétaire adjointe', 6, 0),
(7, 'membre actif', 'membre active', 10, 0),
(8, 'administrateur', 'administratrice', 1, 1),
(9, 'chef de bord', 'cheffe de bord', 1, 1),
(10, 'initiateur', 'initiatrice', 2, 1),
(11, 'éducateur', 'éducatrice', 3, 0),
(12, 'entraineur', 'entraineuse', 4, 0),
(13, 'responsable', 'responsable', 1, 0);

--
-- Index pour la table `rsbl_roles`
--
ALTER TABLE `rsbl_roles`
  ADD PRIMARY KEY (`code`);


COMMIT;
