<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classe Enregistrement_Seance_activite :
  //               acces a la base de donnees
  // copyright (c) 2020 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : cf. require_once + classe Base_Donnees
  //               structures des tables
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 18-jan-2020 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // - Inception
  // attention :
  // a faire : tout
  // ==========================================================================
  
  require_once 'php/metier/seance_activite.php';
  
  // ==========================================================================
  
  // ==========================================================================
  class Enregistrement_Seance_activite {
    
    static function source() {
      return Base_Donnees::$prefix_table . 'seances_activite';
    }
    
    static function collecter(Site_Activite $site = NULL,
                         string $critere_selection,
                         string $critere_tri,
                         array & $seances) {
      $status = false;
      
      $selection = (strlen($critere_selection) > 0) ? " WHERE " . $critere_selection . " " : "";
      $tri = (strlen($critere_tri) > 0) ? " ORDER BY " . $critere_tri . " " : " ORDER BY indisp.code_objet, indisp.date_debut ";
      
      $source = self::source() . " AS seance INNER JOIN rsbl_motifs_indisponibilite AS motif ON (indisp.code_motif = motif.code) ";
      $jointure_objet = " INNER JOIN ";
      
      $source = $source . $jointure_objet;
      
      $requete = "SELECT indisp.code AS code, information, indisp.code_type AS code_type, date_creation, code_createur, code_motif, code_objet, date_debut, date_fin, motif.nom AS nom_motif, motif.nom_court " . $champs_objet . " FROM " . $source . $selection . $tri;
      
      //echo '<p>', $requete, '</p>', PHP_EOL;
      try {
        $bdd = Base_Donnees::accede();
        $resultat = $bdd->query($requete);
        while ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
          if (is_null($site) || (!is_null($site) && ($site->code() == $donnee->code_site))) {
            if ($donnee->code_type == 1) {
              $indisponibilite = new Indisponibilite_Support($donnee->code);
              if ($donnee->type_type_support == 1) {
                $indisponibilite->support = new Bateau($donnee->code_objet);
                $indisponibilite->support->def_numero($donnee->numero_support);
              } elseif ($donnee->type_type_support == 2) {
                $indisponibilite->support = new Plateau_Ergo($donnee->code_objet);
              } else {
                $indisponibilite->support = new Support_Activite($donnee->code_objet);
              }
              $indisponibilite->support->def_nom($donnee->nom_support);
              $indisponibilite->support->type = new Type_Support_Activite($donnee->type_support);
              $indisponibilite->support->type->def_nom($donnee->nom_type_support);
            } elseif ($donnee->code_type == 2) {
              $indisponibilite = new Fermeture_Site($donnee->code);
              $site_ferme = NULL;
              if (is_null($site)) {
                if ($donnee->type_site == 1) {
                  $site_ferme = new Site_Activite_Mer($donnee->code_objet);
                } elseif ($donnee->type_site == 2) {
                  $site_ferme = new Salle_Sport($donnee->code_objet);
                }
              } else {
                $site_ferme = $site;
              }
              $site_ferme->def_nom($donnee->nom_site);
              $site_ferme->def_nom_court($donnee->nom_court_site);
              $indisponibilite->site_activite = $site_ferme;
            }
            
            $indisponibilite->def_information($donnee->information);
            
            // -- Information sur la creation
            $indisponibilite->instant_creation = new Instant($donnee->date_creation);
            $indisponibilite->createur = new Membre($donnee->code_createur);
            
            // Periode d'indisponibilite
            $indisponibilite->debut = new Instant($donnee->date_debut);
            $indisponibilite->fin = new Instant($donnee->date_fin);
            
            // Motif d'indisponibilite
            $indisponibilite->motif = new Motif_Indisponibilite($donnee->code_motif);
            $indisponibilite->motif->def_nom($donnee->nom_motif);
            
            $indisponibilites[] = $indisponibilite;
          }
        }
      } catch (PDOException $e) {
            Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $status;
    }
  }
  // ==========================================================================
?>
