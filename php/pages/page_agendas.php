<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateaux En Ligne
  // description : Definition de la classe Page_Agendas
  //               affichage des agendas google du club
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <nom_-fichier.php'
  // dependances :
  // teste avec : PHP 7.1 sur Mac OS 10.14 ; PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 02-mar-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // - Pour l'instant seulement l'agenda des competitions
  // attention :
  // -
  // a faire :
  // -
  // ==========================================================================

  // --- Classes utilisees
  require_once 'php/elements_page/generiques/entete_contenu_page.php';
  require_once 'php/elements_page/generiques/entete_section.php';
  require_once 'php/elements_page/generiques/cadre_inclusion.php';
  require_once 'php/elements_page/generiques/cadre_texte_repliable.php';
  require_once 'php/elements_page/specifiques/page_menu.php';
  
  // --------------------------------------------------------------------------
  // --- connection a la base de donnees
  //include 'php/bdd/base_donnees.php';
  
  // --------------------------------------------------------------------------
  class Page_Agendas extends Page_Menu {
    
    public function definir_elements() {
      
      parent::definir_elements();
      
      $element = new Entete_Contenu_Page();
      $element->def_titre("Agendas du club");
      $this->ajoute_element_haut($element);
    
      // --- Calendrier des competitions --------------------------------------
      $element = new Entete_Section();
      $element->def_titre("Compétitions");
      $this->ajoute_contenu($element);
      
      $agenda = new Cadre_Inclusion();
      $agenda->def_source('https://calendar.google.com/calendar/embed?src=avironplougonvelin.fr_d4gompedlk2f5ka6pb15kl0l58%40group.calendar.google.com&ctz=Europe%2FParis');
      $agenda->def_ratio('1by1');
      $this->ajoute_contenu($agenda);
      
      $element = new Cadre_Texte_Repliable();
      $element->def_titre("S'abonner à cet agenda");
      $element->def_id('agd_comp');
      $element->def_contenu("Adresse pour s'abonner à l'agenda (format ICal) :</br > https://calendar.google.com/calendar/ical/avironplougonvelin.fr_d4gompedlk2f5ka6pb15kl0l58%40group.calendar.google.com/public/basic.ics");
      $this->ajoute_contenu($element);
      
       // --- Calendrier des formations --------------------------------------
      
      $element = new Entete_Section();
      $element->def_titre("Formations");
      $this->ajoute_contenu($element);
      
      $agenda = new Cadre_Inclusion();
      $agenda->def_source('https://calendar.google.com/calendar/embed?src=avironplougonvelin.fr_3p6q61fs2nofh9kt9eiaoq1cps%40group.calendar.google.com&ctz=Europe%2FParis');
      $agenda->def_ratio('1by1');
      $this->ajoute_contenu($agenda);
    
       // --- Calendrier des randonnees --------------------------------------
      $element = new Entete_Section();
      $element->def_titre("Randonnées");
      $this->ajoute_contenu($element);
      
      $agenda = new Cadre_Inclusion();
      $agenda->def_source('https://calendar.google.com/calendar/embed?src=avironplougonvelin.fr_3s243n6g52q0pjug9hra5i5k3k%40group.calendar.google.com&ctz=Europe%2FParis');
      $agenda->def_ratio('1by1');
      $this->ajoute_contenu($agenda);
      
    }
    
  }
  // ==========================================================================
?>
