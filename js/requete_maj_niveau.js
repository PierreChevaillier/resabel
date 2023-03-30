// ============================================================================
// contexte : Resabel - systeme de REServation de Bateaux En Ligne
// description : requete serveur pour modifier le niveau de la personne
// Copyright (c) 2017-2023 AMP. Tous droits reserves
// ----------------------------------------------------------------------------
// utilisation : javascript - controleur action element page web
// dependances :
// - modal.php (de Resabel) : ids des elements du composant
// - nom du script execute par le serveur et code_action
// - page_personnes : id du formulaire de selection des personnes
// - bootstrap (class du bouton)
// ----------------------------------------------------------------------------
// creation : 05-mai-2019 pchevaillier@gmail.com
// revision : 30-mar-2023 pchevaillier@gmail.com jQuery > XMLHttpRequest
// ----------------------------------------------------------------------------
// commentaires :
// attention :
// a faire :
// ----------------------------------------------------------------------------

function requete_maj_niveau(item_menu, code_membre, nouveau_niveau, modal_id) {
  
  const envoi = {code: code_membre, niv: nouveau_niveau};
  
  var xmlhttp = new XMLHttpRequest();
  var url = "php/scripts/membre_niveau_maj.php?";
  const params = new URLSearchParams(envoi).toString();
  url += params;
  
  var ok = false;
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      const titre_modal = document.getElementById(modal_id + "_titre");
      const corps_modal = document.getElementById(modal_id + "_corps");
      const bouton_modal = document.getElementById(modal_id + "_btn");
      
      titre_modal.textContent = "Modification du niveau";
      
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
      // peut-etre plus aux personnes selectionnees
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
