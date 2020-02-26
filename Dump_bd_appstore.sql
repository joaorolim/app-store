CREATE DATABASE  IF NOT EXISTS `bd_appstore` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `bd_appstore`;
-- MySQL dump 10.13  Distrib 5.7.17, for Win64 (x86_64)
--
-- Host: localhost    Database: bd_appstore
-- ------------------------------------------------------
-- Server version	5.7.14-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tbl_modulos`
--

DROP TABLE IF EXISTS `tbl_modulos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_modulos` (
  `mod_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mod_categoria` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `mod_nome` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `mod_valor` decimal(10,2) NOT NULL,
  `mod_sobre` text COLLATE utf8_unicode_ci NOT NULL,
  `mod_img` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mod_created_at` datetime NOT NULL,
  `mod_created_by` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `mod_updated_at` datetime DEFAULT NULL,
  `mod_updated_by` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`mod_id`),
  UNIQUE KEY `modulo_unico` (`mod_categoria`,`mod_nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_modulos`
--

LOCK TABLES `tbl_modulos` WRITE;
/*!40000 ALTER TABLE `tbl_modulos` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_modulos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_modulos_clientes`
--

DROP TABLE IF EXISTS `tbl_modulos_clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_modulos_clientes` (
  `mcl_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `use_id` int(10) unsigned NOT NULL,
  `mod_id` int(10) unsigned NOT NULL,
  `mcl_ativo` tinyint(1) NOT NULL DEFAULT '0',
  `mcl_created_at` datetime NOT NULL,
  `mcl_created_by` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `mcl_updated_at` datetime DEFAULT NULL,
  `mcl_updated_by` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`mcl_id`),
  UNIQUE KEY `mcl_unico` (`use_id`,`mod_id`),
  KEY `fk_mcl_use` (`use_id`),
  KEY `fk_mcl_mod` (`mod_id`),
  CONSTRAINT `fk_mcl_mod` FOREIGN KEY (`mod_id`) REFERENCES `tbl_modulos` (`mod_id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_mcl_use` FOREIGN KEY (`use_id`) REFERENCES `tbl_users` (`use_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_modulos_clientes`
--

LOCK TABLES `tbl_modulos_clientes` WRITE;
/*!40000 ALTER TABLE `tbl_modulos_clientes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_modulos_clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_permissions`
--

DROP TABLE IF EXISTS `tbl_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_permissions` (
  `per_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `per_desc` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `per_obs` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `per_created_at` datetime NOT NULL,
  `per_updated_at` datetime NOT NULL,
  PRIMARY KEY (`per_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_permissions`
--

LOCK TABLES `tbl_permissions` WRITE;
/*!40000 ALTER TABLE `tbl_permissions` DISABLE KEYS */;
INSERT INTO `tbl_permissions` VALUES (1,'role','Permite exibir os papéis cadastrados','2018-04-30 11:32:22','2018-04-30 11:32:22'),(2,'role-alt','Permite alterar os papéis cadastrados','2018-04-30 11:32:24','2018-04-30 11:32:24'),(3,'role-exc','Permite deletar os papéis cadastrados','2018-04-30 11:32:25','2018-04-30 11:32:25'),(4,'role-cad','Permite cadastrar novos papéis','2018-04-30 11:32:26','2018-04-30 11:32:26'),(5,'permission','Permite exibir as permissões de cada papel','2018-04-30 11:32:27','2018-04-30 11:32:27'),(6,'permission-alt','Permite atribuir as permissões para cada papel','2018-04-30 11:32:29','2018-04-30 11:32:29'),(7,'user','Permite exibir os usuários cadastrados','2018-04-30 11:32:30','2018-04-30 11:32:30'),(8,'user-alt','Permite alterar os usuários cadastrados','2018-04-30 11:32:32','2018-04-30 11:32:32'),(9,'user-exc','Permite deletar os usuários cadastrados','2018-04-30 11:32:33','2018-04-30 11:32:33'),(10,'user-cad','Permite cadastrar novos usuários','2018-04-30 11:32:34','2018-04-30 11:32:35');
/*!40000 ALTER TABLE `tbl_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_permissions_roles`
--

DROP TABLE IF EXISTS `tbl_permissions_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_permissions_roles` (
  `pmr_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rol_id` int(10) unsigned NOT NULL,
  `per_id` int(10) unsigned NOT NULL,
  `pmr_created_at` datetime NOT NULL,
  `pmr_updated_at` datetime NOT NULL,
  PRIMARY KEY (`pmr_id`),
  UNIQUE KEY `role_permission_unico` (`rol_id`,`per_id`),
  KEY `fk_pmr_permissions` (`per_id`),
  CONSTRAINT `fk_pmr_permissions` FOREIGN KEY (`per_id`) REFERENCES `tbl_permissions` (`per_id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_pmr_roles` FOREIGN KEY (`rol_id`) REFERENCES `tbl_roles` (`rol_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=211 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_permissions_roles`
--

LOCK TABLES `tbl_permissions_roles` WRITE;
/*!40000 ALTER TABLE `tbl_permissions_roles` DISABLE KEYS */;
INSERT INTO `tbl_permissions_roles` VALUES (1,1,1,'2018-04-28 18:07:22','2018-04-28 18:07:22'),(2,1,2,'2018-04-28 18:07:23','2018-04-28 18:07:23'),(3,1,3,'2018-04-28 18:07:24','2018-04-28 18:07:24'),(4,1,4,'2018-04-28 18:07:25','2018-04-28 18:07:25'),(5,1,5,'2018-04-28 18:07:26','2018-04-28 18:07:26'),(6,1,6,'2018-04-28 18:07:26','2018-04-28 18:07:26'),(7,1,7,'2018-04-28 18:07:26','2018-04-28 18:07:26'),(8,1,8,'2018-04-28 18:07:26','2018-04-28 18:07:26'),(9,1,9,'2018-04-28 18:07:26','2018-04-28 18:07:26'),(10,1,10,'2018-04-28 18:07:26','2018-04-28 18:07:26');
/*!40000 ALTER TABLE `tbl_permissions_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_roles`
--

DROP TABLE IF EXISTS `tbl_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_roles` (
  `rol_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rol_desc` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `rol_obs` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rol_created_at` datetime NOT NULL,
  `rol_created_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rol_updated_at` datetime NOT NULL,
  `rol_updated_by` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`rol_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_roles`
--

LOCK TABLES `tbl_roles` WRITE;
/*!40000 ALTER TABLE `tbl_roles` DISABLE KEYS */;
INSERT INTO `tbl_roles` VALUES (1,'Admin','Administrador do sistema - acesso total','2018-04-27 07:26:22','João Paulo Rolim','2018-04-27 07:26:22','João Paulo Rolim'),(2,'Usuário Padrão','Usuário Padrão - Usuário que acabou de ser criado no sistema','2018-04-27 07:27:22','João Paulo Rolim','2018-04-27 07:27:22','João Paulo Rolim'),(3,'Usuário N1','Usuário Nível 1 - Acesso parcial: Somente exibe e insere. Não pode deletar nem editar.','2018-04-28 17:27:22','João Paulo Rolim','2018-04-29 18:03:45','João Paulo Rolim'),(6,'Usuário N2','Usuário Nível 2 - Acesso parcial: Somente exibe. Não pode deletar, incluir e nem editar','2018-04-30 14:10:00','João Paulo Rolim','2018-04-30 14:10:00','João Paulo Rolim'),(7,'Usuário N3','Usuário Nível 3 - Acesso parcial: Somente exibe. Não pode deletar, incluir e nem editar','2018-04-30 14:10:23','João Paulo Rolim','2018-04-30 14:10:23','João Paulo Rolim'),(8,'Usuário N4','Usuário Nível 4 - Acesso parcial: Somente exibe. Não pode deletar, incluir e nem editar','2018-04-30 14:10:43','João Paulo Rolim','2018-04-30 14:10:43','João Paulo Rolim'),(10,'Diretor','Acesso total ao Sistema','2018-05-01 09:08:21','João Paulo Rolim','2018-05-01 09:08:21','João Paulo Rolim');
/*!40000 ALTER TABLE `tbl_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tbl_users`
--

DROP TABLE IF EXISTS `tbl_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_users` (
  `use_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `use_first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `use_last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `use_gender` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `use_birthday` date NOT NULL,
  `use_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `use_senha` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `use_status` tinyint(1) NOT NULL DEFAULT '0',
  `rol_id` int(10) unsigned NOT NULL DEFAULT '0',
  `use_created_at` datetime NOT NULL,
  `use_created_by` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `use_updated_at` datetime NOT NULL,
  `use_updated_by` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`use_id`),
  UNIQUE KEY `email_unico` (`use_email`),
  KEY `fk_users_roles` (`rol_id`),
  CONSTRAINT `fk_users_roles` FOREIGN KEY (`rol_id`) REFERENCES `tbl_roles` (`rol_id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tbl_users`
--

LOCK TABLES `tbl_users` WRITE;
/*!40000 ALTER TABLE `tbl_users` DISABLE KEYS */;
INSERT INTO `tbl_users` VALUES (1,'João Paulo','Rolim','m','1985-03-07','admin@sg.com','$2a$08$3ZEcKbkzvl0gt5jqer1WLuD1CCLKgscKALRxplu8rYN96oNQmGnv.',1,1,'2018-01-22 07:26:22','João Paulo Rolim','2018-01-22 07:26:22','João Paulo Rolim'),(2,'Sebastião','da Silva','m','1965-08-06','tiao@be.com.br','$2a$08$qZSx29F03RzOA1SNp5C0vuAmTGkFKp9OVc6BUtrInYbsdIBhRwOWG',1,3,'2018-02-01 23:12:31','João Paulo Rolim','2018-02-01 23:12:31','João Paulo Rolim');
/*!40000 ALTER TABLE `tbl_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'bd_appstore'
--

--
-- Dumping routines for database 'bd_appstore'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-02-26  5:32:35
