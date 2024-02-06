/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 *            formulaire de saisie d'un equipage
 * description : controle saisies et requete au serveur modification inscription seance d'activite
 * Copyright (c) 2017-2024 AMP. Tous droits reserves
 * ----------------------------------------------------------------------------
 * utilisation : javascript - onload
 * dependances :
 * - modal.php (de Resabel) : ids des elements du composant
 * - Ids des elements de la page
 * - script execute par le serveur = nom et reponse
 * ----------------------------------------------------------------------------
 * creation : 31-jan-2024 pchevaillier@gmail.com
 * revision :
 * ----------------------------------------------------------------------------
 * commentaires :
 * - en construction
 * actions supportees :
 * - ii : inscription individuelle
 * - di : desinscription individuelle
 * -  modification role seance (responsable <->  equipier)
 * attention :
 * a faire :
 * - suppprimer les logs.
 * ----------------------------------------------------------------------------
 */

var places_limitees = true;
var elt_places_dispo;
var nb_deja_inscrits = 0;
var nb_places_dispo = 0;

var nb_participations = 0;

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

// information sur la seance
var elt_form;
var code_seance = "";
var code_support = "";
var heure_debut = "";

var nouvelles_participations = [];

function initialisation() {
  console.log("Initialisations");
  
  // Affichage du nombre de places disponibles (si capacite limitee)
  elt_places_dispo = document.getElementById("nb_places_dispo");
  places_limitees  = (elt_places_dispo !== null);
  if (places_limitees) {
    nb_places_dispo = Number(elt_places_dispo.textContent);
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
  code_support = elt_form.dataset.support;
  heure_debut = elt_form.dataset.debut;
  nb_participations = Number(elt_form.dataset.npart);
  nb_deja_inscrits = nb_participations;
  console.log("Nbre deja inscrits = " + nb_deja_inscrits);
  
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
  if (code == 0)
    elt_aff_resp.textContent = "";
  else
    elt_aff_resp.textContent = nom_resp;
  if (code_resp == 0 && code > 0) {
    nb_participations++;
    if (places_limitees) nb_places_dispo--;
  }
  if (code_resp > 0 && code == 0) {
    nb_participations--;
    if (places_limitees) nb_places_dispo++;
  }
  if (places_limitees) {
    elt_places_dispo.textContent = nb_places_dispo;
  }
  
  
  console.log("modif responsable : " + code + " = " + nom_resp);
  // TODO: desactiver/activer la checkbox correspondant au nouveau responsable
  
  // nouveau contexte
  code_resp = code;
}

function controle_saisie_participation(elt, code, nom) {
  let rang = 0;
  let id_aff_equipier = "";
  
  // y a plus de places...
  
  if (elt.checked) {
    let nb_places_equipiers = nb_places_dispo;
    if (code_resp == 0) nb_places_equipiers--;
    if (places_limitees && nb_places_equipiers == 0) {
      elt.checked = false;
      console.log(" plus de place...");
      return;
    }
    
    nouvelles_participations.push(nom);
    rang = nb_participations + 1;
    if (code_resp > 0) rang--;
    id_aff_equipier = "equip_" + code_support + "_" + heure_debut + "_" + rang;
    console.log("ajout equipier : " + code + " = " + nom + "rang = " + rang + " id = "  + id_aff_equipier
                + "code resp = " + code_resp);
    elt_aff_equipier = document.getElementById(id_aff_equipier);
    elt_aff_equipier.textContent = nom;
    nb_participations++;
    if (places_limitees) {
      nb_places_dispo--;
      elt_places_dispo.textContent = nb_places_dispo;
    }
  } else {
    // on decoche...
    nb_participations--;
    if (places_limitees) {
      nb_places_dispo++;
      elt_places_dispo.textContent = nb_places_dispo;
    }
    // il faut effacer le nom dans l'afficheur, et decaler les noms des inscrits
    const index = nouvelles_participations.indexOf(nom);
    if (index !== -1) {
      nouvelles_participations.splice(index, 1);
    }
    // on efface cd qui a ete saisi
    let rang_debut = nb_deja_inscrits + 1;
    let rang_fin = rang_debut + nb_participations;
    for (rang = rang_debut; rang < rang_fin; rang++) {
      id_aff_equipier = "equip_" + code_support + "_" + heure_debut + "_" + rang;
      elt_aff_equipier = document.getElementById(id_aff_equipier);
      elt_aff_equipier.textContent = "";
    }
    // puis on les remplit a nouveau
    for (rang = rang_debut; rang < rang_fin - 1; rang++) {
      id_aff_equipier = "equip_" + code_support + "_" + heure_debut + "_" + rang;
      elt_aff_equipier = document.getElementById(id_aff_equipier);
      elt_aff_equipier.textContent = nouvelles_participations[rang - rang_debut];
    }
  }
}

function requete_inscription_responsable(elem,
                                         code_seance,
                                    code_site,
                                    code_support,
                                    debut, fin,
                                    id_champ) {
// TODO: c'etait un essai. A revoir completement
  resp = true;
  code_personne = 512;
  code_action = 'i';
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
        //ok = afficher_retour_inscription(modal_id, code_action, this.responseText);
        location.reload();
      }
    };
  
  xmlhttp.open('GET', url, true);
//  xmlhttp.send();
  console.log("request sent");
  return ok;
}

// ============================================================================
