// ============================================================================
// contexte    : Resabel V2
// description : controle des actions sur une seance d'activite
// utilisation : javascript - controleur action element page web
// teste avec  : firefox, safari sur Mac OS 10.14,
//               jQuery 3.3.1
// dependances : JQuery
//               ids des elements
//               bootstrap : boutons et grid
// Copyright (c) 2017-2020 AMP. Tous droits reserves
// ----------------------------------------------------------------------------
// creation : 13-avr-2020 pchevaillier@gmail.com
// revision :
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
  params = code_seance + ", " + code_site + ", " + code_support
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
  
  let responsable = (rang == 0)? 1: 0;
  envoi = {deb: debut, resp: responsable};
  var possibilites = [];
  $.getJSON("php/scripts/membres_dispo_collecter.php?", envoi, function(retour) {
            console.log( "success membres_dispo_collecter" );
            $.each( retour, function(cle, valeur) {
                   p = JSON.parse(valeur);
                   //console.log(p);
                   possibilites.push(p);
                 })
            })
           .fail(function(retour) {
                 console.log("error membres_dispo_collecter");
                 //$("#statut_requete_json").html("<p>Oups: ça ce marche pas...</p>");
                 return false;
                 });
  /*
  possibilites = [ {value: "1", label: "Adèle Gamby"},{value: "111", label: "Catherine Bocher"},{value: "17000", label: "Catherine Bruneau"},{value: "18020", label: "Catherine Causeur"},{value: "16", label: "Catherine Lagadec "},{value: "112", label: "Catherine Lamour"},{value: "17", label: "Cathou Viollette"},{value: "18030", label: "Céline Gueneugues"},
  {value: "18", label: "Céline Prince"},{value: "152", label: "Christian Lannuzel"},{value: "20", label: "Christian Venot"},{value: "16039", label: "Christine Arsant"},{value: "22", label: "Christophe Perrochon"},{value: "23", label: "Claude Langlois"},{value: "115", label: "Claudie Dubrana"},{value: "24", label: "Claudine Lassée "},
  {value: "18037", label: "Corinne Paillet"},{value: "26", label: "David Banks"},{value: "117", label: "Denis Creach"},{value: "137", label: "Marcelline Calzas"},{value: "17031", label: "Marie Boeuf"},
  {value: "17039", label: "Marie Aude Pina Silas"},{value: "67", label: "Marie-Christine Robelet"},{value: "1521", label: "Marie-Françoise Kermaidic"},{value: "68", label: "Marie-Josée Le Beux"},{value: "9009", label: "Marie-No Guével"},{value: "69", label: "Marie-Noelle Bénard"},{value: "138", label: "Marine Lombard"},{value: "18011", label: "Marion Lorzil"},{value: "17044", label: "Maryse Pichon"},{value: "101", label: "Pierre Chevaillier"} ];
  */
  
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
  let code_action = 'ii';
  let responsable = (rang == 0) ? 1: 0;
  console.log(code_personne, " ", nom_participant, " resp ", responsable);
  envoi = {act: code_action, id: code_seance, sa: code_site, s: code_support, deb: debut, fin: fin, p: code_personne, resp: responsable};
  
  var jqxhr = $.getJSON("php/scripts/inscription_seance_activite_maj.php?", envoi, function(retour) {
                        console.log( "success inscription_seance_activite_maj" );
                        texte.innerHTML = nom_participant;
                        $.each( retour, function(cle, valeur) {
                              status = JSON.parse(valeur);
                               console.log(cle + " " + status);
                               }
                              )
                        /*
                        let handler_id = code_support + '_' + debut;
                        var handler = document.getElementById(code_support + '_' + debut);
                        console.log("handler " + handler_id);
                        handler.dispatchEvent(new Event('Fini'));
                         */
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
function activer_controle_annulation_seance(code_seance, id_modal, html_info_seance, html_info_partipations, html_mailto) {
  $("#" + id_modal + "_titre").html("Annulation séance");
  html_corps = '<div class="alert alert-warning" role="alert">Opération irréversible !<br />Ne pas oublier de prévenir les personnes intéressées.</div>';
  html_corps = html_corps + '<div class="card"><div class="card-body"><p>' + html_info_seance + '</p><p>' + html_info_partipations + '</p></div></div>';
  html_corps = html_corps + '<div><button type="button" class="btn btn-outline-primary"><a href="' + html_mailto + '">Envoyez un mail aux participants</a></button></div>';
  html_corps = html_corps + '<div><button type="button" class="btn btn-primary" onclick="supprimer_seance_activite(' + code_seance + '); return false;">Confirmer annulation</button></div>';
  $("#" + id_modal + "_corps").html("<div>" + html_corps + "</div>");
  
  $("#" + id_modal + "_btn").html("Ne rien faire");

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



// ============================================================================
