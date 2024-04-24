FROM php:8.2-fpm

## Main
#RUN apt-get update && \
#    apt-get install --no-install-recommends -y \
#        imagemagick \
#        nano \
#        mc \
#        memcached \
#        sudo \
#        git \
#        zip \
#        unzip \
#        libicu-dev \
#        zlib1g-dev \
#        libzip-dev \
#        libxml2-dev && \
#        docker-php-ext-install zip mbstring intl pcntl exif iconv && \
#    rm -r /var/lib/apt/lists/*
#
## Mcrypt
#RUN apt-get update && \
#    apt-get install --no-install-recommends -y \
#        libmcrypt-dev && \
#        pecl install mcrypt-1.0.2 && \
#    rm -r /var/lib/apt/lists/*
#
# GD
RUN apt-get update && \
    apt-get install --no-install-recommends -y \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev && \
        docker-php-ext-install gd && \
    rm -r /var/lib/apt/lists/*

# Postgres
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

#composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && mv composer.phar /usr/local/bin/composer

RUN apt install git -y


# Supervisor
RUN apt-get update && \
    apt-get install --no-install-recommends -y \
        supervisor && \
    rm -r /var/lib/apt/lists/*
COPY ./config/laravel-worker.conf /etc/supervisor/conf.d

##Xdebug
#COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
#RUN install-php-extensions xdebug
#ENV PHP_IDE_CONFIG 'serverName=msrf'
#RUN echo "xdebug.mode=debug,coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.start_with_request = yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.client_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.log=/var/log/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
#    && echo "xdebug.idekey = PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

WORKDIR /project
