<?php
  // ==========================================================================
  // description : definition des classes relatives a la representation du temps
  // utilisation : require_once <chemin_vers_ce_fichierr_php>
  // dependances : definition de locale - impact sur encodage des string retournees
  //               voir resabel/php/utilitaires/definir_locale.php
  // teste avec  : PHP 7.1 sur MacOS 10.14
  // contexte    : Applications WEB
  // Copyright (c) 2017-2020 AMP
  // --------------------------------------------------------------------------
  // creation : 11-nov-2017 pchevaillier@gmail.com
  // revision : 08-jan-2018 pchevaillier@gmail.com Intervalle temporel (debut)
  // revision : 18-fev-2018 pchevaillier@gmail.com Calendrier::date_html
  // revision : 10-jun-2019 pchevaillier@gmail.com
  // revision : 26-dec-2019 pchevaillier@gmail.com refonte, gros impact / utilisation
  // revision : 30-dec-2019 pchevaillier@gmail.com Calendrier::annee_semaine
  // revision : 05-jan-2020 pchevaillier@gmail.com Instant::valeur_cle_horaire
  // revision : 11-jan-2020 pchevaillier@gmail.com Interval_Temporel
  // revision : 26-dec-2022 pchevaillier@gmail.com compatibilite php 7.x et 8.x
  // --------------------------------------------------------------------------
  // commentaires :
  // - utilisation des classes DateTime et associees
  // - pas d'utilisation de timestamp (sauf usage tres specifique : format)
  // - en evolution
  // - hebergement AMP / OVH : que php 'de base', donc pas le module
  //     'internationalisation'
  //   consequence : gestion a la main des nom de jours et de mois (cf Calendrier)
  // considere : utilisation de gettext (https://www.php.net/manual/en/book.gettext.php)
  // attention :
  // - calcul duree approximatif (pb : annees inhabituelles, heure d'ete ...
  // a faire :
  // - finir intervalle temporel. Utiliser les fonctions php / timeDiff
  //   ou supprimer la classe...
  // ==========================================================================
  
  // --- Classes utilisees
  
  // --- variables statiques
  
  // --------------------------------------------------------------------------
  class Instant extends DateTimeImmutable {

    public function valeur_cle() {
      return $this->format('Y-m-d H:i');
    }
    
    public function valeur_cle_date() {
      return $this->format('Y-m-d');
    }
    
    public function valeur_cle_horaire() {
      $cle = 'PT' . $this->format('H') . 'H' . $this->format('i') . 'M';
      return $cle;
    }
    
    public function lendemain() {
      return $this->add(new DateInterval('P1D'));
    }
    
    public function veille() {
      return $this->sub(new DateInterval('P1D'));
    }

    public function jour() {
      $valeur = $this->format('Y-m-d') . " 00:00:00";
      return new Instant($valeur);
    }
    
    public function numero_semaine() {
      return $this->format("W"); // ISO-8601 week number of year, weeks starting on Monday
    }
    
    public function annee_semaine() {
      return $this->format("o"); // ISO-8601 week-numbering year.
    }
    
    public function heure_hiver() {
      return (1 - date('I', $this->getTimestamp()));
    }
    
    public function est_egal(Instant $autre_instant) {
      return $this->getTimestamp() == $autre_instant->getTimestamp();
    }
    
    public function est_apres(Instant $autre_instant) {
      return $this->getTimestamp() >= $autre_instant->getTimestamp();
    }
    
    public function est_avant(Instant $autre_instant) {
         return $this->getTimestamp() <= $autre_instant->getTimestamp();
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
  
    public function date_texte(): string {
      $j = $this->getTimestamp();
      $str = Calendrier::$jours[date("N", $j) - 1]
        . " " . date("j", $j)
        . " " . Calendrier::$mois[date("n", $j) - 1]
        . " " . date("Y", $j)
      ;
      return $str;
     /*
      $fmt = new IntlDateFormatter('fr_FR',
                            IntlDateFormatter::FULL,
                            IntlDateFormatter::FULL,
                            'America/Los_Angeles',
                            IntlDateFormatter::GREGORIAN,
                            'l j F Y');
      $str = $fmt($this); // $this->getTimestamp());
      return $str;
      */
      //return strftime('%A %d %B %Y', $this->getTimestamp()); // php < 8
    }
  
    public function date_texte_abbr(): string {
      $j = $this->getTimestamp();
      $str = Calendrier::$jours_courts[date("N", $j) - 1]
        . " " . date("j", $j)
        . " " . Calendrier::$mois_courts[date("n", $j) - 1]
        . " " . date("Y", $j)
      ;
      return $str;
      //return strftime('%a %d %h %y', $this->getTimestamp()); // deprecated 8.x
    }
  
    public function date_texte_court(): string {
      $j = $this->getTimestamp();
      $str = Calendrier::$jours_courts[date("N", $j) - 1]
        . " " . date("j", $j)
        . " " . Calendrier::$mois_courts[date("n", $j) - 1];
      return $str;
      //return strftime('%a %d %h', $this->getTimestamp());  // deprecated 8.x
    }
    
    public function heure_texte() {
      return $this->format('H:i');
    }
  }
  
  // --------------------------------------------------------------------------
  class Intervalle_Temporel {
     public static function origine() {
       return Calendrier::creer_instant(0);
     }
     
     public static function fin_des_temps() {
       return Calendrier::creer_instant(PHP_MAX_INT);
     }
     
     private $debut;
     public function debut() { return $this->debut; }
     
     private $fin;
     public function fin() { return $this->fin; }
     
     public function __construct(Instant $debut, Instant $fin) {
       if (is_null($debut))
         throw new InvalidArgumentException("Le debut de l'intervalle temporel n'est pas specifie (null)");
       elseif (is_null($fin))
         throw new InvalidArgumentException("La fin de l'intervalle temporel n'est pas specifiee (null)");
       elseif ($debut->est_apres($fin))
         throw new RangeException("La date de debut de l'intervalle doit etre avant celle de sa fin");
       else {
         $this->debut = $debut;
         $this->fin = $fin;
       }
     }
    
    public function chevauche(Intervalle_Temporel $autre_intervalle) {
      $condition = ($autre_intervalle->fin()->est_avant($this->debut()) || $autre_intervalle->debut()->est_apres($this->fin()));
      return !$condition;
    }
    
    public function couvre(Intervalle_Temporel $autre_intervalle) {
      return ($this->debut()->est_avant($autre_intervalle->debut()) && $this->fin()->est_apres($autre_intervalle->fin()));
    }
  }
   
  /* version avant dec-2019
  class Instant {
    private $valeur = 0;
    
    public function __construct($valeur) {
      $this->definir($valeur);
    }
    
    public function date() {
      return $this->valeur;
    }
    
    public function timestamp() { return $this->valeur; }
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
  
   */
  // ---------------------------------------------------------------------------
  /* Version avant dec-2019
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
  // Fabrique d'instants et autres fonctions sur les dates
  // + "localisation" des formats texte (jour et mois)
  // car strftime est deprecated en version 8.1
  // et que l'hebergement
  // ne permet pas d'utiliser l'extension internationale de php
  // donc retour a l'ancienne version...
  // utilise par Instant::date_texte_abrev et Instant::date_texte_court
  class Calendrier {
    public static $jours = array("Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "dimanche");
    public static $mois = array("janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");
    public static $jours_courts = array("Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim");
    public static $mois_courts = array("janv", "févr", "mars", "avr", "mai", "juin", "juil", "août", "sept", "oct", "nov", "déc");
    
    public static function maintenant() {
      return new Instant("now");
    }
       
    public static function aujourdhui() {
      return new Instant("today");
    }
    
    public static function creer_Instant(int $timestamp) {
      return (new Instant())->setTimestamp($timestamp);
    }
    
    public static function jours_futurs_texte($nJours, & $resultat) {
      $d = new Instant("today");
      $un_jour = new DateInterval('P1D');
      for ($j = 0; $j < $nJours; $j++) {
        $ts = $d->valeur_cle_date(); //$d->getTimestamp();
        $resultat[$ts] = $d->date_texte();
        $d = $d->add($un_jour);
      }
    }
    
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
         throw new InvalidArgumentException('Erreur Calendrier::creer_DateInterval_depuis_time_sql - format heure invalide: ' . $timestamp_sql);
         return null;
       }
    }
    
    public static function date_jour_semaine($jourSemaine, $numSemaine, $annee) {
      $jourDeLAn = mktime(0, 0, 0, 1, 1, $annee);
      $jour_semaine_jourDeLAn = date("N", $jourDeLAn);
      if (date('W', $jourDeLAn) == 1) // modif du 20-dec-2015
        $jours = ($numSemaine - 1) * 7 + $jourSemaine - $jour_semaine_jourDeLAn + date('j', $jourDeLAn);
      else
        $jours = ($numSemaine - 1) * 7 + $jourSemaine + (7 - $jour_semaine_jourDeLAn + 1); // ajout 20-dec-2015
      $j = mktime(0, 0, 0, date('n', $jourDeLAn),  $jours, $annee);
      return Calendrier::creer_Instant($j);
    }
    
    public static function annee_semaine(DateTimeImmutable $jour) {
      $j = $jour->getTimestamp();
      return date("o", $j); // PCh 30-dec-2019 : ISO-8601 week-numbering year.
      /*
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
       */
    }
    
  }
  /* Version avant dec-2019
  class Calendrier {
    
    
    public static $jours = array("lundi", "mardi", "mercredi", "jeudi", "vendredi", "samedi", "dimanche");
    public static $mois = array("janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre");
    
    public static $jours_courts = array("lun", "mar", "mer", "jeu", "ven", "sam", "dim");
    public static $mois_courts = array("janv", "févr", "mars", "avr", "mai", "juin", "juil", "août", "sept", "oct", "nov", "déc");
    
    public $fuseau_horaire = null;
    private static $instance = null;
    
    private static function reference($objet) {
      self::$instance = $objet;
    }
    
    public static function obtenir() {
      return self::$instance;
    }
    
    public function __construct() {
      $this->fuseau_horaire = new DateTimeZone("Europe/Paris");
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
    
    public function jours_futurs_texte($n, & $resultat) {
      $d = new DateTime("now", $this->fuseau_horaire);
      $d->setTime(0, 0, 0); // pour etre coherent
      $un_jour = new DateInterval('P1D');
      for ($j = 0; $j < $n; $j++) {
        $ts = $d->getTimestamp();
        $jour = new Instant($ts);
        $resultat[$ts] = $this->date_texte($jour);
        $d = $d->add($un_jour);
      }
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
    
    public function def_DateInterval_depuis_time_sql(string $time_sql) {
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
    */
  // ===========================================================================
?>
