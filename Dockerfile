FROM php:8.2-apache

# 1. Kopiert alle hochgeladenen Daten in den Container
COPY . /var/www/html/

# 2. DIREKT-ZÜNDUNG: Kopiert den Inhalt deines spezifischen Ordners direkt auf die oberste Ebene,
# völlig ohne fehleranfällige Suchbefehle!
RUN if [ -d "/var/www/html/The_Shadows_Within-_A_B5_Legacy" ]; then \
        cp -r /var/www/html/The_Shadows_Within-_A_B5_Legacy/* /var/www/html/; \
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
