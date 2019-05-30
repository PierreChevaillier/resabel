/* ------------------------------------------------------------------------
 * contexte : Resabel - systeme de REServation de Bateaux En Ligne
 * description : generation et controle de la valeur de l'identifiant de
 *               connexion des utilisateurs
 * copyright (c) 2018-2019 AMP. Tous droits reserves.
 * --------------------------------------------------------------------------
 * utilisation : javascript (formulaire)
 * dependences : formulaire_membre.php
 * creation: 09-mai-2019 pchevaillier@gmail.com
 * revision:
 * ----------------------------------------------------------------------------
 * commentaires : 
 * attention : 
 * a faire :
 * - des tests et des tests
 * ajouter l'instruction window.onload ... (cf. formulaire_membre.php)
 * ----------------------------------------------------------------------------
 */

function initialiser_id(champ_id, champ_prn, champ_nom) {
  identifiant = champ_prn.value.toLowerCase() + "." + champ_nom.value.toLowerCase();
  identifiant = identifiant.replace(/é/g, "e");
  identifiant = identifiant.replace(/è/g, "e");
  identifiant = identifiant.replace(/ë/g, "e");
  identifiant = identifiant.replace(/ì/g, "i");
  identifiant = identifiant.replace(/ô/g, "o");
  identifiant = identifiant.replace(/ç/g, "c");
  identifiant = identifiant.replace(/ñ/g, "n");
  identifiant = identifiant.replace(/'/g, "");
  identifiant = identifiant.replace(/\ /g, "");
  champ_id.value = identifiant;
}

function verifier_identifiant(element) {
  supprimer_message_erreur(element);
  var est_correct = false;
  var x = element.value;
  if (x == null || x == "") {
    est_correct = true;
  } else {
    var regExpr = /^[a-zA-Z.-]+$/;
    if (!regExpr.test(x)) {
      element.style.color = "red";
      element.focus();
    } else {
      est_correct = true;
    }
  }
  if (est_correct) {
    element.style.color = "black";
  }
  return est_correct;
}


// Concue pour etre appele au chargement de la page
function creer_gestionnaire_evenement(id_id, id_prn, id_nom) {
  var champ_id = document.getElementById(id_id);
  champ_id.addEventListener("change", function() { verifier_identifiant(champ_id); });
  
  var champ_prenom = document.getElementById(id_prn);
  var champ_nom = document.getElementById(id_nom);
  champ_prenom.addEventListener("change", function() { initialiser_id(champ_id, champ_prenom, champ_nom); });
  champ_nom.addEventListener("change", function() { initialiser_id(champ_id, champ_prenom, champ_nom); });
  return true;
  
}
