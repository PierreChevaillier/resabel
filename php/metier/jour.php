<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : Classes utilkisees pour la gestion
  // copyright (c) 2018-2020 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier_php>
  // dependances :
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.3 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 25-dec-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire :
  // -
  // - finir intervalle temporel. Utiliser les fonctions php / timeDiff
  // ===========================================================================
  
  // --- Classes utilisees
  
  
  // ---------------------------------------------------------------------------
  class Instant extends DateTime {
    
    public static function maintenant() {
      return new Instant("now");
    }
    
    public static function aujourdhui() {
      return new Instant("today");
    }
    
    public function jour() {
      $j = new Instant($this->format('Y-m-d'));
      $j->setTime(0, 0, 0);
      return $j;
    }
    
    public function date() {
      return new Instant($this->format('Y-m-d'));
    }
    
    public function date_html() {
      return $this->format('Y-m-d');
    }
    
    public function date_sql() {
      return $this->format('Y-m-d');
    }

    public function date_heure_sql() {
      return $this->format('Y-m-d H:i:s');
    }
    
    public function date_texte() {
      return strftime('%A %d %B %Y', $this->getTimestamp());
    }
    
    public function date_texte_abbr() {
      return strftime('%a %d %h %y', $this->getTimestamp());
    }
    
    public function heure_texte() {
      return $this->format('H:i');
    }
/*
    public function dupliquer () {
      return new Instant($this->valeur);
    }
  */
    /*
    public function est_egal($autre_instant) {
      return $this->valeur == $autre_instant->valeur;
    }
    
    public function est_avant($autre_instant) {
      return $this->valeur < $autre_instant->valeur;
    }
    
    public function est_apres($autre_instant) {
      return $this->valeur > $autre_instant->valeur;
    }
     */
    
  }
  /*
  class Intervalle_Temporel {
    public static function origine() {
     return new Instant(0);
    }
    
    public static function fin_des_temps() {
      return new Instant(PHP_MAX_INT);
    }
    
    private $debut;
    public function debut() { return $this->debut; }
    
    private $fin;
    public function fin() { return $this->fin; }
    
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
   */
  // --------------------------------------------------------------------------
  // Fabrique d'instants et autres
  
  class Calendrier {
    
    public static function maintenant() {
      return new Instant("now");
    }
    
    public static function aujourdhui() {
      return new Instant("today");
    }
    /*
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
    */
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
    
    public static function jours_futurs_texte($nJours, & $resultat) {
      $d = new Instant("today");
      $un_jour = new DateInterval('P1D');
      for ($j = 0; $j < $nJours; $j++) {
        $ts = $d->getTimestamp();
        //$jour = new Instant($ts);
        $resultat[$ts] = $d->date_texte();
        $d = $d->add($un_jour);
      }
    }
    /*
    public function date_texte($jour) {
      $j = $jour->date();
      return self::$jours[date("N", $j) - 1] . " " . date("j", $j) . " " . self::$mois[date("n", $j) - 1] . " " . date("Y", $j);
    }
  
    public function date_html($jour) {
      $j = $jour->date();
      return date("Y", $j) . "-" . date("m", $j) . "-" . date("d", $j);
    }
    
    public function date_texte_court($jour) {
      $j = $jour->date();
      return self::$jours_courts[date("N", $j) - 1] . " " . date("j", $j) . " " . self::$mois_courts[date("n", $j) - 1];
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
  
    public function formatter_date_heure_sql($jour) {
      // AAAA-MM-JJ HH:MM:SS
      $j = $jour->date();
      return date("Y", $j) . "-" . date("m", $j) . "-" . date("d", $j) . " " . date("H", $j) . ":" . date("i", $j) . ":" . date("s", $j);
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
    */
    public static function creer_DateInterval_depuis_time_sql(string $time_sql) {
      // en entree : hh:mm:ss
      // resultat new DateInterval('PThhHmmMssS');
       if (strlen($time_sql) === 8) {
         $heures = substr($time_sql, -8, 2);
         $minutes = substr($time_sql, -5, 2);
         $secondes = substr($time_sql, -2, 2);
         $expression_iso8601 = 'PT' . $heures . 'H' . $minutes . 'M' . $secondes . 'S';
         return new DateInterval($expression_iso8601);
       } else {
         throw new InvalidArgumentException('Erreur Calendrier::def_DateInterval_depuis_time_sql - format heure invalide: ' . $timestamp_sql);
         return null;
       }
    }
    
  }
   
  // ===========================================================================
?>
