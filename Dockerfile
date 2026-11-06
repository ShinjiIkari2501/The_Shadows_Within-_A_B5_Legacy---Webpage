# Nutzen des offiziellen PHP-Images mit Apache-Webserver
FROM php:8.2-apache

# Installiert SQLite-Treiber für PHP
RUN apt-get update && apt-get install -y --no-install-recommends \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

# ERZWINGE PHP-VERARBEITUNG IN APACHE:
# Diese Zeilen stellen sicher, dass Apache .php-Dateien als PHP ausführt, statt sie als Text anzuzeigen.
RUN echo "<FilesMatch \.php$>" > /etc/apache2/mods-available/php.conf \
    && echo "    SetHandler application/x-httpd-php" >> /etc/apache2/mods-available/php.conf \
    && echo "</FilesMatch>" >> /etc/apache2/mods-available/php.conf \
    && a2enmod proxy 2>/dev/null || true

# Aktiviert das Apache Rewrite-Modul
RUN a2enmod rewrite

# Access protection: the web server must never hand out the database, SQL files, source files etc.
COPY apache-protect.conf /etc/apache2/conf-available/b5-protect.conf
RUN a2enconf b5-protect

# Kopiert den gesamten Inhalt in das Web-Verzeichnis
COPY . /var/www/html/

# DATENBANK-RECHTE SETZEN:
RUN chown -R www-data:www-data /var/www/html/ \
    && chmod -R 775 /var/www/html/Database \
    && chmod 664 /var/www/html/Database/b5_project.sqlite

# Setzt die Index.php als Standard-Startseite fest
RUN echo "DirectoryIndex Index.php" >> /etc/apache2/apache2.conf

EXPOSE 80
