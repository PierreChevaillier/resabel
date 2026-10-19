-- --------------------------------------------------------
-- Donnees requises minimales de la table 'connexions'
-- L'administrateur de Resabel au sein du club
-- Il peut se connecter (connexion = 1)
-- mais ne peut pas participer aux activites (actif = 0)
-- --------------------------------------------------------
-- creation : 17-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------

TRUNCATE TABLE `rsbl_connexions`;

INSERT INTO `rsbl_connexions` (`code_membre`, `identifiant`, `mot_passe`, `date_mot_passe`, `actif`, `date_actif`, `connexion`, `date_connexion`, `date_creation`) VALUES(1, 'admin.resabel', 0x243279243132246851356a453647747841745257616b484e32414e782e5456447965687661613958512e67686a7738627434794c6f4e58764b447847, '2026-10-17 18:49:00', 0, '2024-11-12 20:38:50', 1, '2025-02-21 22:18:26', '2024-01-01 00:00:00');

-- --------------------------------------------------------
