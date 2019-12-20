#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
        set -- php-fpm "$@"
fi

if [ "$1" = 'php-fpm' ] || [ "$1" = 'bin/console' ]; then
        mkdir -p var/cache var/logs
        setfacl -R -m u:www-data:rwX -m u:"$(whoami)":rwX var
        setfacl -dR -m u:www-data:rwX -m u:"$(whoami)":rwX var

fi

# Save root environment vars
# These are needed when loading as www-data user.
env > /etc/environment

# Fix directory permissions
# This can get screwed because of Volume mounts
chown www-data:www-data /var/www/slack -R

exec docker-php-entrypoint "$@"
