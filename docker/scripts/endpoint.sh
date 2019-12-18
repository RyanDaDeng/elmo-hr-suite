#!/bin/bash

# Save root environment vars
# These are needed when loading as www-data user.
env > /etc/environment

# Fix directory permissions
# This can get screwed because of Volume mounts
chown www-data:www-data /var/www/tms -R

supervisord -n