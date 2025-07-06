# 🎯 Control de Asistencias Laravel + Docker

<div align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</div>

---

## 📦 Instalación Rápida

<table>
<tr>
<td>

### 🔽 **Paso 1: Clonar**
```bash
git clone https://github.com/joisecarlip/ControlDeAsistencias.git Sistema-De-Asistencias
cd Sistema-De-Asistencias
```

</td>
<td>

### ⚙️ **Paso 2: Configurar**
```bash
cp .env.example .env
```

</td>
</tr>
<tr>
<td>

### 🐳 **Paso 3: Docker**
```bash
docker-compose up -d --build
```

</td>
<td>

### 🚀 **Paso 4: Laravel**
```bash
docker-compose exec app bash
```

</td>
</tr>
</table>

### 🔧 **Configuración Laravel (dentro del contenedor)**

```bash
# 📥 Instalar dependencias
composer install --optimize-autoloader --no-dev

# 🔑 Generar clave
php artisan key:generate

# 📁 Configurar permisos
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 🧹 Limpiar caché
php artisan config:clear && php artisan cache:clear

# 🗃️ Base de datos
php artisan migrate && php artisan db:seed
```

<div align="center">
  <h3>🎉 ¡Listo! Tu aplicación está en <code>http://localhost:8000</code></h3>
</div>

## 🎛️ Comandos Útiles

<details>
<summary><b>🐳 Gestión de Docker</b></summary>

```bash
# ▶️ Iniciar contenedores
docker-compose up -d

# ⏹️ Detener contenedores
docker-compose down

# 🗑️ Eliminar todo (¡CUIDADO!)
docker-compose down -v

# 👀 Ver logs
docker-compose logs

# 🔍 Estado de contenedores
docker-compose ps

# 💻 Acceder al contenedor
docker-compose exec app bash
```

</details>

<details>
<summary><b>🎨 Comandos Laravel</b></summary>

```bash
# 🗃️ Migraciones
php artisan migrate
php artisan migrate:fresh --seed

# 🧹 Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

</details>



## 🌐 Acceso a la aplicación

Una vez instalado, puedes acceder a la aplicación en:

- **Aplicación web:** `http://localhost:8000`



## 🐛 Solución de problemas

### Error de permisos

Si encuentras errores de permisos, ejecuta:

```bash
docker-compose exec app bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### Error de conexión a base de datos

1. Verifica que el contenedor de base de datos esté ejecutándose:
   ```bash
   docker-compose ps
   ```

2. Revisa la configuración en el archivo `.env`

3. Reinicia los contenedores:
   ```bash
   docker-compose down
   docker-compose up -d
   ```

### Limpiar instalación

Para empezar desde cero:

```bash
docker-compose down -v
docker-compose up -d --build
```

## 🚀 Desarrollo

<div align="center">
  <img src="https://img.shields.io/badge/Git-F05032?style=for-the-badge&logo=git&logoColor=white" alt="Git">
  <img src="https://img.shields.io/badge/Composer-885630?style=for-the-badge&logo=composer&logoColor=white" alt="Composer">
  <img src="https://img.shields.io/badge/Artisan-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Artisan">
</div>

**🚀 ¡Proyecto listo para producción con Laravel y Docker!**