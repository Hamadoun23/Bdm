# Campagnes GDA

Suivi des campagnes commerciales du Groupe GDA **pour le compte des banques**
qui lui confient la vente de leurs cartes : ventes sur le terrain, enrôlements
sur l'application mobile, reporting téléphonique, contrats de prestation et
rapports de performance.

Deux clients aujourd'hui — la **BDM**, organisée en agences, et **UBA Mali**,
dont les commerciaux dépendent directement du client pour la carte GDA. Un
administrateur choisit celui qu'il consulte à la connexion ; les données des
deux ne se mélangent jamais. Voir [docs/CLIENTS_GDA.md](docs/CLIENTS_GDA.md).

**Django** en backend, **React** en frontend, reliés par **Inertia.js**.
Le serveur porte toute la donnée et la logique ; React ne fait qu'afficher les
props qu'il reçoit.

Architecture détaillée : [docs/STACK.md](docs/STACK.md).

```
backend/     Django 6.0 — modèles, vues, services, exports
frontend/    React 18 + Vite — 57 pages, aucune règle métier
docs/        Plan de migration, démarrage, procédure de bascule
scripts/     Outillage : rechargement de dump, comptes de test, recette
docker/      Configuration nginx, partagée par la stack locale et la production
```

## Démarrer en local

Deux façons, au choix. **Tout dans Docker** — rien à installer sur la machine :

```bash
docker compose -f docker-compose.local.yml up -d --build
```

Base MySQL, Django derrière gunicorn et nginx démarrent ensemble. Le code de
`backend/` est monté dans le conteneur et gunicorn tourne en `--reload` : une
modification Python est prise en compte sans reconstruire l'image.

**Ou en natif**, si l'on veut le rechargement à chaud de Vite sur le frontend :

```bash
# 1. Base de données seule (MySQL 8, même moteur qu'en production)
docker compose -f docker-compose.dev.yml up -d
scripts/charger_dump_prod.sh bdm_prod_AAAA-MM-JJ_HHMM.sql

# 2. Backend
cd backend
python -m venv .venv
.venv/Scripts/python.exe -m pip install -r requirements.txt
.venv/Scripts/python.exe manage.py migrate
.venv/Scripts/python.exe manage.py runserver 8001

# 3. Frontend
cd frontend
npm install
npm run dev
```

Les deux stacks partagent le volume `bdm_db_dev_data`, donc les mêmes données —
mais elles ne peuvent pas tourner en même temps : le nom du conteneur MySQL
entrerait en conflit. Arrêter l'une avant de lancer l'autre.

L'application répond sur <http://localhost:8001>.
Détails et cas particuliers : [docs/DEMARRAGE_MIGRATION.md](docs/DEMARRAGE_MIGRATION.md).

## Déployer

```bash
docker compose -f docker-compose.django.yml --env-file backend/.env.production up -d --build
```

L'application tourne en production sur <https://bdm.gdamali.net> depuis le
15/08/2026. Laravel reste installé et arrêté sur le serveur : le retour
arrière prend moins d'une minute, voir [docs/RETOUR_ARRIERE.md](docs/RETOUR_ARRIERE.md).

Procédure de déploiement détaillée : [docs/BASCULE_PRODUCTION.md](docs/BASCULE_PRODUCTION.md).

## Rôles

| Rôle | Identifiant de connexion | Accès | Client |
|---|---|---|---|
| Administrateur | son nom | Tout : campagnes, utilisateurs, agences, rapports | au choix |
| Direction | e-mail ou téléphone | Consultation des campagnes, rapports, performances | au choix |
| Commercial | son téléphone | Ventes, enrôlements, contrat de prestation | le sien |
| Commercial téléphonique | son téléphone | Reporting d'appels, contrat de prestation | le sien |

## Vérifier

```bash
# Le cloisonnement entre clients, et la vente UBA de bout en bout.
backend/.venv/Scripts/python.exe scripts/tester_multi_client.py \
    --admin <votre nom> --mot-de-passe-admin '<votre mot de passe>'

# Les écritures, exactement comme le fait le navigateur.
backend/.venv/Scripts/python.exe scripts/tester_ecritures.py

# Les exports produisent-ils des fichiers valides ?
backend/.venv/Scripts/python.exe scripts/verifier_exports.py \n    --admin <votre nom> --mot-de-passe-admin '<votre mot de passe>'
```

## Historique

Cette application était écrite en Laravel jusqu'en août 2026. Le backend a été
porté vers Django à schéma de base identique — aucune migration de données, les
mots de passe bcrypt existants restent valides.

La version Laravel, ses vues Blade et son React d'origine sont conservés dans le
dépôt privé **`bdm-archive`**.

Le détail de la migration, les décisions d'architecture et les écarts assumés
sont documentés dans [docs/PLAN_MIGRATION_DJANGO.md](docs/PLAN_MIGRATION_DJANGO.md).
