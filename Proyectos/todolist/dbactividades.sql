-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
 /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
 /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 /*!40101 SET NAMES utf8mb4 */;

-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS `dbactividades`;
USE `dbactividades`;

-- --------------------------------------------------------
-- Tabla: actividades
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `actividades` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `actividad` varchar(500) NOT NULL,
    `descripcion` text NOT NULL,
    `observacion` text DEFAULT NULL,
    `tipo_actividad` varchar(100) DEFAULT NULL,
    `estado` int(11) NOT NULL,
    `fecha_de_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
    `fecha_de_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Tabla: usuarios
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuarios` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `nombre` varchar(100) NOT NULL,
    `apellido` varchar(100) NOT NULL,
    `nombre_usuario` varchar(50) NOT NULL UNIQUE,
    `email` varchar(150) NOT NULL UNIQUE,
    `password` varchar(255) NOT NULL,
    `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
    `activo` tinyint(1) NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_nombre_usuario` (`nombre_usuario`),
    UNIQUE KEY `unique_email` (`email`),
    INDEX `idx_email` (`email`),
    INDEX `idx_nombre_usuario` (`nombre_usuario`),
    INDEX `idx_activo` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Datos iniciales de la tabla actividades
-- --------------------------------------------------------

INSERT INTO `actividades` (
    `actividad`, `descripcion`, `observacion`, `tipo_actividad`, `estado`
)
VALUES
('Revisión bibliográfica 2025', 'Investigación de fuentes teóricas y antecedentes relacionados.', 'Buscar en Scopus y Web of Science.', NULL, 1),
('Deberes de calculo 4', 'Deberes de calculo Completos wq', NULL, NULL, 0),
('Deberes de calculo 6', 'Deberes de calculo Completos Practica nueva', 'Pendiente revisar el último ejercicio.', NULL, 2),
('Valdes Jokabeth', 'Estudiantes del curso de 3a Materia Programación Web Y Diseño', NULL, NULL, 0),
('Valdes Jokabeth2', 'Estudiantes del curso de 3a Materia Programación Web Y Diseño', NULL, NULL, 1),
('Deberes de calculo 6', 'Deberes de calculo Completos wq', NULL, NULL, 0);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
 /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
 /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
