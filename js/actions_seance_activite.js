// ============================================================================
// contexte : Resabel - systeme de REServAtion de Bateau En Ligne
// description : controle des actions sur une seance d'activite
// Copyright (c) 2017-2023 AMP. Tous droits reserves.
// ----------------------------------------------------------------------------
// utilisation : javascript - controleur action element page web
// teste avec  : firefox, safari sur Mac OS 10.14,
//               jQuery 3.3.1
// dependances : JQuery, JQuery-ui
//               ids des elements
//               bootstrap : boutons et grid
//               scripts PHP (cote serveur)
// ----------------------------------------------------------------------------
// creation : 13-avr-2020 pchevaillier@gmail.com
// revision : 17-feb-2023 pchevaillier@gmail.com + changement horaire seance
// revision : 29-mar-2023 pchevaillier@gmail.com utilisation XMLHttpRequest
// revision : 25-jan-2024 pchevaillier@gmail.com + changement support activite
// ----------------------------------------------------------------------------
// commentaires :
// - en evolution
// attention :
// a faire :
// - suppprimer les logs.
// ----------------------------------------------------------------------------

// tentative 20-apr-2020 - peut servir d'exemple de code
/*
function creer_gestionnaire_evenement(page) {
  // retrouver les elements sensibles / classe cel_seance
  var cellules_seance = document.getElementsByClassName("cel_seance");
  //cellules_seance.forEach( (cellule) => {  id = cellule.id; console.log(page +  ' ' +  id);} );
  
  for (let cellule of cellules_seance) {
    id = cellule.id;
    console.log(page +  ' ' +  id);
    //ajouter 1 eventListener qui indique de (re)charger la page a l'endroit de id_element
    //cellule.addEventListener('Fini', function (e) { console.log(id + 'a recu fini'); document.location.href=page + '#' + id;}, false );
    cellule.addEventListener('Fini', function (e) { console.log(id + 'a recu fini'); location.reload();}, false );
  }
  return true;
}
 */

function activer_formulaire(code_seance, code_site, code_support, debut, fin, id_contexte_saisie, rang) {
  //const id_contexte_saisie = code_support + '_' + debut + '_' + rang;
  const id_champ_nom = "nom_part"; //+ id_contexte_saisie;
  const id_champ_code = "code_part"; //+ id_contexte_saisie;
  
  //alert("activer_formulaire() de action_seance_activite.js : code_support : " + code_support + " debut  " + debut + " rang " + rang);
  let formulaire = document.createElement("form");
  
  let champ_nom = document.createElement("input");
  champ_nom.id = id_champ_nom;
  champ_nom.focus();
  formulaire.autocomplete = "off";
  formulaire.appendChild(champ_nom);
  
  let champ_code = document.createElement("input");
  champ_code.id = id_champ_code;
  champ_code.hidden = true;
  formulaire.appendChild(champ_code);
  
  let conteneur = document.createElement('div');
  conteneur.classList.add("container")
  formulaire.appendChild(conteneur);
  let ligne = document.createElement('div');
  ligne.classList.add("row");
  conteneur.appendChild(ligne);
  let cellule_bnt1 = document.createElement('div');
  cellule_bnt1.classList.add("col-sm");
  cellule_bnt1.style.textAlign = 'center';
  ligne.appendChild(cellule_bnt1);
  
  let bouton = document.createElement("button");
  bouton.type = "submit";
  bouton.classList.add("btn", "btn-success", "btn-sm");
  bouton.innerHTML = "Ok";
  var params = code_seance + ", " + code_site + ", " + code_support
    + ", '" + debut + "', '" + fin + "', '" + id_contexte_saisie + "', " + rang;
  console.log("params " + params);
  bouton.setAttribute("onclick", "fin_saisie_participant(" + params + ");");
  cellule_bnt1.appendChild(bouton);
  
  let cellule_bnt2 = document.createElement('div');
  cellule_bnt2.classList.add("col-sm");
  cellule_bnt2.style.textAlign = 'center';
  ligne.appendChild(cellule_bnt2);
  
  let abandon = document.createElement("button");
  abandon.type = "cancel";
  abandon.classList.add("btn", "btn-warning", "btn-sm");
  abandon.innerHTML = "Annul.";
  abandon.setAttribute("onclick", "annuler_saisie_participant('" + id_contexte_saisie + "');");
  cellule_bnt2.appendChild(abandon);

  
  parent = document.getElementById(id_contexte_saisie);
  console.log("id_contexte_saisie : " + id_contexte_saisie);
  
  parent.appendChild(formulaire);
  
  const responsable = (rang == 0)? 1: 0;
  const envoi = {deb: debut, resp: responsable};
  var possibilites = [];
  
  var xmlhttp = new XMLHttpRequest();
  var url = "php/scripts/membres_dispo_collecter.php?";
  params = new URLSearchParams(envoi).toString();
  url += params;
  console.log(url);
  
  var ok = false;
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        console.log("chargement membres disponibles: ok");
        var dict = JSON.parse(this.responseText);
        for (var entree in dict) {
          valeur = dict[entree];
          p = JSON.parse(valeur);
          console.log(p);
          possibilites.push(p);
        }
        $( "#nom_part" ).autocomplete({ minLength: 2,
                                        source: possibilites,
                                        focus: function( event, ui ) {
                                          $( "#nom_part" ).val( ui.item.label );
                                          return false;
                                        },
                                       select: function( event, ui ) {
                                                $( "#nom_part" ).val( ui.item.label );
                                                $( "#code_part" ).val( ui.item.value );
                                                return false;
                                              }
                                            });
        ok = true;
      }
    };
  
  xmlhttp.open('GET', url, true);
  xmlhttp.send();
  console.log("request sent");
  return ok;
  }

