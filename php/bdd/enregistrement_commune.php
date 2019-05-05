<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classe Enregistrement_Commune : acces a la base de donnees
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : cf. require_once + classe Base_Donnees
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 07-jan-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // - non teste
  // a faire :
  // -
  // ==========================================================================
  
  require_once 'php/metier/commune.php';
  
  class Erreur_Commune_Introuvable extends Exception { }
  
  // ==========================================================================
  class Enregistrement_Commune {
    private $commune = null;
    public function def_commune($commune) { $this->commune = $commune; }
    
    static function source() {
      return Base_Donnees::$prefix_table . 'communes';
    }
    
    public function lire() {
      $trouve = false;
      try {
        $bdd = Base_Donnees::accede();
        
        $requete= $bdd->prepare("SELECT * FROM " . self::source() . " WHERE code = :code_commune LIMIT 1");
        $code = $this->commune->code();
        $requete->bindParam(':code_commune', $code, PDO::PARAM_INT);
        
        $requete->execute();
        
        if ($donnee = $requete->fetch(PDO::FETCH_OBJ)) {
          $this->commune->def_code_postal($donnee->code_postal);
          $this->commune->def_nom($donnee->nom);
          $trouve = true;
        } else {
          //throw new Erreur_Commune_Introuvable();
          return $trouve;
        }
      } catch (PDOexception $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $trouve;
    }
    
    static function collecter($critere_selection, $critere_tri, & $communes) {
      $status = false;
      $communes = array();
      $selection = (strlen($critere_selection) > 0) ? " WHERE " . $critere_selection . " " : "";
      $tri = (strlen($critere_tri) > 0) ? " ORDER BY " . $critere_tri . " " : "";
      try {
        $bdd = Base_Donnees::accede();
        $requete = "SELECT code, nom FROM " . self::source() . $selection . $tri;
        $resultat = $bdd->query($requete);
        while ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
          $commune = new Commune();
          $commune->def_code($donnee->code);
          $commune->def_nom($donnee->nom);
          $communes[$commune->code()] = $commune;
        }
      } catch (PDOException $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $status;
      }
      
    }
  // ==========================================================================
?>
