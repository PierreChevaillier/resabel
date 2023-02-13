<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServation de Bateaux En Ligne
  // description : Definition de la classe Base_Donnees
  //               et connexion a la base de donnees (utilisation PDO)
  // --------------------------------------------------------------------------
  // utilisation : php - include_once 'base_donnees.php'
  //               dans tous les scripts faisant des requetes
  //               sur la base de donnees
  // dependances : cf include
  // utilise avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
  //  - depuis 2023 :
  //    PHP 8.2 sur macOS 13.2 ;
  //    PHP 7.3 sur macOS 11.6
  //    PHP 7.3 sur hebergeur web
  // copyright (c) 2014-2023 AMP. Tous droits réserves.
  // --------------------------------------------------------------------------
  // creation:
  // revision : 07-dec-2014 pchevaillier@gmail.com
  // revision : 26-jul-2017 pchevaillier@gmail.com config / OVH
  // revision : 04-jan-2018 pchevaillier@gmail.com version objet
  // revision : 05-jan-2018 pchevaillier@gmail.com ajout prefix_table
  // revision : 17-jun-2018 pchevaillier@gmail.com masquage info. connexion
  // revision : 23-dec-2018 pchevaillier@gmail.com sortir_sur_exception
  // revision : 04-fev-2023 pchevaillier@gmail.com majeur : nom methode  + info cnx
  // ------------------------------------------------------------------------
  // commentaires :
  // - utilisation PDO (PHP Data Objects)
  // - les informations sur la base et les donnees de connexion
  //   sont dans le fichier informations_bdd.php qui est exclu du depot
  //   et est specifique a chaque environnement d'execution
  // attention :
  // -
  // a faire :
  // - 
  // ==========================================================================

  include_once 'informations_bdd.php';

  // --- Creation d'une instance afin de creer le point d'acces a la base
  new Base_Donnees();
  
  class Base_Donnees {
    
    private static ?PDO $acces = null;
    public static function acces(): ?PDO {
      return self::$acces;
    }
    
    // Utiliser cette fonction de facon a pouvoir donner
    // le moins d'information sur les erreurs rencontrees 
    public static function sortir_sur_exception(string $table, Exception $e): void {
      die("Erreur requete sur la table " . $table . " : ligne "
          . $e->getLine() . " :<br /> " . $e->getMessage());
    }
    
    static $prefix_table = '';
    private $driver = '';
    private $server = '';
    private $base = '';
    private $access_user = '';
    private $access_pwd = '';
    
    public function __construct() {
      $this->initialiser();
      if (is_null(self::$acces))
        $this->connecter();
    }
    
    private function initialiser(): void {
      $this->driver = DRIVER;
      $this->server = SERVER;
      $this->base = BASE;
      $this->access_user = ACCESS_USER;
      $this->access_pwd = ACCESS_PWD;
      self::$prefix_table = PREFIX_TABLE;
    }
    
     private function connecter():void {
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