function fin_saisie_participant(code_seance, code_site, code_support, debut, fin, id_contexte_saisie, rang) {
  console.log("fin_saisie_participant "  + id_contexte_saisie);
  let parent = document.getElementById(id_contexte_saisie);

  // valeur saisie
  champ_nom = document.getElementById("nom_part"); // + id_contexte_saisie);
  champ_code = document.getElementById("code_part"); // + id_contexte_saisie);
  let nom_participant = champ_nom.value;
  let code_personne = champ_code.value;
  console.log(code_personne, " ", nom_participant);
  
  // Suppression du formulaire de saisie
  while (parent.firstChild) {
    parent.removeChild(parent.firstChild);
  }
  
  // texte pour affichage nom du participant
  let texte = document.createElement("span");
  parent.appendChild(texte);

  if (code_personne == 0) {
    texte.innerHTML = "&nbsp;";
    return false;
  }
  
  // Enregistrement de la nouvelle participation dans la base de donnees
  const code_action = 'ii';
  const responsable = (rang == 0) ? 1: 0;
  console.log(code_personne, " ", nom_participant, " resp ", responsable);
  envoi = {act: code_action, id: code_seance, sa: code_site, s: code_support, deb: debut, fin: fin, p: code_personne, resp: responsable};
  
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
  /*
  var jqxhr = $.getJSON("php/scripts/inscription_seance_activite_maj.php?", envoi, function(retour) {
                        console.log( "success inscription_seance_activite_maj" );
                        texte.innerHTML = nom_participant;
                        $.each( retour, function(cle, valeur) {
                              status = JSON.parse(valeur);
                               console.log(cle + " " + status);
                               }
                              )
                       
                        location.reload(); // necessaire pour prendre en compte nouveau contexte
                        return true;
                        })
                      .fail(function(retour) {
                            console.log("error inscription_seance_activite_maj.php");
                            texte.innerHTML = "Erreur";
                            //$("#statut_requete_json").html("<p>Oups: ça ce marche pas...</p>");
                            return false;
                            });
  
  return true;
*/
}

// ----------------------------------------------------------------------------
function annuler_saisie_participant(id_contexte_saisie) {
  console.log("annulation saisie participation " + id_contexte_saisie);
  let parent = document.getElementById(id_contexte_saisie);
  // Suppression du formulaire de saisie
  while (parent.firstChild) {
    parent.removeChild(parent.firstChild);
  }
  location.reload();
  return true;
}

