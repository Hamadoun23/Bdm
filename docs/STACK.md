# La stack technique des campagnes GDA

Django porte la donnée et la logique. React affiche. Inertia relie les deux.
Il n'y a ni API REST à maintenir, ni routeur côté client, ni gestionnaire
d'état : le serveur envoie une page et ses props, React les rend.

---

## Vue d'ensemble

```
navigateur ──HTTPS──► nginx de l'hôte  (TLS, Let's Encrypt)
                          │
                          ▼  127.0.0.1:8092
                    nginx (conteneur)      /static/ et /storage/ servis ici
                          │
                          ▼  django:8000
                    gunicorn — 3 workers
                          │
                    Django 6.0 ──► MySQL 8.0
```

| Couche | Technologie | Rôle |
|---|---|---|
| Interface | React 18 + Vite 7 | 57 pages, aucune règle métier |
| Liaison | Inertia.js 2 | Transporte `composant + props` |
| Serveur | Django 6.0 + gunicorn | Modèles, vues, services, exports |
| Base | MySQL 8.0 | Schéma hérité de Laravel, inchangé |
| Diffusion | nginx ×2 | TLS en frontal, statiques en second |

---

## Pourquoi Inertia

Une page Django ne renvoie pas du HTML mais un couple `(nom de page, props)` :

```python
return render(request, "Rapports/CampagneSynthese", {
    "campagne": {...},
    "resume": {...},
})
```

React reçoit ces props et affiche. Aucun `fetch`, aucune route côté client,
aucun cache à invalider — le serveur reste la seule source de vérité.

C'est ce qui a permis de reprendre les **57 pages sans les modifier** lors de la
migration depuis Laravel : Inertia parle le même protocole des deux côtés.

---

## Organisation du code

```
backend/
├── config/          settings, urls, wsgi
├── core/            socle : Partenaire, User, Agence, TypeCarte, auth, exports
├── campagnes/       campagnes, contrats, aides, import de commerciaux
├── terrain/         ventes, clients, enrôlements, reporting téléphonique
├── rapports/        rapports de campagne, performances (aucun modèle)
├── templates/       gabarit racine + documents (contrat, fiche client)
└── static/          logo, service worker, favicon

frontend/src/
├── Pages/           57 pages — reçoivent des props, n'appellent jamais l'API
├── Components/      briques d'interface
├── Layouts/         AppLayout, AuthCard
└── app.jsx          point d'entrée Inertia
```

Les apps Django suivent le métier, pas la technique : `campagnes` contient ses
modèles, ses vues et ses services, plutôt qu'un dossier `models/` global.

---

## Le cloisonnement par client

L'application sert plusieurs banques. Un administrateur choisit laquelle il
consulte, et **tout ce qu'il voit ensuite en dépend**.

```python
from core.partenaires import filtrer_campagnes, partenaire_courant

campagnes = filtrer_campagnes(Campagne.objects.all(), partenaire_courant(request))
```

Les fonctions `filtrer_*` de [core/partenaires.py](../backend/core/partenaires.py)
sont le point de passage obligé : une requête qui ne les traverse pas mélange
les données des deux clients. Le partenaire courant est aussi partagé à toutes
les pages Inertia sous la prop `client`.

Détail du modèle et de ses conséquences : [CLIENTS_GDA.md](CLIENTS_GDA.md).

---

## Trois mécanismes à connaître

### Le helper `route()` du frontend

Les pages appellent `route('admin.campagnes.show', id)`, comme du temps de
Laravel. Django reconstruit à chaque requête l'objet `window.Ziggy` que la
bibliothèque `ziggy-js` attend, à partir de son propre URLconf
([backend/core/routes.py](../backend/core/routes.py)).

**Conséquence :** un nom de route Django doit rester identique à celui de
l'ancien Laravel. Le renommer casse silencieusement les pages qui l'appellent.

### Les corps JSON

Inertia envoie les formulaires en `application/json`. Django ne remplit
`request.POST` que pour les corps de formulaire : `CorpsJsonMiddleware`
([backend/core/middleware.py](../backend/core/middleware.py)) fait la
conversion, et aplatit les objets imbriqués en clés pointées (`propose.3`),
comme le faisait `$request->input('propose.3')`.

### Le jeton CSRF

Django pose le cookie `csrftoken` et attend l'en-tête `X-CSRFToken`. Axios
cherche par défaut `XSRF-TOKEN` — le nom de Laravel. Les deux lignes de
[frontend/src/bootstrap.js](../frontend/src/bootstrap.js) qui corrigent ce
point conditionnent **tous** les formulaires de l'application.

---

## Le schéma de base

Les modèles sont en `managed = False` et pointent sur les tables existantes :
Django ne génère aucun DDL pour eux. Les évolutions de schéma passent donc par
des migrations en SQL explicite (`migrations.RunSQL`), toujours additives.

| | |
|---|---|
| Tables métier | 30 — `partenaires` et `adhesions_cartes` ajoutées en août 2026 |
| Colonnes ajoutées depuis la bascule | `enrolement_clients.numero_compte`, puis `partenaire_id` sur `agences`, `users`, `campagnes` et `types_cartes` |
| Colonnes assouplies | `ventes.agence_id` et `enrolement_clients.agence_id` deviennent nullables — un client sans agences n'en renseigne aucune |
| Tables ajoutées par Django | 7, techniques (`django_session`, `django_cache`…) |
| Mots de passe | bcrypt `$2y$`, vérifiés tels quels — aucune réinitialisation |

