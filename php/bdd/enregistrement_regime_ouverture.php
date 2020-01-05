<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classe Enregistrement_Regime_Ouverture (d'un site d'activite)
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : Classe Regime_Ouverture et derivees
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 01-jul-2019 pchevaillier@gmail.com
  // revision : 27-dec-2019 pchevaillier@gmail.com impact refonte Calendrier
  // --------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // - cas du regime hebdomadaire
  // ==========================================================================

  require_once 'php/metier/regime_ouverture.php';
  require_once 'php/metier/calendrier.php';
  
  // --------------------------------------------------------------------------
  class Erreur_Type_Regime_Ouverture extends Exception { }

  // --------------------------------------------------------------------------
  class Enregistrement_Regime_Ouverture {
    static function source() {
      return Base_Donnees::$prefix_table . 'regimes_ouverture';
    }
    
    public static function creer($code) {
      $regime = null;
      try {
        $bdd = Base_Donnees::accede();
        $code_sql = "SELECT code, code_type, nom, heure_ouverture, heure_fermeture, duree_seance, de_jour_uniquement, decalage_heure_hiver FROM " . self::source() . " WHERE code = "  . $code . " ORDER BY code, jour_semaine";
        //echo '<p>', $code_sql, '</p>';
        $requete = $bdd->query($code_sql);
        while ($donnee = $requete->fetch(PDO::FETCH_OBJ)) {
          // Il faut trouver le type de l'objet a instancier (pas terrible...)
          if ($donnee->code_type == 1) {
            if (!isset($regime)) {
              $regime = new Regime_Diurne($code);
            }
            // 1 seul enregistrement avec jour_semaine = 0
            
            $regime->heure_ouverture = Calendrier::creer_DateInterval_depuis_time_sql($donnee->heure_ouverture);
            $regime->heure_fermeture = Calendrier::creer_DateInterval_depuis_time_sql($donnee->heure_fermeture);
            $regime->decalage_heure_hiver = Calendrier::creer_DateInterval_depuis_time_sql($donnee->decalage_heure_hiver);
            
          } elseif ($donnee->code_type == 2) {
            if (!isset($this->regime)) {
              $regime = new Regime_Hebdomadaire($code);
            }
            // si pas encore d'entree ppour ce jour : creer l'entree avec liste de plages horaires vides
            // pour l'entree du jour, creer une plage horaire et l'ajouter a la liste pour le sour de la semaine
          } else {
            $requete->closeCursor();
            throw new Erreur_Type_Regime_Ouverture();
          }
          $regime->def_nom($donnee->nom);
          $regime->duree_seance = Calendrier::creer_DateInterval_depuis_time_sql($donnee->duree_seance);
        }
      } catch (PDOexception $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      $requete->closeCursor();
      return $regime;
    }
    
  }
  // ==========================================================================
?>
