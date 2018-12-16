<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classe Enregistrement_Site_Web: interface table 'Site_Web'
  // copyright (c) 2018 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : Classe Club et Base_Donnees
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 16-dec-2018 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // - en chantier : pas fonctionnel
  // - lire, ajouter, modifier, supprimer, tester_exist, compter, collecter, verfier_xxx
  // attention :
  // - 
  // a faire :
  // -
  // ==========================================================================

  require_once 'php/metier/site_web.php';
  
  class Erreur_Site_Web_Introuvable extends Exception { }
  // ==========================================================================
  class Enregistrement_Site_Web {
    
    public function __construct($code_site_web) {
      new Site_Web($code_site_web);
      try {
        $this->lire_identite();
        Site_Web::accede()->initialiser();
      } catch (Erreur_Site_Web_Introuvable $e) {
        die("Site web introuvable dans la base de donnees");
      }
    }
    
    static function source() {
      return Base_Donnees::$prefix_table . 'site';
    }
    
    public function lire_identite() {
      $bdd = Base_Donnees::accede();
      $site = Site_Web::accede();
      $critere = "code = " . $bdd->quote($site->code()) ;
      $requete = "SELECT sigle, adresse_racine, courriel_contact, fuseau_horaire FROM "
      . self::source() . " WHERE " . $critere;
      try {
        $resultat = $bdd->query($requete);
        if ($resultat->rowCount() > 0) {
          $donnee = $resultat->fetch(PDO::FETCH_OBJ);
          Site_Web::accede()->def_sigle($donnee->sigle);
          Site_Web::accede()->def_adresse_racine($donnee->adresse_racine);
          Site_Web::accede()->def_courriel_contact($donnee->courriel_contact);
          Site_Web::accede()->def_fuseau_horaire($donnee->fuseau_horaire);
        } else {
          throw new Erreur_Site_Web_Introuvable();
          return;
        }
      } catch (PDOexception $e) {
        die("Erreur recherche dans " . self::source() . " avec " . $critere . " : ligne " . $e->getLine() . ' :<br /> ' . $e->getMessage());
      }
      $resultat->closeCursor();
    }
    
  }
  // ==========================================================================
?>
