<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServation de Bateaux En Ligne
  // description : Definition de la classe Base_Donnees
  //               et connexion a la base de donnees (utilisation PDO)
  // --------------------------------------------------------------------------
  // utilisation : php - require_once 'base_donnees.php'
  //               dans toutes les scripts php faisant des requetes
  //               sur la base de donnees
  // dependances : fichier informations_Connexion.php
  // teste avec  : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
  // copyright (c) 2014-2018 AMP. Tous droits réserves.
  // --------------------------------------------------------------------------
  // creation:
  // revision : 07-dec-2014 pchevaillier@gmail.com
  // revision : 26-jul-2017 pchevaillier@gmail.com config / OVH
  // revision : 04-jan-2018 pchevaillier@gmail.com version objet
  // revision : 05-jan-2018 pchevaillier@gmail.com ajout prefix_table
  // revision : 17-jun-2018 pchevaillier@gmail.com masquage info. connexion
  // revision : 23-dec-2018 pchevaillier@gmail.com sortir_sur_exception
  // ------------------------------------------------------------------------
  // commentaires :
  // - utilisation PDO (PHP Data Objects)
  // - les informations sur la base et les donnees de connexion
  //   sont dans le fichier informations_connexion.php qui est exclu du depot
  // attention :
  // - mettre $base_locale a false sur l'hebergeur web
  // a faire :
  // - ne pas creer de nouvel acces si existe deja ?
  // ==========================================================================

  $base_locale = true; // false sur le serveur de l'hebergeur
  new Base_Donnees($base_locale);
  
  class Base_Donnees {
    
    private static $acces;
    public static function accede() {
      return self::$acces;
    }
    
    // Utiliser cette fonction de facon a pouvoir donner
    // le moins d'information sur les erreurs rencontrees 
    public static function sortir_sur_exception($table, $e) {
      die("Erreur requete sur la table " . $table . " : ligne "
          . $e->getLine() . " :<br /> " . $e->getMessage());
    }
    
    static $prefix_table = 'rsbl_';
    private $driver = '';
    private $server = '';
    private $base = '';
    private $access_user = '';
    private $access_pwd = '';
    
    public function __construct($base_locale) {
      $this->initialise($base_locale);
      $this->connecte();
    }
    
    private function initialise($base_locale) {
      $this->driver = 'mysql';
      include 'informations_connexion.php';
    }
    
     private function connecte() {
       try {
         $dsn = $this->driver . ':host=' . $this->server . ';dbname=' . $this->base . ';charset=utf8';
         self::$acces = new PDO($dsn, $this->access_user, $this->access_pwd,
                        array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
       } catch (PDOException $e) {
         // There was an error with the connection
         exit('<b>Erreur connexion base de donnees, ligne '. $e->getLine() .' :</b> '. $e->getMessage());
       }
     }
  }
  
  // ===========================================================================
?>
