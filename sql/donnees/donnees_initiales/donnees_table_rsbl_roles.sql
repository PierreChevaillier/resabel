-- --------------------------------------------------------
-- Donnees de la table roles
-- Roles pouvant etre assumes
-- --------------------------------------------------------
-- creation : 16-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------
-- Commentaires :
-- Il y a des dependances vers cette table dans les donnees minimales
-- Attention :
-- --------------------------------------------------------

TRUNCATE TABLE `rsbl_roles`;

INSERT INTO `rsbl_roles` (`code`, `nom_masculin`, `nom_feminin`) VALUES(1, 'président', 'présidente');
INSERT INTO `rsbl_roles` (`code`, `nom_masculin`, `nom_feminin`) VALUES(2, 'vice-président', 'vice-présidente');
INSERT INTO `rsbl_roles` (`code`, `nom_masculin`, `nom_feminin`) VALUES(3, 'trésorier', 'trésorière');
INSERT INTO `rsbl_roles` (`code`, `nom_masculin`, `nom_feminin`) VALUES(4, 'trésorier adjoint', 'trésorière adjointe');
INSERT INTO `rsbl_roles` (`code`, `nom_masculin`, `nom_feminin`) VALUES(5, 'secrétaire', 'secrétaire');
INSERT INTO `rsbl_roles` (`code`, `nom_masculin`, `nom_feminin`) VALUES(6, 'secrétaire adjoint', 'scrétaire adjointe');
INSERT INTO `rsbl_roles` (`code`, `nom_masculin`, `nom_feminin`) VALUES(7, 'membre actif', 'membre active');
INSERT INTO `rsbl_roles` (`code`, `nom_masculin`, `nom_feminin`) VALUES(8, 'administrateur', 'administratrice');
INSERT INTO `rsbl_roles` (`code`, `nom_masculin`, `nom_feminin`) VALUES(9, 'chef de bord', 'cheffe de bord');
INSERT INTO `rsbl_roles` (`code`, `nom_masculin`, `nom_feminin`) VALUES(10, 'initiateur', 'initiatrice');
INSERT INTO `rsbl_roles` (`code`, `nom_masculin`, `nom_feminin`) VALUES(11, 'éducateur', 'éducatrice');
INSERT INTO `rsbl_roles` (`code`, `nom_masculin`, `nom_feminin`) VALUES(12, 'entraineur', 'entraineuse');
INSERT INTO `rsbl_roles` (`code`, `nom_masculin`, `nom_feminin`) VALUES(13, 'responsable', 'responsable');


-- --------------------------------------------------------
