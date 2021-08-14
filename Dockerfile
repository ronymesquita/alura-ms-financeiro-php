FROM composer

RUN docker-php-ext-install sockets

RUN pecl install swoole
RUN docker-php-ext-enable swoole
