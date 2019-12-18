#!/usr/bin/env bash
# Helpful shell scripts

# Save users current env
env > /tmp/env

# Ensure env vars are loaded from Root's knowledge
export $(cat /etc/environment | xargs) 2> /dev/null

# Load users env vars back over the top
export $(cat /tmp/env | xargs) 2> /dev/null
rm /tmp/env
export SSH_AUTH_SOCK=/var/ssh/socket

# Start in web dir
cd /var/www/survey-backend

# Load git prompt script
source /etc/profile.d/git-prompt.sh

# Prompt Style
# http://bashrcgenerator.com/
export PS1="\
\[\e[40;1;31m\]\u\
\[\e[40;1;33m\]@\
\[\e[40;1;35m\]\H\
\[\e[40;1;36m\][\w]\
\[\e[40;1;37m\]\$(__git_ps1) \
\[\e[40;1;33m\]\\$ \[\e[0m\]"

# Colours
C_MSG='\033[1;32;40m' # green-on-black
C_NORM='\033[0m' # reset

# Hard enable xdebug
php_xdebug_on()
{
    sudo cp /etc/php.d/mods-available/xdebug.ini /etc/php.d
    sudo supervisorctl restart php-fpm
    printf "${C_MSG}PHP XDEBUG: Enabled${C_NORM}\n"
}
alias xdebug-on=php_xdebug_on

# Hard disable xdebug
php_xdebug_off()
{
    sudo rm -f /etc/php.d/xdebug.ini
    sudo supervisorctl restart php-fpm
    printf "${C_MSG}PHP XDEBUG: Disabled${C_NORM}\n"
}
alias xdebug-off=php_xdebug_off

# Restart apache without getting yourself disconnected
apache_restart()
{
    sudo supervisorctl restart php-fpm
    printf "${C_MSG}Apache restarted${C_NORM}\n"
}
alias apache-restart=apache_restart

# Disable app debug mode
app_debug_off()
{
    cp /etc/php.d/mods-available/xdebug.ini /etc/php.d
    sudo supervisorctl restart php-fpm
    printf "${C_MSG}APP DEBUG Mode: Disabled${C_NORM}\n"
    echo "Kernel will no longer start in debug mode."
}
alias debug-off=app_debug_off

# Enable app debug mode
app_debug_on()
{
    sudo a2enconf app-debug > /dev/null
    sudo supervisorctl restart php-fpm
    printf "${C_MSG}APP DEBUG Mode: Enabled${C_NORM}\n"
    echo "Kernel always start in debug mode."
}
alias debug-on=app_debug_on

# Disable app debug mode
profiler_off()
{
    profiler_on disable
}
alias profiler-off=profiler_off

# Enable app debug mode
profiler_on()
{
    PROFILER=${1:-help}

    # Disable everything going in
    sudo a2disconf app-xdebug-trace > /dev/null
    sudo php5dismod xhprof > /dev/null
    sudo php5dismod memprof > /dev/null
    sudo php5dismod elmo-profiler > /dev/null

    # Pick what to execute
    if [ "$PROFILER" = "xhprof" ]; then
        sudo php5enmod elmo-profiler > /dev/null
        sudo php5enmod xhprof > /dev/null
        printf "${C_MSG}PROFILER: XHPROF Enabled${C_NORM}\n"
    elif [ "$PROFILER" = "memprof" ]; then
        sudo php5enmod elmo-profiler > /dev/null
        sudo php5enmod memprof > /dev/null
        printf "${C_MSG}PROFILER: MEMPROF Enabled${C_NORM}\n"
    elif [ "$PROFILER" = "xdebug" ]; then
        sudo php5enmod elmo-profiler > /dev/null
        sudo a2enconf app-xdebug-trace > /dev/null
        printf "${C_MSG}PROFILER: XDEBUG Trace Enabled${C_NORM}\n"
    elif [ "$PROFILER" = "disable" ]; then
        printf "${C_MSG}PROFILER: Disabled${C_NORM}\n"
    else
        sudo php5enmod elmo-profiler > /dev/null
        printf "${C_MSG}PROFILER: Bootstrap mode only${C_NORM}\n"
        echo "The profiler is running Bootstrap mode only."
        echo "If you wish to use a PHP Profiler, specify an argument to profiler-on:"
        echo "  memprof - memProf Profiler (Memory)"
        echo "  xhprof  - xhProf Profiler (Memory and CPU)"
        echo "  xdebug  - xDebug Trace Profiler"
    fi

    # Restart Apache to ensure front-end gets the message
    sudo supervisorctl restart php-fpm
}
alias profiler-on=profiler_on

# LS colours
export LS_COLORS='di=1:fi=0:ln=31:pi=5:so=5:bd=5:cd=5:or=31:mi=0:ex=35:*.rpm=90'
export LS_OPTIONS='--color=auto'

# LS extensions (ls, ll, l)
alias ls='ls $LS_OPTIONS'
alias ll='ls $LS_OPTIONS -l'
alias l='ls $LS_OPTIONS -lA'

# Docker/Survey compatibility installer
# This is deprecated.
app_docker_install()
{
    if [ ! -f /var/www/survey-backend/dev.sh ]; then
        printf "${C_MSG}Installing V7 Docker compatibility fixes ... ${C_NORM}"
        cp /home/www-data/survey-backend/app_dev.php /var/www/survey-backend/web/app_dev.php
        cp /home/www-data/survey-backend/config_* /var/www/survey-backend/app/config/
        cp /var/www/survey-backend/htaccess.tpl /var/www/survey-backend/web/.htaccess
        mkdir -p /var/www/survey-backend/app/runtime/dev
        touch /var/www/survey-backend/app/runtime/dev/config.json
        mkdir -p /var/www/survey-backend/app/runtime/test
        touch /var/www/survey-backend/app/runtime/test/config.json
        printf "${C_MSG}Done. ${C_NORM}\n"
    else
        printf "${C_MSG}Deprecated command. Run this instead:${C_NORM}\n"
        printf "${C_MSG}dev.sh setup-auto                    ${C_NORM}\n"
    fi


}
alias app-docker-install=app_docker_install
