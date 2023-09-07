// ============================================================================
// contexte : Resabel - systeme de REServation de Bateaux En Ligne
//            controle actions utilisateurice - cote client
// description : requete serveur pour modifier l'etat actif (on non) d'un support d'activite
// Copyright (c) 2017-2023 AMP. Tous droits reserves
// ----------------------------------------------------------------------------
// utilisation : javascript - controleur action element page web
// dependances :
// - modal.php (de Resabel) : ids des elements du composant
// - vue_support_activite.php : operation afficher_actions de Menu_Actions_Membre
// - nom du script execute par le serveur et code_action
// - bootstrap (class du bouton)
// ----------------------------------------------------------------------------
// creation : 29-aug-2020 pchevaillier@gmail.com
// revision : 30-mar-2023 pchevaillier@gmail.com jQuery > XMLHttpRequest
// ----------------------------------------------------------------------------
// commentaires :
// attention :
// a faire :
// ----------------------------------------------------------------------------

function afficher_maj_support_actif(modal_id, reponse) {
  var ok = true;
  const titre_modal = document.getElementById(modal_id + "_titre");
  const corps_modal = document.getElementById(modal_id + "_corps");
  const bouton_modal = document.getElementById(modal_id + "_btn");
  
  titre_modal.textContent = "Modification état support activités";
  var code_html = "<div>";
  var items = [];
  var dict = JSON.parse(reponse);
  for (var entree in dict) {
    valeur = dict[entree];
    //console.log("JSON retour : " + entree + " =>" + valeur);
    items.push(valeur + "<br />");
  }
  code_html += items.join("");
  corps_modal.innerHTML = code_html + "</div>";
  bouton_modal.textContent = "Fermer";
  bouton_modal.classList.add("btn-success");
  bouton_modal.addEventListener("click", function() { location.reload(); });
  return ok;
}

  
function requete_maj_support_actif(code_support, nouveau_statut, modal_id) {
  const envoi = {code: code_support, statut: nouveau_statut};

  var xmlhttp = new XMLHttpRequest();
  var url = "php/scripts/support_activite_actif_maj.php?";
  const params = new URLSearchParams(envoi).toString();
  url += params;
  
  var ok = false;
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        ok = afficher_maj_support_actif(modal_id, this.responseText);
      }
    };
  
  xmlhttp.open('GET', url, true);
  xmlhttp.send();
  return ok;
}

// ============================================================================
