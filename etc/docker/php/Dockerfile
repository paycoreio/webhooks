FROM alpine:3.6

RUN apk --update --no-cache add \
    php7 \
    php7-fpm \
    php7-xml \
    php7-dom \
    php7-json \
    php7-phar \
    php7-iconv \
    php7-openssl \
    php7-zlib \
    php7-pdo \
    php7-pdo_pgsql \
    php7-pgsql \
    php7-pcntl \
    php7-ctype \
    php7-mbstring \
    php7-xdebug \
    sudo \
    unzip \
    supervisor \
    nginx \
    curl \
    bash

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
