<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : Acces aux informations stockees en base de donnees
  //               sur la structure organisationnelle : composantes, roles...
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : 
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 25-mai-2019 pchevaillier@gmail.com
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // -
  // a faire : TOUT
  // - ajouter le club dans toutes les tables
  // lister les roles assures par une personne au sein de differentes composantes
  // - SELECT * FROM `rsbl_roles_membres` INNER JOIN rsbl_roles ON rsbl_roles.code = rsbl_roles_membres.code_role  INNER JOIN rsbl_composantes ON rsbl_composantes.code = rsbl_roles_membres.code_composante WHERE code_membre = 101 ORDER BY rsbl_composantes.nom
  
  // ==========================================================================

  require_once 'php/metier/struct_orga.php';
  require_once 'php/metier/membre.php';

  class Enregistrement_Composante {
    static function source() {
      return Base_Donnees::$prefix_table . 'composantes';
    }
    
    //private $composante = null;
    //public function composante() { return $this->composante; }
    //public function def_composante($composante) { $this->composante = $composante; }
    
    static function collecter($code_club, & $composantes) {
      $status = false;
      try {
        $bdd = Base_Donnees::accede();
        $requete = "SELECT * FROM " . self::source() . " WHERE code_club = " . $code_club . " ORDER BY nom";
        $resultat = $bdd->query($requete);
        while ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
          $composante= new Composante($donnee->code);
          $composante->def_genre($donnee->genre);
          $composante->def_nom_court($donnee->nom_court);
          $composante->def_nom($donnee->nom);
          $composante->def_courriel_contact($donnee->courriel_contact);
          $composante->def_liste_diffusion($donnee->liste_diffusion);
        
          $composantes[$composante->code()] = $composante;
        }
      } catch (PDOException $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $status;
    }
  }
  
  class Enregistrement_Entite_Organisationnelle {
    
    static function collecter(& $entite_orga) {
      $status = false;
      try {
        $bdd = Base_Donnees::accede();
        $requete = "SELECT RC.code_composante, R.nom_masculin, R.nom_feminin, RC.code_role, RC.rang, RM.code_membre, M.genre, M.prenom, M.nom, M.telephone, M.courriel FROM `rsbl_roles_composantes` AS RC INNER JOIN rsbl_roles_membres AS RM on (RC.code_composante = RM.code_composante AND RC.code_role = RM.code_role) INNER JOIN rsbl_membres AS M ON RM.code_membre = M.code INNER JOIN rsbl_roles AS R ON R.code = RC.code_role ORDER BY RC.code_composante, RC.rang, RM.rang, M.prenom, M.nom";
        $resultat = $bdd->query($requete);
        while ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
          $role_membre = new Role_Membre();
          
          // Informations sur la personne qui a ce role dans la composante
          $membre = new Membre($donnee->code_membre);
          $membre->def_genre($donnee->genre);
          $membre->def_prenom($donnee->prenom);
          $membre->def_nom($donnee->nom);
          $membre->def_telephone($donnee->telephone);
          $membre->def_courriel($donnee->courriel);
          $role_membre->membre = $membre;
          
          // La composante dans laquelle la personne a un role
          $rc =  new Role_Composante();
          $rc->composante = new Composante($donnee->code_composante);
          $role = new Role($donnee->code_role);
          $role->nom_feminin = $donnee->nom_feminin;
          $role->nom_masculin = $donnee->nom_masculin;
          
          $rc->role = $role;
          
          $rc->rang_role = $donnee->rang;
          
          $role_membre->role_composante = $rc;
        
          $entite_orga[] = $role_membre;
        }
      } catch (PDOException $e) {
        Base_Donnees::sortir_sur_exception('rsbl_roles_composantes', $e);
      }
      return $status;
    }
  }
  // ==========================================================================
?>
