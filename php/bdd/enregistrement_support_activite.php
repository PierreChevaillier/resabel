<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classe Enregistrement_Support_Activite
  //               acces a la base de donnees
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : cf. require_once + classe Base_Donnees + structure table
  //               code_type pour instantiation objet
  //               de la bonne sous-classe de Support_Activite
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
  
  require_once 'php/metier/support_activite.php';
  
  class Erreur_Support_Activite_Introuvable extends Exception { }
  
  // ==========================================================================
  class Enregistrement_Support_Activite {
    private $support_activite = null;
    public function support_activite() { return $this->support_activite; }
    public function def_support_activite($support_activite) {
      $this->support_activite = $support_activite;
    }
    
    static function source() {
      return Base_Donnees::$prefix_table . 'supports';
    }
    
    public function lire_identite() {
      $trouve = false;
      try {
        $bdd = Base_Donnees::accede();
        
        $code_sql = "SELECT support.code, numero, support.nom AS nom, type.nom_court AS nom_type, type.code_type AS code_type FROM rsbl_supports AS support INNER JOIN rsbl_types_support AS type ON support.code_type_support = type.code WHERE support.code = :code_support_activite LIMIT 1";
        
        $requete= $bdd->prepare($code_sql);
        $code = $this->support_activite->code();
        $requete->bindParam(':code_support_activite', $code, PDO::PARAM_INT);
        
        $requete->execute();
        
        if ($donnee = $requete->fetch(PDO::FETCH_OBJ)) {
          // Il faut trouver le type de l'objet a instancier (pas terrible...)
          if ($donnee->code_type == 1) {
            $this->support_activite = new Bateau($code);
            $this->support_activite->def_numero($donnee->numero);
          } elseif ($donnee->code_type == 2) {
            $this->support_activite = new Plateau_Ergo($code);
          }
          $this->support_activite->def_nom($donnee->nom);
          
          $this->support_activite->type = new Type_Support_Activite($donnee->code_type);
          $this->support_activite->type->def_nom($donnee->nom_type);
          $trouve = true;
        } else {
          throw new Erreur_Support_Activite_Introuvable();
          return $trouve;
        }
      } catch (PDOexception $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $trouve;
    }
    
    static function collecter($critere_selection, $critere_tri, & $support_activites) {
      $status = false;
      $support_activites = array();
      $selection = (strlen($critere_selection) > 0) ? " WHERE " . $critere_selection . " " : "";
      $tri = (strlen($critere_tri) > 0) ? " ORDER BY " . $critere_tri . " " : "";
      try {
        $bdd = Base_Donnees::accede();
        $requete = "SELECT support.code AS code, numero, support.nom AS nom, type.nom_court AS nom_type, type.code AS type, type.code_type AS code_type FROM rsbl_supports AS support INNER JOIN rsbl_types_support AS type ON support.code_type_support = type.code " . $selection . $tri;
        //echo "<p>" . $requete . "</p>";
        $resultat = $bdd->query($requete);
        while ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
          if ($donnee->code_type == 1) {
            $support_activite = new Bateau($donnee->code);
            $support_activite->def_numero($donnee->numero);
          } elseif ($donnee->code_type == 2) {
           $support_activite = new Plateau_Ergo($donnee->code);
          }
          $support_activite->def_nom($donnee->nom);
          $support_activite->type = new Type_Support_Activite($donnee->type);
          $support_activite->type->def_nom($donnee->nom_type);
          
          $support_activites[$support_activite->code()] = $support_activite;
        }
      } catch (PDOException $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $status;
      }
      
    }
  
  // ==========================================================================
?>
