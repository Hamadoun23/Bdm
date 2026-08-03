# Contexte projet — Refonte BDM (pour la prochaine session Claude)

> Document de passation. La session précédente a pris fin en plein chantier — ce fichier permet de reprendre sans perdre le contexte. Lis-le en entier avant de reprendre le travail.

**Dernière mise à jour** : 2026-07-30 (session 3 — fin de la Phase 3, conversion Inertia terminée à 100% des pages accessibles)

---

## 1. Ce qui a été demandé

L'utilisateur (Cissé) trouve l'app **Campagne BDM** (Laravel 12 / Blade / Bootstrap 5 / MySQL, doc complète dans `bdm_v1.md`) trop lourde et datée visuellement. Trois volets demandés :

1. **Refonte complète du front** : Blade → **Inertia.js + React**, styling **Tailwind CSS**. Le backend Laravel (contrôleurs, routes, auth, middleware `CheckRole`/`EnsureCompteActif`) reste inchangé — seul le rendu change.
2. **Migration base de données** : MySQL → **Postgres**, via l'image Postgres de **Supabase self-hosted** + le conteneur **Studio** uniquement (pas de GoTrue/PostgREST/Realtime/Storage — Laravel garde 100% de l'auth et du stockage fichiers).
3. **Déploiement** : le tout sous **Docker**, d'abord en local pour validation, puis sur un **VPS Contabo** (`194.163.187.59`) une fois validé, avec bascule du domaine `bdm.gdamali.net` (actuellement sur hébergement mutualisé) vers le VPS.

Design demandé : **moderne, simple**, inspiré de templates Envato haut de gamme et de **21st.dev** (esthétique shadcn/ui). L'utilisateur a explicitement validé la direction sidebar-icônes + cartes après un premier essai raté (trop "détails" pas top), puis a demandé de reproduire un dashboard fintech de référence (Dribbble) — **adapté aux vraies données de l'app**, pas copié tel quel. Dernière consigne ferme : **l'orange `#FF6A3A` est la SEULE couleur d'accent** — le marron/brun de la charte GDA (`#381419`, `#b26440`) a été **explicitement banni et retiré partout**.

⚠️ **Le mot de passe root du VPS a été partagé en clair dans le chat** à un moment de la session précédente. Il doit être considéré comme compromis : mettre en place une auth par clé SSH et changer ce mot de passe **avant toute connexion réelle au VPS**. Le VPS n'a été touché à aucun moment — tout le travail s'est fait en local.

---

## 2. Plan approuvé (référence complète)

Un plan détaillé en 5 phases a été validé avec l'utilisateur et écrit dans :
**`C:\Users\cisse\.claude\plans\linked-munching-bachman.md`**

Résumé des phases :
- **Phase 0** — Docker Compose local (app Laravel, Postgres/Supabase, Studio, node/Vite)
- **Phase 1** — Migration MySQL → Postgres (schéma consolidé)
- **Phase 2** — Scaffolding Inertia/React/Tailwind
- **Phase 3** — Conversion des ~90 vues Blade, page par page, par groupes (A: socle+auth+dashboard, B: commercial, C: CRUD admin, D: campagnes, E: direction, F: rapports/perf, G: legacy)
- **Phase 4** — Checklist de validation locale
- **Phase 5** — Déploiement VPS (esquissé seulement, à détailler plus tard)

