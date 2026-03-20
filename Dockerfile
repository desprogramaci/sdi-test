FROM php:8.2-fpm

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip libpq-dev unzip git curl libonig-dev libxml2-dev

# Extensiones críticas para Laravel y Postgres
RUN docker-php-ext-install pdo_pgsql pgsql mbstring bcmath

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

# Dar permisos a storage (Vital en Docker)
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
