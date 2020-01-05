<?php
  // ===========================================================================
  // description : definition des classes relatives aux marees
  // utilisation :
  // teste avec  : PHP 5.5.3 sur Mac OS 10.11 ; PHP 7 sur serveur OVH
  // contexte    : Applications WEB
  // Copyright (c) 2017-2018 AMP
  // ---------------------------------------------------------------------------
  // creation: 11-nov-2017 pchevaillier@gmail.com (version France 2018)
  // revision: 28-nov-2017 pchevaillier@gmail.com
  // revision: 08-jan-2018 pchevaillier@gmail.com calcul affichage duree
  // revision: 23-dec-2018 pchevaillier@gmail.com version Resabel V2
  // ---------------------------------------------------------------------------
  // commentaires :
  // attention :
  // a faire :
  // - separer metier, presentation et persistance
  // ===========================================================================
  
  // --- Classes utilisees
  require_once 'php/metier/calendrier.php';
  require_once 'php/elements_page/generiques/element.php';
  //require_once 'php/bdd/base_donnee.php';
  
  // ---------------------------------------------------------------------------
  class Point_Maree {
    private $instant = null;
    private $hauteur = 0.0;
    private $point = '';
    public function point() {
      return $this->point;
    }
    
    public function hauteur() {
      return $this->hauteur;
    }
    
    public function heure() {
      return $this->instant;
    }
    
    public function __construct(string $point,
                                Instant $instant,
                                float $hauteur) {
      $this->instant = $instant;
      $this->hauteur = $hauteur;
      $this->point = $point;
    }
    
    public function dupliquer() {
      $heure = clone $this->instant; //->dupliquer();
      $copie = new Point_Maree($this->point, $heure, $this->hauteur);
      return $copie;
    }
  }
  
  // --------------------------------------------------------------------------
  class Maree {
    public $debut = null;
    public $fin = null;
    
    private $coefficient = 0;
    public function coefficient() {
      return $this->coefficient;
    }
    public function def_coefficient(float $valeur) {
      $this->coefficient = $valeur;
    }
    
    public function __construct(Point_Maree $debut, Point_Maree $fin) {
      $this->debut = $debut->dupliquer();
      $this->fin = $fin->dupliquer();
    }
    
    public function marnage() {
      return abs($this->debut->hauteur() - $this->fin->hauteur());
    }
    /*
    public function duree() {
      $i = new Intervalle_Temporel($this->debut->heure(), $this->fin->heure());
      return $i->duree();
    }
    */
    public function duree_texte() {
      $intervale = $this->debut->heure()->diff($this->fin->heure());
      $texte = $intervale->format("%H:$I");
      return $texte;
      //$i = new Intervalle_Temporel($this->debut->heure(), $this->fin->heure());
      //return $i->duree_texte();
    }
  }
  
  // --------------------------------------------------------------------------
  class Marees_Jour {
    private $lieu = '';
    private $jour = null;
    private $marees = array();
    
    public function __construct($lieu, $jour) {
      $this->lieu = $lieu;
      $this->jour = $jour;
    }
    
    public function ajouter(Maree $maree) {
      $this->marees[] = $maree;
    }
    
    public function marees() {
      return $this->marees;
    }
  }
  
  // ==========================================================================
  // Presentation des informations sur les marees
  
  
  class Table_Marees_Jour extends Element {
    private $marees = null; // l'objet qui contient les marees a afficher
    
    public function __construct($marees_jour) {
      $this->marees = $marees_jour;
    }
    
    public function initialiser() {}
    
    private function afficher_niveaux() {
      echo '<div style="height:120px; float:left;" >';
      foreach ($this->marees->marees() as $maree) {
        if ($maree->debut->point() === 'PM')
          echo '<div class="maree_haute" style="width:40px; height:30px;">PM</div>';
        else
          echo '<div class="maree_basse" style="width:40px; height:30px;">BM</div>';
        if ($maree->fin->point() === 'PM')
          echo '<div class="maree_haute" style="width:40px; height:30px;">PM</div>';
        else
          echo '<div class="maree_basse" style="width:40px; height:30px;">BM</div>';
      }
      echo '</div>';
    }
    
    private function afficher_heures() {
      echo '<div style="height:120px; float:left;" >';
      foreach ($this->marees->marees() as $maree) {
        $classe_div = ($maree->debut->point() === 'PM') ? 'maree_haute': 'maree_basse';
        //echo '<div class="' . $classe_div . '" style="width:60px; height:30px;">' . Calendrier::obtenir()->heures_minutes_texte($maree->debut->heure()) . '</div>';
        echo '<div class="' . $classe_div . '" style="width:60px; height:30px;">' . $maree->debut->heure()->heure_texte() . '</div>';
        
        $classe_div = ($maree->fin->point() === 'PM') ? 'maree_haute': 'maree_basse';
        //echo '<div class="' . $classe_div . '" style="width:60px; height:30px;">' . Calendrier::obtenir()->heures_minutes_texte($maree->fin->heure()) . '</div>';
        echo '<div class="' . $classe_div . '" style="width:60px; height:30px;">' . $maree->fin->heure()->heure_texte() . '</div>';
      }
      echo '</div>';
    }
    
    private function afficher_hauteurs() {
      echo '<div style="height:120px; float:left;" >';
      foreach ($this->marees->marees() as $maree) {
        $classe_div = ($maree->debut->point() === 'PM') ? 'maree_haute': 'maree_basse';
        echo '<div class="' . $classe_div . '" style="width:60px; height:30px;">' . $maree->debut->hauteur() . ' m</div>';
        
        $classe_div = ($maree->fin->point() === 'PM') ? 'maree_haute': 'maree_basse';
        echo '<div class="' . $classe_div . '" style="width:60px; height:30px;">' . $maree->fin->hauteur() . ' m</div>';
      }
      echo '</div>';
    }
    
    
    private function afficher_coefficients() {
      echo '<div style="height:120px; float:left; ">';
      foreach ($this->marees->marees() as $maree)
      echo '<div class="cadre_coef_maree" style="width:40px;"><div class="coef_maree">' . $maree->coefficient() . '</div></div>';
      echo '</div>';
    }
    
    private function afficher_marnages() {
      echo '<div style="height:120px; float:left; ">';
      foreach ($this->marees->marees() as $maree)
      echo '<div class="marnage_maree" style="width:60px;">' . $maree->marnage() . ' m</div>';
      echo '</div>';
    }
    
    public function afficher_debut() {
      echo '<div>';
    }
    
    public function afficher_corps() {
      $this->afficher_niveaux();
      $this->afficher_coefficients();
      $this->afficher_heures();
      $this->afficher_hauteurs();
      $this->afficher_marnages();
      echo '<div style="clear: both;"></div>';
    }
    
    public function afficher_fin() {
      echo '</div>';
    }
  }
    
  // ==========================================================================
  // Enregistrement des informations sur les marees
  
  class Enregistrement_Maree {
    
    static function source() {
      return Base_Donnees::$prefix_table . 'heures_marees';
    }
    
    static public function recherche_marees_jour($lieu, $jour) {
      $bdd = Base_Donnees::accede();
      $marees = null;
      $horaires = array();
      $coefficients = array();
      
      /*
      $n = self::recherche_horaires($bdd,
                               $lieu,
                               $jour->date_heure_sql(),
                               $jour->lendemain()->date_heure_sql(),
                               $horaires,
                               $coefficients);
      if ($n > 0) {
        $marees = new Marees_Jour($lieu, $jour);
        $i = 0;
        $j = 0;
        while ($i < $n-1) {
          $m = new Maree($horaires[$i],$horaires[$i+1]);
          $m->def_coefficient($coefficients[$j]);
          $marees->ajouter_dans_marees($m);
          $i += 2;
          $j++;
        }
      }
       */
      // pour avoir les marees qui se termine le lendemain (avant midi a priori)
      $fin = $jour->add(new DateInterval('P1DT12H'));
      $n = self::recherche_horaires($bdd,
                               $lieu,
                               $jour->date_heure_sql(),
                               $fin->date_heure_sql(),
                               $horaires,
                               $coefficients);
      if ($n > 0) {
        $marees = new Marees_Jour($lieu, $jour);
        $i = 0;
        $j = 0;
        while ($i < $n-1) {
          // teste si debut ou fin maree est ce jour
          $j1 = $horaires[$i]->heure()->jour();
          $j2 = $horaires[$i+1]->heure()->jour();
          if (($j1 == $jour) || ($j2 == $jour)) {
            $m = new Maree($horaires[$i],$horaires[$i+1]);
            $m->def_coefficient($coefficients[$j]);
            $marees->ajouter($m);
          }
          $i += 2;
          $j++;
        }
      }
      
      return $marees;
    }
    
    static public function recherche_horaires($base_donnees,
                                              $lieu,
                                              $debut,
                                              $fin,
                                              & $horaires,
                                              & $coefficients) {
      $requete = "SELECT etat, date_heure, hauteur, coefficient FROM " . self::source() . " WHERE code_lieu = '" . $lieu . "' AND date_heure BETWEEN '" . $debut . "' AND '" . $fin . "' ORDER BY date_heure";
      //echo $requete;
      try {
        $resultat = $base_donnees->query($requete);
        while ($donnee = $resultat->fetch()) {
          $h = new Instant($donnee['date_heure']);
          $m = new Point_Maree($donnee['etat'], $h, $donnee['hauteur']);
          $horaires[] = $m;
          //          $h->date_clair = $donnee['date'];
          if ($donnee['etat'] == "PM") {
            $coefficients[] = $donnee['coefficient'];
          }
        }
      } catch (PDOexception $e) {
        die("Erreur recherche marees : ligne " . $e->getLine() . ' :</b> '. $e->getMessage());
      }
      $resultat->closeCursor();
      return count($horaires);
    }
    
  }
  
  // ==========================================================================
  ?>
