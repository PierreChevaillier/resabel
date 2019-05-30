<?php
  // ===========================================================================
  // description : definition des classes relatives a la representation du temps
  // utilisation : require_once car instantiation d'une variable statique
  // teste avec  : PHP 5.5.3 sur Mac OS 10.11
  // contexte    : Applications WEB
  // Copyright (c) 2017-2018 AMP
  // ---------------------------------------------------------------------------
  // creation : 11-nov-2017 pchevaillier@gmail.com
  // revision : 08-jan-2018 pchevaillier@gmail.com Intervalle temporel (debut)
  // revision : 18-fev-2018 pchevaillier@gmail.com Calendrier::date_html
  // ---------------------------------------------------------------------------
  // commentaires :
  // - en evolution
  // attention :
  // - calcul duree approximatif (pb : annees inhabituelles, heure d'ete ...
  // a faire :
  // - finir intervalle temporel. Utiliser les fonctions php / timeDiff
  // ===========================================================================
  
  // --- Classes utilisees
  
  // --- variables statiques
  
  new Calendrier();
  
  // ---------------------------------------------------------------------------
  class Instant {
    private $valeur = 0;
    
    public function __construct($valeur) {
      $this->definir($valeur);
    }
    
    public function date() {
      return $this->valeur;
    }
    
    public function definir($valeur) {
      $this->valeur = $valeur;
    }
    
    public function dupliquer () {
      return new Instant($this->valeur);
    }
   
    public function est_egal($autre_instant) {
      return $this->valeur == $autre_instant->valeur;
    }
    
    public function est_avant($autre_instant) {
      return $this->valeur < $autre_instant->valeur;
    }
    
    public function est_apres($autre_instant) {
      return $this->valeur > $autre_instant->valeur;
    }
    
  }
  
  class Intervalle_Temporel {
    public static function origine() {
     return new Instant(0);
    }
    
    public static function fin_des_temps() {
      return new Instant(PHP_MAX_INT);
    }
    
    private $debut;
    private $fin;
    
    public function __construct($debut, $fin) {
      if ($debut == null)
        throw new InvalidArgumentException("Le debut de l'intervalle temporel n'est pas specifiee (null)");
      elseif ($fin == null)
        throw new InvalidArgumentException("La fin de l'intervalle temporel n'est pas specifiee (null)");
      elseif ($debut->est_apres($fin))
        throw new RangeException("La date de debut de l'intervalle doit etre avant celle de sa fin");
      else {
        $this->debut = $debut;
        $this->fin = $fin;
      }
    }
    
    public function duree() {
      return $this->fin->date() - $this->debut->date();
    }
    
    public function duree_texte() {
      $duree = $this->duree();
      // inspiree de la methode trouvee dans http://php.net/manual/en/function.time.php
      $comp = array(
                    //'an' => $duree / 31556926 % 12,
                    //'sem' => $duree / 604800 % 52,
                    'j' => $duree / 86400 % 7,
                    'h' => $duree / 3600 % 24,
                    'm' => $duree / 60 % 60,
                    's' => $duree % 60
                    );
      foreach ($comp as $k => $v)
      if ($v > 0)
        $resultat[] = $v . $k;
      return join($resultat);
    }
    
    public function duree_chevauchement($autre_intervalle) {
      $fin = min($this->fin->date(), $autre_intervalle->fin->date());
      $debut = max($this->debut->date(), $autre_intervalle->debut->date());
      return max(0, $fin - $debut);
    }
    
    public function commence_avant($autre_intervalle) {
      return $this->debut->est_avant($autre_intervalle->debut);
    }
    
  }
  // ---------------------------------------------------------------------------
  class Calendrier {
    
    
    public static $jours = array("lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi", "dimanche");
    public static $mois = array("janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");
    
    public static $jours_courts = array("lun", "mar", "mer", "jeu", "ven", "sam", "dim");
    public static $mois_courts = array("janv", "févr", "mars", "avr", "mai", "juin", "juil", "août", "sept", "oct", "nov", "déc");
    
    private static $instance = null;
    
    private static function reference($objet) {
      self::$instance = $objet;
    }
    
    public static function obtenir() {
      return self::$instance;
    }
    
    public function __construct() {
      //date_default_timezone_set("Europe/Paris"); parametre du site defini dans la base de donnees
      self::reference($this);
    }
    
    public function maintenant() {
      return $this->heure($this->aujourdhui(), date("H"), date("i"), date("s"));
    }
    
    public function aujourdhui() {
      return $this->jour(date("d"), date("m"), date("Y"));
    }
    
    public function lendemain($jour) {
      $j = $jour->date();
      $l = mktime(0, 0, 0, date("n", $j), date("d", $j) + 1, date("Y", $j));
      $resultat = new Instant($l);
      return $resultat;
    }
    
    public function jour_semaine($jour) {
      $j = $jour->date();
      return self::$jours[date("N", $j) - 1];
    }
    
    public function numero_semaine($jour) {
      $j = $jour->date();
      return date("W", $j);
    }
    
    public function annee_semaine($jour) {
      $j = $jour->date();
      $jourDeLAn = mktime(0, 0, 0, 1, 1, date("Y", $j));
      $jour_semaine_jourDeLAn = date("N", $jourDeLAn);
      // On est le jour de l'an et c'est un dimanche : derniere semaine de l'annee qui se termine.
      // cas du 1er janvier 2017
      if (($j == mktime(0, 0, 0, 1, 1, date("Y", $j))) && (date("N", $j) == 7))
        return (date("Y", $j) - 1);
      elseif ((date("W", $j) == 53) && (date('W', $jourDeLAn) == 53) && (date("N", $j) >= $jour_semaine_jourDeLAn))
        return (date("Y", $j) - 1);
      else
        return date("Y", $j);
    }
    
    public function date_jour_semaine($jourSemaine, $numSemaine, $annee) {
      $jourDeLAn = mktime(0, 0, 0, 1, 1, $annee);
      $jour_semaine_jourDeLAn = date("N", $jourDeLAn);
      if (date('W', $jourDeLAn) == 1) // modif du 20-dec-2015
        $jours = ($numSemaine - 1) * 7 + $jourSemaine - $jour_semaine_jourDeLAn + date('j', $jourDeLAn);
      else
        $jours = ($numSemaine - 1) * 7 + $jourSemaine + (7 - $jour_semaine_jourDeLAn + 1); // ajout 20-dec-2015
      $j = mktime(0, 0, 0, date('n', $jourDeLAn),  $jours, $annee);
      return new Instant($j);
    }
    
    public function date_texte($jour) {
      $j = $jour->date();
      return self::$jours[date("N", $j) - 1] . " " . date("j", $j) . " " . self::$mois[date("n", $j) - 1] . " " . date("Y", $j);
    }
  
    public function date_html($jour) {
      $j = $jour->date();
      return date("Y", $j) . "-" . date("m", $j) . "-" . date("d", $j);
    }
    
    public function date_texte_court($jour) {
      return self::$jours_courts[date("N", $jour) - 1] . " " . date("j", $jour) . " " . self::$mois_courts[date("n", $jour) - 1];
    }
    
    public function heures_minutes_texte($instant) {
      $h = date("H", $instant->date());
      $m = date("i", $instant->date());
      return $h . "h" . $m;
    }
    
    public function jour($jour_mois, $mois, $annee) {
      return new Instant(mktime(0, 0, 0, $mois, $jour_mois, $annee));
    }
   
    public function heure($jour, $heures, $minutes, $secondes) {
      $j = $jour->date();
      return new Instant(mktime($heures, $minutes, $secondes, date("n", $j), date("j", $j), date("Y", $j)));
    }

    public function formatter_date_sql($jour) {
      // AAAA-MM-JJ
      $j = $jour->date();
      return date("Y", $j) . "-" . date("m", $j) . "-" . date("d", $j);
    }
  
    public function def_depuis_date_sql($date_sql) {
      // $date : AAAA-MM-JJ
      if (strlen($date_sql) === 10) {
        $annee = substr($date_sql, -10, 4);
        $mois = substr($date_sql, -5, 2);
        $jour_mois = substr($date_sql, -2, 2);
        return self::jour($jour_mois, $mois, $annee);
      } else {
        throw new InvalidArgumentException('Erreur Calendrier::def_depuis_date_sql - format date invalide: ' . $date_sql);
        return null;
      }
    }
      
    public function def_depuis_timestamp_sql($timestamp_sql) {
      // timestamp = AAAA-MM-JJ HH:MM:SS
      if (strlen($timestamp_sql) === 19) {
        $annee = substr($timestamp_sql, -19, 4);
        $mois = substr($timestamp_sql, -14, 2);
        $jour_mois = substr($timestamp_sql, -11, 2);
        $heures = substr($timestamp_sql, -8, 2);
        $minutes = substr($timestamp_sql, -5, 2);
        $secondes = substr($timestamp_sql, -2, 2);
        return new Instant(mktime($heures, $minutes, $secondes, $mois, $jour_mois, $annee));
      } else {
        throw new InvalidArgumentException('Erreur Calendrier::def_depuis_timestamp_sql - format date invalide: ' . $timestamp_sql);
        return null;
      }
    }
    
  }
    
  // ===========================================================================
?>
