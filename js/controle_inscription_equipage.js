/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 *            formulaire de saisie d'un equipage
 * description : controle saisies et requete au serveur modification inscription seance d'activite
 * Copyright (c) 2017-2024 AMP. Tous droits reserves
 * ----------------------------------------------------------------------------
 * utilisation : javascript - onload
 * dependances :
 * - modal.php (de Resabel) : ids des elements du composant
 * - Ids des elements de la page (cf. appels a document.getElementById)
 * - script execute par le serveur = nom et donnees envoyees
 * ----------------------------------------------------------------------------
 * creation : 31-jan-2024 pchevaillier@gmail.com
 * revision : 19-fev-2024 pchevaillier@gmail.com
 * ----------------------------------------------------------------------------http://localhost/resabel-v2/resabel/activites.php?a=ii&j=2024-02-19&sa=1&pc=PT08H30M&dc=PT09H30M&ts=0&s=0
 * commentaires :
 * - en construction
 * attention :
 * a faire :
 * - suppprimer les logs.
 * ----------------------------------------------------------------------------
 */

var places_limitees = true;
var elt_places_dispo;
var nb_places_dispo = 0; // valeur varie au gres des saisies

var nb_deja_inscrits = 0; // valeur avant la saisie (ne change pas)
var max_places_dispo = 0; // valeur avant la saisie (ne change pas)
var nb_resp_requis = 0;   // valeur avant la saisie (ne change pas)
var nb_resp_inscrit = 0;  // valeur avant la saisie (ne change pas)

var nb_participations = 0; // valeur varie au gres des saisies

var places_init_min = true;
var elt_init_min;
var nb_init_min = 0;

var places_init_max = true;
var elt_init_max;
var nb_init_max = 0;

var saisie_resp = true;
var code_resp = 0;
var elt_saisie_resp;
var elt_aff_resp;
var text_aff_resp = "";
var equipiers = true; // valeur avant la saisie (ne change pas)

// information sur la seance
var elt_form;
var code_site;
var code_seance = "";
var code_support = "";
var heure_debut = "";
var heure_fin = "";

var nouvelles_participations = [];

function initialisation() {
  console.log("Initialisations");
  
  // Affichage du nombre de places disponibles (si capacite limitee)
  elt_places_dispo = document.getElementById("nb_places_dispo");
  places_limitees  = (elt_places_dispo !== null);
  if (places_limitees) {
    nb_places_dispo = Number(elt_places_dispo.textContent);
    max_places_dispo = nb_places_dispo;
    console.log("n_places_dispo : " + nb_places_dispo);
  } else {
    console.log("pas de limite de capacite");
  }
  
  // Contraintes eventuelles / nombre de participant.e.s en initiation
  elt_init_min =  document.getElementById("nb_init_min");
  places_init_min  = (elt_init_min !== null);
  if (places_init_min) nb_init_min = Number(elt_init_min.textContent);
  
  elt_init_max =  document.getElementById("nb_init_max");
  places_init_max  = (elt_init_max !== null);
  if (places_init_max) nb_init_max = Number(elt_init_max.textContent);
  
  // donnees sur la seance
  elt_form = document.getElementById("form_ie");
  code_seance = elt_form.dataset.seance;
  code_site = elt_form.dataset.site;
  code_support = elt_form.dataset.support;
  heure_debut = elt_form.dataset.debut;
  heure_fin = elt_form.dataset.fin;
  
  nb_deja_inscrits = Number(elt_form.dataset.npart);
  nb_participations = nb_deja_inscrits;
  
  nb_resp_requis = Number(elt_form.dataset.resprequis);
  nb_resp_inscrit = Number(elt_form.dataset.respinscrit);
  
  equipiers = !(nb_resp_requis > 0 && nb_resp_inscrit == 0 && max_places_dispo == 1);
  
  console.log("[INIT] Nbre deja inscrits = " + nb_deja_inscrits
              + " dont resp = " + nb_resp_inscrit
              + " (requis = " + nb_resp_requis + ") equipier(s) : " + equipiers
              );
  
  // Element pour affichage du responsable (si besoin)
  elt_saisie_resp = document.getElementById("champ_resp");
  saisie_resp = (elt_saisie_resp !== null);
  if (saisie_resp) {
    id_aff_resp = "resp_" + code_support + "_" + heure_debut + "_0";
    elt_aff_resp = document.getElementById(id_aff_resp);
  }
  return;
}

