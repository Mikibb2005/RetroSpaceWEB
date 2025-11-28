/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.13-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: mikisito_db
-- ------------------------------------------------------
-- Server version	10.11.13-MariaDB-0ubuntu0.24.04.1

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
-- Table structure for table `estadisticas`
--

DROP TABLE IF EXISTS `estadisticas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `estadisticas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(50) DEFAULT NULL,
  `valor` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estadisticas`
--

LOCK TABLES `estadisticas` WRITE;
/*!40000 ALTER TABLE `estadisticas` DISABLE KEYS */;
/*!40000 ALTER TABLE `estadisticas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `foro_comentarios`
--

DROP TABLE IF EXISTS `foro_comentarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `foro_comentarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contenido` text NOT NULL,
  `fecha_publicacion` timestamp NULL DEFAULT current_timestamp(),
  `autor_id` int(11) DEFAULT NULL,
  `hilo_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `autor_id` (`autor_id`),
  KEY `hilo_id` (`hilo_id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `foro_comentarios_ibfk_1` FOREIGN KEY (`autor_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `foro_comentarios_ibfk_2` FOREIGN KEY (`hilo_id`) REFERENCES `foro_hilos` (`id`),
  CONSTRAINT `foro_comentarios_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `foro_comentarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `foro_comentarios`
--

LOCK TABLES `foro_comentarios` WRITE;
/*!40000 ALTER TABLE `foro_comentarios` DISABLE KEYS */;
INSERT INTO `foro_comentarios` VALUES
(1,'hola, primer coment','2025-11-22 20:35:08',3,1,NULL),
(2,'Y se puede responder de tranqui','2025-11-22 20:35:20',3,1,1),
(3,'probando interaccion entre dos cuentas','2025-11-22 20:41:45',4,1,NULL);
/*!40000 ALTER TABLE `foro_comentarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `foro_hilos`
--

DROP TABLE IF EXISTS `foro_hilos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `foro_hilos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `autor_id` int(11) DEFAULT NULL,
  `sticky` tinyint(1) DEFAULT 0,
  `archivos` text DEFAULT NULL COMMENT 'JSON array de rutas de archivos',
  PRIMARY KEY (`id`),
  KEY `autor_id` (`autor_id`),
  CONSTRAINT `foro_hilos_ibfk_1` FOREIGN KEY (`autor_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `foro_hilos`
--

LOCK TABLES `foro_hilos` WRITE;
/*!40000 ALTER TABLE `foro_hilos` DISABLE KEYS */;
INSERT INTO `foro_hilos` VALUES
(1,'Primer Hilo creado','Hola, soy Miki es desarrollador de esta pagina web, esto solo es el primer hilo que creo y lo voy a utilizar para saludar a todo el que entre nuevo en este foro.\r\nUn saludo.','general','2025-11-22 20:12:19',3,0,NULL);
/*!40000 ALTER TABLE `foro_hilos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts_diario`
--

DROP TABLE IF EXISTS `posts_diario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts_diario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `contenido` text NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `codigo_embed` text DEFAULT NULL,
  `fecha_publicacion` timestamp NULL DEFAULT current_timestamp(),
  `autor_id` int(11) DEFAULT NULL,
  `archivos` text DEFAULT NULL COMMENT 'JSON array de rutas de archivos',
  PRIMARY KEY (`id`),
  KEY `autor_id` (`autor_id`),
  CONSTRAINT `posts_diario_ibfk_1` FOREIGN KEY (`autor_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts_diario`
--

LOCK TABLES `posts_diario` WRITE;
/*!40000 ALTER TABLE `posts_diario` DISABLE KEYS */;
INSERT INTO `posts_diario` VALUES
(1,'Primer post en RetroSpace','¡Bienvenidos a mi diario! Este es el primer post.\r\n\r\nAqui como su propio nombre indica voy a estar subiendo un diario de los temas tecnologicos, tales como mis estudios (Ingenieria Informatica), mi canal de Youtube (@Bloop434) y mods o proyectos que vaya haciendo, que por cierto, tambien podreis ver en el apartado de Proyectos que ahi ire subiendo todos los que voy haciendo y como evolucionan.\r\n\r\nEn este foro se baneara y bloqueara el acceso a los usuarios que falten el respeto a quien sea, y queremos que no haya ningun tipo de odio, posiciones politicas, ni nada de eso.\r\n\r\nY sin mucho mas que decir espero que os mole la idea, y que hagais muchos amigos, voy a seguir desarrollando cosas para la web.','','','2025-11-22 14:04:13',1,'[\"\\/uploads\\/diario\\/6922e12b12190_1763893547.jpg\"]');
/*!40000 ALTER TABLE `posts_diario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proyecto_actualizaciones`
--

DROP TABLE IF EXISTS `proyecto_actualizaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `proyecto_actualizaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `proyecto_id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `contenido` text NOT NULL,
  `archivos` text DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `proyecto_id` (`proyecto_id`),
  CONSTRAINT `proyecto_actualizaciones_ibfk_1` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proyecto_actualizaciones`
--

LOCK TABLES `proyecto_actualizaciones` WRITE;
/*!40000 ALTER TABLE `proyecto_actualizaciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `proyecto_actualizaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proyecto_comentarios`
--

DROP TABLE IF EXISTS `proyecto_comentarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `proyecto_comentarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `actualizacion_id` int(11) NOT NULL,
  `autor_id` int(11) NOT NULL,
  `contenido` text NOT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `actualizacion_id` (`actualizacion_id`),
  KEY `autor_id` (`autor_id`),
  CONSTRAINT `proyecto_comentarios_ibfk_1` FOREIGN KEY (`actualizacion_id`) REFERENCES `proyecto_actualizaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `proyecto_comentarios_ibfk_2` FOREIGN KEY (`autor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proyecto_comentarios`
--

LOCK TABLES `proyecto_comentarios` WRITE;
/*!40000 ALTER TABLE `proyecto_comentarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `proyecto_comentarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proyectos`
--

DROP TABLE IF EXISTS `proyectos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `proyectos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `categoria` enum('Programacion','Hardware','Mods','GameMaker') NOT NULL,
  `link1` varchar(255) DEFAULT NULL,
  `link2` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `autor_id` int(11) DEFAULT NULL,
  `archivos` text DEFAULT NULL COMMENT 'JSON array de rutas de archivos',
  `fecha_actualizacion` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `autor_id` (`autor_id`),
  CONSTRAINT `proyectos_ibfk_1` FOREIGN KEY (`autor_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proyectos`
--

LOCK TABLES `proyectos` WRITE;
/*!40000 ALTER TABLE `proyectos` DISABLE KEYS */;
INSERT INTO `proyectos` VALUES
(1,'RetroSpace web','Este proyecto está hecho para hacer pruebas de funcionamiento de la web. También quiero que se use para yo subir los logs de actualizaciones que iré haciendo. Y en los comentarios quiero que me dejéis las funcionalidades que queréis que mejore/añada.','Programacion','','','','','2025-11-22 14:04:13',1,'[\"\\/uploads\\/proyectos\\/6922f45f6c28b_1763898463.png\"]','2025-11-23 12:48:08');
/*!40000 ALTER TABLE `proyectos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seguidores`
--

DROP TABLE IF EXISTS `seguidores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `seguidores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seguidor_id` int(11) DEFAULT NULL,
  `seguido_id` int(11) DEFAULT NULL,
  `fecha_seguimiento` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `seguidor_id` (`seguidor_id`,`seguido_id`),
  KEY `seguido_id` (`seguido_id`),
  CONSTRAINT `seguidores_ibfk_1` FOREIGN KEY (`seguidor_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `seguidores_ibfk_2` FOREIGN KEY (`seguido_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seguidores`
--

LOCK TABLES `seguidores` WRITE;
/*!40000 ALTER TABLE `seguidores` DISABLE KEYS */;
INSERT INTO `seguidores` VALUES
(1,4,3,'2025-11-22 20:42:01'),
(2,1,3,'2025-11-23 11:22:40');
/*!40000 ALTER TABLE `seguidores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `nombre_real` varchar(100) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `rol` enum('admin','user') DEFAULT 'user',
  `fecha_registro` timestamp NULL DEFAULT current_timestamp(),
  `esta_bloqueado` tinyint(1) DEFAULT 0,
  `biografia` text DEFAULT NULL,
  `avatar` text DEFAULT NULL,
  `etiquetas_so` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES
(1,'admin','admin@retrospace.local','Administrador Supremo','$2y$10$hScxccQaMSUwlJ5Ax/0qvueUdk21.oH.YbeutECieXd.e7uASUVpC','admin','2025-11-22 14:04:13',0,'Soy el admin del sitio.','data:image/svg+xml;base64,CiAgICAgICAgPHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHdpZHRoPScxNTAnIGhlaWdodD0nMTUwJz4KICAgICAgICAgICAgPGNpcmNsZSBjeD0nNzUnIGN5PSc3NScgcj0nNzUnIGZpbGw9JyNGNTlFMEInLz4KICAgICAgICAgICAgPHRleHQgeD0nNzUnIHk9Jzk1JyBmb250LXNpemU9JzYwJyB0ZXh0LWFuY2hvcj0nbWlkZGxlJz7irZA8L3RleHQ+CiAgICAgICAgPC9zdmc+CiAgICA=',NULL),
(2,'testuser','test@example.com',NULL,'$2y$10$YZeuGDjOfnXbzBxoUEROmeJ/tIJGNHgQNLQvyglUudGeroaQ3uKty','user','2025-11-22 15:06:39',0,NULL,NULL,NULL),
(3,'Mikibb2005','mikibb2005@gmail.com','Miguel Blánquez Bravo','$2y$10$zvzqH/EG7JmgBnnVReSeBeXrbU05VygypOioG/XOTPOwna1EazLZ2','user','2025-11-22 15:09:13',0,'Soy el creador de esta pagina web, estudiante de Ingenieria Informatica y duaño del canal de YouTube Bloop.','data:image/svg+xml;base64,CiAgICAgICAgPHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHdpZHRoPScxNTAnIGhlaWdodD0nMTUwJz4KICAgICAgICAgICAgPGNpcmNsZSBjeD0nNzUnIGN5PSc3NScgcj0nNzUnIGZpbGw9JyNFQzQ4OTknLz4KICAgICAgICAgICAgPHRleHQgeD0nNzUnIHk9Jzk1JyBmb250LXNpemU9JzYwJyB0ZXh0LWFuY2hvcj0nbWlkZGxlJz7wn46uPC90ZXh0PgogICAgICAgIDwvc3ZnPgogICAg','[\"Windows XP\",\"Ubuntu\",\"Batocera.linux\",\"iOS 17\"]'),
(4,'miki2','mikibb20051@gmail.com','','$2y$10$/k/A0./RRhEDiSjblctibedT/kyOJRmqz6tovincA1OGnawAlYqtS','user','2025-11-22 20:41:25',0,'Soy la cuenta secundaria de pruebas del creador de este foro / web','data:image/svg+xml;base64,CiAgICAgICAgPHN2ZyB4bWxucz0naHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmcnIHdpZHRoPScxNTAnIGhlaWdodD0nMTUwJz4KICAgICAgICAgICAgPGNpcmNsZSBjeD0nNzUnIGN5PSc3NScgcj0nNzUnIGZpbGw9JyNFQzQ4OTknLz4KICAgICAgICAgICAgPHRleHQgeD0nNzUnIHk9Jzk1JyBmb250LXNpemU9JzYwJyB0ZXh0LWFuY2hvcj0nbWlkZGxlJz7wn46uPC90ZXh0PgogICAgICAgIDwvc3ZnPgogICAg',NULL);
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

-- Dump completed on 2025-11-28 23:22:50
