-- --------------------------------------------------------
-- Donnees requises de la table regimes_ouverture
-- Modes de gestion des horaires d'ouverture pour un site d'activite
-- --------------------------------------------------------
-- creation : 16-oct-2026 pchevaillier@gmail.com
-- --------------------------------------------------------
-- Commentaires :
-- Il n'y a pas de dependances vers cette table dans les donnees minimales
-- Attention :
-- La valeur du type definit la classe de l'objet instancie
-- quand il est cree a partir des donnees lues dans cette table
-- --------------------------------------------------------

TRUNCATE TABLE `rsbl_regimes_ouverture`;

INSERT INTO `rsbl_regimes_ouverture` (`code_type`, `nom`, `description`, `jour_semaine`, `heure_ouverture`, `heure_fermeture`, `duree_seance`, `de_jour_uniquement`, `decalage_heure_hiver`) VALUES(1, 'Sorties en mer', "Séances d'une heure entre le levée et le coucher du soleil. Tous les jours identiques", 0, '09:00:00', '23:00:00', '01:00:00', 1, '00:00:00');
INSERT INTO `rsbl_regimes_ouverture` (`code_type`, `nom`, `description`, `jour_semaine`, `heure_ouverture`, `heure_fermeture`, `duree_seance`, `de_jour_uniquement`, `decalage_heure_hiver`) VALUES(2, 'Salle sport', 'Salle de sport en libre acces', 0, '08:00:00', '22:00:00', '01:00:00', 0, '00:00:00');

-- --------------------------------------------------------
