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
# description : lance les tests unitaires pour les classes metier
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
 
# Fondamentaux
phpunit --testdox InstantTest.php
phpunit --testdox Intervalle_TemporelTest.php

phpunit --testdox ConnexionTest.php

# Intermediaires
phpunit --testdox MembreTest.php
phpunit --testdox Seance_Activite_Test.php
phpunit --testdox Site_ActiviteTest.php
phpunit --testdox IndisponibiliteTest.php

# Composes
phpunit --testdox Activite_JournaliereTest.php

# =============================================================================
