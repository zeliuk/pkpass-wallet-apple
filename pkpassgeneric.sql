-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 10.200.1.74
-- Tiempo de generación: 28-05-2025 a las 20:17:23
-- Versión del servidor: 10.11.11-MariaDB-deb11-log
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pkpassgeneric`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `distribution`
--

CREATE TABLE `distribution` (
  `id` int(11) NOT NULL,
  `idpersona` int(11) NOT NULL,
  `idcard` text NOT NULL,
  `device` text NOT NULL,
  `authkey` text NOT NULL,
  `devicelibraryidentifier` text NOT NULL,
  `devicetoken` text NOT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `idcard` text NOT NULL,
  `layout` text NOT NULL,
  `has_logo` int(1) NOT NULL,
  `nombre` text DEFAULT NULL,
  `apellidos` text DEFAULT NULL,
  `puesto` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `telefono` text DEFAULT NULL,
  `linkedin` text NOT NULL,
  `notapersonal` text NOT NULL,
  `imagen` text DEFAULT NULL,
  `imagenvcard` text NOT NULL,
  `logo_empresa` text NOT NULL,
  `empresa` text NOT NULL,
  `empresa_slug` text NOT NULL,
  `empresa_url_server` text NOT NULL,
  `empresa_cif` text NOT NULL,
  `direccion_empresa` text NOT NULL,
  `telefono_empresa` text NOT NULL,
  `web_empresa` text NOT NULL,
  `descripcion_empresa` text NOT NULL,
  `instagram_empresa` text NOT NULL,
  `twitter_empresa` text NOT NULL,
  `linkedin_empresa` text NOT NULL,
  `behance_empresa` text NOT NULL,
  `youtube_empresa` text NOT NULL,
  `color_background` text NOT NULL,
  `color_text_1` text NOT NULL,
  `color_text_2` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `idcard`, `layout`, `has_logo`, `nombre`, `apellidos`, `puesto`, `email`, `telefono`, `linkedin`, `notapersonal`, `imagen`, `imagenvcard`, `logo_empresa`, `empresa`, `empresa_slug`, `empresa_url_server`, `empresa_cif`, `direccion_empresa`, `telefono_empresa`, `web_empresa`, `descripcion_empresa`, `instagram_empresa`, `twitter_empresa`, `linkedin_empresa`, `behance_empresa`, `youtube_empresa`, `color_background`, `color_text_1`, `color_text_2`) VALUES
(1, 'berritxarrak-gorka-urbizu', 'storeCard', 1, 'Gorka', 'Urbizu', 'Músico', 'berritxarrak@berritxarrak.net', '+34 XXX XX XX XX', '', '', 'https://zeliuk.xyz/pkpass-wallet-apple/generic/vcard/img/background_gorka.png', 'https://zeliuk.xyz/pkpass-wallet-apple/generic/vcard/img/berritxarrakvcard.jpg', 'https://zeliuk.xyz/pkpass-wallet-apple/generic/vcard/img/berritxarrak_logo.png', 'Berri Txarrak', 'berritxarrak', '', '', 'HERRIKO PLAZA, Nº 5 1c, de LEKUMBERRI-NAVARRA, CP 31870', '', 'https://www.berritxarrak.net', '', 'http://www.instagram.com/gorkaurbizu', '', '', '', '', '#000000', '#FFFFFF', '#b7ad3f');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `distribution`
--
ALTER TABLE `distribution`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dist` (`idpersona`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `distribution`
--
ALTER TABLE `distribution`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=270;

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `distribution`
--
ALTER TABLE `distribution`
  ADD CONSTRAINT `dist` FOREIGN KEY (`idpersona`) REFERENCES `empleados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
