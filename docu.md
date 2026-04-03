# Documentation projet — Campagne BDM (Laravel)

Application de gestion des campagnes de cartes BDM : ventes terrain, stocks, rapports, contrats de prestation, direction en lecture seule, **commerciaux téléphoniques** (reporting d’appels), journal des connexions.

---

## 1. Stack technique

| Couche | Technologie |
|--------|-------------|
| Framework | Laravel 11+ (PHP) |
| Front app principale | Blade + Bootstrap 5.3 (CDN) + `public/css/gda-theme.css` |
| Auth | Laravel Breeze (layouts `guest` + Vite/Tailwind pour certaines pages) |
| PWA | `site.webmanifest`, service worker (partials layouts) |
| Build optionnel | Vite + Tailwind (`resources/css/app.js`, `tailwind.config.js`) |
| Base de données | MySQL (cible production) ; SQLite en mémoire en tests (certaines migrations SQL restent spécifiques MySQL) |

---

## 2. Rôles utilisateurs (`users.role`)

Valeurs possibles (ENUM MySQL après migrations récentes) :

| Rôle | Code | Accès principal |
|------|------|-----------------|
| Administrateur | `admin` | CRUD agences, utilisateurs, types de cartes, campagnes, stocks, rapports admin, journal connexions, reporting téléphonique global |
| Commercial terrain | `commercial` | Ventes, clients associés, contrat, performances, dashboard terrain |
| Commercial téléphonique | `commercial_telephonique` | **Pas** de ventes / fiches clients commerciales ; **reporting téléphonique** ; contrat + aides si signataire ; performances (vue agence) ; dashboard dédié |
| Direction | `direction` | Lecture : clients, rapports, campagnes détail (`/direction/campagnes`), performances, alertes stock ; pas d’admin CRUD |

**Identification à la connexion** (`LoginRequest`) : e-mail, téléphone, ou (admin/direction) nom normalisé. Comptes inactifs (`actif = 0`) : déconnexion pour commerciaux, téléphoniques et direction (`EnsureCompteActif`, `LoginRequest`).

---

## 3. Commerciaux téléphoniques — principe d’implémentation

1. **Nouveau rôle en base** : extension de l’ENUM MySQL `users.role` avec `commercial_telephonique` (migration `2026_03_31_200000_add_commercial_telephonique_and_logs.php`).
2. **Séparation des permissions par middleware** : le middleware `role` (`app/Http/Middleware/CheckRole.php`) vérifie `in_array($user->role, $roles)`. Les routes **ventes** et **clients commerciaux** restent `role:commercial` uniquement. Les routes **contrat** sont `role:commercial,commercial_telephonique`. Les routes **reporting** sont `role:commercial_telephonique` uniquement.
3. **Modèle `User`** : `isCommercial()` = terrain seul ; `isCommercialTelephonique()` ; `isCommercialOuTelephonique()` pour contrat, signataires campagne, aide hebdo éligible, sync comptes actifs.
4. **Données métier** : table `telephonique_rapports` (fiche journalière : appels, joignabilité, typologie clients, cartes proposées, motifs non joignables) ; contrôleur `App\Http\Controllers\Commercial\TelephoniqueRapportController`.
5. **Campagnes** : les listes de signataires / bénéficiaires incluent **terrain + téléphonique** là où le code utilisait seulement `commercial` (`Admin\CampagneController`, sync pivot `campagne_commercial_contrat`).
6. **Journal des connexions** : table `user_login_logs` ; insert après `authenticate()` dans `AuthenticatedSessionController`.
7. **Front** : vues `resources/views/commercial/telephonique/`, `dashboard/telephonique.blade.php`, entrées menu `layouts/app.blade.php` conditionnées par `isCommercialTelephonique()`.

---

