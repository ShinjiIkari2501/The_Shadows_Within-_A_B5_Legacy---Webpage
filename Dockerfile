FROM php:8.2-apache

# Kopiert absolut alles eins zu eins in den Web-Server-Ordner
COPY . /var/www/html/

# RADIKAL-ZÜNDUNG: Erlaubt dem Apache-Server, in ALLE Unterordner zu schauen!
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf
RUN echo "<Directory /var/www/html/>" >> /etc/apache2/apache2.conf \
    && echo "    Options Indexes FollowSymLinks" >> /etc/apache2/apache2.conf \
    && echo "    AllowOverride All" >> /etc/apache2/apache2.conf \
    && echo "    Require all granted" >> /etc/apache2/apache2.conf \
    && echo "</Directory>" >> /etc/apache2/apache2.conf

# Schaltet die Dateinamens-Toleranz ein
RUN echo "DirectoryIndex index.php Index.php index.html Index.html" >> /etc/apache2/apache2.conf

RUN a2enmod rewrite
EXPOSE 80
