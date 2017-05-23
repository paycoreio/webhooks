FROM php:7.1-fpm

MAINTAINER serge@merge.git

RUN apt-get update  && apt-get install -y --no-install-recommends \
    sudo \
    unzip \
    libpq-dev \
    supervisor

RUN rm -rf /var/lib/apt/lists/*

#RUN sudo apt-get install php7.0-cgi
#RUN sudo ln -s /usr/lib/cgi-bin/php /usr/bin/php7.0-cgi

#Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version

#Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Kiev /etc/localtime
RUN "date"

RUN docker-php-ext-install pcntl

#Install pgsql php extension
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

#Install xdebug extension
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
COPY etc/docker/php/configurations/xdebug.ini /usr/local/etc/php/conf.d/
COPY etc/docker/php/bin/* /usr/local/bin/

COPY etc/docker/php/configurations/supervisor/conf.d/rabbitmq-consumer.conf /etc/supervisor/conf.d/rabbitmq-consumer.conf
COPY etc/docker/php/configurations/supervisor/supervisord.conf /etc/supervisor/supervisord.conf

CMD ["/app/etc/docker/php/bin/enter_dev"]
ENV TERM=xterm