// ----------------------------------------------------------------------------
function activer_controle_annulation_seance(code_seance, modal_id, html_info_seance, html_info_partipations, html_mailto) {
  
  const titre_modal = document.getElementById(modal_id + "_titre");
  const corps_modal = document.getElementById(modal_id + "_corps");
  const bouton_modal = document.getElementById(modal_id + "_btn");
  
  titre_modal.innerHTML = "Annulation séance";
  html_corps = '<div class="alert alert-warning" role="alert">Opération irréversible !<br />Ne pas oublier de prévenir les personnes intéressées.</div>';
  html_corps = html_corps + '<div class="card"><div class="card-body"><p>' + html_info_seance + '</p><p>' + html_info_partipations + '</p></div></div>';
  html_corps = html_corps + '<div><button type="button" class="btn w-100 mb-2 rounded-3 btn-outline-primary"><a href="' + html_mailto + '">Envoyez un mail aux participants...</a></button></div>';
  html_corps = html_corps + '<div><button type="button" class="btn w-100 mb-2 rounded-3 btn-outline-danger" onclick="supprimer_seance_activite(' + code_seance + '); return false;">... puis Confirmer annulation</button></div>';
  corps_modal.innerHTML = "<div>" + html_corps + "</div>";
  bouton_modal.textContent = "Ne rien faire";
  bouton_modal.classList.add("btn-secondary");
  
  return true;
}

// ----------------------------------------------------------------------------
//function supprimer_seance_activite(code_seance, code_site, code_support, debut, fin, id_parent) {
function supprimer_seance_activite(code_seance) {
  const code_action = 'de'; // desinscription equipage
  const envoi = {act: code_action, id: code_seance};
//  alert("demande annulation séance " + code_seance);
//  return;
  var jqxhr = $.getJSON("php/scripts/inscription_seance_activite_maj.php?", envoi, function(retour) {
                         console.log( "success inscription_seance_activite_maj - suppression seance" );
                         location.reload(); // necessaire pour prendre en compte nouveau contexte
                         return true;
                         })
                       .fail(function(retour) {
                             console.log("error inscription_seance_activite_maj.php - suppression seance");
                             //$("#statut_requete_json").html("<p>Oups: ça ce marche pas...</p>");
                             return false;
                             });
  return true;
}

// ----------------------------------------------------------------------------
// Changement d'horaire d'une seance, meme equipage, meme support

// Activation de la boite modale pour confirmer la realisation de l'action
function activer_controle_changer_horaire_seance(code_seance, modal_id, html_info_seance, html_info_partipations, html_mailto, debut_nouveau_creneau, fin_nouveau_creneau) {
  
  const titre_modal = document.getElementById(modal_id + "_titre");
  const corps_modal = document.getElementById(modal_id + "_corps");
  const bouton_modal = document.getElementById(modal_id + "_btn");
  
  titre_modal.innerHTML = "Changement horaire séance";
  html_corps = '<div class="alert alert-warning" role="alert">Opération irréversible !<br />Ne pas oublier de prévenir les personnes intéressées.</div>';
  html_corps = html_corps + '<div class="card"><div class="card-body"><p>' + html_info_seance + '</p><p>' + html_info_partipations + '</p></div></div>';
  html_corps = html_corps + '<div><button type="button" class="btn w-100 mb-2 rounded-3 btn-outline-primary"><a href="' + html_mailto + '">Envoyez un mail aux participants</a></button></div>';
  html_corps = html_corps + '<div><button type="button" class="btn w-100 mb-2 rounded-3 btn-primary" onclick="modifier_horaire_seance_activite(' + code_seance
    + ', \'' + debut_nouveau_creneau
    + '\', \'' + fin_nouveau_creneau
    + '\'); return false;">Confirmer changement horaire</button></div>';
  corps_modal.innerHTML = "<div>" + html_corps + "</div>";
  
  bouton_modal.textContent = "Ne rien faire";
  bouton_modal.classList.add("btn-secondary");
  return true;
}

