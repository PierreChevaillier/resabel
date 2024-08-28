<?php
/* ============================================================================
 * Resabel - systeme de REServAtion de Bateau En Ligne
 * Copyright (C) 2024 Pierre Chevaillier
 * contact: pchevaillier@gmail.com 70 allee de Broceliande, 29200 Brest, France
 *
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
 * description : traitement requete / activation - desactivation
 *               du compte d'un membre (connexion)
 * utilisation : php - traitement XMLHttpRequest
 * dependances :
 * - script qui lance cette requete : requete_maj_statut_cnx.js
 * ----------------------------------------------------------------------------
 * creation : 28-aug-2024 pchevaillier@gmail.com
 * revision :
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
 * a faire :
 * -
 * ============================================================================
 */
set_include_path('./../../');
  
include('php/utilitaires/controle_session.php');
include('php/utilitaires/definir_locale.php');

// --- connexion a la base de donnees (et instantiation du 'handler')
include_once 'php/bdd/base_donnees.php';
  
// --- classes utilisees
require_once 'php/metier/membre.php';
require_once 'php/bdd/enregistrement_membre.php';
require_once 'php/metier/connexion.php';
require_once 'php/bdd/enregistrement_connexion.php';
// ----------------------------------------------------------------------------

// --- informations fournies dans la requete

// Le code de la personne
$code = (isset($_GET['code'])) ? $_GET['code'] : 0;

// le nouveau statut de connexion
if (isset($_GET['cnx']) && preg_match('/[01]/', $_GET['cnx']))
  $nouveau_statut = $_GET['cnx'];
else
  die();

// --- Recherche des informations sur la personne correspondante
//     dans la base de donnees
//     On en a besoin pour afficher ensuite son identite
$personne = new Membre($code);
$enreg_membre = new Enregistrement_Membre();
$enreg_membre->def_membre($personne);

$trouve = $enreg_membre->lire();

// --- Modification des informations dans la base de donnees
if (!$trouve) {
    $donnee = array('code' => $code, 'err' => 'membre introuvable');
} else {
  $connexion = new Connexion($code);
  $enreg_connexion = new Enregistrement_Connexion();
  $enreg_connexion->def_connexion($connexion);
  
  $enreg_connexion->activer_compte($nouveau_statut);
  
  // Informations utiles pour identifier la personne
  $civil = $personne->civilite();
  $donnee[] = "Le compte de " . $civil . " " . $personne->prenom . " " . $personne->nom;
  if ($nouveau_statut == 1)
    $donnee[] = "est maintenant actif";
  else
    $donnee[] = "n'est maintenant plus actif";
}

// ----------------------------------------------------------------------------
// --- Reponse a la requete
$resultat_json = json_encode($donnee);
echo $resultat_json;
exit();
  
// ============================================================================
?>
