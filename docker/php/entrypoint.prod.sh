#!/bin/sh
set -e

# public/ vient d'un volume nommé partagé avec nginx : Docker ne le peuple qu'au
# tout premier démarrage (volume vide). Sans cette resynchronisation depuis la
# copie figée dans l'image, chaque déploiement continuait à servir les assets
# Vite du premier build. Le lien symbolique public/storage n'est pas dans
# public-dist : il est recréé juste après et donc préservé.
if [ -d /var/www/html/public-dist ]; then
    rm -rf /var/www/html/public/build
    cp -a /var/www/html/public-dist/. /var/www/html/public/
fi

php artisan storage:link || true
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
