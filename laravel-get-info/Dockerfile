FROM php:7.3.11-apache
COPY .env.prod ./www/.env
COPY . /var/www
WORKDIR /var/www
RUN apt-get update -y && apt-get install -y zip unzip
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php composer-setup.php \
  && php -r "unlink('composer-setup.php');" \
  && php composer.phar install --no-dev --no-scripts \
  && rm composer.phar

# RUN composer install


# CMD [ "php", "artisan", "serve" ]

