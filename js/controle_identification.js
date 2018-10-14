/* ------------------------------------------------------------------------
 * contexte : Resabel - systeme de REServation de Bateaux En Ligne
 * description : cryptage du mot de passe saisi
 * copyright (c) 2014-2018 AMP. Tous droits réserves.
 * --------------------------------------------------------------------------
 * utilisation : javascript
 * creation: 26-dec-2014 pchevaillier@gmail.com
 * revision: 10-jul-2016 pchevaillier@gmail.com motdepasse_crypte
 * revision: 14-oct-2018 pchevaillier@gmail.com ids elts en parametre
 * ------------------------------------------------------------------------
 * commentaires : 
 * attention : 
 * a faire :
 * - des tests et des tests
 * -----------------------------------------------------------------------
 */

function crypte_mdp(elt) {
  id_crypte = elt.id + "_crypte";
  elt_crypte = document.getElementById(id_crypte)
  elt_crypte.value = calcMD5(elt.value);
  return true;
}

/*
function cryptage(id_mdp, id_crypte) {
  var elt_mdp = document.getElementById(id_mdp)
  var mdp = elt_mdp.value;
	var mdp_crypte = calcMD5(mdp);
  var elt_crypte = document.getElementById(id_crypte)
	elt_crypte.value = mdp_crypte;
  return true;
}
*/
