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
 * description : destruction des donnees de session lors de la deconnexion
 * utilisation : include <chemin_vers_ce_fichier.php>
 * dependances :
 * - aucune
 * ----------------------------------------------------------------------------
 * creation : 29-dec-2018 pchevaillier@gmail.com
 * revision : 18-apr-2025 pchevaillier@gmail.com
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
 * a faire :
 * -
 * ============================================================================
 */

 session_start(); // doit etre la premiere instruction

/*
  unset($_SESSION['clb']);
  unset($_SESSION['n_clb']);
  unset($_SESSION['prs']);
  unset($_SESSION['usr']);
  unset($_SESSION['n_usr']);
  unset($_SESSION['act']);
  unset($_SESSION['cdb']);
  unset($_SESSION['prm']);
  unset($_SESSION['adm']);
*/
session_unset(); //$_SESSION = array();
session_destroy();

header("location: ../../index.html");
exit();
// ============================================================================
?>
