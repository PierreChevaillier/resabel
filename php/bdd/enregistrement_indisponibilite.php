<?php
  // ==========================================================================
  // contexte : Resabel - systeme de REServAtion de Bateau En Ligne
  // description : classe Enregistrement_Indisponibilite :
  //               acces a la base de donnees
  // copyright (c) 2018-2024 AMP. Tous droits reserves.
  // --------------------------------------------------------------------------
  // utilisation : php - require_once <chemin_vers_ce_fichier.php>
  // dependances : cf. require_once + classe Base_Donnees
  // teste avec : PHP 7.1 sur Mac OS 10.14 ;
  //              PHP 7.0 sur hebergeur web
// - depuis 2023 :
//   PHP 8.2 sur macOS 13.x et PHPUnit 9.5
  // --------------------------------------------------------------------------
  // creation : 10-jun-2019 pchevaillier@gmail.com
  // revision : 07-jan-2020 pchevaillier@gmail.com indispo support et ferm. site
// revision : 25-apr-2024 pchevaillier@gmail.com struct. code +ajouter +supprimer +modifier
  // --------------------------------------------------------------------------
  // commentaires :
  // - dans collecter : si le site est specifie, il ne faut collecter que
  //   les indisponibilites relatives a ce site.
  // attention :
  // a faire :
// - lire
//$nom_classe = "Fermeture_Site";
//$fermeture_site = new $nom_classe($code);
  // ==========================================================================
  
  require_once 'php/metier/indisponibilite.php';
  require_once 'php/metier/calendrier.php';
  require_once 'php/metier/membre.php';
  require_once 'php/metier/support_activite.php';
  require_once 'php/metier/site_activite.php';
  
  require_once 'php/bdd/enregistrement_site_activite.php';
