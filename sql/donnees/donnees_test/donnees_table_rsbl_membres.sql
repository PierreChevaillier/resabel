-- ============================================================================
-- Ajout des donnees de la table 'membres' requises pour les tests
-- creation : 19-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------
-- Commentaires :
-- Attention :
--   - la table peut deja contenir des donnees (donnees minipales)
-- A faire :
-- --------------------------------------------------------

-- Membres pouvant pratiquer les activites du club, non debutant, pas chef de bord 
INSERT INTO `rsbl_membres` (`code`, `niveau`, `genre`, `prenom`, `nom`, `date_naissance`, `code_commune`, `rue`, `telephone`, `telephone2`, `courriel`, `cdb`, `num_licence`) VALUES(101, 2, 'M', 'Pierre', 'Chevaillier', '2024-01-01', 29130, '', '0601020304', '0298010203', 'pchevaillier@gmail.com', 0, '');
INSERT INTO `rsbl_membres` (`code`, `niveau`, `genre`, `prenom`, `nom`, `date_naissance`, `code_commune`, `rue`, `telephone`, `telephone2`, `courriel`, `cdb`, `num_licence`) VALUES(102, 2, 'M', 'Yann', 'Fañch', '2024-01-01', 29019, '', '0601020304', '0298010203', '', 0, '');
INSERT INTO `rsbl_membres` (`code`, `niveau`, `genre`, `prenom`, `nom`, `date_naissance`, `code_commune`, `rue`, `telephone`, `telephone2`, `courriel`, `cdb`, `num_licence`) VALUES(103, 2, 'F', 'Gwenn', 'Le Gall', '2024-01-01', 29019, '', '0601020304', '0298010203', '', 0, '');

-- Membre pouvant pratiquer les activites du club, debutant, pas chef de bord 
INSERT INTO `rsbl_membres` (`code`, `niveau`, `genre`, `prenom`, `nom`, `date_naissance`, `code_commune`, `rue`, `telephone`, `telephone2`, `courriel`, `cdb`, `num_licence`) VALUES(104, 1, 'F', 'Françoise', 'Le Guezennec', NULL, 29019, '', '0601020304', '', '', 0, '');

-- Membre pouvant pratiquer les activites du club, non debutant, chef de bord 
INSERT INTO `rsbl_membres` (`code`, `niveau`, `genre`, `prenom`, `nom`, `date_naissance`, `code_commune`, `rue`, `telephone`, `telephone2`, `courriel`, `cdb`, `num_licence`) VALUES(26001, 2, 'F', 'Hélène', "Créac'h", '2024-01-01', 29130, '', '0601020304', '0298010203', 'son.mail@mail.com', 1, '');

-- Visiteur pouvant pratiquer les activites du club, debutant, pas chef de bord 
INSERT INTO `rsbl_membres` (`code`, `niveau`, `genre`, `prenom`, `nom`, `date_naissance`, `code_commune`, `rue`, `telephone`, `telephone2`, `courriel`, `cdb`, `num_licence`) VALUES(9001, 1, 'M', 'Z', 'Visiteur01', '2024-01-01', 29130, '', '', '', '', 0, '');

-- --------------------------------------------------------
