FROM php:7.1-fpm

RUN apt-get update -q && apt-get install -y -q --no-install-recommends \
    sudo \
    unzip \
    libpq-dev \
    supervisor \
    nginx-light

RUN rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql pcntl \
    && pecl install xdebug apcu

COPY etc/docker/php/assets/php.ini /usr/local/etc/php/php.ini

COPY etc/docker/php/assets/nginx/nginx.conf /etc/nginx/nginx.conf
COPY etc/docker/php/assets/nginx/symfony.conf /etc/nginx/sites-enabled/default

COPY etc/docker/php/assets/xdebug.ini /usr/local/etc/php/conf.d/
COPY etc/docker/php/bin/* /usr/local/bin/

COPY etc/docker/php/assets/supervisor/conf.d/* /etc/supervisor/conf.d/
COPY etc/docker/php/assets/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

CMD ["/app/etc/docker/php/bin/enter_dev"]
ENV TERM=xterm
EXPOSE 80
