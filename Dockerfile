# Utilisation de l'image officielle PHP avec Apache (version plus légère)
FROM php:8.1-apache

# Installation des extensions nécessaires pour MySQL et sécurisation de l'image
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install pdo pdo_mysql mysqli gd && \
    apt-get clean

# Activation des modules Apache
RUN a2enmod rewrite headers

# Copie des fichiers dans le conteneur
COPY ./www /var/www/html

# Attribution des droits pour les fichiers
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Exposition du port 80
EXPOSE 80

# Lancement d'Apache en mode foreground
CMD ["apache2-foreground"]
