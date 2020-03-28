<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classe Enregistrement_Seance_activite :
  //               acces a la base de donnees
  // copyright (c) 2020 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : cf. require_once + classe Base_Donnees
  //               structures des tables
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 18-jan-2020 pchevaillier@gmail.com
  // revision : 08-mar-2020 pchevaillier@gmail.com (suppression participation)
  // --------------------------------------------------------------------------
  // commentaires :
  // - en evolution
  // attention :
  // a faire :
  // ==========================================================================
  
  require_once 'php/metier/calendrier.php';
  require_once 'php/metier/seance_activite.php';
  
  // ==========================================================================
  class Information_Participation_Seance_Activite {
    public $code_seance = 0;
    public $code_site;
    public $code_support_activite;
    public $debut;
    public $fin;
    public $code_participant;
    public $responsable;
  }
  
  // ==========================================================================
  class Enregistrement_Seance_activite {
    
    static function source() {
      return Base_Donnees::$prefix_table . 'seances_activite';
    }
    
    static function collecter(Site_Activite $site = NULL,
                         string $critere_selection,
                         string $critere_tri,
                         array & $seances = null) {
      $status = false;
      if (is_null($seances)) $seance = array();
  
      $selection = (strlen($critere_selection) > 0) ? " WHERE " . $critere_selection . " " : "";
      $tri = (strlen($critere_tri) > 0) ? " ORDER BY " . $critere_tri . " " : " ";
      
      $source = self::source() . " AS seance INNER JOIN rsbl_sites_activite AS site ON (site.code = seance.code_site) INNER JOIN rsbl_participations_activite AS participation ON (participation.code_seance = seance.code) ";
      
      $requete = "SELECT seance.code AS code, seance.code_site AS code_site, seance.code_support AS code_support, seance.date_debut AS date_debut, seance.date_fin AS date_fin, seance.code_responsable AS code_responsable, seance.information AS info_seance, participation.code_membre AS code_participant, participation.information AS info_participation, site.code_type AS code_type_site FROM " . $source . $selection . $tri;
      
      //echo '<p>', $requete, '</p>', PHP_EOL;
      try {
        $bdd = Base_Donnees::accede();
        $resultat = $bdd->query($requete);
        $code_seance_courante = 0;
        $seance = null;
        while ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
          if (is_null($site) || (!is_null($site) && ($site->code() == $donnee->code_site))) {
            if ($donnee->code != $code_seance_courante) {
              $seance = new Seance_Activite();
              $seance->def_code($donnee->code);
              $seances[] = $seance;
              
              if ($donnee->code_type_site == 1) {
                $seance->site = new Site_Activite_Mer($donnee->code_type_site);
              } elseif ($donnee->code_type == 2) {
                $seance->site_activite = new Salle_Sport($donnee->code_type_site);
              }
    
              $seance->support = new Support_activite($donnee->code_support);
              $seance->definir_horaire(new Instant($donnee->date_debut), new Instant($donnee->date_fin));
              if (strlen($donnee->code_responsable) > 0)
                $seance->responsable = new Membre($donnee->code_responsable);
              $seance->information = $donnee->info_seance;
              $code_seance_courante = $donnee->code;
            }
            $participant = new Membre($donnee->code_participant);
            $participation = $seance->creer_participation($participant, false);
            $participation->information = $donnee->info_participation;
          }
        }
      } catch (PDOException $e) {
            Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $status;
    }
    
    static function ajouter_participation(Information_Participation_Seance_Activite $infos) {
      $status = false;
      $bdd = Base_Donnees::accede();
      
      $bdd->beginTransaction();
      
      // test si seance existe deja
      $nouvelle_seance = false;
      try {
        $requete= $bdd->prepare("SELECT COUNT(*) as n FROM " . self::source() . " WHERE code = :code_seance");
        $code_seance = $infos->code_seance;
        $requete->bindParam(':code_seance', $code_seance, PDO::PARAM_INT);
        $requete->execute();
        if ($resultat = $requete->fetch(PDO::FETCH_OBJ)) {
         $nouvelle_seance = ($resultat->n == 0);
        }
      } catch (PDOexception $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
    
      // si nouvelle seance = creation seance
      $code_seance = $infos->code_seance;
      if ($nouvelle_seance) {
        // creation de l'enregistrement de la seance
        try {
          $code_sql = "INSERT INTO " . self::source()
          . " (code_site, code_support, date_debut, date_fin, code_responsable, information) VALUES"
          . " (:code_site, :code_support, :debut, :fin, :code_resp, \"\")";
          $requete= $bdd->prepare($code_sql);
              
          $requete->bindParam(':code_site', $infos->code_site, PDO::PARAM_INT);
          $requete->bindParam(':code_support', $infos->code_support_activite, PDO::PARAM_INT);
          $requete->bindParam(':debut', $infos->debut, PDO::PARAM_STR);
          $requete->bindParam(':fin', $infos->fin, PDO::PARAM_STR);
          if ($infos->responsable == 1)
            $requete->bindParam(':code_resp', $infos->code_participant, PDO::PARAM_INT);
          else
            $requete->bindParam(':code_resp', $infos->code_participant, PDO::PARAM_NULL);
          $requete->execute();
               
        } catch (PDOexception $e) {
          die("Erreur Mise a jour " . self::source() . " informations pour " . $code . " : ligne " . $e->getLine() . " :<br /> ". $e->getMessage());
          //Base_Donnees::sortir_sur_exception(self::source(), $e);
        }
        
        // il faut le code de la seance qui vient d'etre ajoutee pour inserer la participation...
        try {
          $code_sql = "SELECT MAX(code) as x FROM " . self::source();
          $requete = $bdd->prepare($code_sql);
          $requete->execute();
          if ($resultat = $requete->fetch(PDO::FETCH_OBJ)) {
             $code_seance = $resultat->x;
          }
        } catch (PDOexception $e) {
          Base_Donnees::sortir_sur_exception(self::source(), $e);
        }
      } else {
        // la seance existe deja. Si le participant est responsable de la seance
        // alors mise a jour de l'information.
        if ($infos->responsable == 1) {
          try {
            $requete = $bdd->prepare("UPDATE " . self::source() . " SET code_responsable = :code_resp WHERE code = :code_seance");
            $requete->bindParam(':code_seance', $code_seance, PDO::PARAM_INT);
            $requete->bindParam(':code_resp', $infos->code_participant, PDO::PARAM_INT);
            $requete->execute();
          } catch (PDOexception $e) {
            Base_Donnees::sortir_sur_exception(self::source(), $e);
          }
        }
      }
      
      // creation participation
      try {
         $code_sql = "INSERT INTO " . Base_Donnees::$prefix_table . "participations_activite"
         . " (code_seance, code_membre, information) "
         . " VALUES (" . $code_seance . ", " . $infos->code_participant . ", \"\")";
         $requete= $bdd->prepare($code_sql);
         $requete->execute();
      } catch (PDOexception $e) {
        die("Erreur Mise a jour " . self::source() . " informations pour " . $code . " : ligne " . $e->getLine() . " :<br /> ". $e->getMessage());
        //Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      
      // fin transaction
      $bdd->commit();
      return $status;
    }
    
    static function supprimer_participation(Information_Participation_Seance_Activite $infos) {
      $status = false;
      $code_seance = $infos->code_seance;
      $bdd = Base_Donnees::accede();
      $bdd->beginTransaction();
      
      try {
        // supprimer la participation
        $source = Base_Donnees::$prefix_table . "participations_activite";
        $code_sql = "DELETE FROM " . $source . " WHERE code_seance = " . $code_seance . " AND code_membre = " . $infos->code_participant;
        $requete = $bdd->prepare($code_sql);
        $requete->execute();
      } catch (PDOexception $e) {
         Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      
      // si plus de participation, alors supprimer la seance
      try {
        $n_participants = 0;
        $source = Base_Donnees::$prefix_table . "participations_activite";
        $requete = $bdd->prepare("SELECT COUNT(*) as n FROM " . $source . " WHERE code_seance = :code_seance");
        $requete->bindParam(':code_seance', $code_seance, PDO::PARAM_INT);
        $requete->execute();
        if ($resultat = $requete->fetch(PDO::FETCH_OBJ)) {
          $n_participants = $resultat->n;
        }
      } catch (PDOexception $e) {
       Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      
      if ($n_participants == 0) {
        // plus de participant inscrit pour la seance, alors on la supprime
        try {
          $requete = $bdd->prepare("DELETE FROM " . self::source() . " WHERE code = :code_seance");
          $requete->bindParam(':code_seance', $code_seance, PDO::PARAM_INT);
          $requete->execute();
        } catch (PDOexception $e) {
          Base_Donnees::sortir_sur_exception(self::source(), $e);
        }
      } else {
        // encore des participants
        // si le participant etait le responsable, mettre le champ a NULL
        if ($infos->responsable == 1) {
          try {
            $requete = $bdd->prepare("UPDATE " . self::source() . " SET code_responsable = NULL WHERE code = :code_seance");
            $requete->bindParam(':code_seance', $code_seance, PDO::PARAM_INT);
            $requete->execute();
          } catch (PDOexception $e) {
            Base_Donnees::sortir_sur_exception(self::source(), $e);
          }
        }
      }
      $bdd->commit();
      $status = true;
      return $status;
    }
    
  }
  // ==========================================================================
?>
