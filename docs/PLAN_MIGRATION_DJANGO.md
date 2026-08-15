# Plan de migration BDM — Laravel → Django

> Objectif : supprimer Laravel. Django porte **toute** la donnée et la logique métier.
> React reste une **couche de vues pure** qui reçoit des props. Aucun code superflu.
> Rien n'est touché en prod avant la bascule finale.

---

## 0. Décision d'architecture

| Question | Décision | Conséquence |
|---|---|---|
| Transport Django → React | **Inertia.js** (`inertia-django`) | Les vues Django renvoient `nom_de_page + props`. Zéro API REST à écrire, zéro client HTTP côté React. |
| Rôle de React | **Vues uniquement** | Pas de react-router, pas de TanStack Query, pas de Redux/Zustand. Aucun `fetch`. Les 76 pages `.jsx` sont reprises quasi telles quelles. |
| Logique métier | **100 % Django** | Calculs, permissions, agrégats, primes, exports : tout en Python. React n'a aucune règle métier. |
| Base de données | **Schéma identique, zéro migration métier** | Les modèles pointent sur les tables existantes (`managed = False`). Django n'ajoute que ses tables techniques. |
| Mots de passe | **Conservés tels quels** | Backend d'auth qui vérifie le bcrypt Laravel `$2y$` directement. Aucun reset utilisateur. |

**Pourquoi Inertia et pas DRF** — le code React actuel utilise `useForm`, `router`, `usePage`, `<Link>` et 199 appels `route()`. En Inertia, tout ça reste valide : c'est le même protocole, seul le serveur change. En DRF il faudrait réécrire les 7 500 lignes de front. Inertia est aussi *plus proche* de ce qui est demandé : le serveur pousse les props, le client affiche.

---

## 1. Arborescence cible

```
c:\xampp\htdocs\BDM\
├── backend/                        # Django — toute la logique
│   ├── manage.py
│   ├── requirements.txt
│   ├── .env
│   ├── config/
│   │   ├── settings.py             # un seul fichier, sections dev/prod
│   │   ├── urls.py                 # inclut les urls des apps
│   │   └── wsgi.py
│   ├── core/                       # socle transverse
│   │   ├── models.py               # User, Agence, TypeCarte, UserLoginLog
│   │   ├── auth_backend.py         # vérification bcrypt Laravel
│   │   ├── decorators.py           # @role_required, @compte_actif
│   │   ├── inertia.py              # share() global (auth, flash)
│   │   ├── routes.py               # génération de la table de routes JS
│   │   ├── exports/                # xlsx / docx / pdf
│   │   ├── views.py                # auth, dashboard, profil
│   │   └── urls.py
│   ├── campagnes/                  # models + views + services campagne
│   ├── terrain/                    # ventes, clients, enrôlements, contrat, téléphonique
│   ├── rapports/                   # rapports + performances (pas de modèle)
│   ├── templates/
│   │   ├── app.html                # équivalent app.blade.php
│   │   └── documents/              # contrat, export client (ex-Blade)
│   └── static/                     # logo, favicon, sw.js, manifest
│
├── frontend/                       # React — vues seules
│   ├── package.json
│   ├── vite.config.js
│   ├── tailwind.config.js
│   ├── postcss.config.js
│   └── src/
│       ├── app.jsx                 # point d'entrée Inertia
│       ├── css/app.css
│       ├── lib/cn.js
│       ├── lib/route.js            # shim route() (~50 lignes)
│       ├── Components/             # repris tel quel
│       ├── Layouts/                # repris tel quel
│       └── Pages/                  # 76 pages reprises telles quelles
│
├── docker-compose.dev.yml
├── docker-compose.prod.yml         # réécrit pour Django
└── (app/, routes/, resources/, vendor/… → supprimés à la toute fin)
```

Le Laravel actuel **reste intact** dans l'arbo pendant tout le développement : il sert de référence vivante pour la recette (voir §7). Il n'est supprimé qu'après la bascule validée.

---

## 2. Correspondance des briques