function controle_saisie_responsable(elt) {
  let resp_choisis = elt_saisie_resp.selectedOptions;
  let code = resp_choisis[0].value;
  let nom_resp = resp_choisis[0].label;
  let id_checkbox = "equip_";
  let nb_places_equipiers = nb_places_dispo;
  
  if (code == 0)
    elt_aff_resp.textContent = "";
  else
    elt_aff_resp.textContent = nom_resp;
  if (code_resp == 0 && code > 0) {
    // [pas de resp selectionne] --> [resp selectionne]
    nb_participations++;
    if (places_limitees) nb_places_dispo--;
    if (equipiers) {
      id_checkbox += code;
      checkbox = document.getElementById(id_checkbox);
      checkbox.checked = true;
      checkbox.disabled = true;
    }
  }
  if (code_resp > 0 && code == 0) {
    // [resp selectionne] --> [ pas de resp selectionne]
    nb_participations--;
    if (places_limitees) nb_places_dispo++;
    if (equipiers) {
      id_checkbox += code_resp;
      checkbox = document.getElementById(id_checkbox);
      checkbox.checked = false;
      checkbox.disabled = false;
    }
  }
  if (equipiers && code_resp > 0 && code > 0) {
    // [resp selectionne] --> [resp selectionne]
    // changement de responsable
    // on reactive l'ancien
    id_checkbox = "equip_" + code_resp;
    checkbox = document.getElementById(id_checkbox);
    checkbox.checked = false;
    checkbox.disabled = false;
    // on desactive le nouveau
    id_checkbox = "equip_" +  code;
    checkbox = document.getElementById(id_checkbox);
    checkbox.checked = true;
    checkbox.disabled = true;
  }
  if (places_limitees) {
    elt_places_dispo.textContent = nb_places_dispo;
  }
  console.log("modif responsable : " + code + " = " + nom_resp);
  // nouveau contexte
  code_resp = code;
  return;
}

function controle_saisie_participation(elt, code, nom, peut_etre_resp) {
  let rang = 0;
  let id_aff_equipier = "";
  
  // y a plus de places...
  
  if (elt.checked) {
    let nb_places_equipiers = nb_places_dispo;
    if (saisie_resp && code_resp == 0) nb_places_equipiers -= 1;
    if (places_limitees && nb_places_equipiers == 0) {
      elt.checked = false;
      console.log(" plus de place...");
      return;
    }
    
    // Desactivation de l'option dans la liste des responsables
    if (saisie_resp && peut_etre_resp == 1) {
      for (const option of elt_saisie_resp.options) {
        if (option.value == code) {
          option.disabled = true;
          console.log(option.label);
          break;
        }
      }
    }

    // ajoute le nom du participant dans l'afficheur de seance
    rang = nb_participations;
    if (nb_resp_requis > 0) {
      rang += (nb_resp_requis - nb_resp_inscrit);
      if (code_resp > 0) rang -= 1;
    } else {
      rang += 1; // eh oui l'indice commence a 1 quand l'activite ne necessite pas de resp
    }
    id_aff_equipier = "equip_" + code_support + "_" + heure_debut + "_" + rang;
    console.log("ajout equipier : " + code + " = " + nom + "rang = " + rang + " id = "  + id_aff_equipier
                + "code resp = " + code_resp);
    elt_aff_equipier = document.getElementById(id_aff_equipier);
    elt_aff_equipier.textContent = nom;
    
    nouvelles_participations.push(nom);
    nb_participations++;
    if (places_limitees) {
      nb_places_dispo--;
      elt_places_dispo.textContent = nb_places_dispo;
    }
  } else {
    // on decoche...
    // il faut effacer le nom dans l'afficheur, et decaler les noms des inscrits
    const index = nouvelles_participations.indexOf(nom);
    if (index !== -1) {
      nouvelles_participations.splice(index, 1);
    }
    // on efface ce qui a ete saisi
    let rang_debut = nb_deja_inscrits; // + 1;
    if (nb_resp_requis > 0) {
      rang_debut += (nb_resp_requis - nb_resp_inscrit);
    } else {
      rang_debut += 1; // eh oui l'indice commence a 1 quand l'activite ne necessite pas de resp
    }
    let rang_fin = nb_participations; //rang_debut + nb_participations;
    if (nb_resp_requis > 0) {
      rang_fin += (nb_resp_requis - nb_resp_inscrit);
    } else {
      rang_fin += 1; // eh oui l'indice commence a 1 quand l'activite ne necessite pas de resp
    }
    
    for (rang = rang_debut; rang < rang_fin; rang++) {
      id_aff_equipier = "equip_" + code_support + "_" + heure_debut + "_" + rang;
      elt_aff_equipier = document.getElementById(id_aff_equipier);
      elt_aff_equipier.textContent = "";
    }
    // puis on les remplit a nouveau
    for (rang = rang_debut; rang < rang_fin; rang++) {
      id_aff_equipier = "equip_" + code_support + "_" + heure_debut + "_" + rang;
      elt_aff_equipier = document.getElementById(id_aff_equipier);
      elt_aff_equipier.textContent = nouvelles_participations[rang - rang_debut];
    }
    
    // Reactivation de l'option dans la liste des responsables
    if (saisie_resp && peut_etre_resp == 1) {
      for (const option of elt_saisie_resp.options) {
        if (option.value == code) {
          option.disabled = false;
          console.log("reactivation " + option.label);
          break;
        }
      }
    }
    
    nb_participations--;
    if (places_limitees) {
      nb_places_dispo++;
      elt_places_dispo.textContent = nb_places_dispo;
    }
  }
}

