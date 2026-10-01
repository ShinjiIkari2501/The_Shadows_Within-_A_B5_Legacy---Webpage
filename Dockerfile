# Nutzen eines offiziellen PHP-Images mit Apache-Webserver
FROM php:8.2-apache

# Aktiviert das Apache Rewrite-Modul (wichtig für saubere URLs, falls genutzt)
RUN a2enmod rewrite

# Kopiert den gesamten Inhalt deines GitHub-Ordners in das Web-Verzeichnis des Apache-Servers
COPY . /var/www/html/

# Setzt die korrekten Schreib- und Leserechte für die Web-Dateien
RUN chown -R www-data:www-data /var/www/html/

# Informiert Render, dass die Webseite über Port 80 erreichbar ist
EXPOSE 80
