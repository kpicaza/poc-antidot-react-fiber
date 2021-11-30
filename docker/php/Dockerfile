FROM php:8.1.0

RUN apt-get update && apt-get install -y git zip zlib1g-dev libicu-dev g++ libpq-dev libxml2-dev autoconf pkg-config libssh-dev libonig-dev

RUN docker-php-ext-install pdo_pgsql bcmath iconv pcntl mbstring intl calendar sockets

COPY ./conf.d/memory_limit.ini $PHP_INI_DIR/conf.d/memory_limit.ini

RUN usermod -u 1000 www-data

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
