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
 * description : controle saisies et requete au serveur modification inscription seance
 *               (formulaire de saisie equipage)
 * utilisation : javascript - onload
 * dependances :
 * - modal.php (de Resabel) : ids des elements du composant
 * - Ids des elements de la page (cf. appels a document.getElementById)
 * - script execute par le serveur = nom et donnees envoyees
 * ----------------------------------------------------------------------------
 * creation : 31-jan-2024 pchevaillier@gmail.com
 * revision : 19-fev-2024 pchevaillier@gmail.com
 * revision : 21-oct-2024 pchevaillier@gmail.com + desact. boutons et * reinit saisie
 * revision : 12-dec-2024 pchevaillier@gmail.com + erreur en cas de creation concurrente
 * ----------------------------------------------------------------------------
 * http://localhost/resabel-v2/resabel/activites.php?a=ii&j=2024-02-19&sa=1&pc=PT08H30M&dc=PT09H30M&ts=0&s=0
 * commentaires :
 * -
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

var elt_btn_valid; // pour pouvoir activer / deactiver le bouton
var elt_btn_reset;

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
  
  // boutons d'action
  elt_btn_valid = document.getElementById("btn-valid");
  elt_btn_valid.disabled = true;
  elt_btn_reset = document.getElementById("btn-reset");
  elt_btn_reset.disabled = true;
  
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
  
  if (nb_participations - nb_deja_inscrits > 0) {
    elt_btn_valid.disabled = false;
    elt_btn_reset.disabled = false;
  } else {
    elt_btn_valid.disabled = true;
    elt_btn_reset.disabled = true;
  }
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
  
  if (nb_participations - nb_deja_inscrits > 0) {
    elt_btn_valid.disabled = false;
    elt_btn_reset.disabled = false;
  } else {
    elt_btn_valid.disabled = true;
    elt_btn_reset.disabled = true;
  }
}

function reinitialisation_saisie() {
  console.log("REINIT deja inscrits = " + nb_deja_inscrits
              + " max_places = " + max_places_dispo
              + " nb particiaptions = " +nb_participations);
  
  elt_btn_valid.disabled = true;
  elt_btn_reset.disabled = true;
  
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
    //if (code_resp > 0) rang -= 1;
  } else {
    rang_debut += 1; // eh oui l'indice commence a 1 quand l'activite ne necessite pas de resp
  }
  let rang_fin = nb_participations;
  if (nb_resp_requis > 0) {
    rang_fin += (nb_resp_requis - nb_resp_inscrit);
  } else {
    rang_fin += 1; // eh oui l'indice commence a 1 quand l'activite ne necessite pas de resp
  }
  
  for (rang = rang_debut; rang < rang_fin; rang++) {
      id_aff_equipier = "equip_" + code_support + "_" + heure_debut + "_" + rang;
    console.log(id_aff_equipier);
      elt_aff_equipier = document.getElementById(id_aff_equipier);
    if (elt_aff_equipier != null)
      elt_aff_equipier.textContent = "";
    else
      console.log("est null");
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

  // decocher les equipieres selectionnes
  let checkboxes = document.getElementsByName('participants');
  for (var i = 0; i < checkboxes.length; i++) {
    if (checkboxes[i].checked && !checkboxes[i].disabled) {
      checkboxes[i].checked = false;
    }
  }
  
  return;
}


function afficher_retour_inscription(reponse) {
  var ok = false;
  const modal_id = 'aff_act';
  const modal = document.getElementById(modal_id);
  const titre_modal = document.getElementById(modal_id + "_titre");
  const corps_modal = document.getElementById(modal_id + "_corps");
  
  const bouton_modal = document.getElementById(modal_id + "_btn");
  bouton_modal.textContent = "Fermer";
  bouton_modal.addEventListener("click", function() { window.location=document.referrer; });
  
  titre_modal.textContent = "Inscription(s) à une séance";
  var n_cdb_inscrit = 0;
  var n_part_inscrit = 0;
  var dict = JSON.parse(reponse);
  for (var entree in dict) {
    valeur = dict[entree];
    console.log("JSON retour inscription_groupe_seance : " + entree + " =>" + valeur);
    switch (entree) {
      case 'status':
        ok = (valeur === 1);
        if (!ok) {
          console.log("pas bon - code erreur : " + valeur);
          titre_modal.textContent = "Echec opération";
          // certains codes erreur sont pour debug
          // (cf. Enregistrement_Seance_Activite::ajouter_participation)
          if (valeur === 7) {
            corps_modal.textContent = "Au moins 1 personne déjà inscrite sur le même créneau horaire";
          } else if (valeur === 8) {
            corps_modal.textContent = "Le support n'est plus disponible sur ce créneau";
          } else if (valeur === 9) {
            corps_modal.textContent = "L'équipage est complet";
          } else {
            corps_modal.textContent = ""; // efface le contenu initial
          }
          bouton_modal.classList.add("btn-warning");
        }
        break;
      case 'cdb':
        n_cdb_inscrit = valeur;
        console.log("nn cdb:" + n_cdb_inscrit);
        break;
      case 'part':
        n_part_inscrit = valeur;
        console.log("nn part:" + n_part_inscrit);
        break;

    }
  }
  n_inscrits = n_cdb_inscrit + n_part_inscrit;
  if (ok) {
    if (n_inscrits > 0) {
      corps_modal.textContent = "Opération réalisée avec succès : "
      + n_inscrits + " personne(s) inscrite(s)";
      bouton_modal.classList.add("btn-success");
    } else {
      corps_modal.textContent = "Aucune personne sélectionnée";
      bouton_modal.classList.add("btn-warning");
    }
  }
//  corps_modal.innerHTML = "<div><p>Opération réalisée avec succès</p></div>";
  ok = true;
  return ok;
}


function requete_inscription_groupe(formulaire) {
  code_action = 'ie'; // inscription equipage (pas utilise)
  
  let checkboxes = document.getElementsByName('participants');
  let code_participants = [];
  for (var i = 0; i < checkboxes.length; i++) {
    if (checkboxes[i].checked && !checkboxes[i].disabled) {
      code_participants.push(checkboxes[i].value);
    }
  }
  const modal_id = 'aff_act';
  const modal = document.getElementById(modal_id);
  const titre_modal = document.getElementById(modal_id + "_titre");
  const corps_modal = document.getElementById(modal_id + "_corps");
  titre_modal.textContent = "Inscription à une séance";
  corps_modal.textContent = "Opération en cours...";
  
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
