# Nutzen des offiziellen PHP-Images mit Apache-Webserver
FROM php:8.2-apache

# HIER WIRD DAS TRIEBWERK KALIBRIERT: Installiert OpenJDK 21 direkt 
# in das System des Webservers, damit PHP "java -jar" versteht!
RUN apt-get update && apt-get install -y --no-install-recommends \
    openjdk-21-jre-headless \
    && rm -rf /var/lib/apt/lists/*

# Aktiviert das Apache Rewrite-Modul (wichtig für saubere URLs, falls genutzt)
RUN a2enmod rewrite

# Kopiert den gesamten Inhalt deines GitHub-Ordners in das Web-Verzeichnis des Apache-Servers
COPY . /var/www/html/

# Setzt die korrekten Schreib- und Leserechte für die Web-Dateien
RUN chown -R www-data:www-data /var/www/html/

# Setzt die index.php als Standard-Startseite fest
RUN echo "DirectoryIndex Index.php" >> /etc/apache2/apache2.conf

# Informiert Render, dass die Webseite über Port 80 erreichbar ist
EXPOSE 80
