#!/usr/bin/env sh
#
# CLI Toolkit scripts for TMS.

# Start in web dir
cd /var/www/survey-backend

# Util: Colours
C_MSG='\033[1;32;40m' # green-on-black
C_NORM='\033[0m' # reset

BUILD_DATE=$(stat -c %z /endpoint.sh)

cat <<EOM
 Build: $BUILD_DATE
 Help: "dev-help"

EOM

# -------------------------------------------------------------
# Developer help command
toolkit_help()
{
cat << 'EOM'

Available commands:

xdebug-on       Enable PHP xdebug
xdebug-off      Disable PHP xdebug
php-restart     Restart PHP-FPM

EOM
}
alias dev-help='toolkit_help'

# -------------------------------------------------------------
# Xdebug
# Disable/enable the PHP Xdebug module
toolkit_xdebug()
{
    if [ "$1" = true ]; then
        sudo cp /usr/local/etc/php/mods-available/xdebug.ini /usr/local/etc/php
        sudo supervisorctl restart php-fpm
        printf "${C_MSG}PHP XDEBUG: Enabled${C_NORM}\n"
    else
        sudo rm -f /usr/local/etc/php/xdebug.ini
        sudo supervisorctl restart php-fpm
        printf "${C_MSG}PHP XDEBUG: Disabled${C_NORM}\n"
    fi
}
alias xdebug-on='toolkit_xdebug true'
alias xdebug-off='toolkit_xdebug false'

# Webserver Restart
# Restart webserver without terminating the primary thread
toolkit_webserver_restart()
{
    sudo supervisorctl restart php-fpm nginx
    printf "${C_MSG}Webserver restarted${C_NORM}\n"
}
alias apache-restart=toolkit_webserver_restart
alias php-restart=toolkit_webserver_restart


# -------------------------------------------------------------
# Exit assistant
toolkit_exit_helper()
{
    if [ -z $1 ]; then
        command exit 0
    fi
    
    command exit $1
}
alias exit='toolkit_exit_helper'
