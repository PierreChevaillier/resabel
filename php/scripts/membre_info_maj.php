<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : verification et mise a jour des informations sur un membre
  // copyright (c) 2018-2023 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : formulaire_membre.php (ids des champs du formulaire)
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 28-dec-2018 pchevaillier@gmail.com
  // revision : 08-mai-2019 pchevaillier@gmail.com refonte : maj ET creation
  // revision : 25-dec-2019 pchevaillier@gmail.com impact refonte calendrier
  // revision : 02-dec-2023 pchevaillier@gmail.com impact membre <>--> connexion
  // --------------------------------------------------------------------------
  // commentaires :
  // - en chantier
  // ta : type action = [c => creation ; m => modif (maj)]
  // a  : action : [nm => nouveau membre]
  // mbr = code de la personne (valeur si ta = m)
  // i : identifiant de l'objet du document html ($iod)
  // r : code_erreur
  //     0 : pas d'erreur : mise a jour effectuee
  //     99 : pas de mise a jour
  // v : valeur a afficher si erreur (valeur d'un champ du formulaire)
  // attention :
  // -
  // a faire :
  // -
  // ==========================================================================
  
// ----------------------------------------------------------------------------
set_include_path('./../../');

include 'php/utilitaires/controle_session.php';
include 'php/utilitaires/definir_locale.php';

  // --- connection a la base de donnees
  include 'php/bdd/base_donnees.php';
  
  $bdd = Base_Donnees::acces();
  
  require_once 'php/metier/calendrier.php';
  require_once 'php/metier/club.php';
  require_once 'php/bdd/enregistrement_club.php';
  
  require_once 'php/bdd/enregistrement_membre.php';
  require_once 'php/metier/membre.php';

require_once 'php/metier/connexion.php';
require_once 'php/bdd/enregistrement_connexion.php';

  $ok = true;
  
  // --------------------------------------------------------------------------
  // --- recuperation des parametres de l'appel du script
  //     et verification
  
  $code = 0;
  $action = '';
  $mot_passe = "";
  if (isset($_GET['a'])) {
    if ($_GET['a'] == 'm') {
      $action = 'm';
      $code = intval($_GET['mbr']);
    } elseif ($_GET['a'] == 'c') {
      $action = 'c';
      $code = Enregistrement_Membre::generer_nouveau_code();
    } else {
      die("code action incorrect " . $_GET['a']);
    }
  } else {
    die("action non definie ");
  }
  
  if (isset($_GET['o'])) {
    $objet = $_GET['o'];
  } else {
    die("objet cible de l'action non defini");
  }
  
  $membre = new Membre($code);
  $enreg_membre = new Enregistrement_Membre();
  $enreg_membre->def_membre($membre);
  
