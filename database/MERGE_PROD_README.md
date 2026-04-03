# Remplacer la base locale par les données de `prod_bdm.sql`

Les données viennent **uniquement** du fichier **`prod_bdm.sql`** à la racine du projet (même contenu que l’export phpMyAdmin de la prod).  
Techniquement : le fichier est importé dans une base temporaire, puis **toutes les lignes** des tables métier sont recopiées dans votre base locale **après** `migrate:fresh` (schéma Laravel à jour).

La commande **`php artisan db:merge-prod`** :

1. **Enregistre uniquement** les lignes `users` en **`commercial_telephonique`** (pour garder vos mots de passe / rôle après l’import).
2. Importe **`prod_bdm.sql`** dans une base MySQL **temporaire**.
3. **`migrate:fresh`** sur votre `DB_DATABASE` → ancienne base locale effacée.
4. **Copie** agences, users, campagnes, ventes, clients, stocks, etc. **depuis ce dump** vers la base locale.
5. **Réapplique** les deux comptes téléphoniques sauvegardés à l’étape 1.

**Non conservé** : rapports téléphoniques et journal de connexions locaux (non présents dans le dump prod classique).

## Prérequis

- `.env` : `DB_CONNECTION=mysql`, utilisateur avec droit **`CREATE DATABASE`**.
- **`prod_bdm.sql`** à la racine `C:\xampp\htdocs\BDM\prod_bdm.sql` (ou indiquez un autre fichier).

## Commande

Par défaut → **`prod_bdm.sql`** à la racine :

```bash
cd C:\xampp\htdocs\BDM
php artisan db:merge-prod
```

Autre fichier :

```bash
php artisan db:merge-prod C:\chemin\vers\export.sql
```

Sans confirmation :

```bash
php artisan db:merge-prod --yes
```

## Fichiers pièces d’identité

Le dump ne contient que les chemins ; copiez depuis la prod le dossier équivalent à `storage/app/public/cartes-identite` si vous en avez besoin en local.

## Vers la production

Après contrôle en local, exportez la base puis importez sur le serveur **après sauvegarde** et avec le **même code / migrations** déployés.
