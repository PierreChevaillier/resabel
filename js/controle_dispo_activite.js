/* ============================================================================
 * contexte : Resabel - systeme de REServation de Bateaux En Ligne
 *            controle actions utilisateurice - cote client
 * description : modifications contextuelle des options des champs 'select'
 *               du formulaire de recherche des disponibilités pour
 *               inscription a une activite
 * copyright (c) 2018-2023 AMP. Tous droits reserves.
 * --------------------------------------------------------------------------
 * utilisation : javascript (formulaire)
 * dependances :
 * - formulaire_dispo_activite.php (champs du formulaire)
 * utilise avec  :
 *  - firefox, safari sur macOS 13.x
 * ----------------------------------------------------------------------------
 * creation: 22-jul-2019 pchevaillier@gmail.com
 * revision: 08-sep-2019 pchevaillier@gmail.com logique MaJ contectuelle champs saisie
 * revision: 05-jan-2020 pchevaillier@gmail.com creneaux, type support
 * revision: 11-mar-2020 pchevaillier@gmail.com type support - supports
 * revision: 21-mar-2020 pchevaillier@gmail.com creneaux horaires
 * revision: 29-mar-2023 pchevaillier@gmail.com jQuery > XMLHttpRequest
 * ----------------------------------------------------------------------------
 * commentaires : 
 * attention : 
 * a faire :
 * ============================================================================
 */