| Laravel | Django | Note |
|---|---|---|
| `routes/web.php` | `config/urls.py` + `<app>/urls.py` | **Mêmes noms** de routes (`admin.campagnes.index`) → les 199 `route()` du JSX marchent sans retouche |
| Controller | vue fonction + `@role_required` | Pas de classes, on reste plat |
| `Inertia::render('X', [...])` | `inertia.render(request, 'X', props)` | Identique |
| Eloquent | Django ORM | `select_related` / `prefetch_related` ≈ `with()` |
| `HandleInertiaRequests::share()` | `INERTIA_SHARE` (core/inertia.py) | auth.user, flash |
| `CheckRole` middleware | `@role_required('admin','direction')` | décorateur, pas de middleware |
| `EnsureCompteActif` | `@compte_actif` ou middleware | |
| FormRequest | `django.forms.Form` | validation + messages FR |
| `session()->flash()` | `django.contrib.messages` | mappé vers `flash.*` dans share() |
| Blade (contrats, exports) | Template Django | 4 templates à porter |
| PhpSpreadsheet | **openpyxl** | 25 usages |
| PhpWord | **python-docx** | exports graphiques Word |
| DomPDF | **WeasyPrint** | contrat prestation, fiche client |
| ZipStream | `zipfile` (stdlib) | |
| Ziggy | table de routes générée par Django | on garde `ziggy-js` côté JS, on lui donne un objet `window.Ziggy` construit par Django |
| `php artisan cmd` | `manage.py` command | 1 commande à porter (`MergeProdSqlIntoLocal`) |
| Queues / Mail | — | **rien à porter** : aucun job, aucun mail custom |

---

## 3. Modèles — 16 entités, tables inchangées

Toutes en `managed = False` + `db_table` explicite. Django ne touchera jamais au DDL des tables métier.

| Table existante | Modèle Django | App |
|---|---|---|
| `users` | `User` (AbstractBaseUser) | core |
| `agences` | `Agence` | core |
| `types_cartes` | `TypeCarte` | core |
| `user_login_logs` | `UserLoginLog` | core |
| `campagnes` | `Campagne` | campagnes |
| `campagne_agence` | `CampagneAgence` (pivot) | campagnes |
| `campagne_actions` | `CampagneAction` | campagnes |
| `campagne_aide_versements` | `CampagneAideVersement` | campagnes |
| `campagne_aide_beneficiaire` | pivot | campagnes |
| `campagne_contrat_articles` | `CampagneContratArticle` | campagnes |
| `campagne_commercial_contrat` | pivot | campagnes |
| `campagne_remise_type_carte` | pivot | campagnes |
| `contrat_prestation_reponses` | `ContratPrestationReponse` | campagnes |
| `commercial_agence_transferts` | `CommercialAgenceTransfert` | campagnes |
| `clients` | `Client` | terrain |
| `ventes` | `Vente` | terrain |
| `enrolement_clients` | `EnrolementClient` | terrain |
| `telephonique_rapports` | `TelephoniqueRapport` | terrain |
| `primes` | `Prime` | terrain |
| `reclamations` | `Reclamation` | terrain |

**Amorce** : `manage.py inspectdb` sur une copie du dump prod, puis nettoyage manuel (noms de champs, relations, `Meta`).

**Tables ajoutées par Django** (purement additif, aucun risque) :
`django_migrations`, `django_session`, `django_content_type`, `auth_permission`, `auth_group`, `auth_group_permissions`.
Les tables Laravel `cache`, `cache_locks`, `jobs`, `job_batches`, `failed_jobs`, `sessions`, `migrations` deviennent inutilisées — on les laisse en place jusqu'à la bascule validée, on les supprime après.

---

## 4. Le point critique : les mots de passe

Laravel stocke du bcrypt au format `$2y$12$…`. Django attend `algorithme$…`. Deux façons de faire, une seule est bonne :

- ❌ Réécrire la colonne `password` au format Django → **casse le rollback vers Laravel**.
- ✅ **Backend d'authentification custom** qui lit la valeur brute et la vérifie avec `bcrypt` directement (`$2y$` et `$2b$` sont interchangeables, simple substitution de préfixe).

```
core/auth_backend.py
  authenticate(email, password)
    → user = User.objects.get(email=email, actif=True)
    → bcrypt.checkpw(password, hash.replace('$2y$', '$2b$', 1))
```

Au changement de mot de passe depuis Django, on ré-écrit un hash au format `$2y$` → **la colonne reste lisible par Laravel dans les deux sens**, le rollback fonctionne à tout moment.

**Sessions** : Django utilise sa propre table. Au moment de la bascule, tout le monde est déconnecté une fois. C'est le seul impact utilisateur visible.

---

## 5. Le shim `route()` — garder les 199 appels intacts

Le JSX appelle partout `route('admin.campagnes.show', campagne.id)`. On ne touche à aucun de ces appels.

1. Les URLs Django sont nommées **exactement** comme les routes Laravel (`admin.campagnes.show`).
2. `core/routes.py` parcourt l'URLconf Django au démarrage et produit un objet compatible Ziggy :
   `{ 'admin.campagnes.show': { uri: 'admin/campagnes/{campagne}', methods: ['GET'] } }`
   (conversion `<int:campagne>` → `{campagne}`, ~40 lignes, mis en cache).
3. Cet objet est injecté dans `app.html` sous `window.Ziggy`.
4. `ziggy-js` est conservé côté front, `frontend/src/app.jsx` garde sa ligne `window.route = …`.

**Coût total : ~50 lignes Python + 0 ligne JSX modifiée.**

