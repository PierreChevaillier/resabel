// ============================================================================
// contexte : Resabel - systeme de REServAtion de Bateau En Ligne
//            controle actions utilisateurice - cote client
// description : requete au serveur modification inscription seance d'activite
// Copyright (c) 2017-2023 AMP. Tous droits reserves
// ----------------------------------------------------------------------------
// utilisation : javascript - controleur action element page web
// dependances :
// - modal.php (de Resabel) : ids des elements du composant
// - codes ation
// - script execute par le serveur = nom et reponse
// - code_action
// - bootstrap (class du bouton)
// ----------------------------------------------------------------------------
// creation : 29-fev-2020 pchevaillier@gmail.com
// revision : 08-mar-2020 pchevaillier@gmail.com
// revision : 23-mar-2023 pchevaillier@gmail.com XMLHttpRequest au lieu de jQuery
// ----------------------------------------------------------------------------
// commentaires :
// actions supportees :
// - ii : inscription individuelle
// - di : desinscription individuelle
// -  modification role seance (responsable <->  equipier)
// attention :
// a faire :
// - traiter le code de retour de facon a indiquer que l'action s'est mal passee
// - suppprimer les logs.
// ----------------------------------------------------------------------------

//function activer_controle_annulation_participation(modal_id, code_seance, code_site, code_support, debut, fin, code_personne, responsable, code_action) {
function activer_controle_annulation_participation(modal_id, code_seance, code_site, code_support, debut, fin, code_personne, responsable, code_action, html_info_seance, html_info_partipation, html_mailto) {
  const titre_modal = document.getElementById(modal_id + "_titre");
  const corps_modal = document.getElementById(modal_id + "_corps");
  const bouton_modal = document.getElementById(modal_id + "_btn");
  
  titre_modal.innerHTML = "Annulation participation séance";
  html_corps = '<div class="alert alert-warning" role="alert">Opération irréversible !<br />Ne pas oublier de prévenir la personne concernée.</div>';
  html_corps = html_corps + '<div class="card"><div class="card-body"><p>' + html_info_seance + '</p><p>' + html_info_partipation + '</p></div></div>';
  html_corps = html_corps + '<div><button type="button" class="btn w-100 mb-2 rounded-3 btn-outline-primary"><a href="' + html_mailto + '">Envoyez un mail</a></button></div>';
  code_action = 'di';
  html_action  = 'requete_inscription_individuelle('
    + '\'' + modal_id
    + '\', ' + code_seance
    + ', ' + code_site
  + ', ' + code_support
  + ', \'' + debut
  + '\', \'' + fin
  + '\', ' + code_personne
  + ', ' + responsable
  + ', \'' +  code_action
  + '\'); return false;';
  //console.log(html_action);
  html_corps = html_corps + '<div><button type="button" class="btn w-100 mb-2 rounded-3 btn-danger" onclick="' + html_action + '">Confirmer annulation</button></div>';
  corps_modal.innerHTML = "<div>" + html_corps + "</div>";
  bouton_modal.textContent = "Ne rien faire";
  bouton_modal.classList.add("btn-secondary");

  return true;
}

function afficher_retour_inscription(modal_id, code_action, reponse) {
  var ok = false;
  
  const modal = document.getElementById(modal_id);
  const titre_modal = document.getElementById(modal_id + "_titre");
  const corps_modal = document.getElementById(modal_id + "_corps");
  const bouton_modal = document.getElementById(modal_id + "_btn");
  
  var dict = JSON.parse(reponse);
  for (var entree in dict) {
    valeur = dict[entree];
    //console.log("JSON retour : " + entree + " =>" + valeur);
    
    switch (entree) {
      case 'status':
        ok = (valeur === 1);
        if (!ok) {
          console.log("pas bon");
          titre_modal.textContent = "Echec opération";
          if ((valeur === 7) && (code_action == "ii")) {
            // autres codes erreur sont pour debug
            // (cf. Enregistrement_Seance_Activite::ajouter_participation)
            corps_modal.textContent = "Déjà inscrite sur le même créneau horaire";
          } else {
            corps_modal.textContent = ""; // efface le contenu initial
          }
          bouton_modal.classList.add("btn-danger");
        } else {
          if (code_action == "ii")
            titre_modal.textContent = "Inscription à une séance";
          else if (code_action == "di")
            titre_modal.textContent = "Annulation inscription";
          else
            titre_modal.textContent = "ERREUR : Type operation inconnu...";
          corps_modal.textContent = "Opération réalisée avec succès";
          bouton_modal.classList.add("btn-success");
        }
        bouton_modal.textContent = "Fermer";
        bouton_modal.addEventListener("click", function() { location.reload(); });
        break;
    }
  }
  return ok;
}

function requete_inscription_individuelle(modal_id, code_seance, code_site, code_support, debut, fin, code_personne, responsable, code_action) {

  const envoi = {act: code_action, id: code_seance, sa: code_site, s: code_support, deb: debut, fin: fin, p: code_personne, resp: responsable};
  console.log(envoi);
  
  var xmlhttp = new XMLHttpRequest();
  var url = "php/scripts/inscription_seance_activite_maj.php?";
  const params = new URLSearchParams(envoi).toString();
  url += params;
  console.log(url);
  
  var ok = false;
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        ok = afficher_retour_inscription(modal_id, code_action, this.responseText);
      }
    };
  
  xmlhttp.open('GET', url, true);
  xmlhttp.send();
  console.log("request sent");
  return ok;
}

// ----------------------------------------------------------------------------
function requete_changement_role_seance(code_seance, code_personne, code_action) {
  envoi = {act: code_action, id: code_seance,  p: code_personne};
  console.log(envoi);
  
  var xmlhttp = new XMLHttpRequest();
  var url = "php/scripts/inscription_seance_activite_maj.php?";
  const params = new URLSearchParams(envoi).toString();
  url += params;
  console.log(url);
  
  var ok = false;
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        console.log("changement role seance : ok");
        location.reload(); // necessaire pour prendre en compte nouveau contexte
        ok = true;
      }
    };
  
  xmlhttp.open('GET', url, true);
  xmlhttp.send();
  console.log("request sent");
  return ok;
  
}

// ============================================================================
