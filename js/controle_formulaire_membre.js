// ============================================================================
// contexte : Resabel - systeme de REServAtion de Bateau En Ligne
//            controle actions utitisateurice - cote client
//            essais
// description : fiormulaire membre
// utilisation : javascript - controleur action element page web
// teste avec  : firefox, safari sur macOS 13.2,
// dependances : Bootstrap 5.x
//               ids des elements (cf. formulaire_membre.php)
// copyright (c) 2023 AMP. Tous droits reserves
// ----------------------------------------------------------------------------
// creation : 15-mar-2023 pchevaillier@gmail.com
// revision :
// ----------------------------------------------------------------------------
// commentaires :
// - en evolution
// attention :
// a faire :
// ----------------------------------------------------------------------------

// As per the HTML Specification
const emailPattern = "^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$";

// numeros francais (version testee le 16-mar-2023)
const telPattern = "^(0[1-9])(?:[ _.-]?([0-9]{2})){4}$";
//^[\+\(\s.\-\/\d\)]{5,30}$";
//^(\+|00)[1-9][0-9 \-\(\)\.]{7,32}$";
//^(0[1-9])(?:[ _.-]?(\d{2})){4}$"; // marche pas

// ----------------------------------------------------------------------------

const champ_prenom = document.getElementById("prn");
const champ_nom = document.getElementById("nom");
const champ_id = document.getElementById("id");
const champ_courriel = document.getElementById("courriel");
const champ_tel = document.getElementById("tel");

// ----------------------------------------------------------------------------

function initialiser_id(champ_id, champ_prn, champ_nom) {
  identifiant = champ_prn.value.toLowerCase() + "." + champ_nom.value.toLowerCase();
  identifiant = identifiant.replace(/é/g, "e");
  identifiant = identifiant.replace(/è/g, "e");
  identifiant = identifiant.replace(/ë/g, "e");
  identifiant = identifiant.replace(/ì/g, "i");
  identifiant = identifiant.replace(/ï/g, "i");
  identifiant = identifiant.replace(/ô/g, "o");
  identifiant = identifiant.replace(/ç/g, "c");
  identifiant = identifiant.replace(/ñ/g, "n");
  identifiant = identifiant.replace(/'/g, "");
  identifiant = identifiant.replace(/\ /g, "");
  champ_id.value = identifiant;
}


function controle_validite_saisie_texte(champ, nom_champ, pattern, libelle_pattern, requis) {
  var ok = false;
  const msg_err = champ.nextElementSibling.childNodes[0];
  if (requis && champ.validity.valueMissing)
    msg_err.textContent = `Le champ ${nom_champ} doit obligatoirement être renseigné.`;
  else if (champ.validity.tooShort)
    msg_err.textContent = `Le champ ${nom_champ} doit avoir au moins ${champ.minLength} caractères.`;
  else if (champ.validity.tooLong)
    msg_err.textContent = `Le ${nom_champ} doit avoir au maximum ${champ.maxLength} caractères.`;
  else if (champ.value.length > 0) {
    ok = new RegExp(pattern).test(champ.value);
    console.log(champ.value, pattern, ok);
    if (!ok) {
      champ.className = "form-control is-invalid";
      champ.setCustomValidity("format non valide");
      msg_err.textContent = `Le champ ${nom_champ} ne doit contenir que ${libelle_pattern}.`;
    } else {
      champ.setCustomValidity("");
      champ.className = "form-control is-valid";
    }
  } else {
    champ.setCustomValidity("");
    champ.className = "form-control is-valid";
  }
  return ok;
}


function traiter_reponse(element, reponse) {
  var unique = false;
  const msg_err = element.nextElementSibling.childNodes[0];
  var dict = JSON.parse(reponse);
  for (var entree in dict) {
    valeur = dict[entree];
    console.log("JSON retour : " + entree + " =>" + valeur);
    
    switch (entree) {
      case 'status':
        unique = (valeur === 1);
        if (!unique) {
          console.log("pas unique");
          element.className = "form-control is-invalid";
          element.setCustomValidity("format non valide");
          if (valeur === 0)
            msg_err.textContent = "valeur interdite : identifiant déjà attribué";
          else if (valeur === 2)
            msg_err.textContent = "valeur interdite : caractères autorisés : lettres, chiffres, . ou -";
        } else {
          element.setCustomValidity("");
          element.className = "form-control is-valid";
          console.log("unique");
        }
        break;
    }
  }
  return unique;
}

function verifier_identifiant(element) {
  
  const champ_code = document.getElementById("code_mbr");
  const code_membre = champ_code.value;
  const identifiant = element.value;
  
  const envoi = {code: code_membre, id: identifiant};
  console.log(envoi);
  
  var xmlhttp = new XMLHttpRequest();
  var url = "php/scripts/verification_identifiant_unique.php?";
  const params = new URLSearchParams(envoi).toString();
  url += params;
  console.log(url);
  
  var ok = false;
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        ok = traiter_reponse(element, this.responseText);
      }
    };
  
  xmlhttp.open('GET', url, true);
  xmlhttp.send();
  console.log("request sent");
  return ok;
}

// ----------------------------------------------------------------------------
// from https://getbootstrap.com/docs/5.2/forms/validation/
(() => {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  });
  
  // --- controles specifiques
  champ_id.addEventListener("input", function() {
    var ok = false;
    const nom_champ = "identifiant";
    const pattern = "^[a-zA-Z0-9\.\-]+$";
    const libelle_pattern = "des lettres sans accent, un tiret ou un point ";
    ok = controle_validite_saisie_texte(this, nom_champ, pattern, libelle_pattern, true);
    if (ok) verifier_identifiant(champ_id);
  });
  
  
  champ_prenom.addEventListener("input", function() {
    initialiser_id(champ_id, champ_prenom, champ_nom);
    
    const nom_champ = "prénom";
    const pattern = "^[a-zA-Zéèëçñìï\ '-]+$";
    const libelle_pattern = "des lettres, espace, tiret ou apostrophe";
    controle_validite_saisie_texte(this, nom_champ, pattern, libelle_pattern, true);
    }
                                );

  champ_nom.addEventListener("input", function() {
    initialiser_id(champ_id, champ_prenom, champ_nom);
    const nom_champ = "nom";
    const pattern = "^[a-zA-Zéèëçñìï\ '-]+$";
    const libelle_pattern = "des lettres, espace, tiret ou apostrophe";
    controle_validite_saisie_texte(this, nom_champ, pattern, libelle_pattern, true);
  });

  champ_courriel.addEventListener("input", function() {
    const nom_champ = "courriel";
    const libelle_pattern = "des caractères valides pour une adresse mail";
    controle_validite_saisie_texte(this, nom_champ, emailPattern, libelle_pattern, false);
  });
  
  champ_tel.addEventListener("input", function() {
    const nom_champ = "tel";
    const libelle_pattern = "un numéro de tél. français";
    controle_validite_saisie_texte(this, nom_champ, telPattern, libelle_pattern, false);
  });
  
})()

// ============================================================================
