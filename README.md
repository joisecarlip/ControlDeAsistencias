# Control de Asistencias 📋

Sistema de control de asistencias desarrollado en Laravel con Docker para una fácil instalación y despliegue.

## 🚀 Características

- Sistema de registro de asistencias
- Gestión de usuarios
- Panel de administración
- Reportes de asistencia
- Interfaz web responsiva

## 📋 Prerrequisitos

Antes de comenzar, asegúrate de tener instalado:

- [Git](https://git-scm.com/)
- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

## 🛠️ Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/joisecarlip/ControlDeAsistencias.git
cd ControlDeAsistencias
```

### 2. Configurar variables de entorno

```bash
cp .env.example .env
```

> **Nota:** Edita el archivo `.env` con la configuración de tu base de datos y otros parámetros necesarios.

### 3. Construir y levantar los contenedores

```bash
# Construir e iniciar los contenedores en segundo plano
docker-compose up -d --build
```

### 4. Configurar la aplicación Laravel

Accede al contenedor de la aplicación:

```bash
docker-compose exec app bash
```

Dentro del contenedor, ejecuta los siguientes comandos:

```bash
# Instalar dependencias de Composer
composer install --optimize-autoloader --no-dev

# Generar clave de aplicación
php artisan key:generate

# Configurar permisos
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Limpiar caché
php artisan config:clear
php artisan cache:clear

# Ejecutar migraciones y seeders
php artisan migrate
php artisan db:seed
```

### 5. Verificar la instalación

```bash
# Verificar que los contenedores estén ejecutándose
docker-compose ps
```

## 🔧 Comandos útiles

### Gestión de contenedores

```bash
# Iniciar los contenedores
docker-compose up -d

# Detener los contenedores
docker-compose down

# Detener y eliminar volúmenes (¡CUIDADO! Esto eliminará los datos)
docker-compose down -v

# Ver logs de los contenedores
docker-compose logs

# Acceder al contenedor de la aplicación
docker-compose exec app bash
```

### Comandos de Laravel

```bash
# Ejecutar migraciones
php artisan migrate

# Ejecutar seeders
php artisan db:seed

# Crear nueva migración
php artisan make:migration nombre_de_la_migracion

# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## 📁 Estructura del proyecto

```
ControlDeAsistencias/
├── app/                    # Código de la aplicación
├── config/                 # Archivos de configuración
├── database/              # Migraciones y seeders
├── docker-compose.yml     # Configuración de Docker
├── public/                # Archivos públicos
├── resources/             # Vistas, CSS, JS
├── routes/                # Rutas de la aplicación
├── storage/               # Archivos de almacenamiento
├── .env.example          # Ejemplo de variables de entorno
└── README.md             # Este archivo
```

## 🌐 Acceso a la aplicación

Una vez instalado, puedes acceder a la aplicación en:

- **Aplicación web:** `http://localhost:8000`
- **Base de datos:** `localhost:3306` (si usas MySQL)

## 🔒 Configuración de seguridad

### Variables de entorno importantes

Asegúrate de configurar estas variables en tu archivo `.env`:

```env
APP_NAME="Control de Asistencias"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=control_asistencias
DB_USERNAME=usuario
DB_PASSWORD=contraseña_segura
```

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

## 📝 Desarrollo

### Agregar nuevas funcionalidades

1. Crea una nueva rama:
   ```bash
   git checkout -b feature/nueva-funcionalidad
   ```

2. Realiza los cambios necesarios

3. Ejecuta las pruebas:
   ```bash
   docker-compose exec app php artisan test
   ```

4. Commit y push:
   ```bash
   git add .
   git commit -m "Descripción de los cambios"
   git push origin feature/nueva-funcionalidad
   ```

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 🤝 Contribución

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature
3. Commit tus cambios
4. Push a la rama
5. Abre un Pull Request

## 📞 Soporte

Si tienes alguna pregunta o problema, por favor abre un issue en el repositorio de GitHub.

---

**Desarrollado con ❤️ usando Laravel y Docker**