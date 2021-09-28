-- MariaDB dump 10.19  Distrib 10.6.4-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: phalcon
-- ------------------------------------------------------
-- Server version	10.6.4-MariaDB-1:10.6.4+maria~focal

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
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `shippingDate` timestamp NULL DEFAULT NULL,
  `orderCode` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_id_uindex` (`id`),
  KEY `products_users_id_fk` (`user_id`),
  CONSTRAINT `products_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,1,'Turkey',NULL,'1a2b3c'),(2,1,2,'Turkey','2021-09-27 16:30:33','2d3e4r'),(3,2,3,'Turkey',NULL,'3f4f5g'),(4,2,4,'Turkey','2021-09-27 16:31:12','4r3e21'),(5,3,5,'Turkey',NULL,'34hk5j'),(6,3,6,'Turkey','2021-09-27 16:31:15','2j349d'),(7,4,7,'Turkey','2021-09-27 16:31:17','34n2ij'),(8,4,8,'Turkey',NULL,'j23ı45'),(9,5,9,'Turkey','2021-09-27 16:31:23','b53m4n'),(10,5,10,'Turkey',NULL,'5uy34u');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_uindex` (`login`),
  UNIQUE KEY `users_id_uindex` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'customer1@mail.com','$2y$10$dG1VR3JzOUtzbU9wU0Z6OO46dTtmyXht0286KS/41jL.nukm0uIfy','2021-09-27 13:29:47'),(2,'customer2@mail.com','$2y$10$dG1VR3JzOUtzbU9wU0Z6OO46dTtmyXht0286KS/41jL.nukm0uIfy','2021-09-27 13:29:47'),(3,'customer3@mail.com','$2y$10$dG1VR3JzOUtzbU9wU0Z6OO46dTtmyXht0286KS/41jL.nukm0uIfy','2021-09-27 13:29:47'),(4,'customer4@mail.com','$2y$10$dG1VR3JzOUtzbU9wU0Z6OO46dTtmyXht0286KS/41jL.nukm0uIfy','2021-09-27 13:29:47'),(5,'customer5@mail.com','$2y$10$dG1VR3JzOUtzbU9wU0Z6OO46dTtmyXht0286KS/41jL.nukm0uIfy','2021-09-27 13:29:47');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-09-27 13:40:30