// envoi la requete de modification au serveur pour mise a jour dans base de donnees
function modifier_horaire_seance_activite(code_seance, debut_nouveau, fin_nouveau) {
  const code_action = 'mc'; // modification creneau
  const envoi = {act: code_action, id: code_seance, deb: debut_nouveau, fin: fin_nouveau};
  console.log(envoi);
  
  var xmlhttp = new XMLHttpRequest();
  var url = "php/scripts/inscription_seance_activite_maj.php?";
  const params = new URLSearchParams(envoi).toString();
  url += params;
  console.log(url);
  
  var ok = false;
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        console.log("changement horaire seance : ok");
        location.reload(); // necessaire pour prendre en compte nouveau contexte
        ok = true;
      }
    };
  
  xmlhttp.open('GET', url, true);
  xmlhttp.send();
  console.log("request sent");
  return ok;
  /*
  var jqxhr = $.getJSON("php/scripts/inscription_seance_activite_maj.php?", envoi, function(retour) {
                         console.log("success inscription_seance_activite_maj - modification creneau");
                         location.reload(); // necessaire pour prendre en compte nouveau contexte
                         return true;
                         })
                       .fail(function(retour) {
                             console.log("error inscription_seance_activite_maj.php - modification creneau ");
                             return false;
                             });
  return true;
   */
}

// ----------------------------------------------------------------------------
// Changement de support d'activite pour une seance, meme equipage, meme horaire

// Activation de la boite modale pour choisir le nouveau support d'activite
function activer_controle_changer_support_seance(code_seance,
                                                 code_site,
                                                 code_support,
                                                 debut_creneau,
                                                 fin_creneau,
                                                 modal_id,
                                                 html_info_seance,
                                                 html_info_partipations,
                                                 html_mailto) {
  
  const titre_modal = document.getElementById(modal_id + "_titre");
  const corps_modal = document.getElementById(modal_id + "_corps");
  const bouton_modal = document.getElementById(modal_id + "_btn");
  
  titre_modal.innerHTML = "Changement Support d'activité";
  html_corps = '<div class="alert alert-warning" role="alert">Opération irréversible !<br />Ne pas oublier de prévenir les personnes intéressées.</div>';
  html_corps = html_corps + '<div class="card"><div class="card-body"><p>' + html_info_seance + '</p><p>' + html_info_partipations + '</p></div></div>';
  html_corps = html_corps + '<div><button type="button" class="btn w-100 mb-2 rounded-3 btn-outline-primary"><a href="'
    + html_mailto + '">Envoyez un mail aux participants</a></button></div>';
  bouton_modal.textContent = "Ne rien faire";
  
  const envoi = {id: code_seance, sa: code_site};
  console.log(envoi);
  
  var xmlhttp = new XMLHttpRequest();
  var url = "php/scripts/supports_activite_dispo_collecter.php?";
  const params = new URLSearchParams(envoi).toString();
  url += params;
  console.log(url);
  
  var ok = false;
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      console.log("recherche autres supports seance : retour");
      // les boutons pour choisir le nouveau support
      var dict = JSON.parse(this.responseText);
      for (var entree in dict) {
        valeur = dict[entree];
        p = JSON.parse(valeur);
        console.log(entree + " >> " + p['value']);
        html_corps = html_corps + '<div><button type="button" class="btn w-100 mb-2 rounded-3 btn-primary" onclick="modifier_support_seance_activite(' + code_site
          + ', ' + code_seance
          + ', ' + entree
          + '); return false;">Passer sur ' + p['value'] + '</button></div>';
      }
      //console.log(html_corps);
      corps_modal.innerHTML = "<div>" + html_corps + "</div>";

      ok = true;
    }
  };
  
  xmlhttp.open('GET', url, true);
  xmlhttp.send();
  console.log("request sent");
  return ok;
}

function modifier_support_seance_activite(code_site,
                                          code_seance,
                                          code_nouveau_support) {
  const code_action = 'msa'; // modification support activite
  const envoi = {act: code_action, sa: code_site, id: code_seance, s: code_nouveau_support};
  console.log(envoi);
  
  var xmlhttp = new XMLHttpRequest();
  var url = "php/scripts/inscription_seance_activite_maj.php?";
  const params = new URLSearchParams(envoi).toString();
  url += params;
  console.log(url);
  
  var ok = false;
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      console.log("changement support seance : ok");
      var dict = JSON.parse(this.responseText);
      for (var entree in dict) {
        valeur = dict[entree];
        console.log(entree + " >> " + valeur);
      }
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
