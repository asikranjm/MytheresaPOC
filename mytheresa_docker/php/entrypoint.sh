#!/usr/bin/env bash
set -e

until bash -c "echo > /dev/tcp/${DB_HOST}/${DB_PORT}" 2>/dev/null; do
  echo "Waiting for the database to be ready..."
  sleep 1
done

composer install --no-interaction --optimize-autoloader
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load --no-interaction

chown -R www-data:www-data var

exec "$@"