$cnx = new Connexion($code);
$membre->def_connexion($cnx);
$enreg_cnx = new Enregistrement_Connexion();
$enreg_cnx->def_connexion($cnx);

  if ($action == 'm') {
    try {
      $enreg_membre->lire();
    } catch (Erreur_Membre_introuvable $e) {
      die("Pas trouve " . $code);
    }
  } elseif ($action == 'c') {
    $mot_passe = Enregistrement_Membre::generer_mot_passe();
    $cnx->def_mot_de_passe($mot_passe);
    if ($objet == 'n')
      $membre->initialiser_debutant(); // def_niveau(Membre::NIVEAU_DEBUTANT);
  }
  
  // parametre de l'appel de la page du formulaire.
  $params = "a=m&o=" . $objet;
  // --------------------------------------------------------------------------
  // --- recuperation des informations saisies dans le formulaire
  //     et verification
  
  // Verification identifiant correct et unique (saisie obligatoire)
  
  $iod = 'id';
  if (isset($_POST[$iod])) {
    $x = trim($_POST[$iod]);
    $nCar1 = strlen($x);
    $x = strip_tags($x);
    $nCar2 = strlen($x);
    if ($nCar2 != $nCar1) {
      // tres louche => on deconnecte l'utilisateur
      include 'php/scripts/deconnexion.php';
    }
    $erreur = 0;
    if ($nCar2 < 3) {
      $erreur = 1;
    } elseif (!$enreg_cnx->verifier_identifiant_unique($x)) {
      // le nouvel identifiant saisi correspond a celui d'un autre membre
      // or il doit etre unique
      $erreur = 2;
    } elseif (Enregistrement_Club::tester_existe($x)) {
      // l'identifiant saisi correspond a un identifiant de club
      // ce n'est pas possible vu la procedure de controle de l'identifiant lors
      // de la connexion (cf. identification_verif.php)
      $erreur = 3;
    }
    
    if ($erreur > 0) {
      header("location: ../../membre.php?a=m&o=" . $objet . "&mbr=" . $code . "&r=" . $erreur . "&i=" . $iod . "&v=" . $x);
      exit();
    }
    $cnx->def_identifiant($x);
  }
  
  // --------------------------------------------------------------------------
  // Controle de la valeur saisie pour le genre (= civilite = sexe)
  // saisie obligatoire. Utile pour accords en genre et (eventuellement) pour competition
  
  $x = '';
  $iod = 'gnr';
  $erreur = 0;
  if (isset($_POST[$iod])) {
    $x = strtoupper(trim($_POST['gnr']));
    $x = strip_tags($x);
    if (!preg_match("#^(F|H)$#", $x)) {
      $erreur = 1;
      header("location: ../../membre.php?a=m&o=" . $objet . "&mbr=" . $code . "&r=" . $erreur . "&i=" . $iod . "&v=" . $x);
      exit();
    }
    $membre->genre = $x;
  }
  
  // --------------------------------------------------------------------------
  // Controle de la valeur saisie pour le prenom
  
  $iod = 'prn';
  if (isset($_POST[$iod])) {
    //$x = trim(utf8_decode($_POST[$iod]));
    $x = trim($_POST[$iod]);
    $nCar1 = strlen($x);
    $x = strip_tags($x);
    $nCar2 = strlen($x);
    if ($nCar2 != $nCar1) {
      // tres louche => on deconnecte l'utilisateur
      include 'php/scripts/deconnexion.php';
    }
    $erreur = 0;
    if (strlen($x) < 2) $erreur = 1;
    elseif (!preg_match("#^[a-zA-Zéèëçñìïü\ '-]+$#", $x)) $erreur = 2;
    if ($erreur > 0) {
      header("location: ../../membre.php?a=m&o=" . $objet . "&mbr=" . $code . "&r=" . $erreur . "&i=" . $iod . "&v=" . $x);
      exit();
    }
    $membre->def_prenom($x);
  }
  
  // --------------------------------------------------------------------------
  // Controle de la valeur saisie pour le nom (saisie obligatoire)
  $iod = 'nom';
  if (isset($_POST[$iod])) {
    //$x = trim(utf8_decode($_POST[$iod]));
    $x = trim($_POST[$iod]);
    $nCar1 = strlen($x);
    $x = strip_tags($x);
    $nCar2 = strlen($x);
    if ($nCar2 != $nCar1) {
      // tres louche => on deconnecte l'utilisateur
      include 'php/scripts/deconnexion.php';
    }
    $erreur = 0;
    if (strlen($x) < 2) $erreur = 1;
    elseif (!preg_match("#^[a-zA-Zéèëçñìïü\ '-]+$#", $x)) $erreur = 2;
    if ($erreur > 0) {
      header("location: ../../membre.php?a=m&o=" . $objet . "&mbr=" . $code . " &r=" . $erreur . "&i=" . $iod . "&v=" . $x);
      exit();
    }
    $membre->def_nom($x);
  }
  
  // --------------------------------------------------------------------------
  // Controle de la valeur saisie pour le telephone (saisie non obligatoire)
  
  $iod = 'tel';
  if (isset($_POST[$iod])) {
    $x = trim($_POST[$iod]);
    $nCar1 = strlen($x);
    $x = strip_tags($x);
    $nCar2 = strlen($x);
    if ($nCar2 != $nCar1) {
      // tres louche => on deconnecte l'utilisateur
      include 'php/scripts/deconnexion.php';
    }
    $erreur = 0;
    if ($nCar2 > 0 && !preg_match("#^0[1-9]([-. ]?[0-9]{2}){4}$#", $x)) {
      $erreur = 1;
      header("location: ../../membre.php?a=m&o=" . $objet . "&mbr=" . $code . " &r=" . $erreur . "&i=" . $iod . "&v=" . $x);
      exit();
    }
    $membre->telephone = $x;
  }
  
  // --------------------------------------------------------------------------
  // Controle de la valeur saisie pour l'adresse courriel (saisie non obligatoire)
  $iod = 'courriel';
  if (isset($_POST[$iod])) {
    $x = trim($_POST[$iod]);
    $nCar1 = strlen($x);
    $x = strip_tags($x);
    $nCar2 = strlen($x);
    if ($nCar2 != $nCar1) {
      // tres louche => on deconnecte l'utilisateur
      include 'php/scripts/deconnexion.php';
    }
    if ($nCar2 > 0 && !preg_match("#^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$#", $x)) {
      $erreur = 1;
      header("location: ../../membre.php?a=m&o=" . $objet . "&mbr=" . $code . " &r=" . $erreur . "&i=" . $iod . "&v=" . $x);
      exit();
    }
    $membre->courriel = $x;
  }
  
 
  // --------------------------------------------------------------------------
  // Verification du code de la commune de residence (saisie non obligatoire)
  
  $iod = 'cmn';
  if (isset($_POST[$iod])) {
    $membre->code_commune = intval($_POST[$iod]);
  }
  
  $iod = 'nais';
  if (isset($_POST[$iod])) {
    $x = trim(strip_tags($_POST[$iod]));
    if (strlen($x))
      $membre->date_naissance = new Instant($x); //Calendrier::obtenir()->def_depuis_date_sql($x);
    else
      $membre->date_naissance = null;
  }
  
  $iod = 'lic';
  if (isset($_POST[$iod])) {
    $membre->num_licence = strip_tags($_POST[$iod]);
  }
  
  // Niveau
