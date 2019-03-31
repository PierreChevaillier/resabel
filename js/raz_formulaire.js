/* ------------------------------------------------------------------------
 * contexte : Resabel - systeme de REServation de Bateaux En Ligne
 * description : remise a zero des valeurs de tous les champs d'un formulaire
 * copyright (c) 2018-2019 AMP. Tous droits reserves.
 * --------------------------------------------------------------------------
 * utilisation : javascript (formulaire)
 * dependences :
 * creation: 29-mar-2019 pchevaillier@gmail.com
 * revision:
 * ----------------------------------------------------------------------------
 * commentaires : 
 * attention : 
 * a faire :
 * - des tests et des tests
 * ----------------------------------------------------------------------------
 */

function raz_valeurs_formulaire(form) {
  var champs = document.getElementById(form.id).getElementsByClassName("form-control");
  for (let i in champs) {
    //console.log(champs[i].id + " " + champs[i].value);
    champs[i].value = "";
  }
  return true;
}
