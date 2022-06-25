-- MariaDB dump 10.19  Distrib 10.4.24-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: comanda
-- ------------------------------------------------------
-- Server version	10.4.24-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `area`
--

DROP TABLE IF EXISTS `area`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `area` (
  `area_id` int(11) NOT NULL AUTO_INCREMENT,
  `area_desc` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`area_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `area`
--

LOCK TABLES `area` WRITE;
/*!40000 ALTER TABLE `area` DISABLE KEYS */;
INSERT INTO `area` VALUES (1,'BarraTragos'),(2,'Cocina'),(3,'BarraCerveza'),(4,'Administracion'),(5,'CandyBar');
/*!40000 ALTER TABLE `area` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empleados`
--

DROP TABLE IF EXISTS `empleados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empleados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `empleado_area_id` int(11) DEFAULT NULL,
  `nombre` varchar(40) COLLATE utf8_spanish2_ci NOT NULL,
  `date_init` datetime NOT NULL,
  `date_end` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_empleado_area` (`empleado_area_id`),
  KEY `FK_empleado_user` (`user_id`),
  CONSTRAINT `FK_empleado_area` FOREIGN KEY (`empleado_area_id`) REFERENCES `area` (`area_id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  CONSTRAINT `FK_empleado_user` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci COMMENT='empleados table';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleados`
--

LOCK TABLES `empleados` WRITE;
/*!40000 ALTER TABLE `empleados` DISABLE KEYS */;
INSERT INTO `empleados` VALUES (11,15,1,'Athena','2022-06-01 01:54:58',NULL),(12,16,1,'Persefone','2022-06-01 01:55:33',NULL),(13,17,1,'Hera','2022-06-01 01:55:44',NULL),(14,18,2,'Hades','2022-06-01 01:56:01',NULL),(15,19,2,'Zeus','2022-06-01 01:56:28',NULL),(16,20,2,'Odin','2022-06-01 01:56:36',NULL),(17,21,3,'Poseidon','2022-06-01 01:58:02',NULL),(18,22,3,'Wukong','2022-06-01 01:58:24',NULL),(19,10,4,'Pedro','2022-06-01 01:59:42',NULL),(20,30,3,'Lilith','2022-06-02 19:38:09',NULL),(21,31,3,'Juan','2022-06-05 22:50:32',NULL);
/*!40000 ALTER TABLE `empleados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `encuestas`
--

DROP TABLE IF EXISTS `encuestas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `encuestas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `mesa_punt` int(11) NOT NULL,
  `resto_punt` int(11) NOT NULL,
  `mozo_punt` int(11) NOT NULL,
  `cocinero_punt` int(11) NOT NULL,
  `promedio_punt` float NOT NULL,
  `comentario` varchar(66) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fo_encuestas_pedido` (`pedido_id`),
  CONSTRAINT `fo_encuestas_pedido` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `encuestas`
--

LOCK TABLES `encuestas` WRITE;
/*!40000 ALTER TABLE `encuestas` DISABLE KEYS */;
INSERT INTO `encuestas` VALUES (1,17,9,9,9,9,9,'Muy rico todo y buena atencion');
/*!40000 ALTER TABLE `encuestas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historial_empleados`
--

DROP TABLE IF EXISTS `historial_empleados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `historial_empleados` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `username` varchar(40) COLLATE utf8_spanish2_ci NOT NULL,
  `date_login` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_login_user` (`user_id`),
  CONSTRAINT `FK_login_user` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=136 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historial_empleados`
--

LOCK TABLES `historial_empleados` WRITE;
/*!40000 ALTER TABLE `historial_empleados` DISABLE KEYS */;
INSERT INTO `historial_empleados` VALUES (119,10,'Guido','2021-06-24 00:54:03'),(120,10,'Guido','2022-06-24 00:58:51'),(121,10,'Guido','2022-06-24 01:00:10'),(122,10,'Guido','2022-06-24 01:11:00'),(123,10,'Guido','2022-06-24 01:16:53'),(124,10,'Guido','2022-06-24 01:18:08'),(125,10,'Guido','2022-06-24 01:49:22'),(126,15,'User1','2022-06-24 02:25:33'),(127,15,'User1','2022-06-24 02:26:44'),(128,16,'User2','2022-06-24 03:12:04'),(129,17,'User3','2022-06-24 03:12:33'),(130,18,'User4','2022-06-24 03:12:56'),(131,16,'User2','2022-06-24 03:13:16'),(132,18,'User4','2022-06-24 03:13:35'),(133,16,'User2','2022-06-24 03:13:49'),(134,19,'User5','2022-06-24 03:14:07'),(135,19,'User5','2022-06-24 19:33:22');
/*!40000 ALTER TABLE `historial_empleados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mesas`
--

DROP TABLE IF EXISTS `mesas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mesas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mesa_code` varchar(5) COLLATE utf8_spanish2_ci NOT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `estado` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mesa_code` (`mesa_code`),
  KEY `FK_id_mesa_empleado` (`id_empleado`),
  CONSTRAINT `FK_id_mesa_empleado` FOREIGN KEY (`id_empleado`) REFERENCES `empleados` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mesas`
--

LOCK TABLES `mesas` WRITE;
/*!40000 ALTER TABLE `mesas` DISABLE KEYS */;
INSERT INTO `mesas` VALUES (2,'ME002',20,'Cliente Esperando Pedido'),(3,'ME003',12,'Cliente Pagando'),(4,'ME004',20,'Cerrada'),(5,'ME005',NULL,'Cerrada'),(6,'ME006',NULL,'Cerrada'),(8,'ME008',NULL,'Cerrada'),(9,'ME009',NULL,'Cerrada'),(10,'ME010',NULL,'Cerrada'),(11,'ME011',NULL,'Cerrada');
/*!40000 ALTER TABLE `mesas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_mesa` int(11) DEFAULT NULL,
  `estado_pedido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL DEFAULT 'Pendiente',
  `nombre_cliente` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `img_pedido` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `cost_pedido` float NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_Mesa_pedidos` (`id_mesa`),
  CONSTRAINT `FK_Mesa_pedidos` FOREIGN KEY (`id_mesa`) REFERENCES `mesas` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos`
--

LOCK TABLES `pedidos` WRITE;
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
INSERT INTO `pedidos` VALUES (17,5,'Listo Para Servir','Albina','./PedidoImages/Pedido_17.png',0),(18,5,'En preparacion','Albina','./PedidoImages/Pedido_18.png',0);
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto`
--

DROP TABLE IF EXISTS `producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `producto` (
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
  KEY `FK_producto_pedido_asoc` (`producto_pedido_asoc`),
  CONSTRAINT `FK_producto_pedido_asoc` FOREIGN KEY (`producto_pedido_asoc`) REFERENCES `pedidos` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto`
--

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` VALUES (9,2,17,'Listo','Pollo Al Champignon',550,'2022-06-01 02:50:33','2022-06-01 03:20:33',30),(10,3,17,'Listo','Gaseosa Linea Pepsi 2lt.',300,'2022-06-01 02:51:24','2022-06-01 02:56:24',35),(11,3,17,'Listo','Gaseosa Linea Pepsi 2lt.',300,'2022-06-01 03:05:14','2022-06-01 03:10:14',5),(12,3,18,'Listo','Gaseosa Linea Pepsi 2lt.',300,'2022-06-01 03:05:51','2022-06-01 03:10:51',5),(13,2,17,'Listo','Hamburguesa con Bacon',550,'2022-06-01 03:06:59','2022-06-01 03:26:59',20),(14,2,18,'Listo','Hamburguesa con Cheddar y Guarnicion',550,'2022-06-01 03:09:14','2022-06-01 03:27:14',18),(15,2,NULL,'Listo','Ensalada Waldorf',550,'2022-06-01 03:10:27','2022-06-01 03:17:27',7),(16,2,NULL,'Listo','Ensalada Waldorf',350,'2022-06-01 11:54:41','2022-06-01 12:01:41',7),(17,2,NULL,'Listo','Ensalada Rusa',250,'2022-06-01 11:55:24','2022-06-01 12:03:24',8),(18,2,NULL,'Listo','Pollo al Champignon',450,'2022-06-02 00:16:04','2022-06-02 00:36:04',0),(19,2,NULL,'Listo','Pollo al Verdeo',400,'2022-06-02 00:16:29','2022-06-02 00:38:29',0),(20,3,NULL,'Listo','Cerveza Stella Artois 1lt.',300,'2022-06-02 00:17:06','2022-06-02 00:22:06',0),(21,3,NULL,'Listo','Cerveza Stella Artois 1lt.',300,'2022-06-02 20:01:14','2022-06-02 20:06:14',0),(22,3,NULL,'Listo','Cerveza Rabieta Irish Ale 750ml.',300,'2022-06-02 20:01:46','2022-06-02 20:08:46',0),(23,2,NULL,'Listo','Papas bravas',450,'2022-06-02 20:02:07','2022-06-02 20:27:07',0),(24,2,NULL,'Listo','Papas con Cheddar & Bacon',500,'2022-06-02 20:02:29','2022-06-02 20:32:29',0),(25,2,NULL,'Listo','Mila de Pollo',320,'2022-06-06 17:30:47',NULL,NULL),(26,2,NULL,'Listo','Mila de Pollo',320,'2022-06-06 18:36:43',NULL,NULL);
/*!40000 ALTER TABLE `producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `password` text COLLATE utf8_spanish2_ci NOT NULL,
  `isAdmin` tinyint(1) NOT NULL,
  `user_type` varchar(20) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `estado` varchar(20) COLLATE utf8_spanish2_ci NOT NULL,
  `date_init` datetime NOT NULL,
  `date_end` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usernombre` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (10,'Guido','$2y$10$YC33xVvmADBUIczUmSShY.hkCB7pLh5ksH.COU7loi2Zd4V8h.0cy',1,'Admin','Active','2022-06-01 00:32:31',NULL),(15,'User1','$2y$10$3bG3uAu2AJ7VnMcM08IBcu3kLehg9t6YjWBAQ/j6FeLR7VgMzOWN2',0,'Bartender','Active','2022-06-01 01:31:07',NULL),(16,'User2','$2y$10$yT74CCOm7isBvu19UvpP.uXCyy3rTYqLw5mNH4AYb.uIzozX6hY3.',0,'Bartender','Active','2022-06-01 01:31:15',NULL),(17,'User3','$2y$10$izib3ooG60BV9VhfVWEpnuk67M3EzAz/yboSaGlq4tvFrjIKTFAl2',0,'Bartender','Active','2022-06-01 01:31:19',NULL),(18,'User4','$2y$10$CnSjF.0SF2FHYhxXKzjrsuZbWZzS4CrQ.kin1GhgDpHmiDXv.kZQO',0,'Cocinero','Active','2022-06-01 01:31:32',NULL),(19,'User5','$2y$10$QLr2gkRy4rB6rYkRT/lUye4WUv.iCkSr2Bm4gcDFrYF09R1MclHl.',0,'Cocinero','Active','2022-06-01 01:31:41',NULL),(20,'User6','$2y$10$JxpnNeff2MzRNzrX/LfoRu7U/A8GzU7CEdrF3E8KCFyXHNccnLDo2',0,'Cocinero','Active','2022-06-01 01:31:47',NULL),(21,'User7','$2y$10$/jvBeHcBsJXiVqno25eAx.kePekvRQrqDjTOmY8Yd3w8rw3MCcXsq',0,'Mozo','Active','2022-06-01 01:32:01',NULL),(22,'User8','$2y$10$M9DS08Vxs0MR2OdjL1OIxuYcDCOIOffGzdDk3AHS3cms6tGCGS9eO',0,'Mozo','Active','2022-06-01 01:32:05',NULL),(30,'User9','$2y$10$6CYGTBDQ6a3migWWTYHqtuMWIIF7P4NHfOAddeaPvAliFHQUsWg2a',0,'Cervecero','Active','2022-06-01 19:36:36',NULL),(31,'Juan','$2y$10$KV4V4D2ZUMokR9hvCob4Ne/X9vebo0yldM3LyFhYMLw1ddKpdM0A6',0,'Cervecero','Active','2022-06-05 22:28:15',NULL);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-06-25 12:01:43
