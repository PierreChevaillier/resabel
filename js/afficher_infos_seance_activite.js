// ============================================================================
// contexte    : Resabel V2
// description : Affichage des informations sur une seance d'activite dans
//               une "fenetre" modale
// utilisation : javascript - controleur action element page web
// teste avec  : firefox, safari sur Mac OS 10.14
// dependances : id des elements dans un Element_Modal (modal.php)
// Copyright (c) 2017-2020 AMP. Tous droits reserves.
// ----------------------------------------------------------------------------
// creation : 06-mar-2020 pchevaillier@gmail.com
// revision :
// ----------------------------------------------------------------------------
// commentaires :
// - l'element modal sert a afficher plusieurs choses :
//   son contenu doit donc etre cree dynamiquement
// attention :
// a faire :
// ----------------------------------------------------------------------------
function afficher_info_seance(modal_id, entete, corps) {
  $("#" + modal_id + "_titre").html(entete);
  code_html = "<div>" + corps;
  $("#" + modal_id + "_corps").html(code_html + "</div>");
  $("#" + modal_id + "_btn").html("Fermer");
  document.getElementById(modal_id + "_btn").classList.add("btn-info");
  //console.log("fin affichage");
  return true;
}

// ============================================================================
