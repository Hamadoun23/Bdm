# Documentation — Application Campagne BDM (Gda Money)

Application web **Laravel** de pilotage des campagnes de vente de cartes bancaires / prépayées : **ventes terrain**, **stocks**, **contrats de prestation** et aides, **reporting téléphonique**, **rapports** direction, **performances** et **exports** (Excel structurés, PDF selon modules).

---

## 1. Vue d’ensemble

| Domaine | Description |
|--------|-------------|
| **Ventes** | Saisie par commerciaux terrain ; rattachement `campagne_id`, `agence_id`, client, type de carte, montant ; décrément stock agence. |
| **Campagnes** | Périodes, agences cibles, remises, prime meilleur vendeur, signataires contrat, articles de contrat, versements d’aide. |
| **Stocks** | Stock par agence et type de carte ; approvisionnement / ajustement ; mouvements ; alertes seuil bas. |
| **Clients** | Fiches clients (terrain) ; consultation admin/direction ; exports. |
| **Reporting téléphonique** | Fiches journalières (appels, joignabilité, intéressés, non-joignables, cartes proposées) ; lien campagne ; agrégats dans les rapports campagne. |
| **Rapports** | Synthèse par campagne (graphiques, filtres), listes ventes/clients, reporting téléphonique par campagne, **export Excel complet** par campagne (`section=all`). |
| **Performances** | Classements commerciaux / agences / types de cartes ; graphiques (top 5, parts d’agences, répartition types) ; **export Excel global** multi-feuilles. |
| **Direction** | Lecture seule sur campagnes et référentiel types de cartes. |
| **Admin** | Référentiels (agences, utilisateurs, types de cartes), campagnes, stocks, journal des connexions, reporting téléphonique global. |

**Langue interface** : français. **Auth** : session web (Breeze) ; identification flexible (e-mail, téléphone, nom normalisé pour certains rôles).

---

## 2. Stack technique

| Couche | Technologie |
|--------|-------------|
| **PHP** | ^8.2 |
| **Framework** | **Laravel 12** |
| **Auth UI** | Laravel Breeze (Vite + Tailwind sur pages *guest* / auth) |
| **App métier (UI)** | **Blade** + **Bootstrap 5** (CDN) + `public/css/gda-theme.css` |
| **PDF** | `barryvdh/laravel-dompdf` |
| **Excel** | `phpoffice/phpspreadsheet` — structuration via `App\Services\SpreadsheetExportService` |
| **Base de données** | **Visée production : MySQL** (ENUM, SQL spécifique) ; SQLite possible pour dev/tests |
| **PWA** | Route `site.webmanifest` ; thème / icônes dans `public/logo/` |

Fichiers utiles : `composer.json`, `routes/web.php`, `routes/auth.php`, `bootstrap/app.php` (middleware).

---

## 3. Rôles utilisateurs (`users.role`)

| Rôle | Code | Accès résumé |
|------|------|----------------|
| **Administrateur** | `admin` | Tout le back-office : agences, utilisateurs, types de cartes, **CRUD campagnes**, stocks, rapports, journal connexions, export reporting téléphonique global. |
| **Direction** | `direction` | **Lecture** : clients, rapports, campagnes (`/direction/campagnes`), performances, alertes stock. Pas de CRUD admin. |
| **Commercial terrain** | `commercial` | Ventes, *mes clients*, contrat campagne, performances (vue restreinte), dashboard terrain. **Pas** d’accès reporting téléphonique métier (réservé au rôle téléphonique). |
| **Commercial téléphonique** | `commercial_telephonique` | **Pas** de tunnel ventes terrain ni clients commerciaux classiques ; **reporting téléphonique** ; contrat / aides si signataire ; performances (périmètre agence) ; dashboard dédié. |

**Comptes** : `users.actif` ; middleware / login peuvent bloquer les comptes inactifs selon le rôle (`EnsureCompteActif`, `LoginRequest`).

**Méthodes utiles** (`App\Models\User`) : `isAdmin()`, `isDirection()`, `isCommercial()`, `isCommercialTelephonique()`, `isCommercialOuTelephonique()`.

---

## 4. Carte des routes principales (`routes/web.php`)

