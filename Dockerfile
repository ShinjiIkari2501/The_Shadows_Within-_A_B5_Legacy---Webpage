FROM php:8.2-apache
# Schaltet das Hauptverzeichnis auf deinen hochgeladenen Ordner um
ENV APACHE_DOCUMENT_ROOT /var/www/html/the-shadows-within-a-b5-legacy
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

COPY . /var/www/html/
RUN a2enmod rewrite
EXPOSE 80
