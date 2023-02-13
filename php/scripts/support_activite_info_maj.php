<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : verification et mise a jour des informations
  //               sur un support d'activite
  // copyright (c) 2018-2020 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : formulaire_support_activite.php (ids des champs du formulaire)
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 31-aug-2020 pchevaillier@gmail.com cf. membre_info_obtenir.php
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // - voir membre_info_obtenir.php pour les expressions reguliere
  // - en evolution : completer les champs et les controles
  //
  // a : type action = [c => creation ; m => modification (maj)]
  // typ  : type objet : [bat => classe Bateau, erg => classe Plateau-Ergo]
  // sua = code du support d'activite (valeur si ta = m)
  // i : identifiant de l'objet du document html ($iod)
  // r : code_erreur
  //     0 : pas d'erreur : mise a jour effectuee
  //     99 : pas de mise a jour dans la base de donnees
  // v : valeur a afficher si erreur (valeur d'un champ du formulaire)
  // attention :
  // -
  // a faire :
  // -
  // ==========================================================================
  session_start(); // doit etre la premiere instruction
  
  set_include_path('./../../');
  
  // --------------------------------------------------------------------------
  // --- connection a la base de donnees
  include 'php/bdd/base_donnees.php';
  
  $bdd = Base_Donnees::acces();
    
  require_once 'php/bdd/enregistrement_support_activite.php';
  require_once 'php/metier/support_activite.php';
  
  $ok = true; // controle erreurs fatales pour enregistrement
  
  // --------------------------------------------------------------------------
  // --- recuperation des parametres de l'appel du script
  //     et verification
  
  $code = 0;
  $action = '';
  if (isset($_GET['a'])) {
    if ($_GET['a'] == 'm') {
      $action = 'm';
      $code = $_GET['sua'];
    } elseif ($_GET['a'] == 'c') {
      $action = 'c';
    } else {
      die("code action incorrect " . $_GET['a']);
    }
  } else {
    die("action non definie ");
  }
  
  if (isset($_GET['typ'])) {
    $type = $_GET['typ'];
  } else {
    die("type de l'objet cible de l'action non defini");
  }
  
  // parametre de l'appel pur le retour a la page du formulaire (en fin de MaJ)
  $location = "../../support_activite.php?typ=" . $type;

  // --------------------------------------------------------------------------
  // Initialisation des information sur le support d'activite
  $support = null;
  $enregistrement = new Enregistrement_Support_Activite();
  
  if ($action == 'm') {
    $trouve = $enregistrement->lire($code); // TODO traiter pas trouve
    $support = $enregistrement->support_activite();
  } elseif ($action == 'c') {
    if ($type == 'bat') $support = new Bateau($code);
    elseif ($type == 'erg') $support = new Plateau_Ergo($code);
    $enregistrement->def_support_activite($support);
  }
    
  // --------------------------------------------------------------------------
  // --- recuperation des informations saisies dans le formulaire
  //     et verification des valeurs saisies
  
  $erreur = 0;
  // Controle de la valeur saisie pour le nom (saisie obligatoire)
  $iod = 'nom';
  if ($erreur == 0 && isset($_POST[$iod])) {
    //$x = trim(utf8_decode($_POST[$iod]));
    $x = trim($_POST[$iod]);
    $nCar1 = strlen($x);
    $x = strip_tags($x);
    $nCar2 = strlen($x); // il y a des caracteres HTML...
    if ($nCar2 != $nCar1) {
      // tres louche => on les enleve et on cree quand meme l'enregistrement
      //include 'php/scripts/deconnexion.php';
      $erreur = 1;
    }
    if (strlen($x) < 2 || strlen($x) > 20) $erreur = 2;
    elseif (!preg_match("#^[a-zA-Zéèëçñì\ '-]+$#", $x)) $erreur = 3;
    if ($erreur > 0) {
      $location = $location . "&r=" . $erreur . "&i=" . $iod . "&v=" . $x;
    }
    $support->def_nom($x);
  }
  
  // Controle de la valeur saisie pour le numero (saisie obligatoire)
  $iod = 'num';
  if ($erreur  == 0 && isset($_POST[$iod])) {
    //$x = trim(utf8_decode($_POST[$iod]));
    $x = trim($_POST[$iod]);
    $nCar1 = strlen($x);
    $x = strip_tags($x);
    $nCar2 = strlen($x); // il y a des caracteres HTML...
    if ($nCar2 != $nCar1) {
      // tres louche => on les enleve donc on cree quand meme l'enregistrement
      //include 'php/scripts/deconnexion.php';
      $erreur = 4;
    }
    if (strlen($x) < 2)$erreur = 1;
    elseif (strlen($x) > 6) $erreur = 2;
    elseif (!preg_match("#^[a-zA-Z0-9]+$#", $x)) $erreur = 3;
    if ($erreur > 0) {
      $location = $location . "&r=" . $erreur . "&i=" . $iod . "&v=" . $x;
    }
    $support->def_numero($x);
  } elseif (!isset($_POST[$iod])) {
    // Pas dans formulaire => valeur par defaut quand c'est possible
    $support->def_numero(strval($code));
  }
  
  
  // Controle de la valeur saisie pour le code du type de support
  $iod = 'ts';
  if (isset($_POST[$iod])) {
    $code_type_support = $_POST[$iod];
    $support->type = new Type_Support_Activite($code_type_support);
  }
  
  // Pour l'instant les autres champs ont les valeurs par defaut
  // definies dans les classes Support_Activite et derivees
  
  
  // Controle de la valeur saisie pour le nom du modele de support
  $iod = 'mdl';
  if ($erreur  == 0 && isset($_POST[$iod])) {
    $x = trim($_POST[$iod]);
    $nCar1 = strlen($x);
    $x = strip_tags($x);
    $nCar2 = strlen($x);
    if ($nCar2 != $nCar1) {
      // il y a des caracteres HTML...
      // tres louche => on les enleve donc on cree quand meme l'enregistrement
      $erreur = 4;
    }
    if (strlen($x) > 20) $x = substr($x, 0, 20); // cf. table dans base de donnees
    //elseif (!preg_match("#^[a-zA-Z0-9]+$#", $x)) $erreur = 3;
    if ($erreur > 0) {
      $location = $location . "&r=" . $erreur . "&i=" . $iod . "&v=" . $x;
    } else {
      $support->modele = $x;
    }
  }
  
  // Controle de la valeur saisie pour le nom du constructreur du support
  $iod = 'constr';
  if ($erreur  == 0 && isset($_POST[$iod])) {
    $x = trim($_POST[$iod]);
    $nCar1 = strlen($x);
    $x = strip_tags($x);
    $nCar2 = strlen($x);
    if ($nCar2 != $nCar1) {
      // il y a des caracteres HTML...
      // tres louche => on les enleve donc on cree quand meme l'enregistrement
      $erreur = 4;
    }
    if (strlen($x) > 20) $x = substr($x, 0, 20); // cf. table dans base de donnees
    if ($erreur > 0) {
      $location = $location . "&r=" . $erreur . "&i=" . $iod . "&v=" . $x;
    } else {
      $support->modele = $x;
    }
  }

  // Controle de la valeur saisie pour le nom du constructreur du support
  $iod = 'aconst';
  if ($erreur  == 0 && isset($_POST[$iod])) {
    $support->annee_construction = $_POST[$iod];
  }
  
  // champs checkbox
  // https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/checkbox
  $iod = 'actif';
  if ($erreur  == 0)
    $support->actif = isset($_POST[$iod]);
  
  $iod = 'compet';
  if ($erreur  == 0)
    $support->pour_competition = isset($_POST[$iod]);
  
  $iod = 'loisir';
  if ($erreur  == 0)
    $support->pour_loisir = isset($_POST[$iod]);

  $iod = 'mininit';
   if ($erreur  == 0 && isset($_POST[$iod])) {
     $support->nombre_initiation_min = $_POST[$iod];
   }
  
  $iod = 'maxinit';
   if ($erreur  == 0 && isset($_POST[$iod])) {
     $support->nombre_initiation_max = $_POST[$iod];
   }

  // --------------------------------------------------------------------------
  // Enregistrement des nouvelles donnees
  // qu'il y ait une erreur ou pas (sauf erreur fatale)
  $mise_a_jour = false;
  if ($ok) { // pas d'erreur fatale
    if ($action == 'm')
      $mise_a_jour = $enregistrement->modifier();
    elseif ($action == 'c')
      $mise_a_jour = $enregistrement->ajouter();
  }

  // --------------------------------------------------------------------------
  // Definition des parametres de la prochaine page web affichee
  
  $prochaine_action = $action;
  if (!$mise_a_jour) {
    $location = $location . "&r=99";
    if ($action != 'c')
      $location = $location . "&sua=" . $code;
    $location = $location . "&a=" . $prochaine_action;
  } else {
    $location = $location . "&a=";
    if ($action == 'c') $prochaine_action = 'm';
    if ($erreur == 0) // tout s'est bien passe
      $location = $location . "&a=" . $prochaine_action . "&r=0";
    else
      $location = $location . "&a=" . $prochaine_action; // erreur deja renseignee
    $location = $location . "&sua=" . $enregistrement->support_activite()->code();
    header("location: ../../support_activite.php?" . $params . "&r=0&sua=" . $code);
  }
  header("location: " . $location);
  exit();
  
  // ==========================================================================
?>
