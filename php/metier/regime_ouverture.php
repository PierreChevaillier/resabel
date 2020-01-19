<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classes supportant les informations sur les heures
  //               d'ouverture et de fermeture des sites d'activite
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : 
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 30-jun-2019 pchevaillier@gmail.com
  // revision : 25-dec-2019 pchevaillier@gmail.com modif definir_creneaux
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // - Lien vers site pour avoir lat et longitude pour heure lever, coucher soleil
  //   attention timeZone
  // ==========================================================================

  // --------------------------------------------------------------------------
  require_once 'php/metier/calendrier.php';
  
  // --------------------------------------------------------------------------
  abstract class Regime_Ouverture {
    
    public $duree_seance; // DateInterval
    public $jour_uniquement = true;
    
    private $code = 0;
    public function code() { return $this->code; }
    public function def_code($valeur) { $this->code = $valeur;}
    
    private $nom = ""; // utf8
    public function nom() { return $this->nom; }
    public function def_nom($valeur) { $this->nom = $valeur; }
    
    public function __construct($code) { $this->code = $code; }
    
    //public abstract function est_creneau_possible($intervalle_temporel);
    public abstract function definir_creneaux(Instant $jour,
                                              float $latitude,
                                              float $longitude);
  }
  
   // --------------------------------------------------------------------------
  class Regime_Diurne extends Regime_Ouverture {
    public $decalage_heure_hiver; // DateInterval
    public $heure_ouverture; // DateInterval
    public $heure_fermeture; // DateInterval

    public function definir_creneaux(Instant $date_jour,
                                     float $latitude,
                                     float $longitude) {
      
      // Activite de jour uniquement, donc il faut les heures locales
      // de lever et coucher du soleil
      
      $t_lever = date_sunrise($date_jour->getTimestamp(),
                              SUNFUNCS_RET_TIMESTAMP,
                              $latitude,
                              $longitude);
      
      //echo 'latitude ', $latitude, ' long. ', $longitude;
      $lever = Calendrier::creer_Instant($t_lever);
      //echo ' Lever ', $lever->format('d-m-Y H:i:s');
      $t_coucher = date_sunset($date_jour->getTimestamp(),
                              SUNFUNCS_RET_TIMESTAMP,
                              $latitude,
                              $longitude);
      $coucher = Calendrier::creer_Instant($t_coucher);
      //echo 'Coucher ', $coucher->format('d-m-Y H:i:s');
      
      // Initialisation des dates de debut et fin (ouverture, fermeture)
      
      $debut = $date_jour->add($this->heure_ouverture);
      //echo 'Debut ', $debut->format('d-m-Y H:i:s');
      $fin = $date_jour->add($this->heure_fermeture);
      //echo 'Fin ', $fin->format('d-m-Y H:i:s');
      
      //$heure_hiver = (1 - date('I', $jour));
      if ($date_jour->heure_hiver() && isset($this->decalage_heure_hiver)) {
        $debut = $debut->add($this->decalage_heure_hiver);
        $fin = $fin->add($this->decalage_heure_hiver);
      }
      
      $debut_creneau = $debut;
      $fin_creneau = $debut->add($this->duree_seance);
      $creneaux = array();
      
      while ($fin_creneau <= $fin) {
        if (($debut_creneau > $lever) && ($fin_creneau < $coucher))
          $creneaux[] = new Intervalle_Temporel($debut_creneau, $fin_creneau);
        $debut_creneau = $debut_creneau->add($this->duree_seance);
        $fin_creneau = $fin_creneau->add($this->duree_seance);
      }
      return $creneaux;
    }

  }
   // --------------------------------------------------------------------------
  class Regime_Hebdomadaire extends Regime_Ouverture {
    public $horaires_journaliers = array(); //Plages horaires ; remarque : potentiellement plusieurs plages horaires par jour
    public function definir_creneaux(Instant $jour,
                                     float $latitude,
                                     float $longitude) {
    }

  }
  
  class Plage_Horaire {
    public $numero_jour_semaine = 0;
    public $heure_ouverture; // DateInterval
    public $heure_fermeture; // DateInterval
  }
    
  // ==========================================================================
?>