require_once 'php/bdd/enregistrement_support_activite.php';
  // ==========================================================================
  class Erreur_Type_Indisponibilite extends Exception { }
  
  // ==========================================================================
  class Enregistrement_Indisponibilite {
    
    const CODE_TYPE_INDISPO_SUPPORT = 1;
    const CODE_TYPE_INDISPO_SITE = 2;
    
    private $indisponibilite = null;
    public function def_indisponibilite(Indisponibilite $indisponibilite) {
      $this->indisponibilite = $indisponibilite;
    }
    
    static function source() {
      return Base_Donnees::$prefix_table . 'indisponibilites';
    }
    
  static function sql_jointure_source(): string {
    $code_sql = self::source() . " AS indispo"
    . " INNER JOIN " . Base_Donnees::$prefix_table . "motifs_indisponibilite AS motif ON (indispo.code_motif = motif.code)";
    return $code_sql;
  }
  
  static function sql_jointure_support(): string {
    $code_sql =  " INNER JOIN "
        . Base_Donnees::$prefix_table . "supports AS support ON (indispo.code_objet = support.code) "
        . " INNER JOIN " . Base_Donnees::$prefix_table . "types_support AS type_support ON (support.code_type_support = type_support.code) ";
    return $code_sql;
  }

  static function sql_jointure_site(): string {
    $code_sql =  " INNER JOIN "
      . Base_Donnees::$prefix_table . "sites_activite AS site ON (indispo.code_objet = site.code) ";
    return $code_sql;
  }

  static function sql_selection(string $nom_classe, string $critere_selection) {
    $code_sql = " indispo.nom_classe = \"" . $nom_classe . "\" ";
    if (strlen($critere_selection) > 0)
      $code_sql = $code_sql . "AND " . $critere_selection . " ";
    return $code_sql;
  }

  static function sql_champs_support(): string {
    $code_sql = " support.numero AS numero_support, support.nom AS nom_support, support.code_type_support AS code_type_support, support.code_site_base AS code_site, type_support.code_type AS type_type_support, type_support.nom_court AS nom_type_support ";
    return $code_sql;
  }

  static function sql_champs_site(): string {
    $code_sql = " site.code AS code_site, site.code_type AS type_site, site.nom AS nom_site, site.nom_court AS nom_court_site";
    return $code_sql;
  }

  static function sql_requete_collecter(int $type_indisponibilite,
                               string $critere_selection,
                               string $critere_tri): string {
    $source = self::sql_jointure_source();
    $champs = "indispo.code AS code, information, indispo.code_type AS code_type, date_creation, code_createur, code_motif, code_objet, date_debut, date_fin, motif.nom AS nom_motif, motif.nom_court , ";
    $selection = "";
    
    if ($type_indisponibilite == self::CODE_TYPE_INDISPO_SUPPORT) {
      $source = $source . self::sql_jointure_support();
      $selection = self::sql_selection("Indisponibilite_Support", $critere_selection);
      $champs = $champs . self::sql_champs_support();
    } else {
      $source = $source . self::sql_jointure_site();
      $selection = self::sql_selection("Fermeture_site", $critere_selection);
      $champs = $champs . self::sql_champs_site();
    }
    $tri = "";
    if (strlen($critere_tri) > 0)
      $tri = $tri . $critere_tri . " ";
    else
      $tri = $tri . "indispo.code_objet, indispo.date_debut ";
    
    $code_sql = "SELECT " . $champs
      . " FROM " . $source
      . " WHERE " . $selection
      . " ORDER BY " . $tri;
    return $code_sql;
  }


    static function collecter(Site_Activite $site = NULL,
                         int $type_indisponibilite,
                         string $critere_selection,
                         string $critere_tri,
                         array & $indisponibilites): ? bool {
      $status = false;
      if (!(($type_indisponibilite == self::CODE_TYPE_INDISPO_SUPPORT) || ($type_indisponibilite == self::CODE_TYPE_INDISPO_SITE))) {
        throw new Erreur_Type_Indisponibilite();
        return $status;
      }
      $requete_sql = self::sql_requete_collecter($type_indisponibilite,
                                                $critere_selection,
                                                $critere_tri);
      /*
      $selection = " WHERE indisp.code_type = " . $type_indisponibilite . " ";
      $selection = $selection . ((strlen($critere_selection) > 0) ? " AND " . $critere_selection . " " : "");
      $tri = (strlen($critere_tri) > 0) ? " ORDER BY " . $critere_tri . " " : " ORDER BY indisp.code_objet, indisp.date_debut ";
      $source = self::source() . " AS indisp INNER JOIN rsbl_motifs_indisponibilite AS motif ON (indisp.code_motif = motif.code) ";
      $jointure_objet = " INNER JOIN ";
      $champs_objet = ", ";
      if ($type_indisponibilite == self::CODE_TYPE_INDISPO_SUPPORT) {
        $jointure_objet = $jointure_objet . "rsbl_supports AS support ON (indisp.code_objet = support.code) INNER JOIN rsbl_types_support ON (support.code_type_support = rsbl_types_support.code) ";
        $champs_objet = $champs_objet . "support.numero AS numero_support, support.nom AS nom_support, support.code_type_support AS type_support, support.code_site_base AS code_site, rsbl_types_support.code_type AS type_type_support, rsbl_types_support.nom_court AS nom_type_support ";
      } else {
        $jointure_objet = $jointure_objet . "rsbl_sites_activite AS site ON (indisp.code_objet = site.code) ";
        $champs_objet = $champs_objet . "site.code AS code_site, site.code_type AS type_site, site.nom AS nom_site, site.nom_court AS nom_court_site ";
      }
      $source = $source . $jointure_objet;
      
      $requete = "SELECT indisp.code AS code, information, indisp.code_type AS code_type, date_creation, code_createur, code_motif, code_objet, date_debut, date_fin, motif.nom AS nom_motif, motif.nom_court " . $champs_objet . " FROM " . $source . $selection . $tri;
      */
      //echo PHP_EOL, $requete_sql, PHP_EOL;
      $status = true;
      try {
        $bdd = Base_Donnees::acces();
        $resultat = $bdd->query($requete_sql);
        while ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
          if (is_null($site) || (!is_null($site) && ($site->code() == $donnee->code_site))) {
            if ($donnee->code_type == self::CODE_TYPE_INDISPO_SUPPORT) {
              $indisponibilite = new Indisponibilite_Support($donnee->code);
              if ($donnee->type_type_support == Enregistrement_Support_Activite::CODE_TYPE_BATEAU) {
                $indisponibilite->support = new Bateau($donnee->code_objet);
                $indisponibilite->support->def_numero($donnee->numero_support);
              } elseif ($donnee->type_type_support == Enregistrement_Support_Activite::CODE_TYPE_PLATEAU_ERGO) {
                $indisponibilite->support = new Plateau_Ergo($donnee->code_objet);
              } else {
                $indisponibilite->support = new Support_Activite($donnee->code_objet);
              }
              $indisponibilite->support->def_nom($donnee->nom_support);
              $indisponibilite->support->type = new Type_Support_Activite($donnee->code_type_support);
              $indisponibilite->support->type->def_nom($donnee->nom_type_support);
            } elseif ($donnee->code_type == self::CODE_TYPE_INDISPO_SITE) {
              $indisponibilite = new Fermeture_Site($donnee->code);
              $site_ferme = NULL;
              if (is_null($site)) {
                if ($donnee->type_site == 1) {
                  $site_ferme = new Site_Activite_Mer($donnee->code_objet);
                } elseif ($donnee->type_site == 2) {
                  $site_ferme = new Salle_Sport($donnee->code_objet);
                }
              } else {
                $site_ferme = $site;
              }
              $site_ferme->def_nom($donnee->nom_site);
              $site_ferme->def_nom_court($donnee->nom_court_site);
              $indisponibilite->site_activite = $site_ferme;
            }
            
            $indisponibilite->def_information($donnee->information);
            
            // -- Information sur la creation
            $indisponibilite->def_instant_creation(new Instant($donnee->date_creation));
            if (!is_null($donnee->code_createur)) {
              $p = new Membre($donnee->code_createur);
              $indisponibilite->def_createurice($p);
            }
            
            // Periode d'indisponibilite
            $debut = new Instant($donnee->date_debut);
            $fin = new Instant($donnee->date_fin);
            $indisponibilite->definir_periode($debut, $fin);
            
            // Motif d'indisponibilite
            $motif = new Motif_Indisponibilite($donnee->code_motif);
            $motif->def_nom($donnee->nom_motif);
            $indisponibilite->def_motif($motif);
            
            $indisponibilites[] = $indisponibilite;
          }
          $status = true;
        }
      } catch (PDOException $e) {
            Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $status;
    }
    /*
    static function collecter($critere_selection, $critere_tri, & $indisponibilites) {
      $status = false;
      $indisponibilites = array();
      
      $selection = (strlen($critere_selection) > 0) ? " WHERE " . $critere_selection . " " : "";
      $tri = (strlen($critere_tri) > 0) ? " ORDER BY " . $critere_tri . " " : " ORDER BY indisp.code_objet, indisp.date_debut ";
      try {
        $bdd = Base_Donnees::acces();
        $source = self::source() . " AS indisp INNER JOIN rsbl_motifs_indisponibilite AS motif ON indisp.code_motif = motif.code ";
        $requete = "SELECT indisp.code AS code, information, code_type, date_creation, code_createur, code_motif, code_objet, date_debut, date_fin, motif.nom AS nom_motif, motif.nom_court  FROM " . $source . $selection . $tri;
        //echo "<p>" . $requete . "</p>";
        $resultat = $bdd->query($requete);
        while ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
          if ($donnee->code_type == 1) {
            $indisponibilite = new Indisponibilite_Support($donnee->code);
            $indisponibilite->support = new Support_Activite($donnee->code_objet);
          } elseif ($donnee->code_type == 2) {
            $indisponibilite = new Fermeture_Site($donnee->code);
            $site = Enregistrement_Site_Activite::creer($donnee->code_objet);
            $indisponibilite->site_activite = $site;
          //} else {
          //  $indisponibilite = new Indisponibilite($donnee->code);
          }
          
          $indisponibilite->def_information($donnee->information);
          
          // -- Information sur la creation
          $indisponibilite->instant_creation = new Instant($donnee->date_creation);
          $indisponibilite->createur = new Membre($donnee->code_createur);
          
          // Periode d'indisponibilite
          $indisponibilite->debut = new Instant($donnee->date_debut);
          $indisponibilite->fin = new Instant($donnee->date_fin);
          //$indisponibilite->periode = new Intervalle_Temporel($debut, $fin);
          
          // Motif d'indisponibilite
          $indisponibilite->motif = new Motif_Indisponibilite($donnee->code_motif);
          $indisponibilite->motif->def_nom($donnee->nom_motif);
          
          $indisponibilites[$indisponibilite->code()] = $indisponibilite;
        }
      } catch (PDOException $e) {
        Base_Donnees::sortir_sur_exception(self::source(), $e);
      }
      return $status;
      }
      */
    
  public function ajouter_fermeture_site(): bool {
    if (is_null($this->indisponibilite))
      return false;
    else
      return $this->ajouter(self::CODE_TYPE_INDISPO_SITE);
  }
  
  public function ajouter_indisponibilite_support(): bool {
    if (is_null($this->indisponibilite))
      return false;
    else
      return $this->ajouter(self::CODE_TYPE_INDISPO_SUPPORT);
  }
    
  public function ajouter(int $code_classe): bool {
    $status = true;
    
    $table = self::source();
    $code_sql = "INSERT INTO " . $table
      . " ("
      . " code_type,"
      . " nom_classe,"
      . " date_creation,"
      . " code_createur,"
      . " code_motif,"
      . " code_objet,"
      . " date_debut,"
      . " date_fin,"
      . " information"
      . " ) VALUES ("
      . " :code_type, :nom_classe,"
      . " :date_creation, :code_createur,"
      . " :code_motif, :code_objet,"
      . " :date_debut, :date_fin,"
      . " :information"
      . ")";
    $code = $this->indisponibilite->code();
    $bdd = Base_Donnees::acces();
    try {
      $requete = $bdd->prepare($code_sql);
      
      $requete->bindParam(":code_type", $code_classe, PDO::PARAM_INT);
      $nom_classe = "";
      if ($code_classe == self::CODE_TYPE_INDISPO_SITE) {
        $nom_classe = "Fermeture_Site";
      } else {
        $nom_classe = "Indisponibilite_Support";
      }
      $requete->bindParam(":nom_classe", $nom_classe, PDO::PARAM_STR);

      $date_creation = (Calendrier::maintenant())->date_heure_sql();
      $requete->bindParam(':date_creation', $date_creation, PDO::PARAM_STR);
      $createurice = $this->indisponibilite->createurice();
      $code_createurice = NULL;
      if (!is_null($createurice)) {
        $code_createurice = $createurice->code();
        $requete->bindParam(":code_createur", $code_createurice, PDO::PARAM_INT);
      } else {
        $requete->bindParam(":code_createur", $code_createurice, PDO::PARAM_NULL);
      }
      $code_motif = $this->indisponibilite->motif()->code();
      $requete->bindParam(':code_motif', $code_motif, PDO::PARAM_INT);
      $code_objet = $this->indisponibilite->code_objet();
      $requete->bindParam(":code_objet", $code_objet, PDO::PARAM_INT);
      $debut = $this->indisponibilite->debut()->date_heure_sql();
      $requete->bindParam(':date_debut', $debut, PDO::PARAM_STR);
      $fin = $this->indisponibilite->fin()->date_heure_sql();
      $requete->bindParam(':date_fin', $fin, PDO::PARAM_STR);
      $info = $this->indisponibilite->information();
      $requete->bindParam(':information', $info, PDO::PARAM_STR);
      
      $requete->execute();
      
    } catch (PDOexception $e) {
      die("Erreur Insertion Indisponibilite pour " . $code . " : ligne " . $e->getLine() . " : ". PHP_EOL . $e->getMessage() . PHP_EOL);
    }
    return $status;
  }
    
  public function modifier(): bool {
    if (is_null($this->indisponibilite)) return false;
    if (is_null($this->indisponibilite->motif())) return false;
    
    $status = false;
    $bdd = Base_Donnees::acces();
    
    try {
      $requete= $bdd->prepare("UPDATE " . self::source()
                              . " SET code_motif = :code_motif,"
                              . " code_objet = :code_objet,"
                              . " date_debut = :date_debut,"
                              . " date_fin = :date_fin,"
                              . " information = :information"
                              . " WHERE code = :code_indispo");
      
      $code = $this->indisponibilite->code();
      $requete->bindParam(':code_indispo', $code, PDO::PARAM_INT);
      
      $code_motif = $this->indisponibilite->motif()->code();
      $requete->bindParam(':code_motif', $code_motif, PDO::PARAM_INT);
      $code_objet = $this->indisponibilite->code_objet();
      $requete->bindParam(":code_objet", $code_objet, PDO::PARAM_INT);
      $debut = $this->indisponibilite->debut()->date_heure_sql();
      $requete->bindParam(':date_debut', $debut, PDO::PARAM_STR);
      $fin = $this->indisponibilite->fin()->date_heure_sql();
      $requete->bindParam(':date_fin', $fin, PDO::PARAM_STR);
      $info = $this->indisponibilite->information();
      $requete->bindParam(':information', $info, PDO::PARAM_STR);
      
      $requete->execute();
      $status = true;
    } catch (PDOexception $e) {
      Base_Donnees::sortir_sur_exception(self::source(), $e);
    }
    
    return $status;
  }

  public function supprimer(): bool {
    if (is_null($this->indisponibilite)) return false;
    $status = false;
    $bdd = Base_Donnees::acces();
    $code_sql = 'DELETE FROM ' . self::source()
      . ' WHERE code = ' . $this->indisponibilite->code();
    $n = $bdd->exec($code_sql);
    $status = ($n == 1);
    return $status;
  }
}
  // ==========================================================================
?>