$niveau = isset($_POST['niv']) ? intval($_POST['niv']) : $membre->niveau();
$membre->def_niveau($niveau);
  
  // chef de bord ?
  $est_cdb = isset($_POST['cdb']) ? $_POST['cdb'] : $membre->est_chef_de_bord();
  $membre->def_est_chef_de_bord($est_cdb);
  
  // coherence  debutant / chef de bord
  if ($membre->est_debutant()) $membre->def_est_chef_de_bord(false);
  
  // Enregistrement des nouvelles donnees personnelles
  
  $mise_a_jour = false;
  if ($ok) {
    if ($action == 'm')
      $mise_a_jour = $enreg_membre->modifier();
    elseif ($action == 'c') {
      $mise_a_jour = $enreg_membre->ajouter();
      if ($mise_a_jour)
        $enreg_cnx->modifier_mot_de_passe(); // TODO: else...
    }

    /*
    // --- La photo ---------------------------------------------------------------
    $nom_fichier_photo = basename($_FILES["fichierPhoto"]["name"]);
    $photo_ok = (strlen($nom_fichier_photo) > 0);
    if ($photo_ok) {
      $target_dir = "../photos/membres/";
      $tmp_dir = $target_dir . "tmp/";
      $uploaded_file = $tmp_dir . basename($_FILES["fichierPhoto"]["name"]);
      $imageFileType = pathinfo($uploaded_file, PATHINFO_EXTENSION);
      // Check if image file is a actual image or fake image
      $check = getimagesize($_FILES["fichierPhoto"]["tmp_name"]);
      if ($check === false) {
        $photo_ok = false;
        header("location: page_membre_donnees.php?err=p1&usr=" . $p->code);
        exit();
      }
      
      // Check file size
      if ($photo_ok && $_FILES["fichierPhoto"]["size"] > 500000) {
        $photo_ok = false;
        header("location: page_membre_donnees.php?err=p2&usr=" . $p->code);
        exit();
      }
      
      // Allow certain file formats
      if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
          && $imageFileType != "gif" ) {
        $photo_ok = false;
        header("location: page_membre_donnees.php?err=p3&usr=" . $p->code);
        exit();
      }
      
      // Le nouveau fichier photo a l'air correct.
      // Recherche s'il y avait deja un fichier photo pour cette personne.
      
      $ancien = $p->code . ".jpg";
      $ancien = $target_dir . $ancien;
      if (file_exists($ancien)) unlink($ancien);
      // suppression de la vignette
      $ancien = $p->code . "_vignette.jpg";
      $ancien = $target_dir . $ancien;
      if (file_exists($ancien)) unlink($ancien);
      
      // if everything is ok, try to upload file
      if ($photo_ok && move_uploaded_file($_FILES["fichierPhoto"]["tmp_name"], $uploaded_file)) {
        //echo "The file ". basename( $_FILES["fichierPhoto"]["name"]). " has been uploaded.";
        $fichier_photo_tmp = $p->code . "_tmp." . $imageFileType;
        $chemin_fichier_photo_tmp = $tmp_dir . $fichier_photo_tmp;
        rename($uploaded_file, $chemin_fichier_photo_tmp);
        
        // creation du fichier au format jpeg
        if (($imageFileType == "jpg") || ($imageFileType == "jpeg"))
          $photo = imagecreatefromjpeg($chemin_fichier_photo_tmp);
        elseif ($imageFileType == "png")
        $photo = imagecreatefrompng($chemin_fichier_photo_tmp);
        elseif ($imageFileType == "gif")
        $photo = imagecreatefromgif($chemin_fichier_photo_tmp);
        
        $fichier_photo = $p->code . ".jpg";
        $chemin_fichier_photo = $target_dir . $fichier_photo;
        
        list($width, $height) = getimagesize($chemin_fichier_photo_tmp);
        $percent = ($height > 100)? (100 / $height): 1;
        $offset_w = max(0, 100 - $width) / 2;
        $offset_h = max(0, 100 - $height) / 2;
        
        $new_width = $width * $percent;
        $new_height = $height * $percent;
        $image_h = 100;
        $image_w = 100 * $width / $height;
        $photo_finale = imagecreatetruecolor($image_w, $image_h);
        //$photo_finale = imagecreatetruecolor($new_width, $new_height);
        if ($width < 100 || $height < 100) {
          $couleur_fond = imagecolorallocate($photo_finale, 255, 255, 255);
          //imagefill($photo_finale, 0, 0, $couleur_fond);
        }
        imagecopyresampled($photo_finale, $photo, $offset_w, $offset_h, 0, 0, $new_width, $new_height, $width, $height);
        imagejpeg($photo_finale, $chemin_fichier_photo);
        
        //echo "The file ". $chemin_fichier_photo. " has been created.";
        
        // creation de la vignette
        //list($width, $height) = getimagesize($chemin_fichier_photo);
        $percent = 50 / $height;
        $new_width = $width * $percent;
        $new_height = $height * $percent;
        $vignette = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($vignette, $photo, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        $fichier_vignette = $p->code . "_vignette.jpg";
        $chemin_fichier_vignette = $target_dir . $fichier_vignette;
        imagejpeg($vignette, $chemin_fichier_vignette);
        //echo "The file ". $chemin_fichier_vignette. " has been created.";
        
        // suppression fichier temporaires et images
        if (file_exists($uploaded_file)) unlink($uploaded_file);
        if (file_exists($chemin_fichier_photo_tmp)) unlink($chemin_fichier_photo_tmp);
        imagedestroy($photo);
        imagedestroy($vignette);
        
      } else {
        header("location: page_membre_donnees.php?err=p4&usr=" . $p->code);
        exit();
      }
     */
    }
    
  // --- les donnees ont bien ete mises a jour
  //$ta = (isset($_POST['ta'])) ? ("&ta=" . $_POST['ta']) : "";
 
  //echo '<p>code fin ajout' . $code . '</a>';
  if (!$mise_a_jour)
    header("location: ../../membre.php?" . $params . "&r=99&mbr=" . $code);
  else
    header("location: ../../membre.php?" . $params . "&r=0&mbr=" . $code);
  exit();
// ============================================================================
?>
