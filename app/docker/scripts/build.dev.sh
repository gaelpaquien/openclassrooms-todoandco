#!/bin/bash

cd /var/www

echo "Starting building app in DEV environment..."

echo "Installing dependencies..."
composer install || exit 1

echo "Setting up database...."
php bin/console doctrine:database:create --env=dev --if-not-exists
php bin/console doctrine:schema:create --env=dev
echo "yes" | php bin/console doctrine:fixtures:load --env=dev

echo "Clearing cache..."
symfony console cache:clear --env=dev || exit 1

echo "Building app completed successfully!"

exec "$@" || exit 1