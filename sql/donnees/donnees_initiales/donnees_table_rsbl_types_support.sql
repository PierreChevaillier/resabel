-- --------------------------------------------------------
-- Exemple de donnees de la table 'types_support'
-- donnees pour un club d'avirion de mer
-- --------------------------------------------------------
-- creation : 16-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------
-- Commentaires :
-- le code_type definit la classe de l'objet cree a partir
-- de la table
-- Attention :
-- auto-incrementation du code
-- --------------------------------------------------------

TRUNCATE TABLE `rsbl_types_support`;

INSERT INTO `rsbl_types_support` (`code`, `code_type`, `nom`, `nom_court`, `code_type_site`, `nb_pers_min`, `nb_pers_max`, `cdb_requis`) VALUES(1, 1, 'Yole solo', 'Solo', 1, 1, 1, 1);
INSERT INTO `rsbl_types_support` (`code`, `code_type`, `nom`, `nom_court`, `code_type_site`, `nb_pers_min`, `nb_pers_max`, `cdb_requis`) VALUES(2, 1, 'Yole Double', 'Double', 1, 2, 2, 1);
INSERT INTO `rsbl_types_support` (`code`, `code_type`, `nom`, `nom_court`, `code_type_site`, `nb_pers_min`, `nb_pers_max`, `cdb_requis`) VALUES(3, 1, 'Quatre de pointe avec barreur', 'Quatre de pointe', 1, 5, 5, 1);
INSERT INTO `rsbl_types_support` (`code`, `code_type`, `nom`, `nom_court`, `code_type_site`, `nb_pers_min`, `nb_pers_max`, `cdb_requis`) VALUES(4, 1, 'Quatre de couple avec barreur', 'Quatre barrée', 1, 5, 5, 1);
INSERT INTO `rsbl_types_support` (`code`, `code_type`, `nom`, `nom_court`, `code_type_site`, `nb_pers_min`, `nb_pers_max`, `cdb_requis`) VALUES(5, 2, 'Plateau ergos', 'Plateau ergos', 2, 0, NULL, 0);
INSERT INTO `rsbl_types_support` (`code`, `code_type`, `nom`, `nom_court`, `code_type_site`, `nb_pers_min`, `nb_pers_max`, `cdb_requis`) VALUES(6, 1, 'Bateau sécurité', 'Sécu', 1, 1, 4, 1);

-- --------------------------------------------------------
