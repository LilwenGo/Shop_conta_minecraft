FROM php:8.2-apache
WORKDIR /var/www/html
# Configure Apache to allow URL rewrite and .htaccess override
RUN a2enmod rewrite
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
# Installer unzip et curl
RUN apt-get update && apt-get install -y unzip curl && rm -rf /var/lib/apt/lists/*
# Activer l'extenssion PDO
RUN docker-php-ext-install pdo pdo_mysql
# Installer Composer et les dépendances
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY composer.json composer.lock /var/www/html/
RUN composer install
# Ensuite on copie les fichiers
COPY . .