CREATE DATABASE SIGES3;
USE SIGES3;

-- Tabla: facultades
CREATE TABLE facultades (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL
);

-- Tabla: cuentas
CREATE TABLE cuentas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    contraseña VARCHAR(100) NOT NULL,
    tipo INT NOT NULL CHECK (tipo IN (1, 2, 3)), -- 1: Administrador, 2: Moderador, 3: Usuario
    facultad_id INT,  -- Solo aplica para moderadores y usuarios
    FOREIGN KEY (facultad_id) REFERENCES facultades(id)
);

-- Tabla: edificios
CREATE TABLE edificios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    facultad_id INT NOT NULL,
    FOREIGN KEY (facultad_id) REFERENCES facultades(id)
);

-- Tabla: salones
CREATE TABLE salones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    edificio_id INT NOT NULL,
    FOREIGN KEY (edificio_id) REFERENCES edificios(id)
);

-- Tabla: dispositivos
CREATE TABLE dispositivos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    marca VARCHAR(50),
    fecha_alta DATE NOT NULL,
    componentes TEXT,
    estado INT NOT NULL CHECK (estado IN (1, 2, 3)), -- 1: Activo, 2: Mantenimiento, 3: Baja
    salon_id INT NOT NULL,
    FOREIGN KEY (salon_id) REFERENCES salones(id)
);

-- Tabla: incidencias
CREATE TABLE incidencias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    descripcion TEXT NOT NULL,
    estado ENUM('sin resolver', 'resuelta') DEFAULT 'sin resolver',
    fecha_reporte DATE NOT NULL,
    fecha_resolucion DATE,
    calificacion TINYINT CHECK (calificacion BETWEEN 1 AND 5), -- Calificación del 1 al 5
    cambios TEXT, -- Descripción de los cambios realizados para resolver la incidencia
    tecnico_asignado INT, -- Técnico que aceptó la incidencia
    dispositivo_id INT NOT NULL,
    usuario_id INT NOT NULL,  -- Usuario que reportó la incidencia
    FOREIGN KEY (tecnico_asignado) REFERENCES cuentas(id),
    FOREIGN KEY (dispositivo_id) REFERENCES dispositivos(id),
    FOREIGN KEY (usuario_id) REFERENCES cuentas(id)
);

-- tabla extra por GPT 

CREATE TABLE errores_conocidos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    incidencia_id INT NOT NULL,
    causa_raiz TEXT NOT NULL,
    error_conocido TEXT NOT NULL,
    fecha_ingreso DATE NOT NULL,
    fecha_resolucion DATE,
    solucion TEXT NOT NULL,
    necesita_rfc BOOLEAN NOT NULL,
    FOREIGN KEY (incidencia_id) REFERENCES incidencias(id)
);

