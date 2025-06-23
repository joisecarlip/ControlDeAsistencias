#!/bin/bash

echo "Esperando a que MySQL esté disponible..."
until nc -z mysql 3306; do
    sleep 2
done

echo "Ejecutando migraciones..."
php artisan migrate --force

echo "Ejecutando seeders..."
php artisan db:seed --force

# Lanzar PHP-FPM (porque nginx lo usará)
echo "Iniciando PHP-FPM..."
exec php-fpm
