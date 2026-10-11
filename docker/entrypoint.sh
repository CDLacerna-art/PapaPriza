#!/bin/sh
set -e
cd /var/www/html

if [ -z "$APP_KEY" ]; then
  echo "ERROR: APP_KEY is empty. Run 'php artisan key:generate --show' and put it in .env.docker"
  exit 1
fi

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

php artisan package:discover --ansi || true
php artisan storage:link --force || true

# Note: route:cache is NOT used because routes/web.php has closure routes
php artisan config:cache
php artisan view:cache

if [ "$RUN_MIGRATIONS" = "true" ]; then
  php artisan migrate --force
fi

exec "$@"
