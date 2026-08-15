# BDM — Campagne GDA

Suivi des campagnes commerciales du Groupe GDA pour la Banque de Développement
du Mali : ventes de cartes sur le terrain, enrôlements sur l'application mobile,
reporting téléphonique, contrats de prestation et rapports de performance.

**Django** en backend, **React** en frontend, reliés par **Inertia.js**.
Le serveur porte toute la donnée et la logique ; React ne fait qu'afficher les
props qu'il reçoit.

```
backend/     Django 6.0 — modèles, vues, services, exports
frontend/    React 18 + Vite — 57 pages, aucune règle métier
docs/        Plan de migration, démarrage, procédure de bascule
scripts/     Outillage : rechargement de dump, comptes de test, recette
docker/      Configuration nginx de production
```

## Démarrer en local

```bash
# 1. Base de données (MySQL 8, même moteur qu'en production)
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

L'application répond sur <http://127.0.0.1:8001>.
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

| Rôle | Identifiant de connexion | Accès |
|---|---|---|
| Administrateur | son nom | Tout : campagnes, utilisateurs, agences, rapports |
| Direction | e-mail ou téléphone | Consultation des campagnes, rapports, performances |
| Commercial | son téléphone | Ventes, enrôlements, contrat de prestation |
| Commercial téléphonique | son téléphone | Reporting d'appels, contrat de prestation |

## Historique

Cette application était écrite en Laravel jusqu'en août 2026. Le backend a été
porté vers Django à schéma de base identique — aucune migration de données, les
mots de passe bcrypt existants restent valides.

La version Laravel, ses vues Blade et son React d'origine sont conservés dans le
dépôt privé **`bdm-archive`**.

Le détail de la migration, les décisions d'architecture et les écarts assumés
sont documentés dans [docs/PLAN_MIGRATION_DJANGO.md](docs/PLAN_MIGRATION_DJANGO.md).
