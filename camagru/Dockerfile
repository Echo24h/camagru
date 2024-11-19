# Utiliser une image PHP avec FPM (FastCGI Process Manager)
FROM php:8.2-fpm

# Installer les extensions nécessaires pour PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    curl \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de l'application
COPY . .

# Donner les permissions nécessaires
RUN chown -R www-data:www-data /var/www/html

# Exposer le port utilisé par PHP-FPM
EXPOSE 9000