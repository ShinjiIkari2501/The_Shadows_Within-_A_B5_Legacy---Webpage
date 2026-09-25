FROM php:8.2-apache

# 1. Kopiert alle hochgeladenen Daten unberührt in den Server
COPY . /var/www/html/

# 2. SEKTOR-WEICHE: Verlegt das Hauptverzeichnis des Servers direkt in deinen Projektordner!
ENV APACHE_DOCUMENT_ROOT /var/www/html/The_Shadows_Within-_A_B5_Legacy
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 3. Aktiviert die Pfad-Toleranz für Groß- und Kleinschreibung
RUN a2enmod speling
RUN echo "CheckSpelling On" >> /etc/apache2/apache2.conf
RUN echo "CheckCaseOnly On" >> /etc/apache2/apache2.conf
RUN echo "DirectoryIndex index.php Index.php" >> /etc/apache2/apache2.conf

# 4. Aktiviert das Routing-Modul für deine Pfade
RUN a2enmod rewrite
EXPOSE 80
