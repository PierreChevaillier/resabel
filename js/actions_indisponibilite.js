/* ============================================================================
 * contexte : Resabel - systeme de REServation de Bateaux En Ligne
 *            controle actions utilisateurice - cote client
 * description : actions sur une Indisponibilite
 * copyright (c) 2018-2024 AMP. Tous droits reserves.
 * --------------------------------------------------------------------------
 * utilisation : javascript sur evenement
 * dependances :
 * - vue_indisponibilite.php (definit le declencheur du script)
 * utilise avec  :
 *  - firefox, safari sur macOS 13.x
 * ----------------------------------------------------------------------------
 * creation: 30-avr-2024 pchevaillier@gmail.com
 * revision:
 * ----------------------------------------------------------------------------
 * commentaires : 
 * attention : 
 * a faire :
 * ============================================================================
 */

// ----------------------------------------------------------------------------
function afficher_indisponibilite(code_indispo, type_indispo, modal_id, html_resume_indispo, html_details_indispo) {
  
  const titre_modal = document.getElementById(modal_id + "_titre");
  const corps_modal = document.getElementById(modal_id + "_corps");
  const bouton_modal = document.getElementById(modal_id + "_btn");
  
  if (type_indispo == 1)
    titre_modal.innerHTML = "Indisponibilité";
  else
    titre_modal.innerHTML = "Site fermé";
  html_corps = '<div class="card"><div class="card-body"><p>' + html_resume_indispo
    + '</p><p>' + html_details_indispo
    + '</p></div></div>';
  corps_modal.innerHTML = "<div>" + html_corps + "</div>";
  bouton_modal.textContent = "Retour";

  return true;
}

// ----------------------------------------------------------------------------
function activer_controle_suppression_indisponibilite(code_indispo, type_indispo, modal_id, html_resume_indispo) {
  
  const titre_modal = document.getElementById(modal_id + "_titre");
  const corps_modal = document.getElementById(modal_id + "_corps");
  const bouton_modal = document.getElementById(modal_id + "_btn");
  
  if (type_indispo == 1)
    titre_modal.innerHTML = "Suppression indisponibilité";
  else
    titre_modal.innerHTML = "Supression fermeture";
  html_corps = '<div class="alert alert-warning" role="alert">Opération irréversible !</div>';
  html_corps = html_corps + '<div class="card"><div class="card-body"><p>' + html_resume_indispo + '</p></div></div>';
  html_corps = html_corps + '<div><button type="button" class="btn btn-primary" onclick="supprimer_indisponibilite(' + code_indispo + ', ' + type_indispo + '); return false;">Confirmer suppression</button></div>';
  corps_modal.innerHTML = "<div>" + html_corps + "</div>";
  bouton_modal.textContent = "Ne rien faire";

  return true;
}

// ----------------------------------------------------------------------------
function supprimer_indisponibilite(code_indisponibilite, type_indispo) {
  const code_action = 's'; // suppression
  const envoi = {act: code_action, id: code_indisponibilite, typ: type_indispo};
  //console.log("demande suppression indisponibilite " + code_indisponibilite + ' type ' + type_indispo);
  
  var xmlhttp = new XMLHttpRequest();
  var url = "php/scripts/indisponibilite_maj.php?";
  const params = new URLSearchParams(envoi).toString();
  url += params;
  //console.log(url);
  
  var ok = false;
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        /*
        console.log("suppression indisponibilite : ok");
        var dict = JSON.parse(this.responseText);
        for (var entree in dict) {
          valeur = dict[entree];
          console.log(entree + " >> " + valeur);
        }
         */
        location.reload(); // pour prendre en compte nouveau contexte
        ok = true;
      }
    };
  
  xmlhttp.open('GET', url, true);
  xmlhttp.send();
  console.log("requete supression envoyee");
  return ok;
}

// ============================================================================
