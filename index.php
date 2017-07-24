<!doctype html>
<html lang="fr">
  <?php
    require_once 'php/site_web.php';
    
    $sw = new Site_web("AMP - Resabel");
    $sw->initialiser();
    $sigle_club = $sw->sigle_proprietaire();
    ?>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="css/resabel_ecran.css" />
    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script language="javascript" src="js/md5.js"></script>
    <script language="javascript" src="js/control_identification.js"></script>
    <title><?php echo $sigle_club; ?> - Accès à resabel</title>
  </head>
  <body>
    <div class="container-fluid">
      <div class="jumbotron">
        <h1><?php echo $sigle_club; ?></h1>
        <p>Inscription pour les Sorties en Mer</p>
      </div>
      <div class="not_mobile">
        <p>Ce service n'est accessible qu'aux membres du club, vous devez vous connecter à l'aide de votre identifiant.</p>
      </div>
      <div class="panel panel-warning">
        <div class="panel-heading">Ré-inscription en septembre</div>
        <div class="panel-body">
          <p>Pensez à prendre rendez-vous avec un médecin pour obtenir un <strong>certificat médical</strong>.
            N'oubliez pas la mention "compétition" si vous êtes susceptible,
            même juste une fois dans la saison, de participer à une compétition.</p>
        </div>
      </div>

      <?php
        if (isset($_GET['err']) && ($_GET['err'] == 'act'))
          echo "<p class=\"text-danger\">Connexion impossible : votre compte a été désactivé</p>";
        if (isset($_GET['err']) && ($_GET['err'] == 'cnx'))
          echo "<p class=\"text-danger\">Connexion impossible avec cet identifiant</p>";
        ?>
      <center>
      <form role="form" name="identification" method="post" action="identification_membre_verif.php">
        <fieldset class="identification">
          <div class="form-group">
            <label for="identifiant">Identifiant</label>
            <input id="identifiant" type="text" class="form-control" name="identifiant_saisi" required autofocus/>
            <?php
              if (isset($_GET['err']) && ($_GET['err'] == 'id'))
                echo "<p class=\"text-danger\">Erreur: identifiant inconnu</p>";
              //echo "<span id=\"erreur_identifiant\" style=\"color:red\">erreur: identifiant inconnu</span>";
              ?>
          </div>
          <div class="form-group">
            <label for="mdp">Mot de passe</label>
            <input id="mdp" type="password" class="form-control" name="motdepasse_saisi" required/>
            <?php
              if (isset($_GET['err']) && ($_GET['err'] == 'mp'))
                echo "<p class=\"text-danger\">Erreur: mot de passe incorrect</p>";
              ?>
          </div>
          <input type="hidden" id="mdp_crypte" name="motdepasse_crypte" >
          <input class="btn btn-primary" type="submit" onClick="cryptage()" value="Connexion">
        </fieldset>
      </form>
      </center>
      <?php
        require_once 'php/pied_page.php';
        $pp = new Pied_Page();
        $pp->initialiser();
        $pp->afficher();
        //afficher_pied_de_page();
        ?>
    </div>
  </body>
</html>
