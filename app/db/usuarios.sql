-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 23-03-2021 a las 21:21:28
-- Versión del servidor: 8.0.13-4
-- Versión de PHP: 7.2.24-0ubuntu0.18.04.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET nombreS utf8mb4 */;

--
-- Base de datos: `pqElWX5WY2`
--

CREATE DATABASE IF NOT EXISTS `comanda` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish2_ci;
USE `comanda`;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `password` text COLLATE utf8_spanish2_ci NOT NULL,
  `isAdmin` tinyint(1) NOT NULL,
  `user_type` varchar(20) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `estado` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `date_init` datetime NOT NULL,
  `date_end` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `usuarios` (`id`, `username`, `password`, `isAdmin`, `user_type`, `estado`, `date_init`, `date_end`) VALUES
(10, 'Guido', 'guido123', 1, 'Admin', 'Active', '2022-06-01 00:32:31', NULL),
(15, 'User1', 'user1', 0, 'Bartender', 'Active', '2022-06-01 01:31:07', NULL),
(16, 'User2', 'user2', 0, 'Bartender', 'Active', '2022-06-01 01:31:15', NULL),
(17, 'User3', 'user3', 0, 'Bartender', 'Active', '2022-06-01 01:31:19', NULL),
(18, 'User4', 'user4', 0, 'Cocinero', 'Active', '2022-06-01 01:31:32', NULL),
(19, 'User5', 'user5', 0, 'Cocinero', 'Active', '2022-06-01 01:31:41', NULL),
(20, 'User6', 'user6', 0, 'Cocinero', 'Active', '2022-06-01 01:31:47', NULL),
(21, 'User7', 'user7', 0, 'Mozo', 'Active', '2022-06-01 01:32:01', NULL),
(22, 'User8', 'user8', 0, 'Mozo', 'Active', '2022-06-01 01:32:05', NULL),
(30, 'User9', 'user9', 0, 'Mozo', 'Active', '2022-06-01 19:36:36', NULL);


