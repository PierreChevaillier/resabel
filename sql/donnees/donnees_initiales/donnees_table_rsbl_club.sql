-- Donnees minimales de la table 'club'
-- Un club fictif
-- creation : 16-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------

TRUNCATE TABLE `rsbl_club`;

INSERT INTO `rsbl_club` (`identifiant`, `mot_passe`, `nom`, `code_commune`, `telephone`, `courriel`, `fuseau_horaire`) VALUES('RSBL', 0x243279243132246851356a453647747841745257616b484e32414e782e5456447965687661613958512e67686a7738627434794c6f4e58764b447847, 'Club nautique Resabel', 29190, '0298010203', 'contact@club.fr', 'Europe/Paris');

-- --------------------------------------------------------
