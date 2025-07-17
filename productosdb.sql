-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 27-06-2025 a las 20:23:14
-- Versión del servidor: 9.1.0
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `productosdb`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) NOT NULL,
  `producto` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `cantidad` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo`, `producto`, `precio`, `cantidad`) VALUES
(2, '1234', 'prueba', 12.00, 43),
(3, '1234', 'prueba', 12.00, 43),
(4, '1234', 'prueba', 12.00, 43),
(5, '1234', 'prueba', 12.00, 43),
(6, '1234', 'prueba', 12.00, 43),
(7, '1234', 'prueba', 12.00, 43),
(8, '1234', 'prueba', 12.00, 43),
(9, '1234', 'prueba', 12.00, 43),
(10, '1234', 'prueba', 12.00, 43),
(11, '1234', 'prueba', 12.00, 43),
(12, '1234', 'prueba', 12.00, 43),
(13, '1234', 'prueba', 12.00, 43),
(14, '1234', 'prueba', 12.00, 43),
(15, '1234', 'prueba', 12.00, 43),
(16, '1234', 'prueba', 12.00, 43),
(17, '1234', 'prueba', 12.00, 43),
(18, '1234', 'prueba', 12.00, 43),
(19, '1234', 'prueba', 12.00, 43),
(20, '1234', 'prueba', 12.00, 43),
(21, '1234', 'prueba', 12.00, 43),
(22, '1234', 'prueba', 12.00, 43),
(23, '1234', 'prueba', 12.00, 43),
(24, '1234', 'prueba', 12.00, 43),
(25, '1234', 'prueba', 12.00, 43),
(26, '1234', 'prueba', 12.00, 43),
(27, '1234', 'prueba', 12.00, 43),
(28, '1234', 'prueba', 12.00, 43),
(29, '1234', 'prueba', 12.00, 43),
(30, '1234', 'prueba', 12.00, 43),
(48, 'eqwewq', 'Monitor', 345.89, 1),
(31, '1234', 'prueba', 12.00, 43),
(32, '1234', 'prueba', 12.00, 43),
(33, '1234', 'prueba', 12.00, 43),
(34, '1234', 'prueba', 12.00, 43),
(35, '1234', 'prueba', 12.00, 43),
(36, 'Li100', 'Libro', 5.00, 100),
(37, 're', 'prueba', 12.00, 43),
(38, '1234', 'prueba', 12.00, 43),
(39, '1234', 'prueba', 12.00, 43),
(40, '1234', 'prueba', 12.00, 43),
(41, '1234', 'prueba', 12.00, 43),
(42, '1234', 'prueba', 12.00, 43),
(43, '1234', 'prueba', 12.00, 43),
(44, '1234', 'prueba', 12.00, 43),
(45, '1234', 'prueba', 12.00, 43),
(46, '1234', 'prueba', 12.00, 43),
(47, '1', 'dsa3', 23.00, 43);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
