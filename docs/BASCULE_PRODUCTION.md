# Bascule en production — Laravel → Django

Aucune donnée n'est migrée : **c'est la même base, le même schéma**. La bascule
est un changement d'upstream nginx, réversible en moins d'une minute.

---

## Principe

```
                     ┌──────────────────────────┐
   nginx de l'hôte ──┤ 127.0.0.1:8090  Laravel  │  ← aujourd'hui
   (HTTPS public)    │ 127.0.0.1:8092  Django   │  ← après bascule
                     └────────────┬─────────────┘
                                  │
                       même base MySQL `bdm`
```

Les deux stacks tournent en parallèle. Seule la ligne `proxy_pass` du nginx de
l'hôte décide laquelle répond au public.

---

## 1. Avant la bascule

**Dump de sécurité** — même si rien ne sera modifié structurellement :

```bash
docker exec bdm_db mysqldump -u root -p bdm > bdm_avant_bascule_$(date +%F_%H%M).sql
```

**Configuration** :

```bash
cp backend/.env.production.example backend/.env.production
# renseigner DJANGO_SECRET_KEY, DJANGO_ALLOWED_HOSTS, DB_PASSWORD
python -c "from django.core.management.utils import get_random_secret_key as k; print(k())"
```

**Construire et démarrer Django, sans toucher à Laravel** :

```bash
docker compose -f docker-compose.django.yml --env-file backend/.env.production up -d --build
```

**Créer les tables techniques** (opération additive : aucune table métier n'est touchée) :

```bash
docker compose -f docker-compose.django.yml exec django python manage.py migrate
docker compose -f docker-compose.django.yml exec django python manage.py createcachetable
```

Tables ajoutées : `django_migrations`, `django_session`, `django_content_type`,
`auth_permission`, `auth_group`, `auth_group_permissions`, `django_cache`.
Aucune suppression, aucune modification de colonne.

---

## 2. Valider en lecture, avant d'ouvrir au public

Django répond sur `127.0.0.1:8092`, Laravel sert toujours le public. C'est le
moment de comparer les deux sur les **vraies données** :

```bash
# Depuis le serveur, en adaptant les URL dans le script.
python scripts/comparer_stacks.py
python scripts/comparer_routes.py
python scripts/verifier_exports.py
```

Attendu : `Aucun écart`, `Toutes les routes Laravel sont portées`, et
`Tous les exports produisent un fichier valide`.

Vérifier aussi à la main, avec un vrai compte : connexion, tableau de bord,
une campagne, un export Excel.

---

## 3. Basculer

Dans le `server {}` du nginx **de l'hôte**, remplacer :

```nginx
proxy_pass http://127.0.0.1:8090;   # Laravel
```

par :

```nginx
proxy_pass http://127.0.0.1:8092;   # Django
```

puis :

```bash
nginx -t && systemctl reload nginx
```

La bascule est immédiate. **Tous les utilisateurs sont déconnectés une fois** :
les sessions Laravel ne sont pas lisibles par Django. Les mots de passe, eux,
sont inchangés — personne n'a besoin de les réinitialiser.

À faire hors période de campagne active.

---

## 4. Revenir en arrière

Remettre `proxy_pass` sur `8090`, recharger nginx. C'est tout.

Le retour reste possible **à tout moment** : le schéma n'a pas bougé, et les
mots de passe modifiés depuis Django sont écrits au format `$2y$` que Laravel
relit sans difficulté.

---

## 5. Après une à deux semaines stables

Une fois la confiance acquise :

```bash
# Arrêter Laravel
docker compose -f docker-compose.prod.yml down

# Nettoyer le dépôt
git rm -r app routes resources vendor composer.json composer.lock artisan \
          bootstrap config database lang public storage tests \
          phpunit.xml docker-compose.prod.yml docker/php docker/nginx/default.conf
```

Supprimer aussi les tables devenues inutilisées :

```sql
DROP TABLE cache, cache_locks, jobs, job_batches, failed_jobs, sessions, migrations;
```

Enfin, passer les modèles en `managed = True` et poser une migration de
référence :

```bash
python manage.py makemigrations
python manage.py migrate --fake-initial
```

À partir de là, le schéma est géré par Django et les évolutions passent par des
migrations normales.

---

## Points de vigilance

| Point | Ce qu'il faut savoir |
|---|---|
| **HTTPS** | Le nginx de l'hôte termine le TLS. Django s'appuie sur `X-Forwarded-Proto` ; l'en-tête doit être transmis, sinon les cookies sécurisés ne seront pas posés. |
| **HSTS / redirection SSL** | Volontairement laissés au nginx de l'hôte, qui les gère déjà pour les autres sites. `check --deploy` les signale, c'est normal. |
| **Fichiers téléversés** | Le volume `bdm_storage_app` est partagé entre les deux stacks : les pièces d'identité restent accessibles avant comme après. |
| **Fuseau horaire** | Django tourne en UTC avec `USE_TZ = False`, comme Laravel. Ne pas y toucher : un décalage ferait basculer des ventes d'un jour à l'autre. |
| **Sessions** | Une seule déconnexion, au moment de la bascule. Prévenir les utilisateurs. |
| **Mots de passe** | Inchangés. Vérifié dans les deux sens : un hachage PHP est accepté par Python, et inversement. |
