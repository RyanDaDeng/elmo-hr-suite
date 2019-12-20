#This image is built and pushed into ECR
ARG PHP_VERSION=7.2
ARG NGINX_VERSION=1.14

FROM php:${PHP_VERSION}-fpm-alpine

MAINTAINER krishna.durgasi@elmosoftware.com.au

RUN apk add --no-cache \
                acl \
                file \
                gettext \
                git \
                mysql-client \
                openssh \
                openssh-client \
                sudo \
                shadow \
                bash \
                supervisor \
        ;

RUN docker-php-ext-install pdo pdo_mysql bcmath

ARG APCU_VERSION=5.1.12

RUN set -eux; \
        apk add --no-cache --virtual .build-deps \
                $PHPIZE_DEPS \
                icu-dev \
                libzip-dev \
                postgresql-dev \
                zlib-dev \
        ; \
        \
        docker-php-ext-configure zip --with-libzip; \
        docker-php-ext-install -j$(nproc) \
                intl \
                pdo_mysql \
                zip \
        ; \
        pecl install \
                apcu-${APCU_VERSION} \
        ; \
        pecl clear-cache; \
        docker-php-ext-enable \
                apcu \
                opcache \
                pdo_mysql \
        ; \
        \
        runDeps="$( \
                scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
                        | tr ',' '\n' \
                        | sort -u \
                        | awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
        )"; \
	 apk add --no-cache --virtual .api-phpexts-rundeps $runDeps; \
        \
        apk del .build-deps

#Install Composer
RUN curl -sS "https://getcomposer.org/installer" | php
RUN mv composer.phar /usr/local/bin/composer

#Install yarn and Nginx
RUN apk add --update nginx && rm -rf /var/cache/apk/*
RUN apk add -U tzdata && \
    cp /usr/share/zoneinfo/Australia/Sydney /etc/localtime

RUN mkdir -p /etc/supervisor.d
RUN mkdir -p /run/nginx
RUN mkdir -p /var/lib/nginx \
    && chown -R www-data:www-data /var/lib/nginx/tmp \
    && chown -R www-data:www-data /var/tmp \
    && chmod 777 /var/lib/nginx -R

ENV webAppFolder=/var/www/slack
ENV wwwDataUID=82
ENV wwwDataGID=82

# Cleanse web directory
RUN \
    rm -rf /var/www/* \
    && mkdir -p /var/www/slack \
    && chown -R www-data:www-data ${webAppFolder}

WORKDIR ${webAppFolder}

RUN \
    mkdir -p /root/.ssh/ && \
    echo "$SSH_PRIVATE_KEY" > /root/.ssh/id_rsa && \
    chmod -R 400 /root/.ssh/ && \
    /usr/bin/ssh-keyscan -t rsa bitbucket.org >> ~/.ssh/known_hosts 

COPY docker/php/etc/php.d/php.ini ${PHP_INI_DIR}/conf.d/.
     #chown -R $wwwDataUID:$wwwDataGID ${webAppFolder}/app/cache/ ${webAppFolder}/app/* ${webAppFolder}/app/logs
RUN  echo "security.limit_extensions = .php .html" >> /usr/local/etc/php-fpm.d/www.conf && \
     echo "access.format = \"%R – %u %t \\\"%m %r%Q%q\\\" %s %f %{mili}d %{kilo}M %C%%\"" >> /usr/local/etc/php-fpm.d/www.conf

    # Add www-data to sudoers
RUN echo \
    && groupadd sudo \
    && usermod -G sudo www-data \
    && usermod -s /bin/bash www-data \
    && usermod -s /bin/bash root \
    && echo "root ALL=(ALL) ALL" > /etc/sudoers \
    && echo "%sudo ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers

WORKDIR ${webAppFolder}

# Clone the repository to temp folder
COPY . .
RUN composer install --no-interaction --prefer-dist --classmap-authoritative --no-scripts --no-suggest --no-progress --ignore-platform-reqs -n

#Copy custom backend Nginx conf file
COPY docker/nginx/etc /etc

# Add supervisor conf to start the processes
ADD docker/supervisor/supervisord.conf /etc/

#Entrypoint script
COPY docker/scripts/docker-entrypoint.sh /usr/local/bin/docker-entrypoint

#Copy build script to build TMS app inside the container
COPY docker/scripts/build.sh ${webAppFolder}/

#Executable Permissions
RUN chmod -R +x /usr/local/bin/docker-entrypoint /usr/local/bin /usr/bin

#Expose ports
EXPOSE 80

#Entrypoint script
ENTRYPOINT ["docker-entrypoint"]

#Supervisor starts nginx &php-fpm process
CMD ["supervisord", "-c", "/etc/supervisord.conf"]
