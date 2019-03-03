<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classe Club
  // copyright (c) 2018 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : 
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 07-jan-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // source de donnees :
  // https://public.opendatasoft.com/explore/dataset/correspondance-code-insee-code-postal/table/?refine.nom_dept=FINISTERE
  // il peut y avoir plusieurs codes INSEE pour le meme code postal.
  // attention :
  // - 
  // a faire :
  // -
  // ==========================================================================

  class Commune {
    private $code = 0; // code INSEE
    public function code() { return $this->code; }
    public function def_code($valeur) { $this->code = $valeur;}
    
    private $code_postal =  0;
    public function code_postal() { return $this->code_postal; }
    public function def_code_postal($valeur) { $this->code_postal = $valeur;}
    
    private $nom = ""; // utf8
    public function nom() { return $this->nom; }
    public function def_nom($valeur) { $this->nom = $valeur; }
    
  }
  
  // ==========================================================================
?>
