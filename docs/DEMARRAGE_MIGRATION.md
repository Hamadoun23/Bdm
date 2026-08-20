# Environnement de développement

Rien ici ne touche à la production ni à ta base `bdm` locale sous XAMPP.

Deux montages possibles : **tout dans Docker** (le plus simple), ou **natif**
avec seulement la base en conteneur (utile pour le rechargement à chaud de
Vite). Les deux lisent le même volume de données, mais ne peuvent pas tourner
simultanément — le nom du conteneur MySQL entrerait en conflit.

---

## Option A — tout dans Docker

```bash
docker compose -f docker-compose.local.yml up -d --build
```

Trois conteneurs :

| Conteneur | Rôle | Exposé sur |
|---|---|---|
| `bdm_db_dev` | MySQL 8, base `bdm_dev` | `127.0.0.1:3307` |
| `bdm_django_local` | Django + gunicorn (`--reload`) | interne |
| `bdm_web_local` | nginx : statiques, médias, proxy | `127.0.0.1:8001` |

`backend/` et `frontend/dist` sont montés depuis l'hôte : le code Python est
rechargé à chaud, et un `npm run build` suffit à rafraîchir les assets — sans
reconstruire l'image.

```bash
docker compose -f docker-compose.local.yml logs -f django       # suivre les logs
docker compose -f docker-compose.local.yml exec django python manage.py migrate
docker compose -f docker-compose.local.yml down                 # arrêter (garde les données)
```

Reconstruire l'image n'est nécessaire qu'après un changement de dépendances
(`requirements.txt`, `package.json`) : `up -d --build`.

Le port 3307 reste exposé : les scripts de recette et le chargement de dump
attaquent la base depuis l'hôte, comme avant.

---

## Option B — natif, base en conteneur

Trois services à lancer.

### 1. Base de données (MySQL 8, comme en prod)

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

### 2. Backend Django — port 8001

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

### 3. Frontend React — port 5173

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

### Avec quels comptes se connecter

La base de développement ne contient que les comptes repris de la production,
**avec leurs mots de passe d'origine** : on se connecte avec ses identifiants
habituels. Les scripts de recette les prennent en argument plutôt que d'en
imposer un connu.

Seule exception : les commerciaux UBA, qui n'existent pas en production. Ils se
connectent par téléphone, avec le mot de passe que leur attribue
`scripts/acces_commerciaux_uba.py` (cf. [CLIENTS_GDA.md](CLIENTS_GDA.md)).

`scripts/creer_comptes_test.py` sait encore fabriquer quatre comptes `test.*`
à mot de passe commun, un par rôle, si l'on veut éprouver un rôle dont on n'a
pas d'identifiant sous la main. Ils sont à supprimer ensuite : ils polluent les
listes d'utilisateurs et les classements.

---

## Ports utilisés

| Service | Port | Note |
|---|---|---|
| Application | 8001 | nginx en option A, Django en option B — même port |
| Vite | 5173 | option B seulement, assets uniquement |
| Laravel (comparaison) | 8002 | lancé à la demande |
| MySQL 8 dev | 3307 | 3306 = MariaDB XAMPP, laissé tranquille |

Le port 8001 est le même dans les deux montages : c'est celui que reconnaît la
configuration CSRF de Django (`CSRF_TRUSTED_ORIGINS`).

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
