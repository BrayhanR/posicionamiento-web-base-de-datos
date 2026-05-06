-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 13-04-2026 a las 00:48:01
-- Versión del servidor: 8.0.17
-- Versión de PHP: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mi_proyecto`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(10) NOT NULL,
  `cedula` int(10) NOT NULL,
  `nombre` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `apellido` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `correo` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `direccion` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `telefono` bigint(12) NOT NULL,
  `rol` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `contrasena` varchar(100) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

--- Rol de "pasajero" o "admin" se pueden han definido en la misma tabla
-- Algunas claves estan encriptadas, soloes migracion de métodos de encriptación más seguros (password_hash de PHP)
INSERT INTO `usuario` (`id_usuario`, `cedula`, `nombre`, `apellido`, `correo`, `direccion`, `telefono`, `rol`, `contrasena`) VALUES
(5, 214748, 'nombre2', 'apellido2', 'mail5@mail.com', 'calle 12 333', 111111111111, 'pasajero', 'clave2'),
(6, 12345, 'nombreadmin1', 'apellidoadmin1', 'mailadmin@mail.com', '', 0, 'admin', 'admin123'),
(10, 22222222, 'nombre3', 'apellido3', 'mail10@mail.com', '', 0, 'pasajero', 'clave7'),
(11, 0, 'nombre4', '', 'mail7@mail.com', 'Car 45 a # 45 - 97', 66666666666, 'pasajero', '$2y$10$VLcAMAGdOP/js31fWqLvduMecKv9E4L7wcQaLSkdNk3mXUjsq9y2K'),
(12, 123456789, 'pasasjero1', 'pasajeroape1', 'pasajero@mail.com', 'pasajerodir1', 987654, 'pasajero', 'pasajero1'),
(22, 343434343, 'Albert', '', 'mail4545@mail.com', 'direc56', 6767676, 'pasajero', '$2y$10$yzHIEomBIkFX3wX4qd/QVO1lfiYsKlF3fJ12XtbPVIx5ggEvh697O'),
(34, 888769056, 'Brayhan', 'Rodriguez', 'brayhan89@mail.com', 'direc7', 888888888888, 'pasajero', 'pass2'),
(35, 348343434, 'Nombre8', 'Rapellido8', 'mail9@mail.com', 'direccion8', 9999999999, 'pasajero', 'clave6');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD UNIQUE KEY `cedula` (`cedula`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
