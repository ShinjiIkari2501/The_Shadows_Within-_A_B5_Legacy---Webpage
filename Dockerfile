FROM php:8.2-apache

# 1. Kopiert alle hochgeladenen Daten unberührt in den Server
COPY . /var/www/html/

# 2. DYNAMISCHE DETEKTION: Sucht nach der index.php und stellt das Hauptverzeichnis ein
RUN ACTUAL_DIR=$(dirname $(find /var/www/html/ -iname "index.php" | head -n 1)) && \
    sed -ri -e "s!/var/www/html!${ACTUAL_DIR}!g" /etc/apache2/sites-available/*.conf && \
    sed -ri -e "s!/var/www/html!${ACTUAL_DIR}!g" /etc/apache2/apache2.conf

# 3. RADIKALE RECHTE-FREIGABE: Zwingt Apache dazu, in ALLE Ordner (CSS, Schriften, Bilder) zu schauen
RUN echo "<Directory />" > /etc/apache2/conf-available/override.conf \
    && echo "    Options Indexes FollowSymLinks" >> /etc/apache2/conf-available/override.conf \
    && echo "    AllowOverride All" >> /etc/apache2/conf-available/override.conf \
    && echo "    Require all granted" >> /etc/apache2/conf-available/override.conf \
    && echo "</Directory>" >> /etc/apache2/conf-available/override.conf \
    && a2enconf override

# 4. SUPER-UPGRADE: Schaltet die automatische Pfad-Korrektur für Groß-/Kleinschreibung ein!
RUN a2enmod speling
RUN echo "CheckSpelling On" >> /etc/apache2/apache2.conf
RUN echo "CheckCaseOnly On" >> /etc/apache2/apache2.conf
RUN echo "DirectoryIndex index.php Index.php" >> /etc/apache2/apache2.conf

RUN a2enmod rewrite
EXPOSE 80
