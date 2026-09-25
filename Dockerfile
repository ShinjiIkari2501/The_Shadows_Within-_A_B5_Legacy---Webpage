FROM php:8.2-apache

# 1. Kopiert alle hochgeladenen Daten unberührt in den Server
COPY . /var/www/html/

# 2. DYNAMISCHE DETEKTION: Sucht nach der index.php (oder Index.php) 
# und stellt das Hauptverzeichnis vollautomatisch auf genau diesen Ordner ein!
RUN ACTUAL_DIR=$(dirname $(find /var/www/html/ -iname "index.php" | head -n 1)) && \
    sed -ri -e "s!/var/www/html!${ACTUAL_DIR}!g" /etc/apache2/sites-available/*.conf && \
    sed -ri -e "s!/var/www/html!${ACTUAL_DIR}!g" /etc/apache2/apache2.conf

# 3. Aktiviert die Pfad-Toleranz für Groß- und Kleinschreibung bei CSS & Schriften
RUN a2enmod speling
RUN echo "CheckSpelling On" >> /etc/apache2/apache2.conf
RUN echo "CheckCaseOnly On" >> /etc/apache2/apache2.conf
RUN echo "DirectoryIndex index.php Index.php" >> /etc/apache2/apache2.conf

# 4. Aktiviert das Routing-Modul für deine Pfade
RUN a2enmod rewrite
EXPOSE 80
