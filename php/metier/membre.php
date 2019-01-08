<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServation de Bateaux En Ligne
  // description: definition de la classe Personne
  // utilisation : php - require_once
  // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur serveur OVH
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
  // - code : entier
  // - revoir les requetes a la base de donnees
  // - lever une erreur si donnee manquante (chef de bord)
  // -----------------------------------------------------------------------

  require_once 'php/metier/personne.php';
  
  //require_once 'php/metier/calendrier.php';
  //require_once 'php/utilitaires/format_donnees.php';

  class Membre extends Personne {
    private $est_actif = true; // actif = possibilite de pratiquer une activite
    private $est_autorise_connecter = true;
    public $niveau = 0;
    public $date_naissance = null; //"0000-00-00";
    private $est_chef_de_bord = false;
    public $date_derniere_connexion = null; //"0000-00-00 00:00:00";
    public $type_licence = "A";
    public $num_licence = "";
	
    public function def_chef_de_bord($valeur) {  // pas un 'setter' classique
      $this->est_chef_de_bord = ($valeur == 1);
    }
    public function est_chef_de_bord() { return $this->est_chef_de_bord; }
    public function def_est_chef_de_bord($valeur) { $this->est_chef_de_bord = $valeur; }
    
    public function def_actif($valeur) { // pas un 'setter' classique
      $this->est_actif = ($valeur == 1);
    }
    public function est_actif() { return $this->est_actif;}
    
    public function est_autorise_connecter() { return $this->est_autorise_connecter; }
    public function def_autorise_connecter($valeur) {  // pas un 'setter' classique
      $this->autorise_connecter = ($valeur == 1);
    }
    
    public function est_debutant() {
      return ($this->niveau < 2);
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
  
    public function enregistrer_nouveau() {
      $actif = ($this->est_actif()) ? 1: 0;
      $connexion = ($this->est_autorise_connecter()) ? 1: 0;
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
		
    public function initialiser_visiteur() {
      $this->def_actif(1);
      $this->def_autorise_connecter(0);
      $this->def_chef_de_bord(0);
      $this->niveau = 0;
      $this->prenom = "z";
      $this->code_commune = 29190; // Plougonvelin
      $this->genre = "F";
      return;
    }
	
    /*
    public function est_disponible($jour, $creneau) {
      $status = false;
      $requete = "SELECT COUNT(*) AS n FROM inscriptions_sortie WHERE code_membre = '" . $this->code . "' AND  jour = '" . $jour . "' AND horaire = '" . $creneau . "'";
      //echo $requete;
     
      $resultat = mysql_query($requete) or die('Requête personne est disponible invalide : ' . mysql_error());
      $donnee = mysql_fetch_assoc($resultat);
      $status = ($donnee['n'] == 0);
      return $status;
     
    }
     */
	
  }
  ?>
