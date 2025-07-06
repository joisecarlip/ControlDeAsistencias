-- Crear usuario si no existe
CREATE USER IF NOT EXISTS 'laravel'@'%' IDENTIFIED BY 'root';

-- Otorgar permisos
GRANT ALL PRIVILEGES ON laravel.* TO 'laravel'@'%';

-- Refrescar privilegios
FLUSH PRIVILEGES;

-- Usar la base de datos
USE laravel;