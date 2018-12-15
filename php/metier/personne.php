<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServation de Bateaux En Ligne
  // description: definition de la classe Personne
  // utilisation : php - require_once
  // teste avec : PHP 5.5.3 sur Mac OS 10.11, PHP 7.0 sur serveur OVH
  // Copyright (c) 2014-2018 AMP. Tous droits reserves.
  // ------------------------------------------------------------------------
  // creation: 28-fev-2015 pchevaillier@gmail.com
  // revision: 29-avr-2015 pchevaillier@gmail.com, recherche information
  // revision: 17-aug-2016 pchevaillier@gmail.com, nouvel structure table membre
  // revision: 19-nov-2016 pchevaillier@gmail.com, ajout recherche_membres
  // revision: 30-nov-2016 pchevaillier@gmail.com, test si de permanance
  // revision: 05-oct-2018 pchevaillier@gmail.com  chemin vers utilitaires
  // ------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // - revoir les requetes a la base de donnees
  // - lever une erreur si donnee manquante (chef de bord)
  // -----------------------------------------------------------------------

  require_once 'php/utilitaires/format_donnees.php';

  class Personne {
    private $code = "";
    public $identifiant = "";
    private $active = true; // actif = possibilite de pratiquer une activite
    private $autorisee_connecter = true;
    public $niveau = 0;
    public $genre = "F";
    public $mot_passe = "";
    public $prenom = "";
    public $nom = "";
    public $date_naissance = "0000-00-00";
    public $code_commune = "00000";
    public $rue = "";
    public $telephone = "";
    public $telephone2 = "";
    public $courriel = "";
    private $chef_de_bord = false;
    public $date_derniere_connexion = "0000-00-00 00:00:00";
    public $num_licence = "";
	
    public function __construct($code) {
      $this->code = $code;
    }
	
    public function def_code($valeur) { $this->code = $valeur; }
    public function code() { return $this->code; }
	
    public function def_chef_de_bord($valeur) {
      $this->chef_de_bord = ($valeur == 1);
    }
    public function est_chef_de_bord() { return $this->chef_de_bord; }
    
    public function def_active($valeur) {
      $this->active = ($valeur == 1);
    }
    public function est_active() { return $this->active;}
    
    public function est_autorisee_connecter() { return $this->autorisee_connecter; }
    public function def_autorisee_connecter($valeur) {
      $this->autorisee_connecter = ($valeur == 1);
    }
/*
    static public function recherche_membres($critere_selection, $critere_tri, & $personnes) {
      $status = false;
      $personnes = array();
      $selection = "";
      if (strlen($critere_selection) > 0)
        $selection = " WHERE " . $critere_selection;
      $tri = "";
      if (strlen($critere_tri) > 0)
        $tri = " ORDER BY " . $critere_tri;
    	 
      $requete = "SELECT code, identifiant, actif, connexion, niveau, genre, mot_passe, prenom, nom, date_naissance, code_commune, rue, telephone, telephone2, courriel, cdb, derniere_connexion, num_licence FROM membres " . $selection . $tri;
      echo $requete;
      
      $resultat = mysql_query($requete) or die('Requête recherche personnes invalide : ' . mysql_error());
      while ($donnee = mysql_fetch_assoc($resultat)) {
        $p = new Personne($donnee['code']);
        $personnes[] = $p;
        $p->identifiant = $donnee['identifiant'];
        $p->est_actif = ($donnee['actif'] == 1);
        $p->connexion_autorisee = ($donnee['connexion'] == 1);
        $p->niveau = $donnee['niveau'];
        $p->genre = $donnee['genre'];
        $p->mot_passe = $donnee['mot_passe'];
        $p->prenom = $donnee['prenom'];
        $p->nom = $donnee['nom'];
        $p->date_naissance = $donnee['date_naissance'];
        $p->code_commune = $donnee['code_commune'];
        $p->rue = $donnee['rue'];
        $p->telephone = $donnee['telephone'];
        $p->telephone2 = $donnee['telephone2'];
        $p->courriel = $donnee['courriel'];
        $p->est_chef_de_bord = ($donnee['cdb'] == 1);
        $p->date_derniere_connexion = $donnee['derniere_connexion'];
        $p->num_licence = $donnee['num_licence'];
      }
      
      $status = true;
      return $status;
    }
*/
    /*
    public function recherche_informations($base_donnees) {
      $trouve = false;
      $requete = "SELECT identifiant, actif, connexion, niveau, genre, mot_passe, prenom, nom, date_naissance, code_commune, rue, telephone, telephone2, courriel, cdb, derniere_connexion, num_licence FROM membres WHERE membres.code = '". $this->code . "'";
      echo $requete;
      try {
        $resultat = $base_donnees->query($requete);
        $trouve = ($resultat->rowCount() > 0);
        if ($trouve) {
          $donnee = $resultat->fetch(PDO::FETCH_OBJ);
          $this->identifiant = $donnee->identifiant;
          $this->est_actif = ($donnee->actif == 1);
          $this->connexion_autorisee = ($donnee->connexion == 1);
          $this->niveau = $donnee->niveau;
          $this->genre = $donnee->genre;
          $this->mot_passe = $donnee->mot_passe;
          $this->prenom = $donnee->prenom;
          $this->nom = $donnee->nom;
          $this->date_naissance = $donnee->date_naissance;
          $this->code_commune = $donnee->code_commune;
          $this->rue = $donnee->rue;
          $this->telephone = $donnee->telephone;
          $this->telephone2 = $donnee->telephone2;
          $this->courriel = $donnee->courriel;
          $this->est_chef_de_bord = ($donnee->cdb == 1);
          $this->date_derniere_connexion = $donnee->derniere_connexion;
          $this->num_licence = $donnee->num_licence;
        }
      } catch (PDOException  $e) {
        echo "Erreur - requete recherche information personne invalide : ". $e;
      }
      return $trouve;
    }
	*/
    public function enregistrer_nouveau() {
      $actif = ($this->est_active()) ? 1: 0;
      $connexion = ($this->est_autorisee_connecter()) ? 1: 0;
      $cdb = ($this->est_chef_de_bord()) ? 1: 0;
      /*
      $requete = "INSERT INTO membres VALUES('" . $this->code . "', '"
                                                . $this->identifiant . "', '"
                                                .  $actif . "', '"
                                                . $connexion . "', '"
                                                . $this->niveau . "', '"
                                                . $this->genre . "', '"
                                                . $this->mot_passe . "', '"
                                                . $this->prenom . "', '"
                                                . $this->nom . "', '"
                                                . $this->date_naissance . "', '"
                                                . $this->code_commune . "', '"
                                                . $this->rue . "', '"
                                                . $this->telephone . "', '"
                                                . $this->telephone2 . "', '"
                                                . $this->courriel . "', '"
                                                . $cdb  . "', '"
                                                . $this->date_derniere_connexion . "', '"
                                                . $this->num_licence . "')";
      //echo $requete . "<br />";
      $resultat = mysql_query($requete);
      return $resultat;
    */
    }
	
    public function enregistrer_modifications() {
      $actif = ($this->est_actif) ? 1: 0;
      $connexion = ($this->connexion_autorisee) ? 1: 0;
      $cdb = ($this->est_chef_de_bord) ? 1: 0;
      $requete = "UPDATE membres SET "
      . "identifiant = '" . $this->identifiant
			. "', actif = '" . $actif
			. "', connexion = '" . $connexion
			. "', niveau = '" . $this->niveau
			. "', genre = '" . $this->genre 
			. "' , prenom = '" . $this->prenom 
			. "', nom = '" . $this->nom 
			.  "', date_naissance = '" . $this->date_naissance 
			. "', code_commune = '" . $this->code_commune 
			. "', rue = '" . $this->rue 
			. "', telephone = '" . formatter_num_tel_enregistrement($this->telephone) 
			. "', telephone2 = '" . formatter_num_tel_enregistrement($this->telephone2) 
			. "', courriel = '" . $this->courriel 
			. "', cdb = '" . $cdb 
			.  "', num_licence = '" . $this->num_licence 
			. "' WHERE code = '" . $this->code . "'";
      /*
      $resultat = mysql_query($requete);
      return $resultat;
       */
    }
	
    public function initialiser_visiteur() {
      $this->def_active(true);
      $this->def_autorisee_connecter(false);
      $this->def_chef_de_bord(false);
      $this->niveau = 0;
      $this->prenom = "z";
      $this->code_commune = "29190"; // Plougonvelin
      $this->genre = "F";
      return;
    }
	
    public function est_disponible($jour, $creneau) {
      $status = false;
      $requete = "SELECT COUNT(*) AS n FROM inscriptions_sortie WHERE code_membre = '" . $this->code . "' AND  jour = '" . $jour . "' AND horaire = '" . $creneau . "'";
      //echo $requete;
      /*
      $resultat = mysql_query($requete) or die('Requête personne est disponible invalide : ' . mysql_error());
      $donnee = mysql_fetch_assoc($resultat);
      $status = ($donnee['n'] == 0);
      return $status;
       */
    }
	
    public function recherche_si_admin() {
      $status = false;
      if ($this->code() == '101') {
        $status = true;
      } else {
        $requete = "SELECT COUNT(*) AS n FROM roles_membres WHERE code_composante = 'bureau' AND code_membre = '". $this->code() . "'";
        /*
        $resultat = mysql_query($requete) or die('Requête personne est disponible invalide : ' . mysql_error());
        $donnee = mysql_fetch_assoc($resultat);
        $status = ($donnee['n'] > 0);
         */
      }
      return $status;
    }
	
    public function est_debutant() {
      return ($this->niveau < 2);
    }
  }
  ?>
