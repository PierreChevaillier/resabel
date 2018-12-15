<?php
  // ===========================================================================
  // description: verifie les informations de connexion
  //              - identification de l'utilisateur pour la session
  // contexte   : resabel
  // copyright (c) 2014-2018 AMP. All rights reserved
  // ---------------------------------------------------------------------------
  // creation: 01-oct-2014 pchevaillier@gmail.com
  // revision: 12-oct-2014 pchevaillier@gmail.com affichage menu
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
  // ---------------------------------------------------------------------------
  // commentaires :
  // - en chantier V2 - pas completement teste
  // - les informations de connexion sont stockees dans $_SESSION
  // attention :
  // a faire :
  // ===========================================================================
  session_start(); // doit etre la premiere instruction

  unset($_SESSION['login']);
  unset($_SESSION['cdb']);
  unset($_SESSION['club']);
  unset($_SESSION['perm']);
  unset($_SESSION['admin']);
  unset($_SESSION['actif']);
  
  // --- recuperation des informations saisies dans le formulaire
  if (!isset($_POST['id']) || !isset($_POST['mdp_crypte']))
  exit();

  $identifiant = stripslashes(trim(utf8_decode($_POST['id'])));
  //$identifiant = strip_tags(strtolower($identifiant));
  $motdepasse = stripslashes(trim($_POST['mdp_crypte']));
  
  $erreur_identification = (strlen($identifiant) === 0);
  if ($erreur_identification) {
    header("location: ../../index.php?err=id");
    exit();
  }
  $erreur_mot_passe = false;

  // --------------------------------------------------------------------------
  // --- classes utilisees
  set_include_path('./../../');
  
  require_once 'php/metier/club.php';
  require_once 'php/bdd/enregistrement_club.php';
  
  //require_once 'php/jour.php';
  require_once 'php/bdd/enregistrement_personne.php';
  require_once 'php/metier/personne.php';
  //  require_once 'php/permanence.php';
  
  // --------------------------------------------------------------------------
  // --- connection a la base de donnees
  include 'php/bdd/base_donnees.php';
  
  $bdd = Base_donnees::accede();

  // ==========================================================================
  // --- verification de l'identite :
  // l'utilisateur doit etre un membre du club reference,
  // ou connection en tant que 'club'
  
  // Test si connection de type 'club'
  $club = new Club(0);
  $club->identifiant = $identifiant;
  $enreg_club = new Enregistrement_Club();
  $enreg_club->def_club($club);
  $session_club = false;
  try {
    $session_club = $enreg_club->verifier_identite($motdepasse);
  } catch (Erreur_Mot_Passe_Club $e) {
    // l'identifaint est bien celui du club, mais ce n'est le bon mot de passe
    header("location: ../../index.php?err=mdp");
    exit();
  } catch (Erreur_Club_Introuvable $e) {
    $session_club = false;
  }
  
  if ($session_club) {
    $_SESSION['utilisateur'] = utf8_encode($club->nom());
    $_SESSION['club'] = $club->identifiant;
    header("location: page_tableau_journalier_sorties.php?ta=os");
    exit();
  }
  
  // ---------------------------------------------------------------------------
  // l'identifiant de connexion n'est pas celui du club :
  // donc recherche si l'identifiant correspond
  // une personne referencee et autorisee a se connecter
  
  $personne = new Personne("");
  $personne->identifiant = $identifiant;

  $enreg_personne = new Enregistrement_Personne();
  $enreg_personne->def_personne($personne);
  $session_personne = false;
  try {
    $session_personne = $enreg_personne->verifier_identite($motdepasse);
  } catch (Erreur_Mot_Passe_Personne $e) {
    header("location: ../../index.php?err=mdp");
    exit();
  } catch (Erreur_Personne_Introuvable $e) {
    header("location: ../../index.php?err=cnx");
    exit();
  }
  
  if ($session_personne) {
    if (!$personne->est_autorisee_connecter()) {
      // Personne non autorisee a se connecter
      header("location: ../../index.php?err=cnx");
      exit();
    }
    // utilisateur reference et autorise a se connecter
    $utilisateur = $personne->prenom . " " . $personne->nom;
    $_SESSION['login'] = $personne->code();
    $_SESSION['utilisateur'] = $utilisateur;
    $_SESSION['cdb'] = $personne->est_chef_de_bord();
    $_SESSION['active'] = $personne->est_active(); // active = possibilite de s'inscrire
  
    $enreg_personne->modifier_date_derniere_connexion();
    
    // Teste si l'utilisateur est de permanence ou pas
    $permanence = false;
    /*
     Permanence::cette_semaine($perm);
     $permanence = $perm->a_comme_responsable($p);
     */
     if ($permanence)
      $_SESSION['perm'] = true;
     else
     unset($_SESSION['perm']);
  
    // Teste si l'utilisateur est administrateur du systeme d'information
    $admin = $enreg_personne->recherche_si_admin();
    if ($admin)
      $_SESSION['admin'] = true;
    else
      unset($_SESSION['admin']);
    echo "Admin: " . $admin;
  }
  
  // --------------------------------------------------------------------------
  // --- Affichage contextuel en fonction du resultat de l'identification
  //     cas d'une identification personnelle (cas du club traite plus haut)
  if ($erreur_identification)
    header("location: ../../index.php?err=cnx");
  elseif ($erreur_mot_passe)
    header("location: ../../index.php?err=mdp");
  elseif (!$_SESSION['active'])
    header("location: page_tableau_journalier_sorties.php?ta=os&d=" . Jour::aujourdhui()->jour);
  elseif ($permanence) {
    $j0 = date("N");
    $j = max(6, $j0);
    $jperm = mktime(0, 0, 0, date("m"), date("d") + $j - $j0, date("Y"));
    header("location: page_tableau_journalier_sorties.php?ta=os&d=" . $jperm);
  } else {
//    header("location: ../../page_inscription_individuelle.php");
    header("location: ../../page_temporaire.php");
  }
  exit();
  // ==========================================================================
  ?>
