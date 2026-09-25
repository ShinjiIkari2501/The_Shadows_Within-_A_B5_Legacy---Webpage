FROM php:8.2-apache

# 1. Kopiert alle hochgeladenen Daten in den Container
COPY . /var/www/html/

# 2. DIREKT-ZÜNDUNG: Wenn der Unterordner existiert, kopiere den Inhalt heraus
# und erstelle einen virtuellen Link (Symlink), damit BEIDE Pfade zeitgleich funktionieren!
RUN if [ -d "/var/www/html/The_Shadows_Within-_A_B5_Legacy" ]; then \
        cp -r /var/www/html/The_Shadows_Within-_A_B5_Legacy/* /var/www/html/ && \
        rm -rf /var/www/html/The_Shadows_Within-_A_B5_Legacy && \
        ln -s /var/www/html /var/www/html/The_Shadows_Within-_A_B5_Legacy; \
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
