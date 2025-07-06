FROM php:8.3-fpm

# Installe les dépendances
RUN apt-get update && apt-get install -y \
    git unzip curl libicu-dev libzip-dev zip libonig-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip

# Copier les sources
COPY . /var/www/html

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
RUN composer install --no-interaction --prefer-dist

CMD ["php-fpm"]