Les hachages produits par Django sont réécrits au format `$2y$`, relisible par
les deux stacks. C'est ce qui garde le retour arrière possible.

> Après une période de stabilité, on pourra passer les modèles en
> `managed = True` et adopter les migrations Django
> (voir [RETOUR_ARRIERE.md](RETOUR_ARRIERE.md)).

---

## Deux règles de fuseau et d'arrondi

**`USE_TZ = False`, `TIME_ZONE = "UTC"`** — comme Laravel. Avec `USE_TZ = True`,
les filtres `__date` de Django génèrent du `CONVERT_TZ(...)`, qui renvoie `NULL`
sans les tables de fuseaux MySQL : les filtres de campagne remonteraient
silencieusement zéro ligne.

**Les montants passent par `Decimal`**, jamais par des flottants
([backend/core/php.py](../backend/core/php.py)). Les totaux de rapports doivent
tomber au franc près.

---

## Les exports

| Format | Bibliothèque | Usage |
|---|---|---|
| Excel | openpyxl | 20 exports, graphiques natifs inclus |
| Word | python-docx | Graphiques OOXML écrits à la main — modifiables dans Word |
| PDF | xhtml2pdf | Fiche client |
| CSV | `csv` (stdlib) | Séparateur `;` et BOM, pour Excel francophone |

---

## Développer

Tout dans Docker — la même forme qu'en production, en un seul commande :

```bash
docker compose -f docker-compose.local.yml up -d --build   # base + Django + nginx
```

`backend/` est monté dans le conteneur et gunicorn tourne en `--reload` : le
code Python est rechargé à chaud. Les assets viennent de `frontend/dist`, monté
lui aussi, qu'un `npm run build` suffit à rafraîchir.

Ou en natif, pour bénéficier du rechargement à chaud de Vite :

```bash
docker compose -f docker-compose.dev.yml up -d          # MySQL 8 sur :3307
scripts/charger_dump_prod.sh bdm_prod_AAAA-MM-JJ.sql    # données réelles

cd backend  && .venv/Scripts/python.exe manage.py runserver 8001
cd frontend && npm run dev                              # assets sur :5173
```

Django rend les pages, Vite sert les assets avec rechargement à chaud.

---

## Vérifier

```bash
# Le cloisonnement entre clients de GDA, et la vente UBA de bout en bout.
backend/.venv/Scripts/python.exe scripts/tester_multi_client.py \
    --admin <votre nom> --mot-de-passe-admin '<votre mot de passe>'

# Les écritures, exactement comme le fait le navigateur :
# corps JSON, jeton CSRF lu dans le cookie, contrôle en base.
backend/.venv/Scripts/python.exe scripts/tester_ecritures.py

# Les exports produisent-ils des fichiers valides ?
backend/.venv/Scripts/python.exe scripts/verifier_exports.py \n    --admin <votre nom> --mot-de-passe-admin '<votre mot de passe>'
```

> **La leçon de la migration :** le banc de comparaison ne rejouait que des
> requêtes GET, en fabriquant lui-même les en-têtes. Il a laissé passer trois
> défauts d'intégration — le nom du cookie CSRF, le corps JSON, et les URL
> absolues derrière le proxy — qui cassaient tous les formulaires.
> **Un test qui n'écrit pas ne prouve pas que l'application fonctionne.**

---

## Déployer

```bash
cd /opt/bdm-django
git pull origin main
docker compose -f docker-compose.django.yml --env-file backend/.env.production up -d --build
```

`collectstatic` s'exécute au démarrage du conteneur, pas au build : nginx lit
les assets via un volume nommé que Docker n'initialise qu'à sa création. Sans
cela, il continuerait de servir ceux de la version précédente.

---

## Pièges connus

| Piège | Ce qui arrive | Où c'est traité |
|---|---|---|
| Renommer une route Django | Les `route()` du frontend cassent | `core/routes.py` |
| `X-Forwarded-Proto` écrasé | Cookies non sécurisés, URL en `http` | `docker/nginx/django.conf` |
| `request.get_port()` derrière un proxy | Ziggy produit `https://domaine:8000` | `core/routes.py` |
| Base Vite laissée à `/` | Polices et chunks en 404 | `frontend/vite.config.js` |
| `collectstatic` au build | nginx sert les assets d'avant | `backend/entrypoint.sh` |
| Tri SQL sans départage | Ordre instable, pagination non reproductible | tris secondaires sur `id` |
| Requête d'écran sans `filtrer_*` | Les deux clients se mélangent, sans erreur visible | `core/partenaires.py` |
| Prop de page nommée `client` | Elle écrase la prop partagée du même nom | `core/middleware.py` |

---

## Documents liés

- [CLIENTS_GDA.md](CLIENTS_GDA.md) — les clients de GDA, le cloisonnement, les spécificités UBA
- [PLAN_MIGRATION_DJANGO.md](PLAN_MIGRATION_DJANGO.md) — l'architecture retenue et les écarts assumés
- [DEMARRAGE_MIGRATION.md](DEMARRAGE_MIGRATION.md) — monter l'environnement de développement
- [BASCULE_PRODUCTION.md](BASCULE_PRODUCTION.md) — déployer
- [RETOUR_ARRIERE.md](RETOUR_ARRIERE.md) — revenir à Laravel, et quand le supprimer
