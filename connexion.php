<?php
/* ============================================================================
 * Resabel - systeme de REServAtion de Bateau En Ligne
 * Copyright (C) 2024 Pierre Chevaillier
 * contact: pchevaillier@gmail.com 70 allee de Broceliande, 29200 Brest, France
 * ----------------------------------------------------------------------------
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License,
 * or any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 * ----------------------------------------------------------------------------
 * description : page de connexion
 * utilisation : php include <chemin_vers_ce_fichier.php>
 *               premiere instruction sur toutes les pages (ou scripts)
 *               sauf celle de connexion
 *               ainsi que le script de verification de l'identite
 * dependances :
 * - $_GET['s']
 * ----------------------------------------------------------------------------
 * creation : 17-jan-2018 pchevaillier@gmail.com resabel V2
 * revision : 06-oct-2018 pchevaillier@gmail.com formulaire
 * revision : 18-apr-2025 pchevaillier@gmail.com parametrage cookie et id session
 * ----------------------------------------------------------------------------
 * commentaires :
 * - partiellement inspire par une solution trouvee sur
 * https://blog.crea-troyes.fr/1542/comment-securiser-une-session-php-efficacement/
 * attention :
 * -
 * a faire :
 * -
 * ============================================================================
 */
set_include_path('./');
include('php/utilitaires/definir_locale.php');

session_start();
if (!isset($_SESSION)) {
  session_set_cookie_params([
      'httponly' => true,
      'secure' => true,
  ]);
  /*
   d'apres : https://blog.crea-troyes.fr/1542/comment-securiser-une-session-php-efficacement/
  ‘httponly’ => true :
   Ce paramètre indique que le cookie de session ne peut être accédé que par le protocole HTTP,
   et donc pas par JavaScript.
   Cela renforce la sécurité en empêchant les attaques telles que le vol de session via des scripts côté client.
  ‘secure’ => true : Ce paramètre indique que le cookie de session ne sera envoyé
   que sur une connexion sécurisée HTTPS.
   Cela signifie que le cookie ne sera pas envoyé sur des connexions non sécurisées HTTP,
   ce qui protège les données de session contre l’interception lors de la transmission sur le réseau.
  */
  /*
   d'apres : https://blog.crea-troyes.fr/1542/comment-securiser-une-session-php-efficacement/
   */
  $sessionId = bin2hex(random_bytes(32));
  session_id($sessionId);
}
 ?>
<!DOCTYPE html>
  <html lang="fr">
    <?php
      
      // --- connection a la base de donnees
      include 'php/bdd/base_donnees.php';
      
      // --- Information sur le site Web
      require_once 'php/bdd/enregistrement_site_web.php';
      
      if (!isset($_GET['s']))
        die("erreur : valeur non definie");
      if (preg_match('/[0-9]/', $_GET['s']))
        new Enregistrement_site_web($_GET['s']);
      else
        die("erreur : valeur invalide");
    
      // --- Classe definissant la page a afficher
      require_once 'php/pages/page_connexion.php';

      // --- Classes des elements de la page
      require_once 'php/elements_page/generiques/element.php';

      // ----------------------------------------------------------------------
      // --- Creation dynamique de la page
      
      $feuilles_style = array();
      $feuilles_style[] = "css/resabel_ecran.css";
      $nom_site = Site_Web::accede()->sigle() . " Resabel";
      $page = new Page_Connexion($nom_site, "connexion", $feuilles_style);
      
/*
      $info = new Element_Code();
      $info->def_code('<div class="alert alert-warning" role="alert">version de développement incomplète</div>');
      $page->ajoute_contenu($info);
*/

      $info = new Element_Code();
      $code_html = "<div class=\"alert alert-info\" role=\"alert\">Vous devez vous identifez pour accéder à ce service</div>";
      $info->def_code($code_html);
      $page->ajoute_contenu($info);
      
      // --- Affichage de la page
      $page->initialiser();
      $page->afficher();
      // ======================================================================
    ?>
  </html>