DROP TABLE IF EXISTS `producto`;
CREATE TABLE IF NOT EXISTS `producto` (
  `producto_id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_area` int(11) NOT NULL,
  `producto_pedido_asoc` int(11) DEFAULT NULL,
  `producto_estado` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `producto_desc` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `producto_cost` float NOT NULL,
  `time_init` datetime NOT NULL,
  `time_finish` datetime DEFAULT NULL,
  `time_to_finish` int(11) DEFAULT NULL,
  PRIMARY KEY (`producto_id`),
  KEY `FK_producto_pedido_asoc` (`producto_pedido_asoc`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`producto_id`, `producto_area`, `producto_pedido_asoc`, `producto_estado`, `producto_desc`, `producto_cost`, `time_init`, `time_finish`, `time_to_finish`) VALUES
(9, 2, 8, 'Listo Para Servir', 'encuestaso Al Champignon', 550, '2022-06-01 02:50:33', '2022-06-01 03:20:33', 30),
(10, 3, 8, 'Listo Para Servir', 'Gaseosa Linea Pepsi 2lt.', 300, '2022-06-01 02:51:24', '2022-06-01 02:56:24', 35),
(11, 3, 8, 'Listo Para Servir', 'Gaseosa Linea Pepsi 2lt.', 300, '2022-06-01 03:05:14', '2022-06-01 03:10:14', 5),
(12, 3, 8, 'Listo Para Servir', 'Gaseosa Linea Pepsi 2lt.', 300, '2022-06-01 03:05:51', '2022-06-01 03:10:51', 5),
(13, 2, 8, 'Listo Para Servir', 'Hamburguesa con Bacon', 550, '2022-06-01 03:06:59', '2022-06-01 03:26:59', 20),
(14, 2, 8, 'Listo Para Servir', 'Hamburguesa con Cheddar y Guarnicion', 550, '2022-06-01 03:09:14', '2022-06-01 03:27:14', 18),
(15, 2, 8, 'Listo Para Servir', 'Ensalada Waldorf', 550, '2022-06-01 03:10:27', '2022-06-01 03:17:27', 7),
(16, 2, 9, 'Listo Para Servir', 'Ensalada Waldorf', 350, '2022-06-01 11:54:41', '2022-06-01 12:01:41', 7),
(17, 2, 9, 'Listo Para Servir', 'Ensalada Rusa', 250, '2022-06-01 11:55:24', '2022-06-01 12:03:24', 8),
(18, 2, 10, 'Listo Para Servir', 'encuestaso al Champignon', 450, '2022-06-02 00:16:04', '2022-06-02 00:36:04', 0),
(19, 2, 10, 'Listo Para Servir', 'encuestaso al Verdeo', 400, '2022-06-02 00:16:29', '2022-06-02 00:38:29', 0),
(20, 3, 10, 'Listo Para Servir', 'Cerveza Stella Artois 1lt.', 300, '2022-06-02 00:17:06', '2022-06-02 00:22:06', 0),
(21, 3, 11, 'Listo Para Servir', 'Cerveza Stella Artois 1lt.', 300, '2022-06-02 20:01:14', '2022-06-02 20:06:14', 0),
(22, 3, 11, 'Listo Para Servir', 'Cerveza Rabieta Irish Ale 750ml.', 300, '2022-06-02 20:01:46', '2022-06-02 20:08:46', 0),
(23, 2, 11, 'Listo Para Servir', 'Papas bravas', 450, '2022-06-02 20:02:07', '2022-06-02 20:27:07', 0),
(24, 2, 11, 'Listo Para Servir', 'Papas con Cheddar & Bacon', 500, '2022-06-02 20:02:29', '2022-06-02 20:32:29', 0);

--
-- Estructura de tabla para la tabla `area`
--

DROP TABLE IF EXISTS `area`;
CREATE TABLE IF NOT EXISTS `area` (
  `area_id` int(11) NOT NULL AUTO_INCREMENT,
  `area_desc` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`area_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `area`
--

INSERT INTO `area` (`area_id`, `area_desc`) VALUES
(1, 'Salon'),
(2, 'Cocina'),
(3, 'Barra'),
(4, 'Administracion');


--
-- Estructura de tabla para la tabla `mesas`
--

DROP TABLE IF EXISTS `mesas`;
CREATE TABLE IF NOT EXISTS `mesas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mesa_code` varchar(5) COLLATE utf8_spanish2_ci NOT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `estado` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mesa_code` (`mesa_code`),
  KEY `FK_id_mesa_empleado` (`id_empleado`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `mesa_code`, `id_empleado`, `estado`) VALUES
(2, 'M02', 20, 'Cliente Esperando Pedido'),
(3, 'M03', 12, 'Cliente Pagando'),
(4, 'M04', 20, 'Cerrada'),
(5, 'M05', NULL, 'Cerrada'),
(6, 'M06', NULL, 'Cerrada'),
(8, 'M08', NULL, 'Cerrada'),
(9, 'M09', NULL, 'Cerrada'),
(10, 'M10', NULL, 'Cerrada'),
(11, 'M11', NULL, 'Cerrada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE IF NOT EXISTS `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_mesa` int(11) DEFAULT NULL,
  `estado_pedido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'Pendiente',
  `nombre_cliente` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `img_pedido` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `cost_pedido` float NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_Mesa_pedidos` (`id_mesa`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_mesa`, `estado_pedido`, `nombre_cliente`, `img_pedido`, `cost_pedido`) VALUES
(8, 2, 'En Preparacion', 'Fulano_01', './PedidoImages/8.png', 3100),
(9, 3, 'En Preparacion', 'Fulano_02', './PedidoImages/9.png', 600),
(10, 3, 'Listo Para Servir', 'Fulano_03', './PedidoImages/10.png', 1150),
(11, 3, 'Listo Para Servir', 'Fulano_04', './PedidoImages/11.png', 1550),
(12, 2, 'Pendiente', 'Fulano_05', './PedidoImages/Pedido_12.png', 0);



DROP TABLE IF EXISTS `empleados`;
CREATE TABLE IF NOT EXISTS `empleados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `empleado_area_id` int(11) DEFAULT NULL,
  `nombre` varchar(40) COLLATE utf8_spanish2_ci NOT NULL,
  `date_init` datetime NOT NULL,
  `date_end` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_empleado_area` (`empleado_area_id`),
  KEY `FK_empleado_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci comentario='empleados table';

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `user_id`, `empleado_area_id`, `nombre`, `date_init`, `date_end`) VALUES
(11, 15, 1, 'Athena', '2022-06-01 01:54:58', NULL),
(12, 16, 1, 'Persefone', '2022-06-01 01:55:33', NULL),
(13, 17, 1, 'Hera', '2022-06-01 01:55:44', NULL),
(14, 18, 2, 'Hades', '2022-06-01 01:56:01', NULL),
(15, 19, 2, 'Zeus', '2022-06-01 01:56:28', NULL),
(16, 20, 2, 'Odin', '2022-06-01 01:56:36', NULL),
(17, 21, 3, 'Poseidon', '2022-06-01 01:58:02', NULL),
(18, 22, 3, 'Wukong', '2022-06-01 01:58:24', NULL),
(19, 10, 4, 'Guido', '2022-06-01 01:59:42', NULL),
(20, 30, 3, 'Lilith', '2022-06-02 19:38:09', NULL);


DROP TABLE IF EXISTS `encuestas`;
CREATE TABLE IF NOT EXISTS `encuestas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `mesa_punt` int(11) NOT NULL,
  `resto_punt` int(11) NOT NULL,
  `mozo_punt` int(11) NOT NULL,
  `cocinero_punt` int(11) NOT NULL,
  `promedio_punt` float NOT NULL,
  `comentario` varchar(66) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fo_encuestas_pedido` (`pedido_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `encuestas`
--

INSERT INTO `encuestas` (`id`, `pedido_id`, `mesa_punt`, `resto_punt`, `mozo_punt`, `cocinero_punt`, `promedio_punt`, `comentario`) VALUES
(1, 10, 9, 9, 9, 9, 9, 'Muy rico todo'),
(2, 11, 8, 8, 8, 8, 8, 'Muy bueno'),
(3, 9, 7, 7, 7, 8, 7, 'Tardo un poco, pero muy rica la comida');


-- --------------------------------------------------------

DROP TABLE IF EXISTS `historial_empleados`;
CREATE TABLE IF NOT EXISTS `historial_empleados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `username` varchar(40) COLLATE utf8_spanish2_ci NOT NULL,
  `date_login` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_login_user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `historial_empleados`
--

INSERT INTO `historial_empleados` (`id`, `user_id`, `username`, `date_login`) VALUES
(119, 10, 'Guido', '2021-06-24 00:54:03'),
(120, 10, 'Guido', '2022-06-24 00:58:51'),
(121, 10, 'Guido', '2022-06-24 01:00:10'),
(122, 10, 'Guido', '2022-06-24 01:11:00'),
(123, 10, 'Guido', '2022-06-24 01:16:53'),
(124, 10, 'Guido', '2022-06-24 01:18:08'),
(125, 10, 'Guido', '2022-06-24 01:49:22'),
(126, 15, 'User1', '2022-06-24 02:25:33'),
(127, 15, 'User1', '2022-06-24 02:26:44'),
(128, 16, 'User2', '2022-06-24 03:12:04'),
(129, 17, 'User3', '2022-06-24 03:12:33'),
(130, 18, 'User4', '2022-06-24 03:12:56'),
(131, 16, 'User2', '2022-06-24 03:13:16'),
(132, 18, 'User4', '2022-06-24 03:13:35'),
(133, 16, 'User2', '2022-06-24 03:13:49'),
(134, 19, 'User5', '2022-06-24 03:14:07'),
(135, 19, 'User5', '2022-06-24 19:33:22');




--
-- Índices para tablas volcadas
--
ALTER TABLE `producto`
  ADD CONSTRAINT `FK_producto_pedido_asoc` FOREIGN KEY (`producto_pedido_asoc`) REFERENCES `pedidos` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

ALTER TABLE `empleados`
  ADD CONSTRAINT `FK_empleado_area` FOREIGN KEY (`empleado_area_id`) REFERENCES `area` (`area_id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_empleado_user` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `pedidos`
  ADD CONSTRAINT `FK_Mesa_pedidos` FOREIGN KEY (`id_mesa`) REFERENCES `mesas` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

ALTER TABLE `mesas`
  ADD CONSTRAINT `FK_id_mesa_empleado` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;
COMMIT;

ALTER TABLE `encuestas`
  ADD CONSTRAINT `fo_encuestas_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `historial_empleados`
  ADD CONSTRAINT `FK_login_user` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
--
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
