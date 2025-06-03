#!/bin/bash

# Set working directory
# cd /path/to/project

# Start of script
echo "maintenance.sh: Start of daily maintenance script"

# Delete images added in the last 24 hours (including subdirectories)
# echo "maintenance.sh: Delete images added in the last 24 hours (including subdirectories)"
# find "$PWD/path/to/images" -type f -mtime -1 -print -exec rm {} \;

# Set path to .env.local file
ENV_FILE=".env.local"

# Load environment variables from .env.local
if [ -f "$ENV_FILE" ]; then
    echo "maintenance.sh: Loading environment variables from $ENV_FILE"
    source "$ENV_FILE"
fi

# Show value of APP_ENV before change
echo "maintenance.sh: Before the environment change, APP_ENV is : $APP_ENV"

# Switch to DEV environment
echo "maintenance.sh: Switch to DEV environment"
sed -i 's/^APP_ENV=.*/APP_ENV=dev/' "$ENV_FILE"

# Reload environment variables after changing APP_ENV
if [ -f "$ENV_FILE" ]; then
    source "$ENV_FILE"
    echo "maintenance.sh: Reloading environment variables after changing APP_ENV, new value : $APP_ENV"
fi

# Install development dependencies
echo "maintenance.sh: Install development dependencies"
composer install

# Database reset
echo "maintenance.sh: Database reset"
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load --no-interaction

# Switch to PROD environment
echo "maintenance.sh: Switch to PROD environment"
sed -i 's/^APP_ENV=.*/APP_ENV=prod/' "$ENV_FILE"

# Reload environment variables after changing APP_ENV
if [ -f "$ENV_FILE" ]; then
    source "$ENV_FILE"
    echo "maintenance.sh: Reloading environment variables after changing APP_ENV, new value : $APP_ENV"
fi

# Delete temporary files
echo "maintenance.sh: Delete temporary files"
rm -rf var/log/*
rm -rf var/cache/*

# Removing development dependencies
echo "maintenance.sh: Removing development dependencies"
composer install --no-dev

# Clean the Symfony cache in PROD mode
echo "maintenance.sh: Clean the Symfony cache in PROD mode"
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod

# Send a confirmation email
# subject="Blog PHP - Daily maintenance"
# message="The daily maintenance script completed successfully."
# recipient="your@email.com"
# echo "$message" | mail -s "$subject" "$recipient"

# End of script
echo "maintenance.sh: All steps are completed, end of daily maintenance script"
