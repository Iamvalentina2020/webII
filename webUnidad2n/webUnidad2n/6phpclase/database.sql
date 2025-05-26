-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS doguitodb;
USE doguitodb;

-- Crear tabla de mascotas
CREATE TABLE IF NOT EXISTS pets (
    id VARCHAR(50) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    edad VARCHAR(20),
    descripcion TEXT
);

-- Crear tabla de productos
CREATE TABLE IF NOT EXISTS productos (
    id VARCHAR(50) PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    descripcion TEXT
);