### 4.1 Public / auth
- `/` → login ou dashboard.
- Auth Breeze : `routes/auth.php` (login, logout, reset password, etc.).

### 4.2 Authentifié — commun
- `GET /dashboard` — `DashboardController@index` (vue selon rôle).
- `GET /performances` — tableau de bord performances + filtres.
- `GET /performances/export-excel` — export global (résumé, classements, par semaine, ventes détaillées).
- `GET /performances/commercial/{user}` — détail d’un commercial (autorisations vérifiées).
- `GET /performances/commercial/{user}/export-excel` — export détail commercial.

### 4.3 Commercial terrain (`role:commercial` ou combiné selon route)
- Ventes : liste, création, suppression (règles métier), `GET /ventes/export-excel`.
- `POST /api/ventes` — enregistrement vente (API web + session + `role:commercial`).
- Clients : édition / suppression des *mes clients* (routes préfixées).

### 4.4 Commercial terrain + téléphonique
- `GET|POST … /mon-contrat`, accusés versements aides (`ContratPrestationController`).

### 4.5 Commercial téléphonique uniquement
- `/reporting-telephonique` — liste, saisie, enregistrement, suppression (délai), export Excel restreint.

### 4.6 Admin + direction
- `/clients` — liste, fiche, export client (`Clients\ClientController`).
- `/rapports` — liste campagnes, liens synthèse / ventes / clients / téléphonique / **export complet** par campagne.
- `/rapports/export` — export ventes par période (legacy ; l’UI liste principale peut n’exposer que l’export par campagne).
- Sous-routes `rapports/campagnes/{campagne}/…` : synthèse, export multi-sections, ventes filtrées, clients campagne, reporting téléphonique + fiche.

### 4.7 Direction seule
- `/direction/campagnes`, `/direction/campagnes/{campagne}`, `/direction/types-de-cartes`.

### 4.8 Admin seulement (`prefix admin`, `name admin.*`)
- CRUD : `agences`, `users`, `types-cartes`, `campagnes` (+ actions arrêter / annuler / reprogrammer).
- Versements aides, articles de contrat, **stocks** (approvisionner, ajuster, mouvements).
- `journal-connexions` (`UserLoginLogController`).
- `/admin/reporting-telephonique` — liste globale, fiche, export.

### 4.9 API JSON
- `GET /api/stocks/agence/{agenceId}` — stocks par agence (session).
- `routes/api.php` — quasi vide ; la plupart des endpoints sont dans `web.php`.

### 4.10 PWA
- `GET /site.webmanifest` — nom `name` : Campagne BDM / config `app.name`.

---

## 5. Modules fonctionnels détaillés

### 5.1 Ventes (`Commercial\VenteController`, `Api\VenteController`, `VenteService`)
- Création avec contrôles stock, campagne active, rattachement client / type carte / agence.
- Liste et export Excel pour profils autorisés.
- Les ventes portent `campagne_id` pour statistiques et rapports.

### 5.2 Clients
- **Commercial** : clients créés / rattachés au commercial ; modification / suppression sous conditions (délais métier dans le modèle).
- **Admin / direction** : navigation `/clients/{id}`, exports (services dédiés, PDF/Word selon implémentation — voir `ClientExportService`, vues `exports/`).

### 5.3 Campagnes (`Admin\CampagneController`, `Direction\CampagneController`, modèle `Campagne`)
- Statuts (planifiée, active, arrêtée, annulée…) ; synchronisation périodique **`Campagne::syncStatuts()`** (scheduler si configuré).
- Liaison **agences** (ou *toutes agences*), **types** et remises, **prime meilleur vendeur**.
- **Signataires** : pivot `campagne_commercial_contrat` (commerciaux terrain **et** téléphoniques).
- **Contrat** : articles éditables (`CampagneContratArticle`), réponses `contrat_prestation_reponses`, PDF de prestation.
- **Aides** : bénéficiaires, versements (`CampagneAideVersement`), accusés.
- **Détail admin** : `CampagneDetailService` — stats et classements sur fenêtre de dates (recoupée aux bornes campagne).

### 5.4 Stocks (`Admin\StockController`, `Stock`, `MouvementStock`, `StockAlertService`)
- Quantités par `agence_id` + `type_carte_id` ; mouvements tracés.
- Alertes **stock faible** : route `alertes.stock-faible` (admin + direction).

