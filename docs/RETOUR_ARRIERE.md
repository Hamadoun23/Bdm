# Retour arrière vers Laravel

> Bascule effectuée le **15/08/2026 à 15h21 UTC**.
> Laravel est **arrêté, pas supprimé** : le retour prend moins d'une minute.

---

## Procédure

```bash
ssh -i ~/.ssh/bdm_vps_ed25519 root@194.163.187.59

# 1. Redémarrer Laravel
docker start bdm-app-1 bdm-web-1

# 2. Repointer nginx
cp /etc/nginx/sites-available/bdm.laravel.bak /etc/nginx/sites-available/bdm
nginx -t && systemctl reload nginx
```

C'est tout. Le site repasse sur Laravel immédiatement.

Optionnel, pour libérer les ressources :

```bash
cd /opt/bdm-django
docker compose -f docker-compose.django.yml down
```

---

## Pourquoi c'est possible

| Élément | État |
|---|---|
| Schéma de base | **Inchangé** — les 28 tables métier n'ont pas été touchées |
| Mots de passe | **Inchangés** — bcrypt `$2y$`, relisible par les deux stacks |
| Fichiers téléversés | Volume `bdm_storage_app` **partagé** entre les deux stacks |
| Code Laravel | `/opt/bdm` intact, conteneurs arrêtés |
| Vhost nginx d'origine | `/etc/nginx/sites-available/bdm.laravel.bak` |
| Dépôt Laravel | Dépôt privé **`bdm-archive`** (30 commits) |

Django n'a fait qu'**ajouter** 7 tables techniques : `django_migrations`,
`django_session`, `django_content_type`, `auth_permission`, `auth_group`,
`auth_group_permissions`, `django_cache`. Laravel les ignore.

---

## Ce qui se perd en revenant

- Les **sessions ouvertes** : tout le monde se reconnecte une fois (les sessions
  Django ne sont pas lisibles par Laravel). Les identifiants restent les mêmes.
- Les mots de passe **changés depuis Django** restent valides : ils sont écrits
  au format `$2y$` que Laravel relit sans difficulté.

Aucune donnée métier n'est perdue dans un sens comme dans l'autre : les deux
stacks écrivent dans la même base, avec le même schéma.

---

## Architecture en place

```
        Internet (HTTPS)
              │
    nginx de l'hôte — /etc/nginx/sites-available/bdm
              │
              ├──► 127.0.0.1:8092  nginx Django   ◄── ACTIF
              │         └──► 127.0.0.1:8091  gunicorn (3 workers)
              │
              └──► 127.0.0.1:8090  nginx Laravel  ◄── arrêté, prêt à repartir
                        └──► php-fpm

                    bdm-db-1 (MySQL 8.0) — partagé
```

| Emplacement | Contenu |
|---|---|
| `/opt/bdm` | Laravel (arrêté) |
| `/opt/bdm-django` | Django + React (actif) |
| `/opt/bdm-django/backend/.env.production` | Secrets, `chmod 600` |

---

## Mettre à jour Django

```bash
cd /opt/bdm-django
git pull origin main
docker compose -f docker-compose.django.yml --env-file backend/.env.production up -d --build
```

---

## Quand supprimer Laravel définitivement

Après **une à deux semaines** de fonctionnement stable, et pas avant :

```bash
docker rm bdm-app-1 bdm-web-1
docker rmi bdm-app
rm -rf /opt/bdm
```

Puis, dans la base :

```sql
DROP TABLE cache, cache_locks, jobs, job_batches, failed_jobs, sessions, migrations;
```

Enfin, passer les modèles Django en `managed = True` et poser une migration de
référence (`makemigrations` puis `migrate --fake-initial`), pour que le schéma
soit désormais géré par Django.

> Tant que ces étapes ne sont pas faites, le retour arrière reste disponible.
> Rien ne presse.
