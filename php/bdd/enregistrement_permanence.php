<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classe Enregistrement_Permanence: acces a la base de donnees
  // copyright (c) 2018-2019 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : cf. require_once + classe Base_Donnees
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
  // --------------------------------------------------------------------------
  // creation : 28-mai-2019 pchevaillier@gmail.com (a partir de resabel V1)
  // revision :
  // --------------------------------------------------------------------------
  // commentaires :
  // -
  // attention :
  // - non teste
  // a faire :
  // -
  // ==========================================================================
  
  require_once 'php/metier/permanence.php';

require_once 'php/metier/personne.php';
  // ==========================================================================
  class Enregistrement_Permanence {
    public $permanence = null;
    public function permanence(): ?Permanence { return $this->permanence; }
    public function def_permanence(Permanence $permanence): void { $this->permanence = $permanence; }
    
    static function source(): string {
      return Base_Donnees::$prefix_table . 'permanences';
    }
    
    public function lire(): bool {
      $trouve = false;
      try {
        $bdd = Base_Donnees::acces();
        $table_personnes =  Base_Donnees::$prefix_table . 'membres';
        $code_sql = "SELECT annee, semaine, code_membre, genre, prenom, nom, telephone, courriel FROM " . self::source() . " AS perm INNER JOIN " . $table_personnes . " AS pers ON perm.code_membre = pers.code WHERE perm.annee = :annee AND perm.semaine = :semaine LIMIT 1";
       
        $requete= $bdd->prepare($code_sql);
        $annee = $this->permanence->annee();
        $requete->bindParam(':annee', $annee, PDO::PARAM_INT);
        $semaine = $this->permanence->semaine();
        $requete->bindParam(':semaine', $semaine, PDO::PARAM_INT);
        //echo "<p>" . $code_sql . "<br />annee= " . $annee . " - semaine= " . $semaine . "</p>";
        $requete->execute();
        if ($donnee = $requete->fetch(PDO::FETCH_OBJ)) {
          $responsable = new Personne($donnee->code_membre);
          $responsable->def_genre($donnee->genre);
          $responsable->def_prenom($donnee->prenom);
          $responsable->def_nom($donnee->nom);
          $responsable->def_telephone($donnee->telephone);
          $responsable->def_courriel($donnee->courriel);
          $this->permanence->def_responsable($responsable);
          $trouve = true;
        } else {
          return $trouve;
        }
      } catch (PDOexception $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $trouve;
    }
    
    /*
     * Test si la personne passee en argument est celle de permanence
     * recherche de l'information dans la base de donnees
     */
    public function a_comme_responsable(Personne $personne): bool {
      if (is_null($this->permanence) || is_null($personne)) return false;
      $reponse = false;
      try {
        $bdd = Base_Donnees::acces();
        $requete= $bdd->prepare("SELECT COUNT(*) AS n FROM " . self::source() . " WHERE annee = :annee AND semaine = :semaine AND code_membre = :code_membre");
        $annee = $this->permanence->annee();
        $requete->bindParam(':annee', $annee, PDO::PARAM_INT);
        $semaine = $this->permanence->semaine();
        $requete->bindParam(':semaine', $semaine, PDO::PARAM_INT);
        $code_membre = $personne->code();
        $requete->bindParam(':code_membre', $code_membre, PDO::PARAM_INT);
        
        $requete->execute();
        if ($resultat = $requete->fetch(PDO::FETCH_OBJ))
          $reponse = ($resultat->n == 1);
      } catch (PDOException  $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $reponse;
    }
    
    
    function collecter_futures(& $futures) {
      $status = false;
      $futures = array();
      try {
        $bdd = Base_Donnees::acces();
        $table_personnes =  Base_Donnees::$prefix_table . 'membres';
        $annee = $this->permanence->annee();
        $semaine = $this->permanence->semaine();
        $code_sql = "SELECT annee, semaine, code_membre, genre, prenom, nom, telephone, courriel FROM " . self::source() . " AS perm INNER JOIN " . $table_personnes . " AS pers ON perm.code_membre = pers.code WHERE perm.annee  >= " . $annee . " ORDER BY perm.annee, perm.semaine";
        
        $resultat = $bdd->query($code_sql);
        
        while ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
          if ((($donnee->annee == $annee) && ($donnee->semaine >= $semaine)) || ($donnee->annee > $annee)) {
            // que les semaines dans le futur
            $future = new Permanence($donnee->semaine,$donnee->annee);
            $responsable = new Personne($donnee->code_membre);
            $responsable->def_genre($donnee->genre);
            $responsable->def_prenom($donnee->prenom);
            $responsable->def_nom($donnee->nom);
            $responsable->def_telephone($donnee->telephone);
            $responsable->def_courriel($donnee->courriel);
            $future->def_responsable($responsable);
            $futures[] = $future;
          }
        }
      } catch (PDOException $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $status;
      }
    
    static function recherche_derniere(): ?Permanence {
      $permanence_trouvee = null;
      $requete = "SELECT annee, semaine, code_membre FROM " . self::source() . " ORDER by annee DESC, semaine DESC LIMIT 1";
      $bdd = Base_Donnees::acces();
      $resultat = $bdd->query($requete);
      if ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
        $permanence_trouvee = new Permanence($donnee->semaine, $donnee->annee);
        $responsable = new Personne($donnee->code_membre);
        $permanence_trouvee->def_responsable($responsable);
      }
      return $permanence_trouvee;
    }
    
    public function enregistre() {
      // TODO
      $fait = false;
      if ($this->permanence == null)
        return $fait;
      try {
        $bdd = Base_Donnees::acces();
        $annee = $this->permanence->annee();
        $semaine = $this->permanence->semaine();
        $code_responsable = $this->permanence->code_responsable();
      
        $code_sql = "INSERT INTO permanences VALUES(:annee, :semaine, :code_responsable";
        echo $code_sql . "<br />";
        $requete= $bdd->prepare($code_sql);
        $requete->bindParam(':annee', $annee, PDO::PARAM_INT);
        $requete->bindParam(':semaine', $semaine, PDO::PARAM_INT);
        $requete->bindParam(':code_responsable', $code_responsable, PDO::PARAM_INT);
      
        $requete->execute();
      } catch (PDOException $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $fait;
    }
    
    public function change_responsable(int $code_membre): bool {
      if (is_null($this->permanence) || ($code_membre <= 0)) return false;
      $fait = false;
      $code_sql = "UPDATE " . self::source()
        . " SET code_membre = '" . $code_membre
        . "' WHERE annee = '" . $this->permanence->annee()
        . "' AND semaine = '" . $this->permanence->semaine()
        . "' LIMIT 1";
      try {
        $bdd = Base_Donnees::acces();
        $bdd->query($code_sql);
        $fait = true;
      } catch (PDOException $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $fait;
    }
    
  }
  // ==========================================================================
?>
