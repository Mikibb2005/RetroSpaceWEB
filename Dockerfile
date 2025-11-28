# Dockerfile para RetroSpaceWEB (PHP + Apache, DocumentRoot -> public)
FROM php:8.2-apache

# Instalar utilidades y extensiones necesarias (PDO MySQL, zip, mbstring, intl opcional)
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libzip-dev \
        libonig-dev \
        libicu-dev \
        libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql zip mbstring intl \
    && rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copiar el código
COPY . /var/www/html

# Ajustar DocumentRoot para que apunte a /var/www/html/public
# y permitir .htaccess (AllowOverride All)
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf \
 && sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf || true

# Permisos básicos (ajusta si necesitas carpetas escriturables concretas)
RUN chown -R www-data:www-data /var/www/html \
 && find /var/www/html -type d -exec chmod 755 {} \; \
 && find /var/www/html -type f -exec chmod 644 {} \;

EXPOSE 80
CMD ["apache2-foreground"]
