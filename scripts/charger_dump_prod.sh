#!/usr/bin/env bash
#
# Recharge la base de développement `bdm_dev` depuis un dump de production.
#
# Le dump de production contient `CREATE DATABASE bdm` et `USE bdm` : chargé
# tel quel, il ÉCRASERAIT la base `bdm` locale de XAMPP. Ce script retire ces
# deux instructions avant l'import, et n'écrit que dans `bdm_dev`.
#
# Usage :
#   scripts/charger_dump_prod.sh chemin/vers/bdm_prod_2026-08-15_1435.sql
#
set -euo pipefail

CONTENEUR="bdm_db_dev"
BASE="bdm_dev"

DUMP="${1:-}"
if [ -z "$DUMP" ] || [ ! -f "$DUMP" ]; then
  echo "Usage : $0 <fichier.sql>" >&2
  echo "Exemple : $0 bdm_prod_2026-08-15_1435.sql" >&2
  exit 1
fi

if ! docker exec "$CONTENEUR" mysqladmin ping -uroot -proot --silent >/dev/null 2>&1; then
  echo "Le conteneur $CONTENEUR ne répond pas." >&2
  echo "Démarrez-le : docker compose -f docker-compose.dev.yml up -d" >&2
  exit 1
fi

TEMPORAIRE="$(mktemp)"
trap 'rm -f "$TEMPORAIRE"' EXIT

echo "Préparation du dump…"
sed -E '/^CREATE DATABASE .*`bdm`/d; /^USE `bdm`;/d' "$DUMP" > "$TEMPORAIRE"

if grep -qE '^(CREATE DATABASE|USE )' "$TEMPORAIRE"; then
  echo "ERREUR : des instructions CREATE DATABASE / USE subsistent." >&2
  echo "Import annulé pour ne pas risquer d'écraser une autre base." >&2
  exit 1
fi

echo "Import dans $BASE…"
docker exec -i "$CONTENEUR" mysql -uroot -proot "$BASE" < "$TEMPORAIRE" 2>&1 \
  | grep -v "Using a password" || true

# Le dump ne recrée que les tables métier : les tables techniques de Django
# restent en place. On rejoue `migrate` au cas où le dump viendrait d'une base
# où elles n'existaient pas encore.
echo "Vérification des tables techniques Django…"
backend/.venv/Scripts/python.exe backend/manage.py migrate --noinput 2>&1 | tail -3

echo
echo "Contenu chargé :"
docker exec "$CONTENEUR" mysql -uroot -proot "$BASE" -e "
SELECT
  (SELECT COUNT(*) FROM users) users,
  (SELECT COUNT(*) FROM agences) agences,
  (SELECT COUNT(*) FROM campagnes) campagnes,
  (SELECT COUNT(*) FROM ventes) ventes,
  (SELECT COUNT(*) FROM clients) clients,
  (SELECT COUNT(*) FROM enrolement_clients) enrolements;" 2>&1 | grep -v "Using a password"

echo
echo "Fraîcheur des données :"
docker exec "$CONTENEUR" mysql -uroot -proot -N -B "$BASE" -e "
SELECT CONCAT('  dernière vente      : ', COALESCE(MAX(created_at),'aucune')) FROM ventes;
SELECT CONCAT('  dernier enrôlement  : ', COALESCE(MAX(created_at),'aucun')) FROM enrolement_clients;
SELECT CONCAT('  dernière connexion  : ', COALESCE(MAX(logged_in_at),'aucune')) FROM user_login_logs;" 2>&1 \
  | grep -v "Using a password"

echo
echo "Terminé. Les mots de passe sont ceux de la production : chacun se connecte"
echo "avec ses identifiants habituels (admins par leur nom, commerciaux par téléphone)."
