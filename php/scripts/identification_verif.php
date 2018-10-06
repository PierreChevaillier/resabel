<?php
  // ===========================================================================
  // description: verifie des informations de connexion
  //              - identification de l'utilisarteur pour la session
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
  // remplacer connexion.php par index.php
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
  $identifiant = strip_tags(strtolower($identifiant));
  $motdepasse = stripslashes(trim($_POST['mdp_crypte']));

  $erreur_identification = (strlen($identifiant) === 0);
  if ($erreur_identification) {
    header("location: ../../connexion.php?err=id");
    exit();
  }
  $erreur_mot_passe = false;

  // --------------------------------------------------------------------------
  // --- classes utilisees
  set_include_path('./../../');
  
  //require_once 'php/jour.php';
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
  $req_club = "SELECT identifiant, mot_passe, nom FROM club WHERE identifiant = '". $identifiant ."'";
  try {
    $res_club = $bdd->query($req_club);
    if ($res_club->rowCount() > 0) {
      $club = $res_club->fetch(PDO::FETCH_OBJ);
      if ($club->mot_passe != $motdepasse) {
        $erreur_mot_passe = true;
      } else {
        $utilisateur = utf8_encode($club->nom);
        $_SESSION['utilisateur'] = $utilisateur;
        $_SESSION['club'] = $club->identifiant;
      }
      $res_club->closeCursor();
    }
  } catch (PDOException  $e) {
    echo "Erreur requete sur la table club : ".$e;
    exit();
  }
  
  if ($erreur_mot_passe) {
    header("location: ../../connexion.php?err=mdp");
    exit();
  }
  
  if (isset($_SESSION['club'])) {
    unset($_SESSION['login']);
    header("location: page_tableau_journalier_sorties.php?ta=os");
    exit();
  }

  // ---------------------------------------------------------------------------
  // l'identifiant de connexion n'est pas celui du club :
  // donc recherche si l'identifiant correspond a un membre reference (et actif)
  
  $requete = "SELECT code, actif, identifiant, connexion, mot_passe, prenom, nom, cdb FROM membres WHERE identifiant = '". $identifiant . "'";
  //echo $requete;
  try {
    $resultat = $bdd->query($requete);
    $trouve = ($resultat->rowCount() > 0);
    if ($trouve) {
      $donnee = $resultat->fetch(PDO::FETCH_OBJ);
      if ($donnee->mot_passe != $motdepasse)
        $erreur_mot_passe = true;
      elseif ($donnee->connexion == 0) { // compte non loggable (visiteur)
        header("location: ../../index.php?err=cnx");
        exit();
      } else {
        $utilisateur = utf8_encode($donnee->prenom) . " " . utf8_encode($donnee->nom);
        $_SESSION['login'] = $donnee->code;
        $_SESSION['utilisateur'] = $utilisateur;
        $_SESSION['cdb'] = ($donnee->cdb == 1);
        $_SESSION['actif'] = ($donnee->actif == 1); // actif = possibilite de s'inscrire
       
        $p = new Personne($_SESSION['login']);
        
        // Teste si l'utilisateur est de permanence ou pas
      /*
       Permanence::cette_semaine($perm);
       $permanence = $perm->a_comme_responsable($p);
       if ($permanence)
        $_SESSION['perm'] = true;
       else
        unset($_SESSION['perm']);
       */
      
      // --- teste si l'utilisateur est administrateur du systeme d'information
        $admin = $p->recherche_si_admin();
        if ($admin)
          $_SESSION['admin'] = true;
        else
          unset($_SESSION['admin']);
      }
    } else {
      echo 'pas trouve ' . $identifiant;
      $erreur_identification = true;
    }
  } catch (PDOException  $e) {
    echo "Erreur - requete recherche membre par identifiant : ". $e;
  }
  /*
  if (!$erreur_mot_passe && !$erreur_identification) {
    $req_cnx = "UPDATE membres SET derniere_connexion = CURRENT_TIMESTAMP() WHERE code = '" . $_SESSION['login'] . "'";
    mysql_query($req_cnx) or die("erreur MàJ dernière cnx");
  }
   */

  // ---------------------------------------------------------------------------
  // --- Affichage contextuel en fonction du resultat de l'identification
  //     cas d'une identification personnelle (cas du club traite plus haut)
  if ($erreur_identification)
    header("location: ../../connexion.php?err=id");
  elseif ($erreur_mot_passe)
    header("location: ../../connexion.php?err=mdp");
  elseif (!$_SESSION['actif'])
    header("location: page_tableau_journalier_sorties.php?ta=os&d=" . Jour::aujourdhui()->jour);
  elseif ($permanence) {
    $j0 = date("N");
    $j = max(6, $j0);
    $jperm = mktime(0, 0, 0, date("m"), date("d") + $j - $j0, date("Y"));
    header("location: page_tableau_journalier_sorties.php?ta=os&d=" . $jperm);
  } else {
    header("location: page_inscription_individuelle.php");
  }
  exit();
?>
