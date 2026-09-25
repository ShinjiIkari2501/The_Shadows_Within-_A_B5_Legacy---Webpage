FROM php:8.2-apache

# 1. Kopiert alle hochgeladenen Daten unberührt in den Server
COPY . /var/www/html/

# 2. DYNAMISCHE DETEKTION: Sucht nach der index.php und stellt das Hauptverzeichnis ein
RUN ACTUAL_DIR=$(dirname $(find /var/www/html/ -iname "index.php" | head -n 1)) && \
    sed -ri -e "s!/var/www/html!${ACTUAL_DIR}!g" /etc/apache2/sites-available/*.conf && \
    sed -ri -e "s!/var/www/html!${ACTUAL_DIR}!g" /etc/apache2/apache2.conf

# 3. RADIKALE RECHTE-FREIGABE & LESERECHTE-PHALANX:
RUN find /var/www/html/ -type d -exec chmod 755 {} \; && \
    find /var/www/html/ -type f -exec chmod 644 {} \;

RUN echo "<Directory />" > /etc/apache2/conf-available/override.conf \
    && echo "    Options Indexes FollowSymLinks" >> /etc/apache2/conf-available/override.conf \
    && echo "    AllowOverride All" >> /etc/apache2/conf-available/override.conf \
    && echo "    Require all granted" >> /etc/apache2/conf-available/override.conf \
    && echo "</Directory>" >> /etc/apache2/conf-available/override.conf \
    && a2enconf override

# 4. FONTS-RELAIS: Schaltet die MIME-Types für alle gängigen Schriftarten frei!
RUN echo "AddType application/x-font-ttf .ttf" >> /etc/apache2/apache2.conf \
    && echo "AddType application/x-font-opentype .otf" >> /etc/apache2/apache2.conf \
    && echo "AddType application/font-woff .woff" >> /etc/apache2/apache2.conf \
    && echo "AddType application/font-woff2 .woff2" >> /etc/apache2/apache2.conf

# 5. Aktiviert die Pfad-Toleranz für Groß- und Kleinschreibung
RUN a2enmod speling
RUN echo "CheckSpelling On" >> /etc/apache2/apache2.conf
RUN echo "CheckCaseOnly On" >> /etc/apache2/apache2.conf
RUN echo "DirectoryIndex index.php Index.php" >> /etc/apache2/apache2.conf

RUN a2enmod rewrite
EXPOSE 80
