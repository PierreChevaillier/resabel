<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServation de Bateaux En Ligne
  // description: verifie les informations de connexion
  //              - identification de l'utilisateur pour la session
  // copyright (c) 2014-2020 AMP. Tous droits reserves.
  // ---------------------------------------------------------------------------
  // creation: 01-oct-2014 pchevaillier@gmail.com
  // revision: 08-dec-2014 pchevaillier@gmail.com verification Id utilisateur
  // revision: 26-dec-2014 pchevaillier@gmail.com identification et mot de passe
  // revision: 26-jan-2015 pchevaillier@gmail.com chef de bord ou pas
  // revision: 16-fev-2015 pchevaillier@gmail.com retour sur page login si erreur
  // revision: 19-fev-2015 pchevaillier@gmail.com connexion club ou perso
  // revision: 30-avr-2015 pchevaillier@gmail.com de permanence ou pas
  // revision: 16-mai-2015 pchevaillier@gmail.com test admin et id en minuscule
  // revision: 21-mai-2015 pchevaillier@gmail.com termine si $_POST pas definie
  // revision: 02-jun-2015 pchevaillier@gmail.com restriction membres actifs
  // revision: 25-jul-2017 pchevaillier@gmail.com version 2 - utilisation PDO
  // revision: 06-oct-2018 pchevaillier@gmail.com reorganisation + nveau formulaire
  // revision: 10-mar-2019 pchevaillier@gmail.com logique identification club
  // revision: 30-dec-2019 pchevaillier@gmail.com liens vers pages d'accueil
  // ---------------------------------------------------------------------------
  // commentaires :
  // - en chantier V2 - pas completement teste
  // - les informations de connexion sont stockees dans $_SESSION
  // attention :
  // a faire :
  // recuperer le code et le nom du club, meme si session 'prs'
  // ===========================================================================
  session_start(); // doit etre la premiere instruction

  // Les variables suivantes sont utilisees a partir d'ici
  // Variables toujours definies
  unset($_SESSION['clb']);    // code du club
  unset($_SESSION['id_clb']); // identifiant du club
  unset($_SESSION['n_clb']);  // nom du club p.ex. AMP
  
  unset($_SESSION['prs']); // set (=true) si session 'perso' unset : session 'club'
  
  // les variables suivantes ne sont definies que si session 'perso'
  unset($_SESSION['usr']);   // si session perso: code membre personne connectee
  unset($_SESSION['n_usr']); // si session perso: code usage membre personne connectee
 
  unset($_SESSION['act']); // personne connectee active (cf. classe Personne)
  unset($_SESSION['cdb']); // personne connectee est chef de bord
  unset($_SESSION['prm']); // personne connectee est de permanence
  unset($_SESSION['adm']); // personne connectee est administatrice de resabel
  
  if (isset($_GET['c'])) {
    $code_club =  $_GET['c'];
    $_SESSION['clb'] = $code_club;
  } else {
    header("location: ../../index.php");
    exit();
  }
  
  if (isset($_GET['n_clb']))
    $_SESSION['n_clb'] = $_GET['n_clb'];
  
  if (isset($_GET['s'])) {
    $code_site = $_GET['s'];
    $_SESSION['swb'] = $code_site;
  } else {
    header("location: ../../index.php");
    exit();
  }
  
  // --- recuperation des informations saisies dans le formulaire
  if (!isset($_POST['id']) || !isset($_POST['mdp_crypte']))
    exit();

  //$identifiant = stripslashes(trim(utf8_decode($_POST['id'])));
  $identifiant = stripslashes(trim($_POST['id']));
  //$identifiant = strtolower($identifiant);
  // peut etre un identifiant de club (maj et minuscules sont conserves)
  // ou de personne (ou passe alors l'identifiant en minuscules (cf plus loin)
  $motdepasse = stripslashes(trim($_POST['mdp_crypte']));
  
  $erreur_identification = (strlen($identifiant) === 0);
  if ($erreur_identification) {
    header("location: ../../connexion.php?err=id&c=" . $code_club . "&s=" . $code_site);
    exit();
  }
  $erreur_mot_passe = false;
  // --------------------------------------------------------------------------
  // --- classes utilisees
  set_include_path('./../../');
  
  // --------------------------------------------------------------------------
  // --- connection a la base de donnees
  include 'php/bdd/base_donnees.php';
  
  $bdd = Base_donnees::accede();

  require_once 'php/metier/club.php';
  require_once 'php/bdd/enregistrement_club.php';
  
  //require_once 'php/jour.php';
  require_once 'php/bdd/enregistrement_membre.php';
  require_once 'php/metier/membre.php';
  //  require_once 'php/permanence.php';
  
  // ==========================================================================
  // --- verification de l'identite :
  // l'utilisateur doit etre un membre du club reference,
  // ou connection en tant que 'club'
  
  // Test si connection de type 'club' avec bon identifiant et mot de passe du club
  $club = new Club($code_club);
  //$club->identifiant = $identifiant;
  $enreg_club = new Enregistrement_Club();
  $enreg_club->def_club($club);
  $session_club = false;
  
  try {
    $session_club = $enreg_club->verifier_identite($identifiant, $motdepasse);
  } catch (Erreur_Mot_Passe_Club $e) {
    // l'identifiant est bien celui du club, mais ce n'est le bon mot de passe
    header("location: ../../connexion.php?err=mdp&c=" . $code_club . "&s=" . $code_site);
    exit();
  } catch (Erreur_Identifiant_Club $e) {
    $session_club = false;
    // pas d'erreur a ce stade : l'ientifiant est peut-etre celui d'une personne
  } catch (Erreur_Club_Introuvable $e) {
    header("location: ../../connexion.php?err=id&c=" . $code_club . "&s=" . $code_site);
    exit();
  }
  $_SESSION['id_clb'] = $club->sigle();
  
  if ($session_club) {
    if (!isset($_GET['clb'])) {
      $_SESSION['clb'] = $club->code();
      $_SESSION['n_clb'] = $club->nom();
    }
    //header("location: ../../page_tableau_journalier_sorties.php?ta=os");
    header("location: ../../accueil_club.php");
    exit();
  }
  
  // ---------------------------------------------------------------------------
  // l'identifiant de connexion n'est pas celui du club :
  // donc recherche si l'identifiant correspond a un membre du club
  // autorise a se connecter
  
  $membre = new Membre(0);
  $membre->identifiant = strtolower($identifiant);

  $enreg_membre = new Enregistrement_Membre();
  $enreg_membre->def_membre($membre);
  $session_personne = false;
  try {
    $session_personne = $enreg_membre->verifier_identite($motdepasse);
  } catch (Erreur_Mot_Passe_Membre $e) {
    header("location: ../../connexion.php?err=mdp&c=" . $code_club . "&s=" . $code_site);
    exit();
  } catch (Erreur_Membre_Introuvable $e) {
    header("location: ../../connexion.php?err=cnx&c=" . $code_club . "&s=" . $code_site);
    exit();
  }
  
  if ($session_personne) {
    $_SESSION['prs'] = true;
    if (!$membre->est_autorise_connecter()) {
      // Personne non autorisee a se connecter
      header("location: ../../connexion.php?err=cnx&c=" . $code_club . "&s=" . $code_site);
      exit();
    }
    // utilisateur reference et autorise a se connecter
    $utilisateur = $membre->prenom . " " . $membre->nom;
    $_SESSION['usr'] = $membre->code();
    $_SESSION['n_usr'] = $utilisateur;
    $_SESSION['cdb'] = $membre->est_chef_de_bord();
    $_SESSION['act'] = $membre->est_actif(); // active = possibilite de s'inscrire
  
    $enreg_membre->modifier_date_derniere_connexion();
    
    // Teste si l'utilisateur est de permanence ou pas
    $permanence = false;
    /*
     Permanence::cette_semaine($perm);
     $permanence = $perm->a_comme_responsable($p);
     */
     if ($permanence)
      $_SESSION['prm'] = true;
  
    // Teste si l'utilisateur est administrateur du systeme d'information
    $admin = $enreg_membre->recherche_si_admin();
    if ($admin)
      $_SESSION['adm'] = true;
    //echo "Admin: " . $admin;
  }
  
  // --------------------------------------------------------------------------
  // --- Affichage contextuel en fonction du resultat de l'identification
  //     cas d'une identification personnelle (cas du club traite plus haut)
 
  if ($erreur_identification)
    header("location: ../../connexion.php?err=cnx&c=" . $code_club . "&s=" . $code_site);
  elseif ($erreur_mot_passe)
    header("location: ../../connexion.php?err=mdp&c=" . $code_club . "&s=" . $code_site);
  elseif (!$_SESSION['act']) {
    header("location: ../../accueil_perso.php");
   // header("location: ../../page_tableau_journalier_sorties.php?ta=os&d=" . Jour::aujourdhui()->jour);
  } elseif ($permanence) {
    header("location: ../../accueil_perso.php");
    /*
    $j0 = date("N");
    $j = max(6, $j0);
    $jperm = mktime(0, 0, 0, date("m"), date("d") + $j - $j0, date("Y"));
    header("location: ../../page_tableau_journalier_sorties.php?ta=os&d=" . $jperm);
     */
  } else {
    header("location: ../../accueil_perso.php");
  }
  exit();
  // ==========================================================================
  ?>
