FROM php:8.2-fpm

# Instalar dependencias
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip netcat-openbsd \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Copiar Composer desde imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Copiar script de inicialización
COPY ./init.sh /var/www/html/init.sh
RUN chmod +x /var/www/html/init.sh

# Comando por defecto al iniciar el contenedor
CMD ["sh", "/var/www/html/init.sh"]
