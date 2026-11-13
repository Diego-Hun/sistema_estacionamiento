CREATE DATABASE IF NOT EXISTS sistema_estacionamiento CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sistema_estacionamiento;

CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE usuarios (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    correo_electronico VARCHAR(150) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    id_rol BIGINT UNSIGNED NOT NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_usuarios_rol FOREIGN KEY (id_rol) REFERENCES roles(id)
);

CREATE TABLE clientes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    telefono VARCHAR(30) NOT NULL,
    correo_electronico VARCHAR(150) NULL,
    es_suscriptor TINYINT(1) NOT NULL DEFAULT 0,
    convenio VARCHAR(150) NULL,
    fecha_vencimiento DATE NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE tipos_vehiculo (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    tarifa_hora DECIMAL(10,2) NOT NULL DEFAULT 5.00,
    tarifa_minuto DECIMAL(10,4) NOT NULL DEFAULT 0.1000,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE vehiculos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    placa VARCHAR(20) NOT NULL UNIQUE,
    id_cliente BIGINT UNSIGNED NULL,
    id_tipo_vehiculo BIGINT UNSIGNED NOT NULL,
    marca VARCHAR(80) NOT NULL,
    modelo VARCHAR(80) NOT NULL,
    color VARCHAR(50) NOT NULL,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_vehiculos_cliente FOREIGN KEY (id_cliente) REFERENCES clientes(id),
    CONSTRAINT fk_vehiculos_tipo FOREIGN KEY (id_tipo_vehiculo) REFERENCES tipos_vehiculo(id)
);

CREATE TABLE espacios_estacionamiento (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    piso VARCHAR(30) NOT NULL,
    sector VARCHAR(30) NOT NULL,
    numero VARCHAR(20) NOT NULL,
    esta_ocupado TINYINT(1) NOT NULL DEFAULT 0,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY uk_piso_sector_numero (piso, sector, numero)
);

CREATE TABLE metodos_pago (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    estado TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);

CREATE TABLE boletos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo_ticket VARCHAR(30) NOT NULL UNIQUE,
    id_vehiculo BIGINT UNSIGNED NOT NULL,
    id_espacio_estacionamiento BIGINT UNSIGNED NOT NULL,
    id_usuario BIGINT UNSIGNED NOT NULL,
    hora_entrada DATETIME NOT NULL,
    hora_salida DATETIME NULL,
    total_tiempo_minutos INT NULL,
    id_metodo_pago BIGINT UNSIGNED NULL,
    monto_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    esta_pagado TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    CONSTRAINT fk_boletos_vehiculo FOREIGN KEY (id_vehiculo) REFERENCES vehiculos(id),
    CONSTRAINT fk_boletos_espacio FOREIGN KEY (id_espacio_estacionamiento) REFERENCES espacios_estacionamiento(id),
    CONSTRAINT fk_boletos_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id),
    CONSTRAINT fk_boletos_metodo_pago FOREIGN KEY (id_metodo_pago) REFERENCES metodos_pago(id)
);

INSERT INTO roles (nombre, estado, created_at, updated_at) VALUES
('Administrador', 1, NOW(), NOW()),
('Operador', 1, NOW(), NOW()),
('Supervisor', 1, NOW(), NOW());

INSERT INTO tipos_vehiculo (nombre, tarifa_hora, tarifa_minuto, estado, created_at, updated_at) VALUES
('Auto', 10.00, 0.20, 1, NOW(), NOW()),
('Moto', 5.00, 0.10, 1, NOW(), NOW()),
('Camioneta', 15.00, 0.30, 1, NOW(), NOW());

INSERT INTO metodos_pago (nombre, estado, created_at, updated_at) VALUES
('Efectivo', 1, NOW(), NOW()),
('QR', 1, NOW(), NOW());
