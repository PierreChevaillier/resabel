
for f in rsbl_*.sql
do
  echo ">>>>" $f
  mysql resabel < $f 
done
#mysql resabel < rsbl_motifs_indisponibilite.sql

