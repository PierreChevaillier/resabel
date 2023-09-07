// ============================================================================
// contexte : Resabel - systeme de REServAtion de Bateau En Ligne
// description : Affichage des infos sur seance activite dans "fenetre" modale
// Copyright (c) 2017-2023 AMP. Tous droits reserves.
// ----------------------------------------------------------------------------
// utilisation : javascript - controleur action element page web
// utilise avec  :
// - firefox, safari sur macOS 13.x
// dependances :
// - modal.php (de Resabel) : ids des elements du composant
// - bootstrap (classe du bouton)
// ----------------------------------------------------------------------------
// creation : 06-mar-2020 pchevaillier@gmail.com
// revision : 23-mar-2023 pchevaillier@gmail.com - jQuery
// ----------------------------------------------------------------------------
// commentaires :
// - l'element modal sert a afficher plusieurs choses :
//   son contenu doit donc etre cree dynamiquement
// - le code est completement generique / ce qui est affiche
// attention :
// a faire :
// - a supprimer ou a rendre reutilisable.
// ----------------------------------------------------------------------------
function afficher_info_seance(modal_id, entete, corps) {
  
  const titre_modal = document.getElementById(modal_id + "_titre");
  const corps_modal = document.getElementById(modal_id + "_corps");
  const bouton_modal = document.getElementById(modal_id + "_btn");
  
  titre_modal.innerHTML = entete;
  corps_modal.innerHTML= "<div>" + corps + "</div>";
  bouton_modal.textContent = "Fermer";
  bouton_modal.classList.add("btn-success");
  
  return true;
}

// ============================================================================
