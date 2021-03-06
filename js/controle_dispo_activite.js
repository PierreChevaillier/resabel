/* ------------------------------------------------------------------------
 * contexte : Resabel - systeme de REServation de Bateaux En Ligne
 * description : modifications contextuelle des options des champs 'select'
 *               du formulaire de recherche des disponibilités pour
 *               inscription a une activite
 * copyright (c) 2018-2020 AMP. Tous droits reserves.
 * --------------------------------------------------------------------------
 * utilisation : javascript (formulaire)
 * dependences : formulaire_dispo_activite.php (champs du formualire)
 * creation: 22-jul-2019 pchevaillier@gmail.com
 * revision: 08-sep-2019 pchevaillier@gmail.com logique MaJ contectuelle champs saisie
 * revision: 05-jan-2020 pchevaillier@gmail.com creneaux, type support
 * revision: 11-mar-2020 pchevaillier@gmail.com type support - supports
 * revision: 21-mar-2020 pchevaillier@gmail.com creneaux horaires
 * ----------------------------------------------------------------------------
 * commentaires : 
 * attention : 
 * a faire :
 * ----------------------------------------------------------------------------
 */

function chercher_info_site(champ_date, champ_site, champ_prem_creneau, champ_dern_creneau, champ_type_support, champ_support) {
  var est_correct = false;
  var x = champ_site.value;
  var y = 0;
  if (champ_type_support.value.length > 0)
    y = champ_type_support.value;
  var pc = "";
  if (champ_prem_creneau.value.length > 0)
     pc = champ_prem_creneau.value;
  var dc = "";
  if (champ_prem_creneau.value.length > 0)
    dc = champ_dern_creneau.value;
  
  envoi = {'sa': champ_site.value, 'j': champ_date.value, 'ts': champ_type_support.value};

  //alert( "Site act : " + x + " type_support : " + y + " jour : " + champ_date.value + " pc " + pc + " dc : " + dc);

  champ_type_support.options.length = 0;
  champ_type_support.add(new Option("Tous", 0));

  champ_support.options.length = 0;
  champ_prem_creneau.options.length = 0;
  champ_dern_creneau.options.length = 0;

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
}

function tutu(champ_site) {
  var est_correct = false;
  var x = champ_site.value;
  alert('tutu' + x);
  est_correct = true;
  return est_correct;
}

// Concue pour etre appele au chargement de la page
function creer_gestionnaire_evenement(id_date, id_site, id_prem_creneau, id_dern_creneau, id_type_support, id_support) {
  var champ_date = document.getElementById(id_date);
  var champ_site = document.getElementById(id_site);
  var champ_prem_creneau = document.getElementById(id_prem_creneau);
  var champ_dern_creneau = document.getElementById(id_dern_creneau);
  var champ_type_support = document.getElementById(id_type_support);
  var champ_support = document.getElementById(id_support);
  //alert("Chargement site " + champ_site.value)
  champ_site.addEventListener("change", function() { chercher_info_site(champ_date, champ_site, champ_prem_creneau, champ_dern_creneau, champ_type_support, champ_support); });
  champ_type_support.addEventListener("change", function() { chercher_info_site(champ_date, champ_site, champ_prem_creneau, champ_dern_creneau, champ_type_support, champ_support); });
  
  //champ_site.addEventListener("change", function() { tutu(champ_site); });
  return true;
  
}
