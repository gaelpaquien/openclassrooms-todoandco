#!/bin/bash
set -e

log() {
    echo "[$(date '+%H:%M:%S')] $1"
}

log "START: Maintenance ToDo & Co"

log "Installing DEV dependencies"
composer install --no-interaction

log "Database reset"
php bin/console doctrine:database:drop --force --env=dev
php bin/console doctrine:database:create --env=dev
php bin/console doctrine:schema:update --force --env=dev
php bin/console doctrine:fixtures:load --no-interaction --env=dev

log "Cleaning temporary files"
rm -rf var/log/* var/cache/*

log "Installing PROD dependencies"
composer install --no-dev --optimize-autoloader --no-interaction

log "PROD cache"
php bin/console cache:clear --env=prod --no-interaction
php bin/console cache:warmup --env=prod --no-interaction

log "END: Maintenance ToDo & Co completed"