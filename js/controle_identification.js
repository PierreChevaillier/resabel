/* ------------------------------------------------------------------------
 * description: fonctions pour la verification des informations de connexion
 * contexte   : systeme d'information de l'AMP
 * ------------------------------------------------------------------------
 * creation: 26-dec-2014 pchevaillier@gmail.com
 * revision: 10-jul-2016  pchevaillier@gmail.com motdepasse_crypte
 * ------------------------------------------------------------------------
 * commentaires : 
 * attention : 
 * a faire :
 * - des tests et des tests
 * -----------------------------------------------------------------------
 */
 
function cryptage() {
  var mdp = document.identification.motdepasse_saisi.value;
	mdp=calcMD5(mdp);
	//document.identification.motdepasse_saisi.value = mdp;
	document.identification.motdepasse_crypte.value = mdp;
}
