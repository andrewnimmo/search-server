FROM php:7.1-cli

WORKDIR /var/www

RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
        supervisor \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install zip bcmath

RUN curl -L -o /tmp/redis.tar.gz https://github.com/phpredis/phpredis/archive/4.0.2.tar.gz \
    && tar xfz /tmp/redis.tar.gz \
    && rm -r /tmp/redis.tar.gz \
    && mkdir -p /usr/src/php/ext \
    && mv phpredis-4.0.2 /usr/src/php/ext/redis \
    && docker-php-ext-install redis

RUN apt-get install -y curl && \
    curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/bin/composer

RUN mkdir /var/www/apisearch
COPY . /var/www/apisearch
RUN cd /var/www/apisearch && \
    composer install -n --prefer-dist && \
    composer dump-autoload

COPY docker/supervisor-search-server.conf /etc/supervisor/conf.d/search-server.conf
COPY docker/docker-entrypoint.sh /

ENTRYPOINT ["/docker-entrypoint.sh"]
