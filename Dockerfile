# ---------- Stage 1: build front-end assets (Vite) ----------
FROM node:20-alpine AS assets
WORKDIR /app
COPY . .
RUN if [ -f package.json ]; then (npm ci || npm install) && npm run build; fi \
 && mkdir -p public/build

# ---------- Stage 2: PHP + Nginx app image ----------
FROM php:8.4-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache nginx supervisor git unzip \
 && docker-php-ext-install pdo_mysql bcmath opcache \
 && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install PHP packages first (cached unless composer files change)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --no-interaction --prefer-dist

# App source + built assets
COPY . .
COPY --from=assets /app/public/build ./public/build

RUN composer dump-autoload --optimize --no-dev --no-scripts \
 && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache

COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh && chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80
ENTRYPOINT ["entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisord.conf"]
