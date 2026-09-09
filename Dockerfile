FROM php:8.2-apache

# Extensions PHP nécessaires pour MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Activer mod_rewrite pour le routage
RUN a2enmod rewrite

# Copier le projet
COPY . /var/www/html/

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Faire de /public le dossier accessible par le serveur
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

EXPOSE 80
