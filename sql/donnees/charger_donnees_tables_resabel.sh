# !/bin/bash
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
# description : script shell pour les donnees minimales ou initiales 
#               d'une des bases de donnees de Resabel
# utilisation : shell 
#   ./charger_donnees_tables_resabel.sh <nom_base_de_donnees> <repertoire_scripts>
#   par exemple : ./charger_donnees_tables_resabel.sh resabel ./donnees_minimales
# dependances :
#  - les tables de la base de donnees doivent exister
# utilise avec :
#  bash sur macOS 27
# -----------------------------------------------------------------------------
# creation : 19-oct-2026 pchevaillier@gmail.com
# revision : 
# -----------------------------------------------------------------------------
# commentaires :
# - a executer apres la creation des tables de la base de donnees
# attention :
# - mot de passe mysql de l'utilisateur root requis
# a faire :
# -
# =============================================================================

echo "Base de donnees : " $1 " repertoire donnees : " $2

for f in $2/donnees_table_rsbl_*.sql
do
  echo ">>>>" $f
  mysql --database=$1 -u root -p < $f
done

# =============================================================================