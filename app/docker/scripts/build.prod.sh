#!/bin/bash

cd /var/www

echo "Starting building app in PROD environment..."

if [ ! -f .env.local ]; then
  echo "Creating .env.local from .env..."
  cp .env .env.local
fi

echo "Installing dependencies..."
composer install --no-dev --optimize-autoloader --classmap-authoritative --no-interaction || exit 1

echo "Optimizing environment variables..."
composer dump-env prod || exit 1

echo "Setting up database...."
php bin/console doctrine:database:create --env=prod --if-not-exists --no-interaction || exit 1
php bin/console doctrine:schema:update --env=prod --force --no-interaction || exit 1
php bin/console doctrine:migrations:migrate --env=prod --no-interaction || exit 1

echo "Clearing cache..."
php bin/console cache:clear --env=prod --no-interaction || exit 1

echo "Warming up cache..."
php bin/console cache:warmup --env=prod --no-interaction || exit 1

echo "Building app completed successfully!"

exec "$@"