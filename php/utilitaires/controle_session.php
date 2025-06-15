<?php
/* ============================================================================
 * Resabel - systeme de REServAtion de Bateau En Ligne
 * Copyright (C) 2024 Pierre Chevaillier
 * contact: pchevaillier@gmail.com 70 allee de Broceliande, 29200 Brest, France
 * ----------------------------------------------------------------------------
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License,
 * or any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 * ----------------------------------------------------------------------------
 * description : verifiication session active
 * utilisation : php include <chemin_vers_ce_fichier.php>
 *               premiere instruction sur toutes les pages (ou scripts)
 *               sauf celle de connexion
 *               ainsi que le script de verification de l'identite
 * dependances :
 * - aucune
 * ----------------------------------------------------------------------------
 * creation : 14-oct-2018 pchevaillier@gmail.com
 * revision : 18-apr-2025 pchevaillier@gmail.com
 * ----------------------------------------------------------------------------
 * commentaires :
 * - partiellement inspire par une solution proposee par chatGPT
 * attention :
 * -
 * a faire :
 * -
 * ============================================================================
 */

session_start();

// verifie que la variable d'identification et bien definie
if (!isset($_SESSION['usr']) && !isset($_SESSION['clb'])) {
  header("location: index.html");
  die();
}

$duree_max_inactivite = 30 * 60;
if (isset($_SESSION['derniere_activite'])
    && (time() - $_SESSION['derniere_activite'] > $duree_max_inactivite)) {
  // L'utilisateur a ete inactif pendant plus du temps max autorise
  session_unset();
  session_destroy();
  header("location: index.html");
exit();
}
    
$_SESSION['derniere_activite'] = time();

//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
error_reporting(-1);

// ============================================================================
?>
