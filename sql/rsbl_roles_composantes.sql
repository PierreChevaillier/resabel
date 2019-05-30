
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;

--
-- Resabel
--

-- --------------------------------------------------------

--
-- Structure de la table `rsbl_roles_composantes`
--
DROP TABLE IF EXISTS `rsbl_roles_composantes`;
CREATE TABLE `rsbl_roles_composantes` (
  `code_role` TINYINT(3) UNSIGNED NOT NULL,
  `code_composante` TINYINT(3) UNSIGNED NOT NULL,
  `rang` TINYINT(3) NOT NULL,
  `principal` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='association roles - composantes';

INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES
(1, 1, 1, 1),
(1, 3, 1, 1),
(2, 3, 2, 1),
(3, 3, 3, 0),
(4, 3, 4, 0),
(5, 3, 5, 0),
(6, 3, 6, 0),
(7, 3, 10, 0),
(8, 2, 1, 1),
(9, 1, 1, 1),
(10, 1, 2, 1),
(11, 1, 3, 0),
(12, 1, 4, 0),
(13, 11, 1, 0);


COMMIT;