function reinitialisation_saisie() {
  console.log("REINIT deja inscrits = " + nb_deja_inscrits
              + " max_places = " + max_places_dispo
              + " nb particiaptions = " +nb_participations);
  
  // saisie du responsable [resp_selectionne]
  if (code_resp > 0) {
    elt_aff_resp.textContent = "";
    id_checkbox = "equip_" + code_resp;
    checkbox = document.getElementById(id_checkbox);
    checkbox.checked = false;
    checkbox.disabled = false;
  }
  
  // Autre(s) participations(s)
  
  let rang_debut = nb_deja_inscrits;
  if (nb_resp_requis > 0) {
    rang_debut += (nb_resp_requis - nb_resp_inscrit);
    if (code_resp > 0) rang -= 1;
  } else {
    rang_debut += 1; // eh oui l'indice commence a 1 quand l'activite ne necessite pas de resp
  }
  let rang_fin = nb_participations; //rang_debut + nb_participations;
  if (nb_resp_requis > 0) {
    rang_fin += (nb_resp_requis - nb_resp_inscrit);
  } else {
    rang_fin += 1; // eh oui l'indice commence a 1 quand l'activite ne necessite pas de resp
  }
  
  for (rang = rang_debut; rang < rang_fin; rang++) {
    id_aff_equipier = "equip_" + code_support + "_" + heure_debut + "_" + rang;
    elt_aff_equipier = document.getElementById(id_aff_equipier);
    elt_aff_equipier.textContent = "";
  }

  
  // Reactivation des equipiers 'effaces' qui sont dans la liste des responsables possibles
  if (saisie_resp) {
    for (const option of elt_saisie_resp.options) {
      if (nouvelles_participations.find((nom) => nom == option.label))
        option.disabled = false;
    }
  }
  
  // Re-init des variables locales de controle de la saisie
  code_resp = 0;
  nb_participations = nb_deja_inscrits;
  if (places_limitees) {
    nb_places_dispo = max_places_dispo;
    elt_places_dispo.textContent = nb_places_dispo;
  }
  nouvelles_participations.length = 0;

  return;
}


function afficher_retour_inscription(reponse) {
  var ok = false;
  const modal_id = 'aff_act';
  const modal = document.getElementById(modal_id);
  const titre_modal = document.getElementById(modal_id + "_titre");
  const corps_modal = document.getElementById(modal_id + "_corps");
  const bouton_modal = document.getElementById(modal_id + "_btn");
  corps_modal.innerHTML = "<div><p>Opération réalisée avec succès</p></div>";
  bouton_modal.textContent = "Fermer";
  bouton_modal.addEventListener("click", function() { window.location=document.referrer; });
  bouton_modal.classList.add("btn-success");
  ok = true;
  return ok;
}


function requete_inscription_groupe(formulaire) {
  code_action = 'ie'; // inscription equipage (pas utilise)
  
  let checkboxes = document.getElementsByName('participants');
  let code_participants = [];
  for (var i = 0; i < checkboxes.length; i++) {
    if (checkboxes[i].checked) {
      code_participants.push(checkboxes[i].value);
    }
  }
  const envoi = {act: code_action, id: code_seance, sa: code_site, s: code_support, deb: heure_debut, fin: heure_fin, resp: code_resp, part: code_participants};
  console.log("requete_inscription_groupe envoi = " + envoi);
  
  var xmlhttp = new XMLHttpRequest();
  var url = "php/scripts/inscription_groupe_seance_activite.php?";
  const params = new URLSearchParams(envoi).toString();
  url += params;
  console.log(url);
  
  var ok = false;
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        // pour controler l'aller-retour
        /*
        var dict = JSON.parse(this.responseText);
        for (var entree in dict) {
          valeur = dict[entree];
          console.log("Entree : " + entree + " => " + valeur);
        }
         */
        ok = afficher_retour_inscription(this.responseText);
      }
    };
  
  xmlhttp.open('GET', url, true);
  xmlhttp.send();
  console.log("request sent");
  return ok;
}

// ============================================================================
