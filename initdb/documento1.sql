-- Crear tabla usuarios
CREATE TABLE usuarios (
    id_usuario BIGINT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255),
    apellido VARCHAR(255),
    correo VARCHAR(255) UNIQUE,
    correo_verificado_en TIMESTAMP NULL,
    contrasena VARCHAR(255),
    rol ENUM('administrador', 'docente', 'estudiante') DEFAULT 'estudiante',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

-- Insertar usuario administrador
INSERT INTO usuarios (nombre, apellido, correo, contrasena, rol, created_at, updated_at)
VALUES 
('Jose Carlos', 'Iquise Pari', 'admin@example.com', 
    MD5('admin123'), 'administrador', NOW(), NOW());

-- Insertar usuario docente
INSERT INTO usuarios (nombre, apellido, correo, contrasena, rol, created_at, updated_at)
VALUES 
('Ana Rebeca', 'Ccopa Mamani', 'docente@example.com', 
    MD5('docente123'), 'docente', NOW(), NOW());

-- Insertar usuario estudiante
INSERT INTO usuarios (nombre, apellido, correo, contrasena, rol, created_at, updated_at)
VALUES 
('Ronald Alex', 'Diaz Pari', 'estudiante@example.com', 
    MD5('estudiante123'), 'estudiante', NOW(), NOW());

-- Crear tabla sessions
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT,
    last_activity INT
);

-- Crear tabla cache
CREATE TABLE cache (
    `key` VARCHAR(255) PRIMARY KEY,
    value MEDIUMTEXT,
    expiration INT
);

-- Crear tabla cache_locks
CREATE TABLE cache_locks (
    `key` VARCHAR(255) PRIMARY KEY,
    owner VARCHAR(255),
    expiration INT
);

-- Crear tabla jobs
CREATE TABLE jobs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload LONGTEXT,
    attempts TINYINT UNSIGNED,
    reserved_at INT UNSIGNED NULL,
    available_at INT UNSIGNED NOT NULL,
    created_at INT UNSIGNED NOT NULL
);

-- Crear tabla job_batches
CREATE TABLE job_batches (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INT,
    pending_jobs INT,
    failed_jobs INT,
    failed_job_ids LONGTEXT,
    options MEDIUMTEXT NULL,
    cancelled_at INT NULL,
    created_at INT,
    finished_at INT NULL
);

-- Crear tabla failed_jobs
CREATE TABLE failed_jobs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(255) UNIQUE,
    connection TEXT,
    queue TEXT,
    payload LONGTEXT,
    exception LONGTEXT,
    failed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Crear tabla cursos
CREATE TABLE cursos (
    id_curso BIGINT AUTO_INCREMENT PRIMARY KEY,
    codigo_curso VARCHAR(255) UNIQUE,
    nombre_curso VARCHAR(255),
    creditos INT DEFAULT 3,
    descripcion TEXT NULL,
    id_docente BIGINT,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (id_docente) REFERENCES usuarios(id_usuario) ON DELETE CASCADE
);

-- Crear tabla docente_curso
CREATE TABLE docente_curso (
    id_docente BIGINT,
    id_curso BIGINT,
    PRIMARY KEY (id_docente, id_curso),
    FOREIGN KEY (id_docente) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso) ON DELETE CASCADE
);

-- Crear tabla estudiante_curso
CREATE TABLE estudiante_curso (
    id_estudiante BIGINT,
    id_curso BIGINT,
    PRIMARY KEY (id_estudiante, id_curso),
    FOREIGN KEY (id_estudiante) REFERENCES usuarios(id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso) ON DELETE CASCADE
);

-- Crear tabla horarios
CREATE TABLE horarios (
    id_horario BIGINT AUTO_INCREMENT PRIMARY KEY,
    id_curso BIGINT,
    dia_semana VARCHAR(20),
    hora_inicio TIME,
    hora_fin TIME,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (id_curso) REFERENCES cursos(id_curso) ON DELETE CASCADE
);