⚠️ Django ajoute par défaut un `/` final aux URLs, pas Laravel. On définit les paths **sans slash final** et on met `APPEND_SLASH = False` pour que les URLs soient au caractère près identiques (favoris, PWA, liens partagés).

---

## 6. Lots de travail

| Lot | Contenu | Volume source | Estimation |
|---|---|---|---|
| **0 — Socle** ✅ | `backend/` + `frontend/`, settings, Vite, inertia-django, shim routes, docker dev, DB locale depuis le dump, banc de comparaison. | — | ~~3–4 j~~ **fait** |
| **1 — Modèles + auth** ✅ | 20 modèles `managed = False`, User custom, backend bcrypt, `@role_required`, journal de connexion, `share()` Inertia (`peut_vendre` / `peut_enroler`), tableau de bord (4 variantes). | 1 389 l | ~~4–5 j~~ **fait** |
| **2 — Référentiels admin** | Agences, Users + transfert d'agence, Types de cartes, journal connexions, profil. 15 pages React déjà prêtes. | ~800 l | 5–6 j |
| **3 — Campagnes** 🔴 | Modèle Campagne (580 l), CampagneController (755 l), CampagneDetailService, import Excel commerciaux, articles de contrat, aides & versements, actions arrêter/annuler/reprogrammer, signataires. | ~2 000 l | 8–10 j |
| **4 — Terrain** | Ventes, Enrôlements, Clients, Contrat de prestation (acceptation/rejet), reporting téléphonique. | ~1 500 l | 5–6 j |
| **5 — Rapports & performances** 🔴 | RapportController (1 868 l), PerformanceController (1 154 l), CampagneRapportService, CampagneStatsScope, PrimeService. **51 requêtes SQL brutes à porter et à vérifier au franc près.** | ~4 000 l | 10–12 j |
| **6 — Exports** | openpyxl (25 usages), python-docx, WeasyPrint + 4 templates ex-Blade, zip. | ~1 000 l | 5–6 j |
| **7 — Recette iso-fonctionnelle** | Comparaison automatisée Laravel vs Django (voir §7). | — | 5 j |
| **8 — Bascule + nettoyage** | Docker prod, bascule nginx, suppression de Laravel. | — | 2 j |

**Total ≈ 47–56 jours de développement.** Les lots 3 et 5 concentrent le risque et méritent d'être traités en premier après le socle si on veut lever l'incertitude tôt.

---

## 7. Recette : le filet de sécurité

**Les deux stacks parlent le même protocole Inertia.** Une requête avec l'en-tête `X-Inertia: true` renvoie du JSON — le composant + les props. On peut donc comparer les deux backends automatiquement :

```
pour chaque route GET du catalogue :
    props_laravel = GET http://laravel.local/<route>   (X-Inertia: true)
    props_django  = GET http://django.local/<route>    (X-Inertia: true)
    diff(props_laravel, props_django)   →  doit être vide
```

Les deux pointent sur **la même copie de la base prod**, avec le même utilisateur connecté. Tout écart de calcul (montant, total, prime, taux) saute immédiatement. C'est de loin le meilleur investissement de la migration : script à écrire au lot 0, exploité en continu.

Points de vigilance à surveiller dans ces diffs :
- **Arrondis monétaires** — PHP calcule en float, il faut forcer `Decimal` en Python. Les totaux de rapports doivent tomber au franc.
- **Fuseau horaire** — un décalage fait basculer des ventes d'un jour à l'autre et fausse les filtres de campagne. Aligner `TIME_ZONE` sur le comportement Laravel actuel avant tout test de rapport.
- **Tri et pagination** — un `ORDER BY` non déterministe ne donne pas le même ordre dans les deux ORM. Ajouter un tri secondaire stable.
- **SQL brut spécifique MySQL** — les 51 occurrences sont à relire une par une.

---

## 8. Bascule en production

Aucune donnée n'est migrée : **c'est la même base, le même schéma.** La bascule est un changement d'upstream nginx.

1. **Avant** — dump de sécurité de la base prod.
2. Le conteneur Django prod écoute sur `127.0.0.1:8091` (Laravel reste sur `8090`, allumé).
3. Django tourne d'abord **en lecture** contre la base prod : on rejoue le script de comparaison du §7 sur les vraies données.
4. **Bascule** — le nginx de l'hôte pointe sur `8091`. Fenêtre hors période de campagne active.
5. **Rollback** — on repointe sur `8090`. Laravel est intact, le schéma n'a pas bougé, les mots de passe non plus. Rollback en moins d'une minute, à n'importe quel moment.
6. Après 1–2 semaines stables : arrêt de Laravel, suppression de `app/`, `routes/`, `resources/`, `vendor/`, `composer.*`, des tables Laravel inutilisées, et passage des modèles en `managed = True` avec une migration Django de référence (`--fake-initial`).

