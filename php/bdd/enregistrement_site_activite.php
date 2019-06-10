<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classe Enregistrement_Site_Activite
  //               acces a la base de donnees
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : cf. require_once + classe Base_Donnees + structure table
  //               code_type pour instantiation objet
  //               de la bonne sous-classe de site_activite
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 10-jun-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // - non teste
  // a faire :
  // -
  // ==========================================================================
  
  require_once 'php/metier/site_activite.php';
  
  class Erreur_Site_Activite_Introuvable extends Exception { }
  class Erreur_Type_Site_Activite extends Exception { }
  
  // ==========================================================================
  class Enregistrement_Site_Activite {
    private $site_activite = null;
    public function site_activite() { return $this->site_activite; }
    public function def_site_activite($site_activite) {
      $this->site_activite = $site_activite;
    }
    
    static function source() {
      return Base_Donnees::$prefix_table . 'sites_activite';
    }
    
    public function lire_identite() {
      $trouve = false;
      try {
        $bdd = Base_Donnees::accede();
        
        $code_sql = "SELECT site.code AS code, site.nom AS nom_site, site.code_type AS code_type FROM rsbl_sites_activite AS site INNER JOIN rsbl_types_site_activite as type ON site.code_type = type.code WHERE site.code = :code_site_activite LIMIT 1";
        
        $requete= $bdd->prepare($code_sql);
        $code = $this->site_activite->code();
        $requete->bindParam(':code_site_activite', $code, PDO::PARAM_INT);
        
        $requete->execute();
        
        if ($donnee = $requete->fetch(PDO::FETCH_OBJ)) {
          // Il faut trouver le type de l'objet a instancier (pas terrible...)
          if ($donnee->code_type == 1) {
            $this->site_activite = new Site_activite_Mer($code);
          } else {
            throw new Erreur_Type_Site_Activite();
          }
          $this->site_activite->def_nom($donnee->nom_site);
          $trouve = true;
        } else {
          throw new Erreur_site_activite_Introuvable();
          return $trouve;
        }
      } catch (PDOexception $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $trouve;
    }
    
    static function collecter($critere_selection, $critere_tri, & $site_activites) {
      $status = false;
      $site_activites = array();
      $selection = (strlen($critere_selection) > 0) ? " WHERE " . $critere_selection . " " : "";
      $tri = (strlen($critere_tri) > 0) ? " ORDER BY " . $critere_tri . " " : "";
      try {
        $bdd = Base_Donnees::accede();
        $requete = "SELECT code, nom FROM " . self::source() . $selection . $tri;
        $resultat = $bdd->query($requete);
        while ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
          $site_activite = new site_activite($donnee->code);
          $site_activite->def_nom($donnee->nom);
          $site_activites[$site_activite->code()] = $site_activite;
        }
      } catch (PDOException $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $status;
      }
      
    }
  // ==========================================================================
?>
