# Environnement de développement de la migration

Trois services à lancer. Rien ici ne touche à la production ni à ta base
`bdm` locale sous XAMPP.

## 1. Base de données (MySQL 8, comme en prod)

```bash
docker compose -f docker-compose.dev.yml up -d
```

MySQL 8.0 sur **127.0.0.1:3307**, base `bdm_dev`, root/root.
Volontairement pas le MariaDB 10.4 de XAMPP : les rapports contiennent 51
requêtes SQL brutes dont le comportement diffère entre les deux moteurs.

**Charger (ou recharger) le dump de production :**

```bash
# On retire CREATE DATABASE / USE : sans ça, le dump écraserait la base `bdm` locale.
sed -E '/^CREATE DATABASE .*`bdm`/d; /^USE `bdm`;/d' bdm_prod_2026-08-13_1445.sql > /tmp/bdm_dev.sql
docker exec -i bdm_db_dev mysql -uroot -proot bdm_dev < /tmp/bdm_dev.sql
```

## 2. Backend Django — port 8001

```bash
cd backend
.venv/Scripts/python.exe manage.py runserver 8001
```

Premier lancement uniquement :

```bash
cd backend
python -m venv .venv
.venv/Scripts/python.exe -m pip install -r requirements.txt
.venv/Scripts/python.exe manage.py migrate   # crée les 6 tables techniques Django, aucune table métier
```

> Le port 8000 est déjà occupé par un autre projet sur cette machine, d'où 8001.

## 3. Frontend React — port 5173

```bash
cd frontend
npm install     # première fois
npm run dev
```

Django sert les pages, Vite sert les assets avec rechargement à chaud.
Pour un rendu en mode production : `npm run build` puis `VITE_DEV=false` dans `backend/.env`.

---

## Banc de comparaison Laravel ↔ Django

C'est le filet de sécurité de la migration. Les deux stacks parlent le même
protocole Inertia : branchées sur la même base, elles doivent renvoyer des
props identiques.

**Lancer Laravel sur la base de comparaison** (n'utilise pas ton `.env`, mais
`.env.comparaison` qui pointe sur `bdm_dev:3307`) :

```bash
php artisan serve --env=comparaison --port=8002
```

**Les trois vérifications :**

```bash
# Props Inertia, route par route, sur les 4 rôles — le filet principal.
backend/.venv/Scripts/python.exe scripts/comparer_stacks.py

# Table de routes : ce qui reste à porter, et les URI qui divergeraient.
backend/.venv/Scripts/python.exe scripts/comparer_routes.py

# Exports : statut, type MIME et validité du fichier produit.
backend/.venv/Scripts/python.exe scripts/verifier_exports.py
```

Sorties attendues :

```
Aucun écart — les deux stacks produisent les mêmes props
Toutes les routes Laravel sont portées à l'identique.
Tous les exports produisent un fichier valide.
```

> Sous Git Bash, préfixer par `MSYS_NO_PATHCONV=1` : sans quoi les chemins
> passés en argument (`/dashboard`) sont réécrits en chemins Windows.

**À chaque route portée, ajouter son chemin dans la liste `ROUTES` du script.**
Une route qui n'y figure pas n'est pas couverte.

### Comptes de test

Créés uniquement dans `bdm_dev`, jamais en production. Le script refuse de
s'exécuter sur une autre base :

```bash
backend/.venv/Scripts/python.exe scripts/creer_comptes_test.py
```

| Identifiant | Rôle |
|---|---|
| `test.migration@bdm.local` | admin |
| `test.direction@bdm.local` | direction |
| `test.commercial@bdm.local` | commercial |
| `test.telephonique@bdm.local` | commercial téléphonique |

Mot de passe commun : `TestMigration#2026`.

Les deux comptes commerciaux sont rattachés à une agence de la campagne active
et marqués signataires du contrat : sans cela, les écrans de vente et de
contrat resteraient vides et ne prouveraient rien.

---

## Ports utilisés

| Service | Port | Note |
|---|---|---|
| Django | 8001 | 8000 déjà pris par un autre projet |
| Vite | 5173 | assets uniquement |
| Laravel (comparaison) | 8002 | lancé à la demande |
| MySQL 8 dev | 3307 | 3306 = MariaDB XAMPP, laissé tranquille |

## Ce qui n'est pas touché

- La production.
- La base `bdm` du MariaDB XAMPP (ton Laravel local).
- Le fichier `.env` de Laravel.
- Le code Laravel : il reste la référence vivante pour la comparaison jusqu'à
  la bascule.


---

## Aller plus loin

- [PLAN_MIGRATION_DJANGO.md](PLAN_MIGRATION_DJANGO.md) — l'architecture retenue, les lots et l'état d'avancement
- [BASCULE_PRODUCTION.md](BASCULE_PRODUCTION.md) — déployer en production et revenir en arrière
