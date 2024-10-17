#/bin/ssh
# =============================================================================
# Resabel - systeme de REServAtion de Bateau En Ligne
# Copyright (C) 2024 Pierre Chevaillier
# contact: pchevaillier@gmail.com 70 allee de Broceliande, 29200 Brest, France
# -----------------------------------------------------------------------------
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License,
# or any later version.
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
# See the GNU General Public License for more details.
# You should have received a copy of the GNU General Public License
# along with this program. If not, see <https://www.gnu.org/licenses/>.
# -----------------------------------------------------------------------------
# description : lance les tests unitaires pour les classes 'bdd',
#               sous-systeme des classes responsables des acces a la base de donnees
# utilisation : unix shell
# dependances :
# - existence des fichiers des classes de test
# -----------------------------------------------------------------------------
# creation : 11-oct-2024 pchevaillier@gmail.com
# revision :
# -----------------------------------------------------------------------------
# commentaires :
# -
# attention :
# -
# a faire :
# -
# =============================================================================

phpunit --testdox Enregistrement_ConnexionTest.php
phpunit --testdox Enregistrement_ClubTest.php
phpunit --testdox Enregistrement_IndisponibiliteTest.php
phpunit --testdox Enregistrement_MembreTest.php
phpunit --testdox Enregistrement_PermanenceTest.php
phpunit --testdox Enregistrement_Seance_ActiviteTest.php
phpunit --testdox Enregistrement_Site_ActiviteTest.php

# =============================================================================
