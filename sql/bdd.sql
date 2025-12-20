-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: db_muyak
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `approvisionnements`
--

DROP TABLE IF EXISTS `approvisionnements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `approvisionnements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_bon` varchar(50) DEFAULT NULL,
  `date_approvisionnement` date DEFAULT NULL,
  `fournisseur` varchar(255) DEFAULT NULL,
  `user_id` int NOT NULL,
  `observation` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_bon` (`numero_bon`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `approvisionnements_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `approvisionnements`
--

LOCK TABLES `approvisionnements` WRITE;
/*!40000 ALTER TABLE `approvisionnements` DISABLE KEYS */;
INSERT INTO `approvisionnements` VALUES (4,'255jdkot','2025-11-21','xf',3,'dfr','2025-11-21 23:07:15'),(5,'fwsdgf','2025-11-21','xf',3,'xfcb','2025-11-21 23:08:06'),(6,'022545','2025-11-24','NA',3,'RAS','2025-11-24 13:10:09'),(8,'022354','2025-11-24','hdbf',3,'RAS','2025-11-24 13:15:00'),(9,'02213544','2025-11-28','RAS',3,'wxf','2025-11-28 16:19:46');
/*!40000 ALTER TABLE `approvisionnements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `articles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) DEFAULT NULL,
  `categorie` enum('boisson','nourriture') DEFAULT NULL,
  `stock` int DEFAULT NULL,
  `actif` tinyint(1) DEFAULT '1',
  `unite_mesure_id` int DEFAULT NULL,
  `purchase_unite_mesure_id` int DEFAULT NULL,
  `conversion_factor` decimal(10,2) NOT NULL DEFAULT '1.00',
  `type_tarification` enum('standard','varie') DEFAULT 'standard',
  `prix` decimal(10,2) DEFAULT '0.00',
  `type` enum('Produit fini','Matière première') DEFAULT 'Produit fini',
  `stock_seuil` decimal(10,2) DEFAULT '0.00',
  `dernier_cout_achat` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `unite_mesure_id` (`unite_mesure_id`),
  KEY `fk_articles_purchase_unit` (`purchase_unite_mesure_id`),
  CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`unite_mesure_id`) REFERENCES `unites_mesure` (`id`),
  CONSTRAINT `fk_articles_purchase_unit` FOREIGN KEY (`purchase_unite_mesure_id`) REFERENCES `unites_mesure` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (1,'Café','boisson',97,1,5,NULL,1.00,'varie',0.00,'Produit fini',0.00,0.00),(2,'Coca-Cola','boisson',374,1,2,9,24.00,'standard',3500.00,'Produit fini',0.00,0.00),(3,'Croissant','nourriture',91,1,1,8,15.00,'standard',200.00,'Produit fini',0.00,0.00),(4,'Grand Fanta','nourriture',0,0,NULL,NULL,1.00,'standard',500.00,'Produit fini',0.00,0.00),(5,'Banane plantain ','nourriture',-4,1,7,NULL,1.00,'standard',2.50,'Produit fini',0.00,0.00),(7,'American Water 750 ml','boisson',577,1,6,8,12.00,'standard',500.00,'Produit fini',0.00,0.00);
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commandes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `vente_id` int NOT NULL,
  `user_id` int NOT NULL,
  `date_commande` datetime DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('En cours','Terminée','Annulée') DEFAULT 'En cours',
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `vente_id` (`vente_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`vente_id`) REFERENCES `ventes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `commandes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commandes`
--

LOCK TABLES `commandes` WRITE;
/*!40000 ALTER TABLE `commandes` DISABLE KEYS */;
INSERT INTO `commandes` VALUES (1,37,3,'2025-11-28 16:01:10','En cours',NULL,'2025-11-28 15:01:10','2025-11-28 15:01:10'),(2,37,3,'2025-11-28 16:01:23','En cours',NULL,'2025-11-28 15:01:23','2025-11-28 15:01:23'),(3,38,3,'2025-11-28 16:38:56','En cours',NULL,'2025-11-28 15:38:56','2025-11-28 15:38:56');
/*!40000 ALTER TABLE `commandes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `equipements`
--

