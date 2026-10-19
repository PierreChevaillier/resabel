-- Donnees minimales de la table 'composantes'
-- Structures organisationnelles classiques d'un club nautique
-- creation : 16-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------
-- Attention : code utilise comme cle etrangere dans d'autres tables
-- --------------------------------------------------------

TRUNCATE TABLE `rsbl_composantes`;

INSERT INTO `rsbl_composantes` (`code`, `code_club`, `genre`, `nom_court`, `nom`, `courriel_contact`, `liste_diffusion`) VALUES(1, 1, 'M', 'Club', 'Club', 'contact@club-resabel.fr', 'membres.listes@club-resabel.fr');
INSERT INTO `rsbl_composantes` (`code`, `code_club`, `genre`, `nom_court`, `nom`, `courriel_contact`, `liste_diffusion`) VALUES(2, 1, 'M', 'Resabel', 'Système de gestion Resabel', 'resabel@club-resabel.fr', NULL);
INSERT INTO `rsbl_composantes` (`code`, `code_club`, `genre`, `nom_court`, `nom`, `courriel_contact`, `liste_diffusion`) VALUES(3, 1, 'M', 'CA', "Conseil d'Administration", 'ca@club-resabel.fr', 'ca.listes@club-resabel.fr');
INSERT INTO `rsbl_composantes` (`code`, `code_club`, `genre`, `nom_court`, `nom`, `courriel_contact`, `liste_diffusion`) VALUES(4, 1, 'M', 'Bureau', "Bureau du Conseil d'Administration", 'bureau@club-resabel.fr', 'bureau.listes@club-resabel.fr');
INSERT INTO `rsbl_composantes` (`code`, `code_club`, `genre`, `nom_court`, `nom`, `courriel_contact`, `liste_diffusion`) VALUES(5, 1, 'F', 'Comm', 'Commission Communication', 'communication@club-resabel.fr', 'communication.listes@club-resabel.fr');
INSERT INTO `rsbl_composantes` (`code`, `code_club`, `genre`, `nom_court`, `nom`, `courriel_contact`, `liste_diffusion`) VALUES(6, 1, 'F', 'Formation', 'Commission formation', 'formation@club-resabel.fr', 'formation.listes@club-resabel.fr');
INSERT INTO `rsbl_composantes` (`code`, `code_club`, `genre`, `nom_court`, `nom`, `courriel_contact`, `liste_diffusion`) VALUES(7, 1, 'F', 'Compét', 'Commission Compétition', 'competition@club-resabel.fr', 'competition.listes@club-resabel.fr');
INSERT INTO `rsbl_composantes` (`code`, `code_club`, `genre`, `nom_court`, `nom`, `courriel_contact`, `liste_diffusion`) VALUES(8, 1, 'F', 'Entretien', 'Commission entretien', 'entretien@club-resabel.fr', 'entretien.listes@club-resabel.fr');
INSERT INTO `rsbl_composantes` (`code`, `code_club`, `genre`, `nom_court`, `nom`, `courriel_contact`, `liste_diffusion`) VALUES(9, 1, 'F', 'Festiv', 'Commission festivités', 'festivites@club-resabel.fr', 'festivites.listes@club-resabel.fr');
INSERT INTO `rsbl_composantes` (`code`, `code_club`, `genre`, `nom_court`, `nom`, `courriel_contact`, `liste_diffusion`) VALUES(10, 1, 'F', 'Rando', 'Commission randonnées', 'randonnees@club-resabel.fr', 'randonnees.listes@club-resabel.fr');
INSERT INTO `rsbl_composantes` (`code`, `code_club`, `genre`, `nom_court`, `nom`, `courriel_contact`, `liste_diffusion`) VALUES(11, 1, 'F', 'Permanence', 'Equipe des responsables des permanences', NULL, NULL);

-- --------------------------------------------------------
