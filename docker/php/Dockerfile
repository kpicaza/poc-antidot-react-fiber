FROM php:8.1.0RC6

RUN apt-get update && apt-get install -y git zip zlib1g-dev libicu-dev g++ libpq-dev libxml2-dev autoconf pkg-config libssh-dev libonig-dev

RUN docker-php-ext-install pdo_pgsql bcmath iconv pcntl mbstring intl calendar sockets

RUN pecl install -o -f redis \
&& rm -rf /tmp/pear \
&& docker-php-ext-enable redis

COPY ./conf.d/redis.ini $PHP_INI_DIR/conf.d/redis.ini

RUN usermod -u 1000 www-data

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
