<?php
/* ============================================================================
 * contexte : Resabel - systeme de REServAtion de Bateau En Ligne
 * description : Definition de la classe Enregistrement_Type_Support_Activite :
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
 * creation : 31-may-2024 pchevaillier@gmail.com
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
 
require_once 'php/metier/support_activite.php';
require_once 'php/bdd/base_donnees.php';

// ============================================================================
class Enregistrement_Type_Support_Activite {
  
  static function source() {
    return Base_Donnees::$prefix_table . 'types_support';
  }
  
  static function collecter_identites(int $code_classe_support,
                            array & $types_support): bool {
    $ok = true;
    $requete_sql = 'SELECT * FROM '. self::source()
    . ' WHERE code_type = ' . $code_classe_support;
    try {
      $bdd = Base_Donnees::acces();
      $resultat = $bdd->query($requete_sql);
      while ($donnee = $resultat->fetch(PDO::FETCH_OBJ)) {
        $code = $donnee->code;
        $type_support = new Type_Support_Activite($code);
        $type_support->def_nom($donnee->nom);
        $types_support[$code] = $type_support;
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
