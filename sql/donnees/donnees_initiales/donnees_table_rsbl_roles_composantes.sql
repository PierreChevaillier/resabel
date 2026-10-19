-- Donnees initiales de la table association 'rols_composantes'
-- Roles classiques d'une assoiciation er roles plus specifiques Resabel
-- creation : 19-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------

TRUNCATE TABLE `rsbl_roles_composantes`;

--
-- Club
--   - President, vice-president
--   - membre
--   - initiateur, formateur, entraineur, chef de bord

INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(1, 1, 1, 1);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(2, 1, 2, 1);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(7, 1, 3, 0);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(12, 1, 4, 0);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(11, 1, 5, 0);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(10, 1, 6, 0);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(9, 1, 7, 0);

-- Resabel (ne pas modifier)
--   - responsable, membre
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(13, 2, 1, 1);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(7, 2, 2, 0);

-- Conseil d'administration
--   - President, vice-president, secretaire (et adj), tresorier (et adj)
--   - membre, administrateur

INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(1, 3, 1, 1);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(2, 3, 2, 1);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(3, 3, 3, 0);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(4, 3, 4, 0);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(5, 3, 5, 0);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(6, 3, 6, 0);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(7, 3, 7, 0);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(8, 3, 8, 0);

-- bureau
--   - President, vice-president, secretaire (et adj), tresorier (et adj)

INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(1, 4, 1, 1);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(2, 4, 2, 1);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(3, 4, 3, 0);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(4, 4, 4, 0);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(5, 4, 5, 0);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(6, 4, 6, 0);

-- Commissions et permanence
--   - responsable et membre

INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(13, 5, 1, 1);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(7, 5, 2, 0);

INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(13, 6, 1, 1);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(7, 6, 2, 0);

INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(13, 7, 1, 1);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(7, 7, 2, 0);

INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(13, 8, 1, 1);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(7, 8, 2, 0);

INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(13, 9, 1, 1);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(7, 9, 2, 0);

INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(13, 10, 1, 1);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(7, 10, 2, 0);

INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(13, 11, 1, 1);
INSERT INTO `rsbl_roles_composantes` (`code_role`, `code_composante`, `rang`, `principal`) VALUES(7, 11, 2, 0);
