#!/usr/bin/env bash

# Installer Script
# Bash script to set up the application.
#
# Usage:
# 
# Dev install: tools/setup.sh
# Prod Install: tools/setup.sh --prod --env=elmo


# Die on any problems
set -e

# Colors
NOTICE='\033[0;30;47m'
WARN='\033[0;41m'
NC='\033[0m'

# Comment helper
function comment {
    printf "\n${NOTICE} » ${1} ${NC}\n\n"
}

# Comment helper
function warning {
    printf "\n${WARN} » WARNING ${1} ${NC}\n\n"
}

comment "Starting Setup..."

# Production mode?
if $PROD; then
    comment "Running in production mode!"
fi

if [[ ! -f .env ]]; then
    comment "Copying Codeception .env.docker file to .env"
    cp .envdocker .env
fi


comment "Installing PHP Dependencies ..."
    composer install -v --no-dev --prefer-dist --optimize-autoloader --no-ansi --no-interaction

#Permissions for www-data user
chown -R www-data:www-data storage/logs 

comment "RUN Doctrine Migrations..."
php artisan migrate

comment "Generate Application Key ..."
php artisan key:generate

comment "Clear Cache..."
php artisan config:cache

comment "Setup Complete."
