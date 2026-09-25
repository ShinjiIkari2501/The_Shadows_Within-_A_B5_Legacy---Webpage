FROM php:8.2-apache

# Kopiert alle hochgeladenen Daten in den Container
COPY . /var/www/html/

# SUCH-PHALANX: Findet deine index.php (oder Index.php) und schiebt das Projekt ins Hauptverzeichnis
RUN ACTUAL_DIR=$(dirname $(find /var/www/html/ -iname "index.php" | head -n 1)) && \
    if [ "$ACTUAL_DIR" != "/var/www/html" ]; then \
        cp -r $ACTUAL_DIR/* /var/www/html/; \
    fi

# SUPER-FORWARD: Zwingt Apache dazu, sowohl kleine als auch große Dateinamen als Startseite zu akzeptieren!
RUN echo "DirectoryIndex index.php Index.php index.html Index.html" >> /etc/apache2/apache2.conf

RUN a2enmod rewrite
EXPOSE 80