## 4. Arborescence backend (résumé)

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Agences, Users, Types cartes, Campagnes, Stocks, Rapports, Versements, Articles contrat, Login logs, Telephonique rapports admin
│   │   ├── Auth/           # Sessions, mots de passe, etc.
│   │   ├── Api/            # VenteController (store), StockController (JSON)
│   │   ├── Clients/        # Liste / fiche / export clients (admin + direction)
│   │   ├── Commercial/     # Ventes, Clients terrain, Contrat, TelephoniqueRapportController
│   │   ├── Direction/      # Campagnes liste/détail lecture seule, référentiel types cartes
│   │   ├── DashboardController.php
│   │   └── PerformanceController.php
│   ├── Middleware/
│   │   ├── CheckRole.php
│   │   └── EnsureCompteActif.php
│   └── Requests/Auth/LoginRequest.php
├── Models/                 # User, Campagne, Vente, Client, Stock, … (voir §6)
└── Services/
     ├── CampagneDetailService.php    # Données détail campagne + filtres période
     ├── ContratPrestationService.php (si présent)
     ├── PrimeService.php
     └── StockAlertService.php
```

**Routes principales** : `routes/web.php` (API vente sous `/api/ventes` en `web` + session), `routes/auth.php`, `routes/api.php` (quasi vide).

---

## 5. Front-end (vues et assets)

### Layout principal
- `resources/views/layouts/app.blade.php` — navbar par rôle, Bootstrap, `gda-theme.css`, PWA.
- `resources/views/layouts/guest.blade.php` — Breeze / Vite (pages auth alternatives).

### Thème
- `public/css/gda-theme.css` — variables CSS (couleurs BDM / GDA), `body.gda-app`, navbar, cartes, typographie (pile Futura + replis).

### Zones Blade notables
| Dossier / fichier | Rôle |
|-------------------|------|
| `resources/views/auth/login.blade.php` | Connexion (charte Gda Money / Campagne BDM) |
| `resources/views/dashboard/*.blade.php` | Admin, Direction (`readOnly`), Commercial, **Telephonique** |
| `resources/views/admin/**` | CRUD admin, campagnes `show` avec filtre période ventes |
| `resources/views/commercial/**` | Ventes, clients terrain, contrat, **telephonique/** |
| `resources/views/direction/**` | Liste campagnes direction |
| `resources/views/clients/**` | Clients (admin/direction) |
| `resources/views/rapports/**` | Rapports |
| `resources/views/performance/**` | Performances |
| `resources/views/contrats/prestation.blade.php` | Template HTML contrat |
| `resources/views/exports/**` | Export client PDF / Word |

### JavaScript / build
- `resources/js/app.js` + Vite pour Breeze.
- Scripts inline ou `@push('scripts')` sur certaines pages (ex. filtres période campagne).

---

## 6. Base de données — tables et migrations

Les migrations s’exécutent **dans l’ordre du préfixe date**. La liste ci-dessous relie **fichier** → **objet créé ou modifié**.

### 6.1 Laravel / cache / files

| Migration | Tables / effet |
|-----------|----------------|
| `0001_01_01_000000_create_users_table.php` | `users`, `password_reset_tokens`, `sessions` |
| `0001_01_01_000001_create_cache_table.php` | `cache`, `cache_locks` |
| `0001_01_01_000002_create_jobs_table.php` | `jobs`, `job_batches`, `failed_jobs` |

### 6.2 Métier BDM (ordre historique)

| Migration | Tables / colonnes |
|-----------|-------------------|
| `2025_03_23_000001_create_agences_table.php` | `agences` |
| `2025_03_23_000002_add_bdm_columns_to_users_table.php` | `users` : téléphone, `role` (enum initial), `agence_id`, … |
| `2025_03_23_000003_create_clients_table.php` | `clients` |
| `2025_03_23_000004_create_stocks_table.php` | `stocks` |
| `2025_03_23_000005_create_ventes_table.php` | `ventes` |
| `2025_03_23_000006_create_mouvements_stock_table.php` | `mouvements_stock` |
| `2025_03_23_000007_create_reclamations_table.php` | `reclamations` |
| `2025_03_23_000008_create_primes_table.php` | `primes` |
| `2025_03_23_000009_create_campagnes_table.php` | `campagnes` |
| `2025_03_23_100000_add_prenom_and_nullable_email_to_users.php` | `users` |
| `2025_03_23_110000_enhance_campagnes_table.php` | évolution `campagnes` ; `campagne_agence` (pivot) ; `campagne_actions` |
| `2025_03_24_000000_create_types_cartes_and_migrate.php` | `types_cartes` ; adaptation `stocks` / `ventes` |
| `2025_03_24_120000_drop_libelle_ordre_from_types_cartes.php` | `types_cartes` |
| `2026_02_10_000001_add_remise_aide_campagne_and_users_actif.php` | `campagnes`, `users.actif` ; **`campagne_aide_beneficiaire`** (pivot) |
| `2026_03_25_000000_add_remise_types_cartes_to_campagnes.php` | remises ; **`campagne_remise_type_carte`** |
| `2026_03_27_120000_add_campagne_id_to_ventes_table.php` | `ventes.campagne_id` |
| `2026_03_30_120000_campagne_prime_meilleur_vendeur_only.php` | ajustement champs campagne / primes |
| `2026_03_31_100000_users_role_direction_replace_chef.php` | **MySQL** : ENUM `role` → `admin`, `commercial`, `direction` ; retrait `chef_agence` |
| `2026_03_31_110000_clear_agences_chef_id.php` | `agences.chef_id` |
| `2026_03_31_200000_contrats_prestation_aides_versements.php` | colonnes contrat sur `campagnes` ; **`campagne_commercial_contrat`** ; **`contrat_prestation_reponses`** ; **`campagne_aide_versements`** ; champs `users` adresse/pièce identité |
| `2026_03_31_200000_add_commercial_telephonique_and_logs.php` | ENUM `users.role` + `commercial_telephonique` ; **`telephonique_rapports`** ; **`user_login_logs`** |
| `2026_03_31_210000_campagne_contrat_articles.php` | **`campagne_contrat_articles`** |

### 6.3 Pivots et tables clés (référence rapide)

| Table | Rôle |
|-------|------|
| `campagne_agence` | Campagne ↔ agences (si pas « toutes agences ») |
| `campagne_aide_beneficiaire` | Bénéficiaires aide hebdo (sous-ensemble commerciaux) |
| `campagne_remise_type_carte` | Types de cartes concernés par la remise % |
| `campagne_commercial_contrat` | **Signataires** contrat (IDs user terrain + téléphonique) |
| `contrat_prestation_reponses` | Réponse `accepte` / `rejete` / `en_attente` par campagne et user |
| `campagne_aide_versements` | Versements carburant / crédit ; accusé réception |
| `campagne_contrat_articles` | Articles du corps du contrat (titre, contenu) |
| `telephonique_rapports` | Une ligne logique par `(user_id, date_rapport)` — indicateurs appels |
| `user_login_logs` | Une ligne par connexion réussie (horodatage, IP, user-agent) |

> **Note** : le nom exact du pivot dans le code Eloquent pour les signataires est `campagne_commercial_contrat` (voir `Campagne::signatairesContrat()`).

---

## 7. Services et logique métier utiles

- **`Campagne::syncStatuts()`** — planifié quotidiennement ; met à jour statuts de campagne et **réactive/désactive** commerciaux + téléphoniques signataires selon campagnes « vivantes ».
- **`CampagneDetailService::buildShowData($campagne, $request)`** — stats ventes, classement, primes sur une **période** (toute campagne, semaine, mois, ou dates perso, recoupée aux bornes de la campagne).
- **`PrimeService`** — classements mensuels pour performances / primes.
- **`StockAlertService`** — alertes stock faible (dashboard admin/direction).

---

## 8. Sécurité et bonnes pratiques

- Mots de passe hashés ; pas d’exposition des secrets dans le dépôt.
- CSRF sur formulaires web ; middleware `auth` + `role` sur les routes sensibles.
- Journal des connexions : trace les **succès** de login (pas les échecs, sauf logs serveur / rate limit Laravel).

---

## 9. Commandes utiles

```bash
php artisan migrate
php artisan route:list
php artisan schedule:list   # sync campagnes si planificateur configuré
npm run build               # assets Vite (si modification Tailwind / JS)
```

---

## 10. Fichier de suivi du code

Ce document reflète l’état du dépôt à sa dernière mise à jour. Pour toute évolution (nouvelles migrations, routes), compléter les sections **§6** et **routes/web.php** en conséquence.

---

*Généré pour le projet Campagne BDM — documentation technique condensée.*
