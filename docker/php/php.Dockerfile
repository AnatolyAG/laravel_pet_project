FROM php:8.1-fpm

WORKDIR /var/www/laravel

RUN docker-php-ext-install pdo pdo_mysql
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
    
RUN pecl install redis \
 	# && pecl install xdebug \
 	&& docker-php-ext-enable redis

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" && mv composer.phar /usr/local/bin/composer
# install dop extends
RUN apt-get update && apt-get install -y git curl zip 
RUN apt-get install -y nodejs npm