### 5.5 Reporting téléphonique
- **Saisie** : `Commercial\TelephoniqueRapportController` — une fiche par jour / campagne (contraintes d’édition/suppression, cohérence des champs « non joignables »).
- **Admin** : liste filtrable campagne / dates / téléopératrice, fiche détaillée, export structuré.
- **Rapports campagne** : `RapportController` + `CampagneRapportService::telephoniqueRapportsPourCampagneQuery` — périmètre ventes campagne analogues (fiches liées ou orphelines rattachées par agence / rôle).
- Table `telephonique_rapports` : indicateurs + `cartes_proposees` (JSON), `campagne_id` (migration dédiée).

### 5.6 Rapports (`Admin\RapportController`, `CampagneRapportService`)
- **Index** : liste des campagnes ; par campagne : **Synthèse**, **Ventes**, **Clients**, **Reporting téléphonique**, **Export complet** (classeur `.xlsx`).
- **Export campagne** (`exportCampagne`) : sections CSV/XLSX (ventes, commerciaux, agences, types, semaines, mois) ou **`section=all`** : feuilles multiples (ventes détaillées, clients ayant vendu sur le périmètre, synthèses, **synthèse téléphonique** + **fiches téléphonique détail**).
- **Synthèse campagne** : graphiques, KPI, filtres agence / commercial / période / type carte.
- Filtre **ventes** : cohérence avec classements performances (`ventes.agence_id` quand filtre agence).

### 5.7 Performances (`PerformanceController`, `PrimeService`, `CampagneRapportService`)
- **Contexte** : dates (campagne sélectionnée ou intervalle `du`/`au`), campagne optionnelle, agence (admin/direction), comparaison période précédente.
- **Classement commerciaux** : `PrimeService` avec option **`ventesAgenceId`** pour aligner les comptages sur les ventes filtrées (même périmètre que les totaux à l’écran).
- **Graphiques** (admin/direction, non vue « commercial seul » simplifiée) : top 5 commerciaux (barres horizontales), **parts des agences** (donut), **répartition par type de carte**.
- **Tableaux** sous le classement commerciaux : **classement des agences**, **classement des types de cartes** (ventes, montant, **part % volume**).
- **Colonne part %** également sur la liste des commerciaux (dénominateur = total ventes du périmètre).
- **Export Excel** : feuilles Résumé, Classement commerciaux (+ part %), Classement agences, Types cartes, Par semaine, **Ventes détaillées**.

### 5.8 Primes (`PrimeService`, table `primes`)
- Classements mensuels / par campagne pour calculs de primes (estimation **meilleur vendeur** affichée sur l’UI performances selon campagne active).

### 5.9 Journal des connexions (`Admin\UserLoginLogController`, `UserLoginLog`)
- Enregistrement après authentification réussie (horodatage, IP, user-agent).

### 5.10 Contrat de prestation (`ContratPrestationService`, vues `contrats/`)
- Acceptation / refus, prévisualisation, liens avec campagne et réponses stockées.

---

## 6. Services métier (référence)

| Service | Rôle principal |
|---------|----------------|
| `CampagneRapportService` | Synthèses campagne, requêtes ventes filtrées, agrégations par semaine/mois, téléphonique agrégé et requête liste fiches, périmètre utilisateurs campagne. |
| `CampagneDetailService` | Assemblage données page détail campagne admin (stats, filtres dates). |
| `SpreadsheetExportService` | Création classeurs multi-feuilles, tableaux structurés (titres, métadonnées, totaux), titres d’onglets sanitisés, téléchargement stream. |
| `PrimeService` | `getClassementPourCampagne`, `getClassementBetween` avec filtre optionnel sur **`ventes.agence_id`**, rangs avec ex-aequo. |
| `VenteService` | Règles création vente / stock. |
| `StockAlertService` | Seuils et alertes. |
| `ContratPrestationService` | Logique contrat / réponses. |
| `ClientExportService` | Exports client (selon implémentation actuelle). |

---

## 7. Modèles Eloquent (`app/Models`)

