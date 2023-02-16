<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classe Enregistrement_Participation_Activite :
  //               acces a la base de donnees
  // copyright (c) 2018-2020 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : cf. require_once + classe Base_Donnees
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 15-apr-2020 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // - non teste
  // a faire :
  // -
  // ==========================================================================
  
  
  // ==========================================================================
  class Enregistrement_Participation_Activite {
    
    static function source() {
      return Base_Donnees::$prefix_table . 'participations_activite';
    }
  
    static function collecter($critere_selection, $critere_tri, & $participations) {
      $status = false;
      if (is_null($participations))
        $participations = array();
      $selection = (strlen($critere_selection) > 0) ? " WHERE " . $critere_selection . " " : "";
      $tri = (strlen($critere_tri) > 0) ? " ORDER BY " . $critere_tri . " " : "";
      try {
        $bdd = Base_Donnees::acces();
        $requete = "SELECT code_membre AS code FROM " . self::source() . $selection . $tri;
        $resultat = $bdd->query($requete);
        while ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
          $participations[] = $donnee->code;
        }
        $status = true;
      } catch (PDOException $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $status;
      }
      
    }
  // ==========================================================================
?>
