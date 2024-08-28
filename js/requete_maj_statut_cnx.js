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
 * description : controle requete serveur pour modifier le statut de connexion de la personne
 * utilisation : javascript - controleur action element page web
 * dependances :
 * - modal.php (de Resabel) : ids des elements du composant
 * - nom du script execute par le serveur et code_action
 * - page_personnes.php : id du formulaire
 * - bootstrap (class du bouton)
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

function requete_maj_statut_cnx(item_menu, code_membre, nouveau_statut, modal_id) {
  
  const envoi = {code: code_membre, cnx: nouveau_statut};

  var xmlhttp = new XMLHttpRequest();
  var url = "php/scripts/membre_statut_cnx_maj.php?";
  const params = new URLSearchParams(envoi).toString();
  url += params;
  
  var ok = false;
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      const titre_modal = document.getElementById(modal_id + "_titre");
      const corps_modal = document.getElementById(modal_id + "_corps");
      const bouton_modal = document.getElementById(modal_id + "_btn");
      
      if (nouveau_statut == 1)
        titre_modal.textContent = "Réactivation du compte";
      else
        titre_modal.textContent = "Désactivation du compte";
      // le message affiche est construit du cote serveur
      // (infos sur la personne)
      // on ne fait ici qu'afficher les elements de son contenu
      var code_html = "<div>";
      var items = [];
      var dict = JSON.parse(this.responseText);
      for (var entree in dict) {
        valeur = dict[entree];
        //console.log("JSON retour : " + entree + " =>" + valeur);
        items.push(valeur + "<br />");
      }
      code_html += items.join("");
      code_html += "</div>";
      corps_modal.innerHTML = code_html;
      bouton_modal.textContent = "Fermer";
      bouton_modal.classList.add("btn-success");
      
      // Quand on modifie les proprietes d'une personne, elle ne correspond
      // sans doute plus aux personnes selectionnees
      // il faut donc soumettre a nouveau le formulaire de selection
      const formulaire = document.getElementById("form_sel_prs");
      bouton_modal.addEventListener("click", function() { formulaire.submit(); });
    }
  };
  
  xmlhttp.open('GET', url, true);
  xmlhttp.send();
  return ok;
}

// ============================================================================