DROP TABLE IF EXISTS `equipements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `equipements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `description` text,
  `quantite` int DEFAULT '1',
  `etat` enum('Neuf','En service','En réparation','Hors service') DEFAULT 'En service',
  `date_achat` date DEFAULT NULL,
  `valeur` decimal(10,2) DEFAULT NULL,
  `fournisseur` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `quantite_en_service` int NOT NULL DEFAULT '0',
  `quantite_en_reparation` int NOT NULL DEFAULT '0',
  `quantite_hors_service` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `equipements`
--

LOCK TABLES `equipements` WRITE;
/*!40000 ALTER TABLE `equipements` DISABLE KEYS */;
INSERT INTO `equipements` VALUES (1,'Ordinateurss','Xxxx',2,'En service',NULL,NULL,'','2025-11-27 15:53:07','2025-11-28 14:18:18',6,10,3);
/*!40000 ALTER TABLE `equipements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `factures`
--

DROP TABLE IF EXISTS `factures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `factures` (
  `id` int NOT NULL AUTO_INCREMENT,
  `vente_id` int DEFAULT NULL,
  `numero` varchar(50) DEFAULT NULL,
  `montant` decimal(10,2) DEFAULT NULL,
  `statut` enum('impayée','payée','partiel') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero` (`numero`),
  KEY `vente_id` (`vente_id`),
  CONSTRAINT `factures_ibfk_1` FOREIGN KEY (`vente_id`) REFERENCES `ventes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `factures`
--

LOCK TABLES `factures` WRITE;
/*!40000 ALTER TABLE `factures` DISABLE KEYS */;
/*!40000 ALTER TABLE `factures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fonctions`
--

DROP TABLE IF EXISTS `fonctions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fonctions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fonctions`
--

LOCK TABLES `fonctions` WRITE;
/*!40000 ALTER TABLE `fonctions` DISABLE KEYS */;
INSERT INTO `fonctions` VALUES (3,'Administrateur'),(2,'Caissier'),(4,'Magasinier'),(1,'Serveur');
/*!40000 ALTER TABLE `fonctions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventaires`
--

DROP TABLE IF EXISTS `inventaires`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventaires` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date_inventaire` date NOT NULL,
  `responsable_id` int DEFAULT NULL,
  `statut` enum('En cours','Terminé','Annulé') DEFAULT 'En cours',
  `notes` text,
  `conclusion` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `responsable_id` (`responsable_id`),
  CONSTRAINT `inventaires_ibfk_1` FOREIGN KEY (`responsable_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventaires`
--

LOCK TABLES `inventaires` WRITE;
/*!40000 ALTER TABLE `inventaires` DISABLE KEYS */;
INSERT INTO `inventaires` VALUES (1,'2025-11-28',3,'Terminé','','Rien à signaler pour l\'instant\r\n','2025-11-28 16:27:37');
/*!40000 ALTER TABLE `inventaires` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventaires_equipements`
--

DROP TABLE IF EXISTS `inventaires_equipements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventaires_equipements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date_inventaire` date NOT NULL,
  `responsable_id` int DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `responsable_id` (`responsable_id`),
  CONSTRAINT `inventaires_equipements_ibfk_1` FOREIGN KEY (`responsable_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventaires_equipements`
--

LOCK TABLES `inventaires_equipements` WRITE;
/*!40000 ALTER TABLE `inventaires_equipements` DISABLE KEYS */;
INSERT INTO `inventaires_equipements` VALUES (1,'2025-11-28',3,NULL,'2025-11-28 14:23:05');
/*!40000 ALTER TABLE `inventaires_equipements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lignes_approvisionnement`
--

DROP TABLE IF EXISTS `lignes_approvisionnement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lignes_approvisionnement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `approvisionnement_id` int NOT NULL,
  `article_id` int NOT NULL,
  `quantite` int NOT NULL,
  `prix_achat` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `approvisionnement_id` (`approvisionnement_id`),
  KEY `article_id` (`article_id`),
  CONSTRAINT `lignes_approvisionnement_ibfk_1` FOREIGN KEY (`approvisionnement_id`) REFERENCES `approvisionnements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lignes_approvisionnement_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lignes_approvisionnement`
--

LOCK TABLES `lignes_approvisionnement` WRITE;
/*!40000 ALTER TABLE `lignes_approvisionnement` DISABLE KEYS */;
INSERT INTO `lignes_approvisionnement` VALUES (5,4,5,50,1.00),(6,5,5,50,25.00),(7,5,2,25,2.00),(8,5,3,70,150.00),(9,6,7,2,150.00),(10,6,2,120,200.00),(11,6,5,2,500.00),(13,8,7,2,10.00),(14,9,7,50,20.00),(15,9,2,10,20.00);
/*!40000 ALTER TABLE `lignes_approvisionnement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lignes_inventaire`
--

DROP TABLE IF EXISTS `lignes_inventaire`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lignes_inventaire` (
  `id` int NOT NULL AUTO_INCREMENT,
  `inventaire_id` int NOT NULL,
  `article_id` int NOT NULL,
  `stock_theorique` decimal(10,2) NOT NULL,
  `stock_physique` decimal(10,2) NOT NULL,
  `ecart` decimal(10,2) GENERATED ALWAYS AS ((`stock_physique` - `stock_theorique`)) STORED,
  `justification` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventaire_id` (`inventaire_id`),
  KEY `article_id` (`article_id`),
  CONSTRAINT `lignes_inventaire_ibfk_1` FOREIGN KEY (`inventaire_id`) REFERENCES `inventaires` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lignes_inventaire_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lignes_inventaire`
--

LOCK TABLES `lignes_inventaire` WRITE;
/*!40000 ALTER TABLE `lignes_inventaire` DISABLE KEYS */;
INSERT INTO `lignes_inventaire` (`id`, `inventaire_id`, `article_id`, `stock_theorique`, `stock_physique`, `justification`) VALUES (1,1,7,577.00,577.00,NULL),(2,1,5,-4.00,-4.00,NULL);
/*!40000 ALTER TABLE `lignes_inventaire` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lignes_inventaire_equipement`
--

DROP TABLE IF EXISTS `lignes_inventaire_equipement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lignes_inventaire_equipement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `inventaire_id` int NOT NULL,
  `equipement_id` int NOT NULL,
  `equipement_nom` varchar(255) DEFAULT NULL,
  `quantite_en_service` int DEFAULT '0',
  `quantite_en_reparation` int DEFAULT '0',
  `quantite_hors_service` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `inventaire_id` (`inventaire_id`),
  KEY `equipement_id` (`equipement_id`),
  CONSTRAINT `lignes_inventaire_equipement_ibfk_1` FOREIGN KEY (`inventaire_id`) REFERENCES `inventaires_equipements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lignes_inventaire_equipement_ibfk_2` FOREIGN KEY (`equipement_id`) REFERENCES `equipements` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lignes_inventaire_equipement`
--

LOCK TABLES `lignes_inventaire_equipement` WRITE;
/*!40000 ALTER TABLE `lignes_inventaire_equipement` DISABLE KEYS */;
INSERT INTO `lignes_inventaire_equipement` VALUES (1,1,1,'Ordinateurss',6,10,3);
/*!40000 ALTER TABLE `lignes_inventaire_equipement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lignes_vente`
--

DROP TABLE IF EXISTS `lignes_vente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lignes_vente` (
  `id` int NOT NULL AUTO_INCREMENT,
  `commande_id` int NOT NULL,
  `article_id` int NOT NULL,
  `quantite` int NOT NULL DEFAULT '1',
  `prix_unitaire_ht` decimal(10,2) NOT NULL,
  `tva` decimal(5,2) NOT NULL DEFAULT '20.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cout_achat_unitaire` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_article_id` (`article_id`),
  CONSTRAINT `lignes_vente_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lignes_vente`
--

LOCK TABLES `lignes_vente` WRITE;
/*!40000 ALTER TABLE `lignes_vente` DISABLE KEYS */;
INSERT INTO `lignes_vente` VALUES (21,0,5,2,2.50,20.00,'2025-11-22 00:54:10','2025-11-22 00:54:10',0.00),(22,0,1,3,2.50,20.00,'2025-11-22 00:54:43','2025-11-22 00:54:43',0.00),(23,0,2,2,3.00,20.00,'2025-11-22 00:54:43','2025-11-22 00:54:43',0.00),(24,0,3,2,1.50,20.00,'2025-11-22 00:54:43','2025-11-22 00:54:43',0.00),(25,0,5,4,2.50,20.00,'2025-11-23 22:07:35','2025-11-23 22:07:35',0.00),(26,0,2,7,3.00,20.00,'2025-11-23 22:09:49','2025-11-23 22:09:49',0.00),(27,0,2,2,3.00,20.00,'2025-11-23 22:11:31','2025-11-23 22:11:31',0.00),(28,0,2,3,3.00,20.00,'2025-11-24 15:40:13','2025-11-24 15:40:13',0.00),(29,0,2,1,3.00,20.00,'2025-11-24 16:40:47','2025-11-24 16:40:47',0.00),(30,0,2,2,3.00,20.00,'2025-11-24 21:23:55','2025-11-24 21:23:55',0.00),(32,0,3,3,200.00,20.00,'2025-11-24 21:24:42','2025-11-24 21:24:42',0.00),(34,0,7,1,500.00,20.00,'2025-11-24 21:29:29','2025-11-24 21:29:29',0.00),(35,0,3,1,200.00,20.00,'2025-11-24 22:30:51','2025-11-24 22:30:51',0.00),(36,0,2,3,3.00,20.00,'2025-11-25 12:46:12','2025-11-25 12:46:12',0.00),(37,0,2,1,5.00,20.00,'2025-11-25 12:57:16','2025-11-25 12:57:16',0.00),(38,0,2,5,3.00,20.00,'2025-11-25 13:30:40','2025-11-25 13:30:40',0.00),(39,0,2,3,3.00,20.00,'2025-11-25 23:37:35','2025-11-25 23:37:35',0.00),(40,0,7,5,500.00,20.00,'2025-11-25 23:37:59','2025-11-25 23:37:59',0.00),(41,0,2,10,3.00,20.00,'2025-11-25 23:43:37','2025-11-25 23:43:37',0.00),(42,0,7,10,500.00,20.00,'2025-11-25 23:43:51','2025-11-25 23:43:51',0.00),(43,0,3,3,200.00,20.00,'2025-11-26 14:20:28','2025-11-26 14:20:28',0.00),(44,0,2,5,3.00,20.00,'2025-11-26 14:20:35','2025-11-26 14:20:35',0.00),(45,0,2,1,3.00,20.00,'2025-11-26 15:19:47','2025-11-26 15:19:47',0.00),(46,0,7,7,500.00,20.00,'2025-11-26 15:19:58','2025-11-26 15:19:58',0.00),(47,0,2,1,3.00,20.00,'2025-11-26 15:22:22','2025-11-26 15:22:22',0.00),(48,0,7,8,500.00,20.00,'2025-11-26 15:22:32','2025-11-26 15:22:32',0.00),(49,0,2,3,3.00,20.00,'2025-11-26 15:28:23','2025-11-26 15:28:23',0.00),(50,0,7,3,500.00,20.00,'2025-11-26 15:28:31','2025-11-26 15:28:31',0.00),(51,0,7,1,500.00,20.00,'2025-11-26 15:34:23','2025-11-26 15:34:23',0.00),(52,0,7,3,500.00,20.00,'2025-11-26 15:34:31','2025-11-26 15:34:31',0.00),(53,0,2,3,3.00,20.00,'2025-11-26 15:37:11','2025-11-26 15:37:11',0.00),(54,0,5,4,2.50,20.00,'2025-11-28 14:29:46','2025-11-28 14:29:46',0.00),(55,0,7,4,500.00,20.00,'2025-11-28 14:29:46','2025-11-28 14:29:46',0.00),(56,1,2,4,3500.00,20.00,'2025-11-28 15:01:10','2025-11-28 15:01:10',0.00),(57,1,7,3,500.00,20.00,'2025-11-28 15:01:10','2025-11-28 15:01:10',0.00),(58,2,2,2,3500.00,20.00,'2025-11-28 15:01:23','2025-11-28 15:01:23',0.00),(59,2,7,2,500.00,20.00,'2025-11-28 15:01:23','2025-11-28 15:01:23',0.00),(60,3,2,3,3500.00,20.00,'2025-11-28 15:38:56','2025-11-28 15:38:56',0.00);
/*!40000 ALTER TABLE `lignes_vente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personnel`
--

DROP TABLE IF EXISTS `personnel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personnel` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `salaire` decimal(10,2) DEFAULT '0.00',
  `date_embauche` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fonction_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `fk_personnel_fonction` (`fonction_id`),
  CONSTRAINT `fk_personnel_fonction` FOREIGN KEY (`fonction_id`) REFERENCES `fonctions` (`id`),
  CONSTRAINT `personnel_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personnel`
--

LOCK TABLES `personnel` WRITE;
/*!40000 ALTER TABLE `personnel` DISABLE KEYS */;
INSERT INTO `personnel` VALUES (1,3,0.00,'2025-11-27','2025-11-27 11:13:53','2025-11-27 11:13:53',1),(2,2,0.00,'2025-11-27','2025-11-27 11:13:53','2025-11-27 11:13:53',1),(3,4,0.00,'2025-11-27','2025-11-27 11:13:53','2025-11-27 11:20:22',4),(4,1,0.00,'2025-11-27','2025-11-27 11:13:53','2025-11-27 11:14:13',2);
/*!40000 ALTER TABLE `personnel` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tables`
--

DROP TABLE IF EXISTS `tables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tables` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero` int NOT NULL,
  `zone_id` int DEFAULT NULL,
  `capacite` int DEFAULT '4',
  `description` text,
  `nom` varchar(45) DEFAULT NULL,
  `statut` enum('libre','occupée','réservée') DEFAULT 'libre',
  `actif` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_zone_id` (`zone_id`),
  CONSTRAINT `fk_tables_zone_id` FOREIGN KEY (`zone_id`) REFERENCES `zones` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tables`
--

LOCK TABLES `tables` WRITE;
/*!40000 ALTER TABLE `tables` DISABLE KEYS */;
INSERT INTO `tables` VALUES (1,1,1,4,'Près de la fenêtre','GS1','libre',1,'2025-11-21 16:22:09','2025-11-28 15:01:30'),(2,2,1,4,'','Salle 2','libre',1,'2025-11-21 16:22:09','2025-11-25 23:43:56'),(3,3,1,2,'Petite table pour deux','Salle 3','libre',1,'2025-11-21 16:22:09','2025-11-24 22:31:03'),(4,4,1,6,'Table pour groupe','Salle 4','libre',1,'2025-11-21 16:22:09','2025-11-21 16:22:09'),(5,5,1,4,'','Salle 5','libre',1,'2025-11-21 16:22:09','2025-11-21 16:22:09'),(6,6,1,2,'','Salle 6','libre',1,'2025-11-21 16:22:09','2025-11-26 15:22:41'),(7,7,1,8,'Grande table familiale','Salle 7','libre',1,'2025-11-21 16:22:09','2025-11-21 16:22:09'),(8,8,1,4,'','Salle 8','libre',1,'2025-11-21 16:22:09','2025-11-21 16:22:09'),(9,9,1,4,'','Salle 9','libre',1,'2025-11-21 16:22:09','2025-11-21 16:22:09'),(10,10,1,6,'','Salle 10','libre',1,'2025-11-21 16:22:09','2025-11-21 16:22:09'),(41,1,2,4,'Table ensoleillée','Terrasse 1','libre',1,'2025-11-21 16:24:28','2025-11-21 16:24:28'),(42,2,2,2,'','Terrasse 2','libre',1,'2025-11-21 16:24:28','2025-11-21 16:24:28'),(43,3,2,4,'Près du parasol','Terrasse 3','libre',1,'2025-11-21 16:24:28','2025-11-26 14:20:42'),(44,4,2,4,'','Terrasse 4','libre',1,'2025-11-21 16:24:28','2025-11-21 16:24:28'),(45,5,2,6,'Banc pour groupe','Terrasse 5','libre',1,'2025-11-21 16:24:28','2025-11-21 16:24:28'),(46,6,2,2,'','Terrasse 6','libre',1,'2025-11-21 16:24:28','2025-11-21 16:24:28'),(47,7,2,4,'','Terrasse 7','libre',1,'2025-11-21 16:24:28','2025-11-21 16:24:28'),(48,8,2,2,'Mange-debout','Terrasse 8','libre',1,'2025-11-21 16:24:28','2025-11-21 16:24:28'),(49,9,2,4,'','Terrasse 9','libre',1,'2025-11-21 16:24:28','2025-11-21 16:24:28'),(50,10,2,4,'','Terrasse 10','libre',1,'2025-11-21 16:24:28','2025-11-21 16:24:28'),(51,1,3,6,'Salon privé Alpha','VIP 1','libre',1,'2025-11-21 16:24:36','2025-11-26 15:37:17'),(52,2,3,6,'Salon privé Beta','VIP 2','libre',1,'2025-11-21 16:24:36','2025-11-21 16:24:36'),(53,3,3,8,'Grand salon','VIP 3','libre',1,'2025-11-21 16:24:36','2025-11-26 15:20:04'),(54,4,3,4,'','VIP 4','libre',1,'2025-11-21 16:24:36','2025-11-21 16:24:36'),(55,5,3,4,'','VIP 5','libre',1,'2025-11-21 16:24:36','2025-11-21 16:24:36'),(56,6,3,2,'Table discrète','VIP 6','libre',1,'2025-11-21 16:24:36','2025-11-21 16:24:36'),(57,7,3,10,'Table de réception','VIP 7','libre',1,'2025-11-21 16:24:36','2025-11-21 16:24:36'),(58,8,3,6,'','VIP 8','libre',1,'2025-11-21 16:24:36','2025-11-21 16:24:36'),(59,9,3,4,'','VIP 9','libre',1,'2025-11-21 16:24:36','2025-11-21 16:24:36'),(60,10,3,8,'','VIP 10','libre',1,'2025-11-21 16:24:36','2025-11-21 16:24:36'),(61,11,1,4,NULL,'GS11','libre',1,'2025-11-23 23:20:36','2025-11-23 23:20:36');
/*!40000 ALTER TABLE `tables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tarifs`
--

DROP TABLE IF EXISTS `tarifs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tarifs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `article_id` int NOT NULL,
  `zone_id` int NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_article_zone` (`article_id`,`zone_id`),
  KEY `idx_article_id` (`article_id`),
  KEY `idx_zone_id` (`zone_id`),
  CONSTRAINT `fk_tarifs_article_id` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_tarifs_zone_id` FOREIGN KEY (`zone_id`) REFERENCES `zones` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tarifs`
--

LOCK TABLES `tarifs` WRITE;
/*!40000 ALTER TABLE `tarifs` DISABLE KEYS */;
INSERT INTO `tarifs` VALUES (1,1,1,2.50,'2025-11-21 15:57:38','2025-11-21 15:57:38'),(2,1,2,2.50,'2025-11-21 15:57:50','2025-11-21 15:57:50'),(3,1,3,2.50,'2025-11-21 15:57:59','2025-11-21 15:57:59');
/*!40000 ALTER TABLE `tarifs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `unites_mesure`
--

DROP TABLE IF EXISTS `unites_mesure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `unites_mesure` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `symbole` varchar(20) NOT NULL,
  `type` enum('vente','achat') NOT NULL DEFAULT 'vente',
  `description` text,
  `actif` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_nom` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unites_mesure`
--

LOCK TABLES `unites_mesure` WRITE;
/*!40000 ALTER TABLE `unites_mesure` DISABLE KEYS */;
INSERT INTO `unites_mesure` VALUES (1,'Pièce','pce','vente','Article vendu à la pièce',1,'2025-11-20 15:24:41','2025-11-20 15:24:41'),(2,'Litre','L','vente','Volume en litres',1,'2025-11-20 15:24:41','2025-11-20 15:24:41'),(3,'Kilogramme','kg','vente','Poids en kilogrammes',1,'2025-11-20 15:24:41','2025-11-20 15:24:41'),(4,'Gramme','g','vente','Poids en grammes',1,'2025-11-20 15:24:41','2025-11-20 15:24:41'),(5,'Centilitre','cl','vente','Volume en centilitres',1,'2025-11-20 15:24:41','2025-11-20 15:24:41'),(6,'Bouteille','Btl','vente','Article vendu à la bouteille',1,'2025-11-20 15:27:25','2025-11-20 15:27:25'),(7,'Plat','Pl','vente','Plat de nourriture',1,'2025-11-20 15:27:25','2025-11-20 15:27:25'),(8,'Carton','CRT','achat',NULL,1,'2025-11-24 00:06:33','2025-11-24 00:06:33'),(9,'Casier','CS','achat',NULL,1,'2025-11-24 00:06:49','2025-11-24 00:06:49');
/*!40000 ALTER TABLE `unites_mesure` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('vendeuse','gerant') DEFAULT NULL,
  `actif` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Marie Vendeuse','vendeuse@test.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','vendeuse',1),(2,'Paul Gérant','gerant@test.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','gerant',1),(3,'Admin Principal','admin@restaurant.com','$2y$10$g6PYcck/gCEGwAQg.kjxsu9oSQj/IthHg0YpED0igL9ttu4vtr3Ny','gerant',1),(4,'Marie Vendeuse','vendeuse@restaurant.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','vendeuse',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vente_articles`
--

DROP TABLE IF EXISTS `vente_articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vente_articles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `vente_id` int DEFAULT NULL,
  `article_id` int DEFAULT NULL,
  `quantite` int DEFAULT NULL,
  `prix_unitaire` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vente_id` (`vente_id`),
  KEY `article_id` (`article_id`),
  CONSTRAINT `vente_articles_ibfk_1` FOREIGN KEY (`vente_id`) REFERENCES `ventes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `vente_articles_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vente_articles`
--

LOCK TABLES `vente_articles` WRITE;
/*!40000 ALTER TABLE `vente_articles` DISABLE KEYS */;
/*!40000 ALTER TABLE `vente_articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ventes`
--

DROP TABLE IF EXISTS `ventes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ventes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_vente` varchar(50) DEFAULT NULL,
  `table_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `zone` varchar(20) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `statut` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_vente` (`numero_vente`),
  KEY `fk_ventes_users` (`user_id`),
  CONSTRAINT `fk_ventes_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventes`
--

LOCK TABLES `ventes` WRITE;
/*!40000 ALTER TABLE `ventes` DISABLE KEYS */;
INSERT INTO `ventes` VALUES (18,'VTE-2025-0001',1,3,NULL,6.00,'payée','2025-11-22 00:54:10','2025-11-22 00:54:10'),(19,'VTE-2025-0002',43,3,NULL,19.80,'annulee','2025-11-22 00:54:43','2025-11-22 00:54:43'),(20,'VTE-2025-0003',1,3,NULL,12.00,'payee','2025-11-23 22:07:35','2025-11-23 22:07:35'),(21,'VTE-2025-0004',53,3,NULL,25.20,'payee','2025-11-23 22:09:49','2025-11-23 22:09:49'),(22,'VTE-2025-0005',53,3,NULL,7.20,'payee','2025-11-23 22:11:31','2025-11-23 22:11:31'),(23,'VTE-2025-0006',1,3,NULL,10.80,'payee','2025-11-24 15:40:12','2025-11-24 15:40:12'),(24,'VTE-2025-0007',43,3,NULL,3.60,'payee','2025-11-24 16:40:47','2025-11-24 16:40:47'),(25,'VTE-2025-0008',3,3,NULL,0.00,'payee','2025-11-24 21:12:51','2025-11-24 21:12:51'),(26,'VTE-2025-0009',53,3,NULL,10.80,'payee','2025-11-25 12:46:12','2025-11-25 12:46:12'),(27,'VTE-2025-0010',43,3,NULL,6.00,'payee','2025-11-25 12:57:16','2025-11-25 12:57:16'),(28,'VTE-2025-0011',42,3,NULL,18.00,'payee','2025-11-25 13:30:40','2025-11-25 13:30:40'),(29,'VTE-2025-0012',1,3,NULL,3010.80,'payee','2025-11-25 23:37:35','2025-11-25 23:37:35'),(30,'VTE-2025-0013',2,3,NULL,6036.00,'payee','2025-11-25 23:43:37','2025-11-25 23:43:37'),(31,'VTE-2025-0014',43,3,NULL,738.00,'payee','2025-11-26 14:20:28','2025-11-26 14:20:28'),(32,'VTE-2025-0015',53,3,NULL,4203.60,'payee','2025-11-26 15:19:47','2025-11-26 15:19:47'),(33,'VTE-2025-0016',6,3,NULL,4803.60,'payee','2025-11-26 15:22:09','2025-11-26 15:22:09'),(34,'VTE-2025-0017',1,3,NULL,1810.80,'payee','2025-11-26 15:28:11','2025-11-26 15:28:11'),(35,'VTE-2025-0018',51,3,NULL,2410.80,'payee','2025-11-26 15:34:03','2025-11-26 15:34:03'),(37,'VTE-2025-0020',1,3,NULL,28200.00,'payee','2025-11-28 14:33:21','2025-11-28 14:33:21'),(38,'VTE-2025-0021',2,3,NULL,12600.00,'payee','2025-11-28 15:38:56','2025-11-28 15:38:56');
/*!40000 ALTER TABLE `ventes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zones`
--

DROP TABLE IF EXISTS `zones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prefixe` varchar(10) DEFAULT NULL,
  `description` text,
  `actif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zones`
--

LOCK TABLES `zones` WRITE;
/*!40000 ALTER TABLE `zones` DISABLE KEYS */;
INSERT INTO `zones` VALUES (1,'Grande Salle','GS','Zone principale du restaurant',1,'2025-11-21 15:48:32'),(2,'Terrasse','TRS','Zone extérieure',1,'2025-11-21 15:48:32'),(3,'VIP','VIP','Zone réservée pour les événements privés',1,'2025-11-21 15:48:32');
/*!40000 ALTER TABLE `zones` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-20 12:09:44
