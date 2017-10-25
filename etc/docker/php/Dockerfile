FROM alpine:3.6

RUN apk --update --no-cache add \
    php7 \
    php7-amqp \
    php7-apcu \
    php7-xml \
    php7-curl \
    php7-dom \
    php7-json \
    php7-phar \
    php7-intl \
    php7-iconv \
    php7-cgi \
    php7-openssl \
    php7-zlib \
    php7-zip \
    php7-pdo \
    php7-pdo_pgsql \
    php7-pgsql \
    php7-pcntl \
    php7-ctype \
    php7-mbstring \
    php7-mcrypt \
    php7-xmlreader \
    php7-tokenizer \
    php7-simplexml \
    php7-fileinfo \
    php7-xmlwriter \
    php7-bcmath \
    php7-bz2 \
    php7-redis \
    php7-posix \
    php7-xdebug \
    sudo \
    unzip \
    curl \
    bash

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY etc/docker/php/assets/php.ini /etc/php7/php.ini

COPY etc/docker/php/assets/xdebug.ini /etc/php7/conf.d/
COPY etc/docker/php/bin/* /usr/local/bin/

COPY etc/docker/php/assets/supervisor/conf.d/* /etc/supervisor/conf.d/
COPY etc/docker/php/assets/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

CMD ["/app/etc/docker/php/bin/enter_dev"]
ENV TERM=xterm
EXPOSE 8080
