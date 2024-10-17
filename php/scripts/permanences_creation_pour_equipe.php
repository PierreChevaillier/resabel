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
 * description : script de creation des permanences (apres la derniere enregistree)
 *               pour les membres de la composante 'Permanence'
 * utilisation : php
 * dependances :
 * ----------------------------------------------------------------------------
 * creation : 09-oct-2024 pchevaillier@gmail.com
 * revision :
 * ----------------------------------------------------------------------------
 * commentaires :
 * -
 * attention :
 * -
 * a faire :
 * -
 * ============================================================================
 */
declare(strict_types=1);

set_include_path('./../../');

include 'php/utilitaires/controle_session.php';
include_once('php/utilitaires/definir_locale.php');

// --- connexion a la base de donnees (et instantiation du 'handler')
include_once 'php/bdd/base_donnees.php';

// --- classes utilisees
require_once('php/collecteur/collecteur_permanence.php');
require_once('php/enregistreur/enregistreur_permanence.php');

// ----------------------------------------------------------------------------

$codes_membre = array();

Collecteur_Permanence::collecte_codes_membres_equipe($codes_membre);

Enregistreur_Permanence::ajoute_permanences($codes_membre);

header('location:../../permanences.php');
exit();

// ============================================================================
?>
