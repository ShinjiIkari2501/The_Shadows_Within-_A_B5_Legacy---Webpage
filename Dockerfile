FROM php:8.2-apache

# 1. Kopiert alle hochgeladenen Daten in den temporären Ordner
COPY . /tmp/project/

# 2. INTELLIGENTE PLATZHALTER-BRÜCKE: Schiebt den Inhalt des Unterordners
# direkt in das aktive Hauptverzeichnis, völlig egal wie der Ordner heißt!
RUN if [ -d /tmp/project/The_Shadows_Within* ]; then \
        cp -r /tmp/project/The_Shadows_Within*/* /var/www/html/; \
    elif [ -d /tmp/project/*/index.php ]; then \
        cp -r /tmp/project/*/* /var/www/html/; \
    else \
        cp -r /tmp/project/* /var/www/html/; \
    fi

# 3. Erlaubt dem Server den Zugriff auf die frisch sortierten Daten
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf
RUN echo "<Directory /var/www/html/>" >> /etc/apache2/apache2.conf \
    && echo "    Options Indexes FollowSymLinks" >> /etc/apache2/apache2.conf \
    && echo "    AllowOverride All" >> /etc/apache2/apache2.conf \
    && echo "    Require all granted" >> /etc/apache2/apache2.conf \
    && echo "</Directory>" >> /etc/apache2/apache2.conf

# 4. Aktiviert die Pfad-Toleranz für Groß- und Kleinschreibung
RUN a2enmod speling
RUN echo "CheckSpelling On" >> /etc/apache2/apache2.conf
RUN echo "CheckCaseOnly On" >> /etc/apache2/apache2.conf
RUN echo "DirectoryIndex index.php Index.php" >> /etc/apache2/apache2.conf

RUN a2enmod rewrite
EXPOSE 80