**Décisions verrouillées** (ne pas re-proposer d'alternatives sans raison) :
- Inertia.js, pas de SPA séparée + API REST
- Postgres/Supabase = Postgres + Studio seulement, Laravel garde l'auth
- Tout en local d'abord, VPS seulement après validation complète

---

## 3. État d'avancement détaillé

### Phase 0 — Docker (fichiers écrits, **jamais validés en marche**)

Fichiers créés, syntaxiquement corrects (`docker compose config` passe) :
- `docker-compose.yml` (services : `app`, `web` nginx, `node`, `db` Postgres Supabase, `meta` postgres-meta, `studio`)
- `docker/php/Dockerfile`, `docker/php/entrypoint.sh`, `docker/nginx/default.conf`
- `.env.docker.example` (ne touche pas au `.env` existant qui pointe vers MySQL/XAMPP)
- `.dockerignore`

**Blocage non résolu** : Docker Desktop sur la machine ne répondait plus (`docker info` restait bloqué indéfiniment) malgré plusieurs tentatives de redémarrage. **Jamais pu lancer `docker compose up` avec succès** dans la session précédente. À la reprise : vérifier Docker Desktop en premier (peut nécessiter une mise à jour WSL2 ou un redémarrage manuel), puis lancer :
```bash
cp .env.docker.example .env.docker   # ajuster mots de passe si besoin
docker compose --env-file .env.docker up -d --build
docker compose --env-file .env.docker exec app php artisan migrate --seed
```

⚠️ **Important découvert à la reprise de session** : Docker Desktop a fini par redémarrer tout seul entre les deux sessions, et la machine héberge **plusieurs autres projets Docker sans rapport avec BDM** (containers `bekstpro-*`, `supabase_*_EventMotors`, `jusorange-*`, un stack Supabase générique...). L'un d'eux (`supabase-kong`, projet inconnu) **occupe le port 8000**, ce qui fait échouer `php artisan serve --port=8000`. **Ne jamais arrêter/toucher les containers Docker qui n'appartiennent pas à BDM** (`docker ps -a` pour les identifier — les nôtres n'existent pas encore, aucun `docker compose up` de ce projet n'a jamais abouti). Utiliser un autre port pour le serveur local BDM, ex. `php artisan serve --port=8010`.

### Phase 1 — Migration Postgres (fait, vérifié)

- `database/migrations/2026_07_30_000000_pgsql_consolidated_schema.php` : migration consolidée qui recrée le schéma final complet (18 tables métier) directement en Postgres, générée à partir d'un `SHOW CREATE TABLE` sur la vraie base MySQL locale (source de vérité, pas une relecture des 33 migrations historiques). Guardée `if (DB::getDriverName() !== 'pgsql') return;` — inoffensive sur MySQL.
- `database/migrations/2025_03_24_000000_create_types_cartes_and_migrate.php` : corrigée pour être portable (le SQL brut MySQL-only — jointure UPDATE, `ALTER ... MODIFY` — est maintenant conditionné par driver, avec un vrai fallback SQLite/Postgres).
- `app/Services/CampagneRapportService.php::agregerParPeriode` : branche `pgsql` ajoutée (équivalents `TO_CHAR` pour les `YEARWEEK`/`DATE_FORMAT` MySQL).
- Seeders rendus portables : `SoloAdminSeeder`, `FreshMinimalSeeder`, `ResetBusinessDataSeeder` utilisaient `DB::statement('SET FOREIGN_KEY_CHECKS=...')` (MySQL-only) → remplacé par `Schema::disableForeignKeyConstraints()`/`enableForeignKeyConstraints()` (portable).
- **Jamais testé contre une vraie instance Postgres** (bloqué par Docker Desktop, voir Phase 0). Le code a été relu et validé par lecture, pas par exécution réelle sur Postgres.

**Bonus découvert** : la suite de tests (`php artisan test`) était déjà cassée avant que je touche à quoi que ce soit (24 échecs / 25 tests, à cause du SQL MySQL-only qui plantait aussi sur SQLite). En corrigeant la migration ci-dessus, 17/25 passent maintenant. Les 8 échecs restants sont des tests Breeze par défaut obsolètes (`/register`, `/profile`, page d'accueil qui redirige au lieu de renvoyer 200) — **sans rapport avec ce chantier**, préexistants, à traiter séparément si souhaité.

### Phase 2 — Scaffolding Inertia/React/Tailwind (fait)

- `composer require inertiajs/inertia-laravel tightenco/ziggy` (a nécessité de pinner `maennchen/zipstream-php:^3.1.2` pour éviter un conflit de résolution avec PHP 8.2 — sans rapport avec Inertia, conflit préexistant dans le lockfile).
- `npm install @inertiajs/react react react-dom @vitejs/plugin-react ziggy-js chart.js react-chartjs-2 lucide-react clsx tailwind-merge`
- `app/Http/Middleware/HandleInertiaRequests.php` créé, enregistré dans `bootstrap/app.php` (groupe `web`, avant `EnsureCompteActif`). Partage `auth.user` (avec `is_admin`/`is_direction`/`is_commercial`/`is_commercial_telephonique`) et `flash` (success/error/warning/status/success_article).
- `resources/views/app.blade.php` : nouveau root Inertia (coexiste avec l'ancien `resources/views/layouts/app.blade.php`, qui reste utilisé par toutes les pages pas encore converties).
- `resources/js/app.jsx` : nouveau point d'entrée React/Inertia. **`resources/js/app.js` (Alpine) existe toujours** et reste l'entrée des pages Blade non converties (ex. `layouts/guest.blade.php`) — `vite.config.js` construit les deux en parallèle. Ne pas supprimer `app.js` tant que toutes les pages qui l'utilisent ne sont pas converties.
- `tailwind.config.js` : ajout de `resources/js/**/*.{js,jsx}` au `content`, police système neutre en `sans` (Futura déplacée vers `font-brand`, réservée au logo/wordmark), couleurs `gda.orange`/`gda.brun`/`gda.cuivre`/`gda.gris`/`gda.blanc` définies mais **`brun` et `cuivre` ne doivent plus être utilisés nulle part** (bannis par consigne utilisateur, voir §4).

### Phase 3 — Conversion des pages (✅ terminée — toutes les pages accessibles sont sur Inertia/React)

**Système de composants établi** (`resources/js/Components/ui/`) :
- `Button.jsx` (variants primary/secondary/outline/ghost/destructive, tailles sm/md/lg, fonctionne comme `<Link>` si `href` fourni sinon `<button>`)
- `Card.jsx` (`Card`, `CardHeader`, `CardTitle`, `CardBody`)
- `Badge.jsx` (tones neutral/orange/green/amber/blue/red)
- `StatCard.jsx`, `Sparkline.jsx`, `Gauge.jsx` (mini-graphiques, une seule teinte, cf. skill dataviz — pas de rainbow, marks fins)
- `Input.jsx` (`Input`, `PasswordInput` avec toggle œil, `Textarea`, `Label`, `FieldError`)
- `Select.jsx`, `Checkbox.jsx`
- `Modal.jsx` (dialog générique réutilisable)
- `Pagination.jsx` (consomme le format `linkCollection()` de Laravel)
- `resources/js/lib/cn.js` (helper `clsx` + `tailwind-merge`)

**Layout** :
- `resources/js/Components/Sidebar.jsx` : rail d'icônes fixe à gauche sur desktop (76px, tooltip au survol), tiroir avec labels sur mobile (hamburger). Items générés dynamiquement selon le rôle (`itemsFor(user)`). État actif = fond orange clair + barre orange à gauche (**pas de marron** — voir §4).
- `resources/js/Layouts/AppLayout.jsx` : sidebar + topbar (salutation personnalisée "Bonjour, {prénom} !" par défaut, ou `title`/`subtitle` custom, slot `actions` pour un bouton d'action à droite type "Nouvelle campagne"/"Nouvel utilisateur", recherche + notifications décoratives pour l'instant) + zone de contenu avec alertes flash.
- **Pattern de formulaire établi** : pour les CRUD avec create+edit qui partagent les mêmes champs, un composant `Form.jsx` colocalisé dans le dossier de la page (ex. `Pages/Admin/Agences/Form.jsx`) est importé par les fines pages `Create.jsx`/`Edit.jsx`. Utilise `useForm()` d'Inertia. À réutiliser pour toutes les pages CRUD restantes.

**Pages converties (22 groupes de pages, session du 2026-07-30)** :
| Page | Contrôleur | Vue React | Statut |
|---|---|---|---|
| Dashboard (4 variantes : admin/direction, commercial, telephonique, guest) | `DashboardController.php` | `Pages/Dashboard.jsx` | ✅ **Validé visuellement par l'utilisateur** — sert de référence design |
| Login | `Auth/AuthenticatedSessionController.php` | `Pages/Auth/Login.jsx` | ✅ Fait (split-screen, panneau de marque sombre neutre + formulaire) |
| Admin > Campagnes (liste uniquement) | `Admin/CampagneController.php::index` | `Pages/Admin/Campagnes/Index.jsx` | ✅ Fait (tableau, badges statut, modals Arrêter/Annuler/Reprogrammer, pagination) |
| Ventes (commercial, création + historique) | `Commercial/VenteController.php` | `Pages/Ventes/{Create,Index}.jsx` | ✅ Fait (chips type carte/campagne, upload pièce d'identité via `/api/ventes`, actions modifier/supprimer selon délai 48h) |
| Admin > Agences (CRUD) | `Admin/AgenceController.php` | `Pages/Admin/Agences/{Index,Create,Edit,Form}.jsx` | ✅ Fait |
| Admin > Types de cartes (CRUD) | `Admin/TypeCarteController.php` | `Pages/Admin/TypesCartes/{Index,Create,Edit}.jsx` | ✅ Fait |
| Admin > Utilisateurs (liste + filtres, create, edit) | `Admin/UserController.php` | `Pages/Admin/Users/{Index,Create,Edit,Form}.jsx` | ✅ Fait (filtres recherche/rôle/statut contrat, rôle conditionne email/agence/contrat) |
| Admin > Journal des connexions | `Admin/UserLoginLogController.php` | `Pages/Admin/LoginLogs/Index.jsx` | ✅ Fait |
| Admin > Reporting téléphonique (liste + détail) | `Admin/TelephoniqueRapportController.php` | `Pages/Admin/TelephoniqueRapports/{Index,Show}.jsx` | ✅ Fait (exports CSV/xlsx restent des liens directs non-Inertia) |
| Direction > Campagnes (liste) | `Direction/CampagneController.php::index` | `Pages/Direction/Campagnes/Index.jsx` | ✅ Fait. **`show` reste sur Blade** — réutilise `admin.campagnes.show`, non convertible avant ce gros morceau |
| Direction > Référentiel types de cartes | `Direction/ReferentielController.php` | `Pages/Direction/Referentiel/TypesCartes.jsx` | ✅ Fait |
| Clients (admin/direction, liste + fiche) | `Clients/ClientController.php` | `Pages/Clients/{Index,Show}.jsx` | ✅ Fait (export PDF/Excel/Word via modale, liens directs non-Inertia) |
| Commercial > Mon contrat | `Commercial/ContratPrestationController.php` | `Pages/Commercial/Contrat/{Show,NoCampagne,ContratDocument}.jsx` | ✅ Fait, vérifié pour 3 commerciaux différents. Le texte légal (`ContratDocument.jsx`) est une **transcription fidèle** de `contrats/prestation.blade.php` + `prestation_emoluments_annexes.blade.php` — si le texte juridique change, éditer les deux côtés. |
| Commercial > Reporting téléphonique (saisie + historique) | `Commercial/TelephoniqueRapportController.php` | `Pages/Commercial/Telephonique/{Index,Form}.jsx` | ✅ Fait — calculs live (non-joignables, taux, somme motifs vs plafond) portés en React (dérivés du state, remplace le JS vanilla de l'ancienne page) |
| Performances (tableau de bord + détail par commercial) | `PerformanceController.php::{index,show}` | `Pages/Performances/{Index,Show}.jsx` | ✅ Fait — 3 graphiques `react-chartjs-2` (barre horizontale top commerciaux, doughnut agences, barre types cartes), filtres du/au/agence/campagne/comparaison période précédente, 3 classements (commerciaux/agences/types). Vérifié pour admin/direction/commercial + filtre comparaison. |
| Rapports > page d'accueil (liste campagnes + sélection cumul) | `Admin/RapportController.php::index` | `Pages/Rapports/Index.jsx` | ✅ Fait — sélection multi-campagnes pour cumul portée en state React (remplace le JS vanilla de gestion des checkboxes) |
| Rapports > cumul multi-campagnes | `Admin/RapportController.php::cumul` | `Pages/Rapports/Cumul.jsx` | ✅ Fait |
| Rapports > synthèse d'une campagne (avec graphiques) | `Admin/RapportController.php::campagneSynthese` | `Pages/Rapports/CampagneSynthese.jsx` | ✅ Fait — charts `react-chartjs-2` |
| Rapports > ventes/clients/reporting-tel d'une campagne | `Admin/RapportController.php::{campagneVentes,campagneClients,campagneReportingTelephonique}` | `Pages/Rapports/{CampagneVentes,CampagneClients,CampagneReportingTelephonique}.jsx` | ✅ Fait — `campagneReportingTelephoniqueShow` réutilise `Admin/TelephoniqueRapports/Show.jsx` via un prop `backUrl` |
| **Admin > Campagnes create/edit/show** (+ Direction > Campagnes show) | `Admin/CampagneController.php`, `Direction/CampagneController.php::show` | `Pages/Admin/Campagnes/{Index,Create,Edit,Form,ContratArticles,Show}.jsx` + `partials/{Pilotage,Commerciaux,Contrat,Aide,Performances,Historique,Modals}.jsx` | ✅ Fait — le plus gros morceau du chantier. `Show.jsx` a 7 onglets (état local synchronisé dans l'URL via `history.replaceState`), **partagé entre Admin (CRUD complet) et Direction (lecture seule)** via le flag `isDirectionDetail`, tous deux alimentés par le nouveau `CampagneDetailService::toInertiaProps()` qui sérialise `buildShowData()` en props propres. |
| Admin > Utilisateurs — Transfert d'agence/ventes | `UserController::transfertAgenceForm` | `Pages/Admin/Users/TransfertAgence.jsx` | ✅ Fait — filtres date/campagne/agence, sélection de ventes (checkboxes + tout sélectionner), formulaire nouvelle agence/note/maj profil avec confirmation JS |
| Commercial > Modifier ma fiche client | `Commercial/ClientController.php::edit` | `Pages/Commercial/Clients/Edit.jsx` | ✅ Fait — upload pièce d'identité (POST + `_method=put` spoofing, requis par Inertia pour les fichiers), verrouillage après 48h |
| Auth — mot de passe oublié / réinitialisation / confirmation / vérification e-mail | `Auth/{PasswordResetLinkController,NewPasswordController,ConfirmablePasswordController,EmailVerificationPromptController}.php` | `Pages/Auth/{ForgotPassword,ResetPassword,ConfirmPassword,VerifyEmail}.jsx` (+ nouveau `Layouts/AuthCard.jsx`, layout centré léger réutilisé par les 4) | ✅ Fait |
| Profil (infos, mot de passe, suppression de compte) | `ProfileController.php` | `Pages/Profile/Edit.jsx` | ✅ Fait — 3 cartes (infos/mot de passe/suppression), suppression via `Modal` réutilisable, error bags nommés (`updatePassword`/`userDeletion`) passés via l'option `errorBag` de `useForm().put/delete` (sinon Inertia ne route pas les erreurs vers le bon formulaire) |
| Inscription (`RegisteredUserController`) | — | — | ⏭️ **Non converti, intentionnel** — la route est commentée dans `routes/auth.php` (« Inscription désactivée - les utilisateurs sont créés par l'admin »), le contrôleur est mort/inatteignable. Ne pas convertir sauf si la route est un jour réac"tivée. |

**Comportement pendant la transition** : ce mécanisme (clic sur un lien non converti → rechargement classique vers l'ancien Blade) **n'a plus lieu d'être** — toutes les pages atteignables via une route réelle sont maintenant sur Inertia. Il ne reste sur Blade que : les ~13 routes d'export fichiers (PDF/Excel/Word — volontairement de simples `<a href>`, jamais de `visit()` Inertia, cf. Phase 3 du plan) et `RegisteredUserController` (mort). `resources/js/app.js` (Alpine) et `layouts/guest.blade.php` peuvent maintenant être considérés comme obsolètes — **à supprimer après une vérification manuelle rapide qu'aucune page ne les référence plus** (non fait dans cette session, voir §7).

**Méthode de vérification utilisée systématiquement avant de dire qu'une page est prête** (aucun accès navigateur direct depuis l'agent) :
1. `php -l` sur le contrôleur modifié
2. `npm run build` (attrape les erreurs de compilation JSX)
3. Appel direct du contrôleur via `php artisan tinker` avec un `Illuminate\Http\Request` et un `setUserResolver()` simulant chaque rôle concerné (voir exemples §5) — attrape les erreurs runtime (méthodes inexistantes, relations non chargées, etc.) sans dépendre du navigateur.

---

## 4. Décisions de design à respecter absolument

- **Orange `#FF6A3A` = seule couleur d'accent.** Consigne explicite et ferme de l'utilisateur : *"tu retire completement le marron, tu garde l'orange c'est le principal"*. Ne réintroduire ni `gda-brun` (`#381419`) ni `gda-cuivre` (`#b26440`) nulle part dans le CSS/JSX. Vérifié propre (`grep -rn "gda-brun\|gda-cuivre\|381419\|b26440" resources/js/` → aucun résultat) au 2026-07-30 — refaire cette vérification avant de considérer une nouvelle page terminée. Là où le marron servait de fond sombre (login, carte "meilleur commercial", avatar), c'est maintenant du gris neutre foncé (`gray-950`/`gray-900`) ou de l'orange.
- Fond neutre `#F6F5F2` (AppLayout), cartes blanches `rounded-xl border border-gray-200 shadow-card`.
- Une seule carte "mise en avant" par écran en orange plein (pas plus) — éviter de tout mettre en orange, garder un contraste avec le reste en neutre/blanc.
- États vides à traiter avec un style discret (bordure pointillée, texte gris) plutôt que de garder un bloc de couleur pleine qui n'a rien à afficher (ex. carte "Campagne" sans campagne active).
- Icônes : `lucide-react` exclusivement.
- Sidebar en rail d'icônes (pas de nav texte en haut) — pattern validé par l'utilisateur, à garder pour toutes les pages futures.
- Ne jamais fabriquer de fausses données pour un graphique/jauge — toujours calculer depuis les vraies données (ex. le taux "commerciaux actifs" de la jauge dashboard est un vrai calcul, pas un chiffre inventé).

---

## 5. Comment relancer l'environnement de dev

Le `.env` MySQL/XAMPP existant n'a **jamais été touché** — il continue de fonctionner normalement. Pour voir l'app tourner (sans Docker, en local, contre la vraie base MySQL) :

```bash
php artisan serve --port=8010   # 8000 est pris par un autre projet Docker sur cette machine, voir §2
npm run dev
```

Puis ouvrir `http://127.0.0.1:8010` et se connecter avec un compte existant réel (base de données réelle, pas de données de test à part celles déjà en base).

Si `php artisan serve` ou `npm run dev` semblent déjà tourner mais ne répondent pas (processus orphelins d'une session précédente qui a crashé) : identifier le PID avec `netstat -ano | grep ":PORT"`, vérifier que c'est bien un processus `node`/`php` via `Get-Process -Id <PID>` avant de le tuer avec `Stop-Process -Id <PID> -Force`, puis relancer proprement.

Pour valider qu'une page/contrôleur ne plante pas sans dépendre du navigateur, méthode utilisée pendant la session (utile pour debug rapide) :
```bash
php artisan tinker --execute="
use App\Models\User;
use Illuminate\Http\Request;
\$u = User::where('role','admin')->first();
\$request = Request::create('/dashboard','GET');
\$request->setUserResolver(fn() => \$u);
try {
    app(App\Http\Controllers\DashboardController::class)->index(\$request);
    echo 'OK';
} catch (\Throwable \$e) {
    echo 'ERROR: '.\$e->getMessage().' at '.\$e->getFile().':'.\$e->getLine();
}
"
```

Toujours lancer `npm run build` après des changements JS pour attraper les erreurs de compilation avant de dire à l'utilisateur que c'est prêt.

---

## 6. Pièges déjà rencontrés (pour ne pas les refaire)

- `Illuminate\Support\Collection::takeLast()` **n'existe pas** en Laravel — utiliser `->slice(-N)->values()`.
- `DB::statement('SET FOREIGN_KEY_CHECKS=...')` est MySQL-only — utiliser `Schema::disableForeignKeyConstraints()`/`enableForeignKeyConstraints()` (portable).
- Une migration guardée `if (DB::getDriverName() !== 'mysql') return;` **au tout début de `up()`** saute aussi les parties portables (ex. `Schema::create` d'une table utile aux tests sqlite) — ne guarder que les lignes réellement non-portables, pas la méthode entière.
- Avant d'écrire une nouvelle migration consolidée, toute table déjà créée par une migration historique non guardée (la plupart le sont) existera déjà sur Postgres au moment où la migration consolidée s'exécute → prévoir `Schema::dropIfExists(...)` pour toutes les tables concernées, pas seulement `users`.
- `useForm()` d'Inertia initialise ses valeurs **une seule fois au montage** du composant — si une modale réutilisable reste montée en permanence et change juste de données via des props, les valeurs initiales du formulaire restent figées sur le premier montage. Forcer un remount avec un `key` React dynamique (`key={modal.type + modal.campagne.id}`) pour que `useForm` se réinitialise correctement à chaque ouverture avec de nouvelles données.
- Le paquet `maennchen/zipstream-php` en version `3.2.1` exige PHP 8.3 — sur cet environnement (PHP 8.2.12), pinner `^3.1.2` explicitement si `composer require` échoue avec un conflit de résolution.
- Sur cette machine, **Docker Desktop héberge d'autres projets** sans rapport avec BDM (voir §2 Phase 0) — toujours vérifier `docker ps -a` avant de croire qu'un port est libre, et ne jamais arrêter un container qui n'a pas un nom lié à BDM.
- Entre deux sessions, les processus `php artisan serve`/`npm run dev` lancés en arrière-plan peuvent survivre au crash/redémarrage de l'agent (processus orphelins Windows) — vérifier avec `netstat`/`Get-Process` avant de relancer, pour éviter les doublons ou les ports fantômes qui ne répondent plus.
- **Upload de fichier + méthode PUT/PATCH** : Laravel ne lit pas les fichiers multipart sur une requête PUT/PATCH réelle. Pattern Inertia standard : soumettre en `post()` avec un champ caché `_method: 'put'` dans les données du formulaire (spoofing), jamais `form.put()` directement avec un fichier. Utilisé dans `Pages/Commercial/Clients/Edit.jsx`.
- **Error bags nommés côté Inertia** (`$request->validateWithBag('nomDuBag', ...)` côté Laravel, ex. `PasswordController`/`ProfileController::destroy`) : le prop partagé `errors` devient `{ nomDuBag: { champ: [...] } }` au lieu d'un objet plat. Pour qu'un formulaire `useForm()` récupère les bonnes erreurs, il **faut** passer `{ errorBag: 'nomDuBag' }` en option à `form.put(...)`/`form.delete(...)` — sinon `form.errors` reste vide. Utilisé dans `Pages/Profile/Edit.jsx` (`updatePassword` pour le changement de mot de passe, `userDeletion` pour la suppression de compte).
- Sur cette machine, `php artisan tinker --execute="..."` peut rester bloqué indéfiniment si on lui passe un **fichier** en argument (`php artisan tinker chemin.php`) — toujours utiliser `--execute="..."` avec le code inline, jamais un fichier séparé.
- Pour inspecter les props Inertia renvoyées par un contrôleur en tinker : il faut simuler l'en-tête `X-Inertia: true` sur la requête (`$request->headers->set('X-Inertia', 'true')`), sinon `toResponse()` retourne la page HTML complète (le root Blade) au lieu du JSON des props, et `json_decode(...)` échoue silencieusement (retourne `null`).

---

## 7. Prochaines étapes suggérées (par ordre logique)

**État au 2026-07-30 fin de session** : **Phase 3 terminée** — toutes les pages atteignables par une route réelle sont converties Inertia/React et vérifiées (lint + build + appel contrôleur en tinker). Il ne reste plus de "pages en box" (fallback Blade) sur un flux utilisateur normal. Reste :

1. **Nettoyage final Blade/Alpine** (non fait, à faire en premier — rapide) :
   - Vérifier qu'aucune vue ne référence plus `resources/js/app.js` (Alpine) ni `layouts/guest.blade.php`/`layouts/app.blade.php`/`navigation.blade.php` (`grep -rn "app.js\|layouts.guest\|layouts.app\|@extends" resources/views/`).
   - Si confirmé inutilisés : supprimer ces fichiers, retirer l'entrée `app.js` de `vite.config.js`, et supprimer les vues Blade converties devenues mortes (`resources/views/admin/`, `resources/views/commercial/`, `resources/views/auth/*.blade.php` sauf celles encore nécessaires pour les exports PDF/Excel/Word, `resources/views/profile/`, etc.) — **vérifier avant de supprimer qu'aucune n'est encore `@include`e par une vue d'export restante**.
   - Revérifier une dernière fois `grep -rn "gda-brun\|gda-cuivre\|381419\|b26440" resources/js/` (déjà propre au 2026-07-30) et faire un tour visuel rapide des dernières pages ajoutées (Transfert d'agence, Profil, pages Auth secondaires) une fois qu'un accès navigateur est possible.
2. **Validation Docker/Postgres réelle** (Phases 0-1 du plan, jamais testées en conditions réelles) : une fois **Docker Desktop qui répond** correctement, lancer `docker compose up`, `php artisan migrate --seed` contre Postgres, vérifier les données dans Supabase Studio, et se connecter avec les 4 rôles. Rester attentif aux autres projets Docker déjà présents sur la machine (§2) — ne jamais toucher un container qui n'appartient pas à BDM.
3. **Checklist de validation locale complète** (§Phase 4 du plan `linked-munching-bachman.md`) — dérouler item par item une fois Docker/Postgres validés : saisie vente mobile, fenêtre 48h modif/suppression vente ET client, upload pièce d'identité, les ~13 routes d'export, scheduler `Campagne::syncStatuts()`, `php artisan test`.
4. **Phase VPS** (Phase 5 du plan) seulement après validation locale complète — ne pas commencer avant. ⚠️ Premier geste sur le VPS : remplacer l'auth par mot de passe root (partagé en clair dans le chat, considéré compromis) par une clé SSH, puis désactiver l'auth par mot de passe.

**Astuce pour reprendre vite** : le système de composants (`Components/ui/`) et les patterns établis (Form.jsx colocalisé, vérification systématique via `php -l` + `npm run build` + appel contrôleur en tinker avec `X-Inertia: true`, tableau + Card + Badge + Pagination pour les listes, `errorBag` pour les formulaires multiples sur une page, `_method: 'put'` + `forceFormData` pour les uploads) couvrent maintenant l'intégralité de l'app réelle — pour toute nouvelle page (si de nouvelles fonctionnalités sont ajoutées), copier le pattern d'une page similaire déjà faite va plus vite que repartir de zéro.

---

## 8. Fichiers de référence utiles

- `bdm_v1.md` — doc d'architecture complète du projet original (avant refonte)
- `docu.md`, `Info.md` — doc opérationnelle et référentiel agences/commerciaux
- `C:\Users\cisse\.claude\plans\linked-munching-bachman.md` — plan détaillé approuvé (5 phases)
- Ce fichier (`ClaudeContexte.md`) — à **mettre à jour à la fin de chaque session** si le travail doit continuer sur un autre compte/une autre session.
