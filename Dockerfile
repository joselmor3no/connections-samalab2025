# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Instalar extensiones de PHP necesarias y libpq-dev para PostgreSQL
RUN apt-get update && \
    apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libzip-dev \
        zlib1g-dev \
        libicu-dev \
        libxml2-dev \
        libpq-dev \
        vim \
        curl \ 
        && \
    docker-php-ext-configure intl && \
    docker-php-ext-install -j$(nproc) \
        gd \
        intl \
        mysqli \
        pdo_pgsql \
        zip \
        && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configurar Apache
RUN a2enmod rewrite

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Exponer el puerto 80
EXPOSE 80

# Instalar mPDF usando Composer
RUN composer require mpdf/mpdf

# Cambiar permisos de la carpeta de archivos temporales de mPDF
RUN chmod -R 777 /var/www/html/vendor/mpdf/mpdf/tmp

# Descargar e instalar phpPgAdmin usando curl
RUN mkdir /usr/share/phppgadmin && \
    curl -o /tmp/phppgadmin.tar.gz -L https://github.com/phppgadmin/phppgadmin/releases/download/REL_7-13-0/phpPgAdmin-7.13.0.tar.gz && \
    tar -xvzf /tmp/phppgadmin.tar.gz -C /usr/share/phppgadmin --strip-components=1 && \
    rm /tmp/phppgadmin.tar.gz && \
    chown -R www-data:www-data /usr/share/phppgadmin

# Configurar phpPgAdmin
COPY config.inc.php /usr/share/phppgadmin/conf/config.inc.php

# CMD para iniciar el servidor Apache
CMD ["apache2-foreground"]
