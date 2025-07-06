#!/bin/bash

# Comandos útiles para Docker + Laravel

# Construir y levantar contenedores
echo "🚀 Construyendo y levantando contenedores..."
docker-compose up -d --build

# Generar clave de aplicación
echo "🔑 Generando clave de aplicación..."
docker-compose exec app php artisan key:generate

# Ejecutar migraciones
echo "📊 Ejecutando migraciones..."
docker-compose exec app php artisan migrate

# Ejecutar seeders (opcional)
echo "🌱 Ejecutando seeders..."
docker-compose exec app php artisan db:seed

# Limpiar cache
echo "🧹 Limpiando cache..."
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Instalar dependencias de Node.js (si usas Vite)
echo "📦 Instalando dependencias de Node.js..."
docker-compose exec app npm install
docker-compose exec app npm run build

# Configurar permisos
echo "🔒 Configurando permisos..."
docker-compose exec app chown -R www-data:www-data /var/www
docker-compose exec app chmod -R 755 /var/www
docker-compose exec app chmod -R 775 /var/www/storage /var/www/bootstrap/cache

echo "✅ ¡Configuración completada!"
echo "🌐 Aplicación disponible en: http://localhost:8000"
echo "🗄️ phpMyAdmin disponible en: http://localhost:8080"