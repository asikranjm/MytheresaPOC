FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libzip-dev zip postgresql-client \
  && docker-php-ext-install pdo_pgsql zip \
  && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/app
COPY custom-php.ini /usr/local/etc/php/conf.d/

RUN chown -R www-data:www-data /var/www/app \
  && chmod -R 755 /var/www/app

COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 9000
CMD ["php-fpm"]
