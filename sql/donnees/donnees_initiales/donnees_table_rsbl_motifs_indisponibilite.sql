-- --------------------------------------------------------
-- Donnees minimales de la table 'motifs_indisponibilite'
-- Indisponibilite d'un support (code_type_indisponibilite = 1)
-- ou fermeture d'un site (code_type_indisponibilite = 2)
-- --------------------------------------------------------
-- creation : 16-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------
-- Commentaires :
-- Il n'y a pas de dependances vers cette table dans les donnees minimales
-- Attention :
-- auto-incrementation du code
-- --------------------------------------------------------

TRUNCATE TABLE `rsbl_motifs_indisponibilite`;

INSERT INTO `rsbl_motifs_indisponibilite` (`code_type_indisponibilite`, `code_composante`, `nom`, `nom_court`) VALUES(1, 7, 'Réservation pour compétition', 'Compétition');
INSERT INTO `rsbl_motifs_indisponibilite` (`code_type_indisponibilite`, `code_composante`, `nom`, `nom_court`) VALUES(1, 10, 'Réservation pour une randonnée', 'Randonnée');
INSERT INTO `rsbl_motifs_indisponibilite` (`code_type_indisponibilite`, `code_composante`, `nom`, `nom_court`) VALUES(1, 10, 'Réservation pour un évènement', 'Evènement');
INSERT INTO `rsbl_motifs_indisponibilite` (`code_type_indisponibilite`, `code_composante`, `nom`, `nom_court`) VALUES(1, 8, 'Entretien régulier du matériel', 'Entretien');
INSERT INTO `rsbl_motifs_indisponibilite` (`code_type_indisponibilite`, `code_composante`, `nom`, `nom_court`) VALUES(1, 8, 'Avarie, panne', 'Avarie');
INSERT INTO `rsbl_motifs_indisponibilite` (`code_type_indisponibilite`, `code_composante`, `nom`, `nom_court`) VALUES(1, 8, 'Réparation du matériel', 'Réparation');
INSERT INTO `rsbl_motifs_indisponibilite` (`code_type_indisponibilite`, `code_composante`, `nom`, `nom_court`) VALUES(1, 8, 'Hors site pour hivernage', 'Hivernage');
INSERT INTO `rsbl_motifs_indisponibilite` (`code_type_indisponibilite`, `code_composante`, `nom`, `nom_court`) VALUES(1, 4, 'Prêt du support', 'Prêt');
INSERT INTO `rsbl_motifs_indisponibilite` (`code_type_indisponibilite`, `code_composante`, `nom`, `nom_court`) VALUES(1, 4, 'Géré hors Resabel', 'Hors Resabel');
INSERT INTO `rsbl_motifs_indisponibilite` (`code_type_indisponibilite`, `code_composante`, `nom`, `nom_court`) VALUES(1, 6, 'Séance de formation', 'Séance formation');
INSERT INTO `rsbl_motifs_indisponibilite` (`code_type_indisponibilite`, `code_composante`, `nom`, `nom_court`) VALUES(1, 6, 'Stage de formation/perfectionnement', 'Stage');
INSERT INTO `rsbl_motifs_indisponibilite` (`code_type_indisponibilite`, `code_composante`, `nom`, `nom_court`) VALUES(1, 4, 'Journée portes ouvertes', 'Portes ouvertes');
INSERT INTO `rsbl_motifs_indisponibilite` (`code_type_indisponibilite`, `code_composante`, `nom`, `nom_court`) VALUES(2, 7, 'Compétition sur le site', 'Site compétition');
INSERT INTO `rsbl_motifs_indisponibilite` (`code_type_indisponibilite`, `code_composante`, `nom`, `nom_court`) VALUES(2, 4, 'Journéee découverte', 'Découverte');
INSERT INTO `rsbl_motifs_indisponibilite` (`code_type_indisponibilite`, `code_composante`, `nom`, `nom_court`) VALUES(2, 4, 'Navigation interdite', 'Interdiction');
INSERT INTO `rsbl_motifs_indisponibilite` (`code_type_indisponibilite`, `code_composante`, `nom`, `nom_court`) VALUES(2, 4, 'Mauvaises conditions de navigation', 'Sécurité');
INSERT INTO `rsbl_motifs_indisponibilite` (`code_type_indisponibilite`, `code_composante`, `nom`, `nom_court`) VALUES(2, 4, 'Basse mer de grandes marées', 'Grande marée');

-- --------------------------------------------------------
