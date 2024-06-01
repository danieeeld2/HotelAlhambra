-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: localhost    Database: danieeeld22324
-- ------------------------------------------------------
-- Server version	8.0.36-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `fotosHabitaciones`
--

DROP TABLE IF EXISTS `fotosHabitaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fotosHabitaciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Habitacion` varchar(50) NOT NULL,
  `Imagen` longblob NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fotosHabitaciones`
--

LOCK TABLES `fotosHabitaciones` WRITE;
/*!40000 ALTER TABLE `fotosHabitaciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `fotosHabitaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `habitacionesHotel`
--

DROP TABLE IF EXISTS `habitacionesHotel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `habitacionesHotel` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Habitacion` varchar(50) NOT NULL,
  `Capacidad` int NOT NULL,
  `Precio` double NOT NULL,
  `Descripcion` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Habitacion` (`Habitacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `habitacionesHotel`
--

LOCK TABLES `habitacionesHotel` WRITE;
/*!40000 ALTER TABLE `habitacionesHotel` DISABLE KEYS */;
/*!40000 ALTER TABLE `habitacionesHotel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logsHotel`
--

DROP TABLE IF EXISTS `logsHotel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `logsHotel` (
  `id` int NOT NULL AUTO_INCREMENT,
  `MarcaTemporal` int NOT NULL,
  `Descripcion` text NOT NULL,
  `Tipo` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logsHotel`
--

LOCK TABLES `logsHotel` WRITE;
/*!40000 ALTER TABLE `logsHotel` DISABLE KEYS */;
/*!40000 ALTER TABLE `logsHotel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reservasHotel`
--

DROP TABLE IF EXISTS `reservasHotel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reservasHotel` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Habitacion` varchar(50) NOT NULL,
  `Personas` int DEFAULT NULL,
  `Entrada` date NOT NULL,
  `Salida` date NOT NULL,
  `Comentario` text,
  `Precio` double DEFAULT NULL,
  `Estado` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Marca` int NOT NULL,
  `email` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reservasHotel`
--

LOCK TABLES `reservasHotel` WRITE;
/*!40000 ALTER TABLE `reservasHotel` DISABLE KEYS */;
/*!40000 ALTER TABLE `reservasHotel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuariosHotel`
--

DROP TABLE IF EXISTS `usuariosHotel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuariosHotel` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  `apellidos` varchar(70) NOT NULL,
  `dni` varchar(9) NOT NULL,
  `email` varchar(40) NOT NULL,
  `clave` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tarjeta` varchar(16) NOT NULL,
  `rol` varchar(20) NOT NULL DEFAULT 'Cliente',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuariosHotel`
--

LOCK TABLES `usuariosHotel` WRITE;
/*!40000 ALTER TABLE `usuariosHotel` DISABLE KEYS */;
INSERT INTO `usuariosHotel` VALUES (2,'Daniel','Alconchel','49617109Z','danieeeld2@gmail.com','$2y$10$PeEa609.P.mUxDP3Ls0f2.o0Uy5HyMdU2FODUY3Filjkt/49Ngv7K','4000001234567899','Administrador');
/*!40000 ALTER TABLE `usuariosHotel` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-06-01 18:27:09
