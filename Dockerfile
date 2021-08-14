FROM php

RUN docker-php-ext-install zip
RUN docker-php-ext-install sockets

RUN pecl install swoole
RUN docker-php-ext-enable swoole

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
