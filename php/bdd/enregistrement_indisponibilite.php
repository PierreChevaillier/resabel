<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classe Enregistrement_Indisponibilite :
  //               acces a la base de donnees
  // copyright (c) 2018-2020 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : cf. require_once + classe Base_Donnees
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 10-jun-2019 pchevaillier@gmail.com
  // revision : 07-jan-2020 pchevaillier@gmail.com indispo support et ferm. site
  // --------------------------------------------------------------------------
  // commentaires :
  // - dans collecter : si le site est specifie, il ne faut collecter que
  //   les indisponibilites relatives a ce site.
  // attention :
  // a faire :
  // ==========================================================================
  
  require_once 'php/metier/indisponibilite.php';
  require_once 'php/metier/calendrier.php';
  require_once 'php/metier/membre.php';
  require_once 'php/metier/support_activite.php';
  require_once 'php/metier/site_activite.php';
  
  require_once 'php/bdd/enregistrement_site_activite.php';
  
  // ==========================================================================
  class Erreur_Type_Indisponibilite extends Exception { }
  
  // ==========================================================================
  class Enregistrement_Indisponibilite {
    private $indisponibilite = null;
    public function def_indisponibilite(Indisponibilite $indisponibilite) {
      $this->indisponibilite = $indisponibilite;
    }
    
    static function source() {
      return Base_Donnees::$prefix_table . 'indisponibilites';
    }
    
    static function collecter(Site_Activite $site = NULL,
                         int $type_indisponibilite,
                         string $critere_selection,
                         string $critere_tri,
                         array & $indisponibilites): ? bool {
      $status = false;
      if (($type_indisponibilite < 1) || ($type_indisponibilite > 2)) {
        throw new Erreur_Type_Indisponibilite();
        return $status;
      }
      
      $selection = " WHERE indisp.code_type = " . $type_indisponibilite . " ";
      $selection = $selection . ((strlen($critere_selection) > 0) ? " AND " . $critere_selection . " " : "");
      $tri = (strlen($critere_tri) > 0) ? " ORDER BY " . $critere_tri . " " : " ORDER BY indisp.code_objet, indisp.date_debut ";
      $source = self::source() . " AS indisp INNER JOIN rsbl_motifs_indisponibilite AS motif ON (indisp.code_motif = motif.code) ";
      $jointure_objet = " INNER JOIN ";
      $champs_objet = ", ";
      if ($type_indisponibilite == 1) {
        $jointure_objet = $jointure_objet . "rsbl_supports AS support ON (indisp.code_objet = support.code) INNER JOIN rsbl_types_support ON (support.code_type_support = rsbl_types_support.code) ";
        $champs_objet = $champs_objet . "support.numero AS numero_support, support.nom AS nom_support, support.code_type_support AS type_support, support.code_site_base AS code_site, rsbl_types_support.code_type AS type_type_support, rsbl_types_support.nom_court AS nom_type_support ";
      } else {
        $jointure_objet = $jointure_objet . "rsbl_sites_activite AS site ON (indisp.code_objet = site.code) ";
        $champs_objet = $champs_objet . "site.code AS code_site, site.code_type AS type_site, site.nom AS nom_site, site.nom_court AS nom_court_site ";
      }
      $source = $source . $jointure_objet;
      
      $requete = "SELECT indisp.code AS code, information, indisp.code_type AS code_type, date_creation, code_createur, code_motif, code_objet, date_debut, date_fin, motif.nom AS nom_motif, motif.nom_court " . $champs_objet . " FROM " . $source . $selection . $tri;
      
      //echo '<p>', $requete, '</p>', PHP_EOL;
      try {
        $bdd = Base_Donnees::acces();
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
          $status = true;
        }
      } catch (PDOException $e) {
            Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $status;
    }
    /*
    static function collecter($critere_selection, $critere_tri, & $indisponibilites) {
      $status = false;
      $indisponibilites = array();
      
      $selection = (strlen($critere_selection) > 0) ? " WHERE " . $critere_selection . " " : "";
      $tri = (strlen($critere_tri) > 0) ? " ORDER BY " . $critere_tri . " " : " ORDER BY indisp.code_objet, indisp.date_debut ";
      try {
        $bdd = Base_Donnees::acces();
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
            $site = Enregistrement_Site_Activite::creer($donnee->code_objet);
            $indisponibilite->site_activite = $site;
          //} else {
          //  $indisponibilite = new Indisponibilite($donnee->code);
          }
          
          $indisponibilite->def_information($donnee->information);
          
          // -- Information sur la creation
          $indisponibilite->instant_creation = new Instant($donnee->date_creation);
          $indisponibilite->createur = new Membre($donnee->code_createur);
          
          // Periode d'indisponibilite
          $indisponibilite->debut = new Instant($donnee->date_debut);
          $indisponibilite->fin = new Instant($donnee->date_fin);
          //$indisponibilite->periode = new Intervalle_Temporel($debut, $fin);
          
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
      */
    }
  // ==========================================================================
?>
