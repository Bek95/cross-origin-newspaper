FROM php:8.2-fpm

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libonig-dev libpng-dev libpq-dev zip curl \
    && docker-php-ext-install pdo pdo_mysql mbstring zip bcmath gd \
    && rm -rf /var/lib/apt/lists/*

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier uniquement composer.json et composer.lock
COPY composer.json composer.lock ./

# Installer les dépendances PHP sans scripts
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Copier le reste du code (artisan, app, config, etc.)
COPY . .

# Configurer safe directory Git
RUN git config --global --add safe.directory /var/www/html

# Exécuter artisan package:discover maintenant que tout le code est là
RUN php artisan package:discover --ansi

# Fixer les permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