---

## 9. Ce qui disparaît au passage

- `app.js` / Alpine.js — plus aucune vue Blade à servir.
- Ziggy PHP — remplacé par 50 lignes de génération Python.
- Laravel Breeze (register, vérification email, reset) — l'inscription est déjà désactivée ; on ne garde que login / logout / changement de mot de passe / mot de passe oublié.
- Les tables `cache`, `jobs`, `job_batches`, `failed_jobs`, `sessions`, `migrations`.

---

## 10. Décisions prises pendant la réalisation

Quatre points n'étaient pas prévisibles depuis le plan initial :

- **Django 6.0, pas 6.1.** La 6.1 exige MySQL 8.4+ alors que la production tourne en 8.0.45. La 6.0 accepte MySQL 8.0.11+ et Python 3.12–3.14.
- **MySQL 8 en Docker plutôt que le MariaDB 10.4 de XAMPP.** Le moteur local doit être celui de la production : les 51 requêtes SQL brutes ne se comportent pas pareil sur les deux.
- **`USE_TZ = False`.** Avec `True`, les lookups `__date` de Django génèrent du `CONVERT_TZ(...)`, qui renvoie `NULL` si les tables de fuseaux MySQL ne sont pas chargées — les filtres de campagne remonteraient silencieusement zéro ligne.
- **Port 8001 pour Django.** Le 8000 est déjà occupé par un autre projet sur la machine de développement.

## 11. État final — 13/08/2026

**Les six lots sont terminés.** Le backend Laravel est intégralement porté.

### Couverture

| Indicateur | Résultat |
|---|---|
| Routes Laravel portées à l'identique | **105 / 106** (la 106ᵉ, `storage.local.upload`, n'est appelée par rien) |
| URI divergentes | **0** |
| Routes comparées props par props | **78**, sur les 4 rôles |
| Écarts de props | **0** |
| Exports vérifiés (Excel, Word, PDF, CSV) | **26 / 26** produisent un fichier valide |
| Pages React modifiées | **0** sur 76 (seul `app.jsx` a changé) |
| Appels `route()` modifiés | **0** sur 199 |

### Ce qui a été écrit

| Élément | Volume |
|---|---|
| Backend Django | ~8 900 lignes de Python, 4 apps |
| Frontend | repris tel quel, 76 fichiers |
| Outillage de recette | 4 scripts (comparaison props, comparaison routes, vérification exports, comptes de test) |

### Validations structurantes

- **Base de données** : `migrate` vérifié au niveau du schéma — 6 tables techniques ajoutées, **234 colonnes métier strictement inchangées**, aucune suppression.
- **Mots de passe** : compatibilité bcrypt confirmée dans les deux sens ; PHP considère même qu'un hachage produit par Python ne nécessite aucun rehash. Aucune réinitialisation utilisateur.
- **Iso-fonctionnalité** : le banc de comparaison rejoue 78 routes sur les données réelles et ne relève aucun écart de props, y compris sur les calculs sensibles (classements, primes, taux, agrégats hebdomadaires).

### Divergences assumées

Quatre écarts délibérés, tous documentés dans le code :

1. **Tri déterministe ajouté.** Laravel trie certaines listes (journal de connexion, clients homonymes) sans départage : MySQL rend alors un ordre arbitraire, variable d'une exécution à l'autre. Django ajoute un tri secondaire sur l'identifiant. C'est une correction, pas une régression — mais elle explique pourquoi le banc compare le contenu de ces listes sans tenir compte de leur ordre.
2. **`ProfileController` non routé.** Il n'est référencé nulle part dans Laravel : l'écran `Profile/Edit.jsx` y est inatteignable. L'exposer aurait été un ajout de fonctionnalité, pas une migration.
3. **Tri des agences reproduit à l'octet.** Quand une campagne n'est pas « toutes agences », Laravel trie en PHP (majuscules d'abord) et non en SQL (insensible à la casse). Le comportement est reproduit tel quel.
4. **Suffixe « — Enrôlement » du filtre Performances.** Laravel teste un attribut qu'il n'a pas chargé : le suffixe ne s'affiche jamais. Comportement reproduit à l'identique, avec la note explicative.

### Documents

- [DEMARRAGE_MIGRATION.md](DEMARRAGE_MIGRATION.md) — monter l'environnement de développement
- [BASCULE_PRODUCTION.md](BASCULE_PRODUCTION.md) — déployer et basculer, avec la procédure de retour arrière

### Reste à faire

- Recette manuelle dans un navigateur : le banc valide les données, pas le rendu.
- Bascule en production selon [BASCULE_PRODUCTION.md](BASCULE_PRODUCTION.md).
- Suppression de Laravel après une à deux semaines de stabilité.
