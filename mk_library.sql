-- MySQL dump 10.13  Distrib 9.3.0, for macos14.7 (arm64)
--
-- Host: localhost    Database: mk_library
-- ------------------------------------------------------
-- Server version	9.3.0

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
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `books` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `author` varchar(150) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `quantity` int DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `books`
--

LOCK TABLES `books` WRITE;
/*!40000 ALTER TABLE `books` DISABLE KEYS */;
INSERT INTO `books` VALUES (2,'Clean Code','Robert C. Martin','Programming',1,'2025-09-18 09:10:36'),(6,'The Great Gatsby','F. Scott Fitzgerald','Fiction',5,'2025-09-19 06:08:59'),(7,'1984','George Orwell','Dystopian',4,'2025-09-19 06:08:59'),(8,'To Kill a Mockingbird','Harper Lee','Classic',6,'2025-09-19 06:08:59'),(9,'Clean Code','Robert C. Martin','Programming',3,'2025-09-19 06:08:59'),(10,'The Pragmatic Programmer','Andrew Hunt','Programming',2,'2025-09-19 06:08:59'),(11,'Harry Potter and the Sorcerer\'s Stone','J.K. Rowling','Fantasy',10,'2025-09-19 06:08:59'),(12,'The Hobbit','J.R.R. Tolkien','Fantasy',7,'2025-09-19 06:08:59'),(13,'Pride and Prejudice','Jane Austen','Classic',4,'2025-09-19 06:08:59'),(14,'The Catcher in the Rye','J.D. Salinger','Classic',5,'2025-09-19 06:08:59'),(15,'JavaScript: The Good Parts','Douglas Crockford','Programming',3,'2025-09-19 06:08:59'),(16,'Eloquent JavaScript','Marijn Haverbeke','Programming',2,'2025-09-19 06:08:59'),(17,'Introduction to Algorithms','Thomas H. Cormen','Programming',2,'2025-09-19 06:08:59'),(18,'The Lord of the Rings','J.R.R. Tolkien','Fantasy',6,'2025-09-19 06:08:59'),(19,'The Alchemist','Paulo Coelho','Fiction',8,'2025-09-19 06:08:59'),(20,'Thinking, Fast and Slow','Daniel Kahneman','Psychology',5,'2025-09-19 06:08:59');
/*!40000 ALTER TABLE `books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `borrows`
--

DROP TABLE IF EXISTS `borrows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `borrows` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `book_id` int NOT NULL,
  `borrow_date` date NOT NULL DEFAULT (curdate()),
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `status` enum('borrowed','returned') DEFAULT 'borrowed',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `book_id` (`book_id`),
  CONSTRAINT `borrows_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `borrows_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `borrows`
--

LOCK TABLES `borrows` WRITE;
/*!40000 ALTER TABLE `borrows` DISABLE KEYS */;
INSERT INTO `borrows` VALUES (3,9,2,'2025-09-19','2025-09-26',NULL,'borrowed','2025-09-19 05:53:51'),(6,11,2,'2025-09-19','2025-09-26',NULL,'borrowed','2025-09-19 06:01:00');
/*!40000 ALTER TABLE `borrows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (9,'admin','mostofa@gmail.com','$2y$12$8Cl/J.iWLZakEQDlwfWPAOQM548Sk0grESxoDIUJmu2x2xiR8.Oxe','admin','2025-09-19 03:58:57'),(10,'mostofa11','mostofa.kamal15@gmail.com','$2y$12$Ned7efzuviy/XKq8gFwal.PIZQJu/.md0QJoTFU80efgla.2LnL.2','user','2025-09-19 04:44:30'),(11,'mostofa33','mostofa.kamal16@gmail.com','$2y$12$E19hRRKoT0uwxkLVak/crOEvYq/uYcOQ7S7.ZzDc45Pj4ZOTYHz96','user','2025-09-19 04:59:29');
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

-- Dump completed on 2025-09-19 19:13:18
