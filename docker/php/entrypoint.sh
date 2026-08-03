#!/bin/sh
set -e

if [ ! -d "vendor" ]; then
    composer install --no-interaction --no-progress
fi

if [ ! -L "public/storage" ]; then
    php artisan storage:link || true
fi

exec "$@"