function chercher_info_site(champ_date, champ_site, champ_prem_creneau, champ_dern_creneau, champ_type_support, champ_support) {
  var est_correct = false;
  const ts = champ_type_support.value;
  const pc = champ_prem_creneau.value;
  const dc = champ_dern_creneau.value;
  
  const envoi = {'sa': champ_site.value, 'j': champ_date.value, 'ts': champ_type_support.value};

  //console.log("Site act : " + champ_site.value
  //            + " type_support : " + ts
  //            + " jour : " + champ_date.value + " pc " + pc + " dc : " + dc);

  // RaZ du champ type de support (car depend du site)
  champ_type_support.options.length = 0;
  champ_type_support.add(new Option("Tous", 0));

  // RaZ du champ support (car depend du site et du type de support)
  champ_support.options.length = 0;
  champ_prem_creneau.options.length = 0;
  champ_dern_creneau.options.length = 0;

  //console.log(envoi);
  
  var xmlhttp = new XMLHttpRequest();
  var url = "php/scripts/site_activites_info_obtenir.php?";
  const params = new URLSearchParams(envoi).toString();
  url += params;
  console.log(url);
  
  var ok = false;
  xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        //code_html = "<div>";
        var items = [];
        var dict = JSON.parse(this.responseText);
        for (var entree in dict) {
          valeur = dict[entree];
          console.log("JSON retour : " + entree + " =>" + valeur);
          switch (entree) {
            case 'pc':
              choix = JSON.parse(valeur);
              prem_creneau = "";
              choix_possible = false;
              for (var option in choix) {
                libelle = choix[option];
                champ_prem_creneau.add(new Option(libelle, option));
                if (prem_creneau.length === 0)
                  prem_creneau = option;
                if (!choix_possible && option == pc)
                  choix_possible = true;
              }
              if (choix_possible)
                champ_prem_creneau.value = pc;
              else
                champ_prem_creneau.value = prem_creneau;
              break;
              
            case 'dc':
              choix = JSON.parse(valeur);
              dern_creneau = "";
              choix_possible = false;
              rang = 0;
              for (var option in choix) {
                libelle = choix[option];
                champ_dern_creneau.add(new Option(libelle, option));
                if (!choix_possible && option == dc)
                  choix_possible = true;
                if (rang < 2)
                  dern_creneau = option;
                rang += 1;
              }
              if (choix_possible)
                champ_dern_creneau.value = dc;
              else
                champ_dern_creneau.value = dern_creneau;
              break;
              
            case 'ts':
              choix = JSON.parse(valeur);
              choix_possible = false;
              for (var option in choix) {
                libelle = choix[option];
                champ_type_support.add(new Option(libelle, option));
                if (!choix_possible && option == ts)
                  choix_possible = true;
                console.log("option ts" + option + "libele " + libelle);
              }
              if (choix_possible)
                champ_type_support.value = ts;
              else
                champ_type_support.value = 0;
              break;
              
            case 's':
              //if (y == 0)
              champ_support.add(new Option("Tous", 0));
              choix = JSON.parse(valeur);
              for (var option in choix) {
                libelle = choix[option];
                champ_support.add(new Option(libelle, option));
              }
              break;
            default:
              items.push( "<p>" + cle + ": " + valeur + "</p>" );
          }
        }
      }
    };
  
  xmlhttp.open('GET', url, true);
  xmlhttp.send();
  console.log("request sent");
  return ok;
  /*
  var jqxhr = $.getJSON("php/scripts/site_activites_info_obtenir.php?", envoi, function(retour) {
                        console.log( "success" );
                        code_html = "<div>";
                        var items = [];
                        $.each( retour, function(cle, valeur) {
                               switch (cle) {
                               
                               case 'pc':
                                choix = JSON.parse(valeur);
                                prem_creneau = "";
                                choix_possible = false;
                                $.each(choix, function(code, libelle) {
                                       champ_prem_creneau.add(new Option(libelle, code));
                                       if (prem_creneau.length == 0)
                                        prem_creneau = code;
                                       if (!choix_possible && code == pc)
                                        choix_possible = true;
                                      })
                                if (choix_possible)
                                  champ_prem_creneau.value = pc;
                                else
                                  champ_prem_creneau.value = prem_creneau;
                                break;
                               
                                case 'dc':
                                choix = JSON.parse(valeur);
                                dern_creneau = "";
                                choix_possible = false;
                                rang = 0;
                                $.each(choix, function(code, libelle) {
                                       champ_dern_creneau.add(new Option(libelle, code));
                                       if (!choix_possible && code == dc)
                                        choix_possible = true;
                                       if (rang < 2)
                                        dern_creneau = code;
                                       rang += 1;
                                       })
                                if (choix_possible)
                                  champ_dern_creneau.value = dc;
                                else
                                  champ_dern_creneau.value = dern_creneau;
                                break;
                               
                               case 'ts':
                               choix = JSON.parse(valeur);
                               choix_possible = false;
                               $.each(choix, function(code, libelle) {
                                      champ_type_support.add(new Option(libelle, code));
                                      if (!choix_possible && code == y)
                                        choix_possible = true;
                                      console.log("code" + code + "libele " + libelle);
                                      })
                               if (choix_possible)
                                champ_type_support.value = y;
                               else
                                champ_type_support.value = 0;
                              break;
                               case 's':
                               //if (y == 0)
                                  champ_support.add(new Option("Tous", 0));
                               choix = JSON.parse(valeur);
                               $.each(choix, function(code, libelle) {
                                      champ_support.add(new Option(libelle, code));
                                      })
                               break;
                            
                               default:
                               items.push( "<p>" + cle + ": " + valeur + "</p>" );
                               }
                              
                               });
                        code_html += items.join("");
                        //$("body").html(code_html + "</div>");
                        console.log("fin maj champ");
                        })
  .done(function(retour) {
        console.log( "second success" );
        est_correct = true;
        //$("#statut_requete_json").html("<p>C'est un succès !</p>");
        })
  .fail(function(retour) {
        console.log("error");
        //$("#statut_requete_json").html("<p>Oups: ça ne marche pas...</p>");
        })
  .always(function(retour) {
          console.log( "complete" );
          });
  return est_correct;
   */
}

// Concue pour etre appele au chargement de la page
function creer_gestionnaire_evenement(id_date, id_site, id_prem_creneau, id_dern_creneau, id_type_support, id_support) {
  var champ_date = document.getElementById(id_date);
  var champ_site = document.getElementById(id_site);
  var champ_prem_creneau = document.getElementById(id_prem_creneau);
  var champ_dern_creneau = document.getElementById(id_dern_creneau);
  var champ_type_support = document.getElementById(id_type_support);
  var champ_support = document.getElementById(id_support);
  
  champ_site.addEventListener("change", function() { chercher_info_site(champ_date, champ_site, champ_prem_creneau, champ_dern_creneau, champ_type_support, champ_support); });
  champ_type_support.addEventListener("change", function() { chercher_info_site(champ_date, champ_site, champ_prem_creneau, champ_dern_creneau, champ_type_support, champ_support); });
  
  return true;
}
// ============================================================================
