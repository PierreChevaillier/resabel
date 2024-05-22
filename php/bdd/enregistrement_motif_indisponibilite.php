<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description : Definition de la classe Enregistrement_Motif_Indisponibilite :
 //               acces a la base de donnees
 * copyright (c) 2024-2024 AMP. Tous droits reserves.
 * ----------------------------------------------------------------------------
 * utilisation : php require_once <chemin_vers_ce_fichier.php>
 * dependances :
 * - aucune
 * utilise avec :
 *   PHP 8.2 sur macOS 13.x
 *   PHP 8.1 sur hebergeur web
 * ----------------------------------------------------------------------------
 * creation : 15-may-2024 pchevaillier@gmail.com
 * revision :
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
 * a faire :
 *-
 * ============================================================================
 */
 
require_once 'php/metier/indisponibilite.php';
require_once 'php/bdd/base_donnees.php';

// ============================================================================
class Enregistrement_Motif_Indisponibilite {
  
  static function source() {
    return Base_Donnees::$prefix_table . 'motifs_indisponibilite';
  }
  
  static function collecter(int $type_indisponibilite, array & $motifs): bool {
    $ok = true;
    $requete_sql = 'SELECT * FROM '. self::source()
    . ' WHERE code_type_indisponibilite = ' . $type_indisponibilite;
    try {
      $bdd = Base_Donnees::acces();
      $resultat = $bdd->query($requete_sql);
      while ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
        $code = $donnee->code;
        $motif = new Motif_Indisponibilite($code);
        $motif->def_nom($donnee->nom);
        //$motif->def_nom_court($donnee->nom_court);
        $motifs[$code] = $motif;
      }
      $ok = true;
    } catch (PDOException $e) {
          Base_Donnees::sortir_sur_exception(self::source(), $e);
    }
    return $ok;
  }
  
}
// ============================================================================
?>