| Modèle | Tables / idées clés |
|--------|---------------------|
| `User` | Authentification, rôle, agence, relations ventes/clients/primes/signataires/rapports téléphonique/logs. |
| `Agence` | Référentiel agences. |
| `TypeCarte` | Codes types de cartes ; lié stocks et ventes. |
| `Campagne` | Cœur métier ; relations agences, actions, signataires, articles, versements, téléphoniques. |
| `CampagneAction`, `CampagneContratArticle`, `CampagneAideVersement`, `ContratPrestationReponse` | Compléments campagne / contrat / aides. |
| `Client` | Fiche client terrain. |
| `Vente` | Transaction de vente. |
| `Stock`, `MouvementStock` | Inventaire et historique. |
| `Prime` | Primes attribuées. |
| `Reclamation` | Réclamations (module existant). |
| `TelephoniqueRapport` | Fiche reporting téléphonique. |
| `UserLoginLog` | Logs de connexion. |

---

## 8. Front-end

- **Layout app** : `resources/views/layouts/app.blade.php` — menu conditionné par rôle (Performances, Rapports, Admin, Reporting téléphonique, etc.).
- **Thème** : `public/css/gda-theme.css` — charte Gda / BDM.
- **Vues notables** : `resources/views/admin/**`, `commercial/**`, `direction/**`, `dashboard/*.blade.php`, `rapports/**`, `performance/**`, `clients/**`, `auth/login.blade.php`.
- **Graphiques** : Chart.js (CDN) sur pages performances / certains rapports — tooltips adaptés aux barres horizontales (`indexAxis: 'y'`).
- **Scripts** : `@push('scripts')` sur pages concernées ; Breeze utilise Vite (`resources/js/app.js`).

---

## 9. Base de données et migrations

Les migrations sont versionnées sous `database/migrations/`. Référence historique (non exhaustive des fichiers récents) :

- Socle Laravel : `users`, `cache`, `jobs`, …
- Métier : `agences`, `clients`, `stocks`, `ventes`, `mouvements_stock`, `reclamations`, `primes`, `campagnes` + pivots (`campagne_agence`, etc.), `types_cartes`, `ventes.campagne_id`, champs campagne (remises, prime, contrat, aides, articles).
- **Direction / rôles** : ENUM `users.role` (`admin`, `commercial`, `commercial_telephonique`, `direction`) ; suppression ancien `chef_agence`.
- **Téléphonique** : `telephonique_rapports` ; évolutions `cartes_proposees`, `campagne_id`.
- **Traçabilité** : `user_login_logs`.

Pour la liste exacte à jour : `ls database/migrations` ou `php artisan migrate:status`.

> Environnement **MySQL** recommandé : certaines migrations utilisent `DB::statement` pour modifier les ENUM.

---

## 10. Sécurité

- CSRF sur formulaires web ; middleware `auth` + `role` sur routes sensibles.
- Vérifications **politiques métier** dans les contrôleurs (accès campagne, accès fiche téléphonique dans le périmètre campagne, détail commercial, etc.).
- Mots de passe hashés (Breeze / Laravel).
- Journal : connexions **réussies** côté `user_login_logs` (les échecs restent dans les logs applicatifs / rate limiting Laravel selon config).

---

## 11. Commandes utiles

```bash
composer install
php artisan migrate
php artisan route:list
php artisan schedule:list          # si tâches planifiées (ex. sync campagnes)
php artisan test
npm install && npm run build       # assets Breeze / Vite
php artisan serve                  # développement
```

Variables d’environnement : fichier `.env` (base, `APP_KEY`, mail, queue…).

---

## 12. Évolutions récentes documentées (à maintenir)

- **Performances** : classements agences & types, parts % volume commerciaux, graphiques top 5 / agences / types, export Excel **global** multi-feuilles.
- **Rapports** : export campagne **complet** (clients + fiches téléphonique détaillées + synthèses) ; UI sans export « par période » obligatoire sur l’index (route legacy possible).
- **PrimeService** : paramètre **`ventesAgenceId`** pour aligner classement et KPI quand une agence est filtrée.
- **Excel** : `phpoffice/phpspreadsheet` + `SpreadsheetExportService` pour rapports et performances.

---

*Document : vue d’ensemble opérationnelle + technique du dépôt **BDM**. À mettre à jour lors de nouvelles migrations, routes majeures ou modules.*
