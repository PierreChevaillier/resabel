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
# description : script shell pour creer les tables d'une des bases de donnees 
#               de Resabel
# utilisation : shell 
#   ./creer_tables_resabel.sh <nom_base_de_donnees> <repertoire_scripts>
#   par exemple : ./creer_tables_resabel.sh resabel ./tables
# dependances :
#  - la base de donnees doit exister
# utilise avec :
#  bash sur macOS 27
# -----------------------------------------------------------------------------
# creation : 19-oct-2026 pchevaillier@gmail.com
# revision : 
# -----------------------------------------------------------------------------
# commentaires :
# - a executer avant le chargement de la base avec les donnees initiales
# attention :
# - mot de passe mysql de l'utilisateur root requis
# a faire :
# -
# =============================================================================

echo "Base de donnees : " $1 " repertoire scripts : " $2

for f in $2/structure_table_rsbl_*.sql
do
  echo ">>>>" $f
  mysql --database=$1 -u root -p < $f
done

# =============================================================================
