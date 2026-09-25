FROM php:8.2-apache

# 1. Kopiert alle hochgeladenen Daten in den Container
COPY . /var/www/html/

# 2. VIRTUELLES SPIEGEL-RELAIS (Symlink-Phalanx):
# Zieht die Dateien auf die Hauptebene heraus, lässt aber gleichzeitig eine 
# virtuelle Verknüpfung im Unterordner bestehen. Das repariert alle CSS- und Schriftpfade sofort!
RUN ACTUAL_DIR=$(dirname $(find /var/www/html/ -name "index.php" | head -n 1)) && \
    if [ "$ACTUAL_DIR" != "/var/www/html" ]; then \
        cp -r $ACTUAL_DIR/* /var/www/html/ && \
        FOLDER_NAME=$(basename $ACTUAL_DIR) && \
        rm -rf /var/www/html/$FOLDER_NAME && \
        ln -s /var/www/html /var/www/html/$FOLDER_NAME; \
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
