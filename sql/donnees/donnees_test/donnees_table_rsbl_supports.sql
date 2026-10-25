
-- --------------------------------------------------------
-- Aucun support n'existe dans la base de donnees initiale
-- S'il en existe dans la base de test,
-- on commence par les supprimer

TRUNCATE TABLE `rsbl_supports`;

-- Ergos
INSERT INTO `rsbl_supports` (`numero`, `code_type_support`, `nom`, `modele`, `constructeur`, `annee_construction`, `fichier_image`, `actif`, `code_site_base`, `nombre_postes`, `competition`, `loisir`, `nb_initiation_min`, `nb_initiation_max`) VALUES('ErgoG', 5, 'Ergos gymnase', 'modèle compet', 'Ergos Pro', 1994, 'ergo.jpg', 1, 2, 12, 1, 0, 14, 12);
INSERT INTO `rsbl_supports` (`numero`, `code_type_support`, `nom`, `modele`, `constructeur`, `annee_construction`, `fichier_image`, `actif`, `code_site_base`, `nombre_postes`, `competition`, `loisir`, `nb_initiation_min`, `nb_initiation_max`) VALUES('ErgoP', 5, 'Ergos piscine', 'modèle base', '', 2017, 'ergo.jpg', 0, 2, 2, 0, 1, 0, 0);
INSERT INTO `resabel`.`rsbl_supports` (`numero`, `code_type_support`, `nom`, `modele`, `constructeur`, `annee_construction`, `fichier_image`, `actif`, `code_site_base`, `nombre_postes`, `competition`, `loisir`, `nb_initiation_min`, `nb_initiation_max`) VALUES('ErgoC', 5, 'Ergos Club', '', '', 1994, 'ergo.jpg', 1, 2, 2, 1, 1, 0, 0);

-- Yoles loisir
INSERT INTO `rsbl_supports` (`numero`, `code_type_support`, `nom`, `modele`, `constructeur`, `annee_construction`, `fichier_image`, `actif`, `code_site_base`, `nombre_postes`, `competition`, `loisir`, `nb_initiation_min`, `nb_initiation_max`) VALUES('Y201', 2, 'Double 1', '', '', 2018, '', 1, 1, 2, 1, 1, 0, 0);
INSERT INTO `rsbl_supports` (`numero`, `code_type_support`, `nom`, `modele`, `constructeur`, `annee_construction`, `fichier_image`, `actif`, `code_site_base`, `nombre_postes`, `competition`, `loisir`, `nb_initiation_min`, `nb_initiation_max`) VALUES('Y402', 4, '4 barré 2', '', '', 2024, '', 1, 1, 5, 0, 1, 0, 0);

-- Yoles competition
INSERT INTO `rsbl_supports` (`numero`, `code_type_support`, `nom`, `modele`, `constructeur`, `annee_construction`, `fichier_image`, `actif`, `code_site_base`, `nombre_postes`, `competition`, `loisir`, `nb_initiation_min`, `nb_initiation_max`) VALUES('Y403', 4, '4 barré 3', '4x resabel', 'Resabel Constructeur', 2020, '830.jpg', 1, 1, 5, 1, 0, 0, 0);
INSERT INTO `rsbl_supports` (`numero`, `code_type_support`, `nom`, `modele`, `constructeur`, `annee_construction`, `fichier_image`, `actif`, `code_site_base`, `nombre_postes`, `competition`, `loisir`, `nb_initiation_min`, `nb_initiation_max`) VALUES('Y101', 1, 'Solo 1', '1X resabel', 'Resabel Constructeur', 2022, '1007.jpg', 1, 1, 1, 1, 0, 0, 0);

-- Yoles initiation
INSERT INTO `rsbl_supports` (`numero`, `code_type_support`, `nom`, `modele`, `constructeur`, `annee_construction`, `fichier_image`, `actif`, `code_site_base`, `nombre_postes`, `competition`, `loisir`, `nb_initiation_min`, `nb_initiation_max`) VALUES('Y202', 2, 'Double 2 ', '2X Lite Boat', 'Lite Boat', 2022, '1008.jpg', 1, 1, 2, 1, 1, 0, 3);
INSERT INTO `rsbl_supports` (`numero`, `code_type_support`, `nom`, `modele`, `constructeur`, `annee_construction`, `fichier_image`, `actif`, `code_site_base`, `nombre_postes`, `competition`, `loisir`, `nb_initiation_min`, `nb_initiation_max`) VALUES('Y404', 4, 'Albatros', '', '', 2024, NULL, 1, 1, 5, 0, 1, 2, 0);
INSERT INTO `rsbl_supports` (`numero`, `code_type_support`, `nom`, `modele`, `constructeur`, `annee_construction`, `fichier_image`, `actif`, `code_site_base`, `nombre_postes`, `competition`, `loisir`, `nb_initiation_min`, `nb_initiation_max`) VALUES('Y405', 4, 'Petrel', '', '', 2024, NULL, 1, 1, 5, 0, 1, 2, 4);

-- Bateau a moteur pour secu
INSERT INTO `rsbl_supports` (`numero`, `code_type_support`, `nom`, `modele`, `constructeur`, `annee_construction`, `fichier_image`, `actif`, `code_site_base`, `nombre_postes`, `competition`, `loisir`, `nb_initiation_min`, `nb_initiation_max`) VALUES('Secu', 6, 'Sécu mer', '', '', 2020, 'secu.jpg', 1, 1, 2, 0, 0, 0, 0);

-- Yoles desarmees

INSERT INTO `rsbl_supports` (`numero`, `code_type_support`, `nom`, `modele`, `constructeur`, `annee_construction`, `fichier_image`, `actif`, `code_site_base`, `nombre_postes`, `competition`, `loisir`, `nb_initiation_min`, `nb_initiation_max`) VALUES('Y401', 4, '4 barré 1', '', '', 2004, '', 0, 1, 5, 1, 1, 0, 0);
