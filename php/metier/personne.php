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
  // revision: 05-oct-2018 pchevaillier@gmail.com chemin vers utilitaires
  // revision: 03-mar-2019 pchevaillier@gmail.com ajout nom_commune
  // ------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // - code : entier
  // - revoir les requetes a la base de donnees
  // - lever une erreur si donnee manquante (chef de bord)
  // -----------------------------------------------------------------------

  require_once 'php/utilitaires/format_donnees.php';

  class Personne {
    protected $code = 0;
    public $identifiant = "";
    public $genre = "F";
    public $prenom = "";
    public $nom = "";
    public $code_commune = 0;
    public $nom_commune = ""; // pas dans la table
    public $rue = "";
    public $telephone = "";
    public $telephone2 = "";
    public $courriel = "";
    
    public $est_membre_actif = False;
    public $est_autorise_connecte = False;
    public $est_nouveau = False;
 
    public function __construct($code) {
      $this->code = $code;
    }
	
    public function def_code($valeur) { $this->code = $valeur; }
    public function code() { return $this->code; }
	
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
 
  }
  ?>
