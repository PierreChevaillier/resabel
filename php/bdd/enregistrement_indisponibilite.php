<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classe Enregistrement_Indisponibilite :
  //               acces a la base de donnees
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : cf. require_once + classe Base_Donnees
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 10-jun-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // - non teste
  // a faire :
  // -
  // ==========================================================================
  
  require_once 'php/metier/indisponibilite.php';
  require_once 'php/metier/calendrier.php';
  require_once 'php/metier/membre.php';
  require_once 'php/metier/support_activite.php';
   require_once 'php/metier/site_activite.php';
  // ==========================================================================
  class Enregistrement_Indisponibilite {
    private $indisponibilite = null;
    public function def_indisponibilite($indisponibilite) { $this->indisponibilite = $indisponibilite; }
    
    static function source() {
      return Base_Donnees::$prefix_table . 'indisponibilites';
    }
    
    static function collecter($critere_selection, $critere_tri, & $indisponibilites) {
      $status = false;
      $indisponibilites = array();
      $cal = Calendrier::obtenir();
      $debut = $cal->aujourdhui();
      $debut_sql = $cal->formatter_date_heure_sql($debut);
      
      $selection = (strlen($critere_selection) > 0) ? " WHERE " . $critere_selection . " " : "";
      $tri = (strlen($critere_tri) > 0) ? " ORDER BY " . $critere_tri . " " : " ORDER BY indisp.code_objet, indisp.date_debut ";
      try {
        $bdd = Base_Donnees::accede();
        $source = self::source() . " AS indisp INNER JOIN rsbl_motifs_indisponibilite AS motif ON indisp.code_motif = motif.code ";
        $requete = "SELECT indisp.code AS code, information, code_type, date_creation, code_createur, code_motif, code_objet, date_debut, date_fin, motif.nom AS nom_motif, motif.nom_court  FROM " . $source . $selection . $tri;
        //echo "<p>" . $requete . "</p>";
        $resultat = $bdd->query($requete);
        while ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
          if ($donnee->code_type == 1) {
            $indisponibilite = new Indisponibilite_Support($donnee->code);
            $indisponibilite->support = new Support_Activite($donnee->code_objet);
          } elseif ($donnee->code_type == 2) {
            $indisponibilite = new Fermeture_Site($donnee->code);
            $indisponibilite->site_activite = new Site_Activite($donnee->code_objet);
          } else {
            $indisponibilite = new Indisponibilite($donnee->code);
          }
          
          $indisponibilite->def_information($donnee->information);
          
          // -- Information sur la creation
          $indisponibilite->instant_creation = $cal->def_depuis_timestamp_sql($donnee->date_creation);
          $indisponibilite->createur = new Membre($donnee->code_createur);
          
          // Periode d'indisponibilite
          $debut = $cal->def_depuis_timestamp_sql($donnee->date_debut);
          $fin = $cal->def_depuis_timestamp_sql($donnee->date_fin);
          $indisponibilite->periode = new Intervalle_Temporel($debut, $fin);
          
          // Motif d'indisponibilite
          $indisponibilite->motif = new Motif_Indisponibilite($donnee->code_motif);
          $indisponibilite->motif->def_nom($donnee->nom_motif);
          
          $indisponibilites[$indisponibilite->code()] = $indisponibilite;
        }
      } catch (PDOException $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $status;
      }
      
    }
  // ==========================================================================
?>
