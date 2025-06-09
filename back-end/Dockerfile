FROM php:8.3-fpm

# Installe les dépendances Symfony, etc.
RUN apt-get update && apt-get install -y \
    git unzip curl libicu-dev libzip-dev zip libonig-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql intl zip

# Copier les sources
COPY . /var/www/html

# Installer Composer (si ce n’est pas déjà fait)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install

WORKDIR /var/www/html

CMD ["php-fpm"]