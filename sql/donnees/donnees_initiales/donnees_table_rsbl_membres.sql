-- ============================================================================
-- Donnees requises minimales de la table 'membres'
-- L'administrateur de Resabel au sein du club
-- c'est cet utilisateur qui pourra creer tous les autres
-- creation : 17-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------
-- Commentaires :
--  - la valeur du champ code est incrementee automatiquement
--    ce code est utillise dans d'autres tables
-- A faire :
--   - ajouter les visiteurs
-- --------------------------------------------------------

TRUNCATE TABLE `rsbl_membres`;

INSERT INTO `rsbl_membres` (`code`, `niveau`, `genre`, `prenom`, `nom`, `date_naissance`, `code_commune`, `rue`, `telephone`, `telephone2`, `courriel`, `cdb`, `num_licence`) VALUES(1, 0, 'M', 'Admin', 'Resabel', '2024-01-01', 29130, '', '0601020304', '0298010203', 'admin.resabel@club-resabel.fr', 0, '');

-- --------------------------------------------------------
