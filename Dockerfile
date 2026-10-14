# Nutzen eines offiziellen PHP-Images mit Apache-Webserver
FROM php:8.2-apache

# KORREKTUR: Aktiviert das Rewrite-Modul UND alle notwendigen Proxy-Tunnel im Verbund!
RUN a2enmod rewrite \
    && a2enmod proxy \
    && a2enmod proxy_http \
    && a2enmod proxy_wstunnel

# Kopiert den gesamten Inhalt deines GitHub-Ordners in das Web-Verzeichnis des Apache-Servers
COPY . /var/www/html/

# Setzt die korrekten Schreib- und Leserechte für die Web-Dateien
RUN chown -R www-data:www-data /var/www/html/

# --- HIER DIE NEUE ZEILE FÜR DIE STARTSEITE ---
RUN echo "DirectoryIndex Index.php" >> /etc/apache2/apache2.conf

# Informiert Render, dass die Webseite über Port 80 erreichbar ist
EXPOSE 80
