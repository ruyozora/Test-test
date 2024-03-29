-- MySQL dump 10.13  Distrib 5.5.21, for Win32 (x86)
--
-- Host: localhost    Database: tt
-- ------------------------------------------------------
-- Server version	5.5.21

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
-- Table structure for table `assortment`
--

DROP TABLE IF EXISTS `assortment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `assortment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storeId` int(11) DEFAULT NULL,
  `productId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `storeId` (`storeId`),
  KEY `productId` (`productId`),
  CONSTRAINT `assortment_ibfk_1` FOREIGN KEY (`storeId`) REFERENCES `store` (`id`),
  CONSTRAINT `assortment_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `product` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assortment`
--

LOCK TABLES `assortment` WRITE;
/*!40000 ALTER TABLE `assortment` DISABLE KEYS */;
INSERT INTO `assortment` VALUES (1,1,1),(2,1,2),(3,1,4),(4,1,6),(5,1,7),(6,1,8),(7,2,3),(8,2,5),(9,2,9);
/*!40000 ALTER TABLE `assortment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `basket`
--

DROP TABLE IF EXISTS `basket`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `basket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personId` int(11) DEFAULT NULL,
  `productId` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `personId` (`personId`),
  KEY `productId` (`productId`),
  CONSTRAINT `basket_ibfk_1` FOREIGN KEY (`personId`) REFERENCES `person` (`id`),
  CONSTRAINT `basket_ibfk_2` FOREIGN KEY (`productId`) REFERENCES `product` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `basket`
--

LOCK TABLES `basket` WRITE;
/*!40000 ALTER TABLE `basket` DISABLE KEYS */;
INSERT INTO `basket` VALUES (1,1,1),(2,1,2),(3,1,6),(4,1,8),(5,2,3),(6,2,5),(7,2,9),(8,3,1),(9,4,4),(10,4,8),(11,18,3),(12,18,5);
/*!40000 ALTER TABLE `basket` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext,
  `type` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `person`
--

LOCK TABLES `person` WRITE;
/*!40000 ALTER TABLE `person` DISABLE KEYS */;
INSERT INTO `person` VALUES (1,'Marisa Kirisame','adult'),(2,'Louise the Zero','adult'),(3,'Tristana, the Megling Gunner','veteran'),(4,'Sona, the Faceroll support','veteran'),(5,'├РтАЩ├Р┬╖├СтВм├Р┬╛├С┬Б├Р┬╗├СтА╣├Р┬╣ #1','adult'),(6,'├РтАЩ├Р┬╖├СтВм├Р┬╛├С┬Б├Р┬╗├СтА╣├Р┬╣ #2','adult'),(7,'├РтАЩ├Р┬╖├СтВм├Р┬╛├С┬Б├Р┬╗├СтА╣├Р┬╣ #3','adult'),(8,'├РтАЩ├Р┬╖├СтВм├Р┬╛├С┬Б├Р┬╗├СтА╣├Р┬╣ #4','adult'),(9,'├РтАЩ├Р┬╖├СтВм├Р┬╛├С┬Б├Р┬╗├СтА╣├Р┬╣ #5','adult'),(10,'├РтАЩ├Р┬╖├СтВм├Р┬╛├С┬Б├Р┬╗├СтА╣├Р┬╣ #6','adult'),(11,'├РтАЩ├Р┬╖├СтВм├Р┬╛├С┬Б├Р┬╗├СтА╣├Р┬╣ #7','adult'),(12,'├РтАЩ├Р┬╖├СтВм├Р┬╛├С┬Б├Р┬╗├СтА╣├Р┬╣ #8','adult'),(13,'├РтАЩ├Р┬╖├СтВм├Р┬╛├С┬Б├Р┬╗├СтА╣├Р┬╣ #9','adult'),(14,'├РтАЩ├Р┬╖├СтВм├Р┬╛├С┬Б├Р┬╗├СтА╣├Р┬╣ #10','adult'),(15,'├РтАЩ├Р┬╖├СтВм├Р┬╛├С┬Б├Р┬╗├СтА╣├Р┬╣ #11','adult'),(16,'├РтАЩ├Р┬╡├СтАЪ├Р┬╡├СтВм├Р┬░├Р┬╜ #1','veteran'),(17,'├РтАЩ├Р┬╡├СтАЪ├Р┬╡├СтВм├Р┬░├Р┬╜ #2','veteran'),(18,'├РтАЩ├Р┬╡├СтАЪ├Р┬╡├СтВм├Р┬░├Р┬╜ #3','veteran');
/*!40000 ALTER TABLE `person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (1,'├Р┼У├С┬П├С┬Б├Р┬╛'),(2,'├Р┬е├Р┬╗├Р┬╡├Р┬▒'),(3,'├Р┼У├СтА╣├Р┬╗├Р┬╛'),(4,'├Р┼У├Р┬╛├Р┬╗├Р┬╛├Р┬║├Р┬╛'),(5,'├Р┼У├Р┬╛├Р┬╗├Р┬╛├СтАЪ├Р┬╛├Р┬║'),(6,'├Р┬б├Р┬░├СтАж├Р┬░├СтВм'),(7,'├Р┼╕├Р┬╡├СтАб├Р┬╡├Р┬╜├С┼Т├Р┬╡'),(8,'├Р┼б├Р┬╛├Р┬╜├СтАЮ├Р┬╡├СтАЪ├СтА╣'),(9,'├РтАЬ├Р┬▓├Р┬╛├Р┬╖├Р┬┤├Р┬╕'),(10,'├Р┬Э├Р┬╡├Р┬╕├С┬Б├Р┬┐├Р┬╛├Р┬╗├С┼Т├Р┬╖├С╞Т├Р┬╡├Р┬╝├СтА╣├Р┬╣ ├СтАЪ├Р┬╛├Р┬▓├Р┬░├СтВм');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `queue`
--

DROP TABLE IF EXISTS `queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `storeId` int(11) DEFAULT NULL,
  `personId` int(11) DEFAULT NULL,
  `personPosition` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `storeId` (`storeId`),
  KEY `personId` (`personId`),
  CONSTRAINT `queue_ibfk_1` FOREIGN KEY (`storeId`) REFERENCES `store` (`id`),
  CONSTRAINT `queue_ibfk_2` FOREIGN KEY (`personId`) REFERENCES `person` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `queue`
--

LOCK TABLES `queue` WRITE;
/*!40000 ALTER TABLE `queue` DISABLE KEYS */;
INSERT INTO `queue` VALUES (1,1,1,1),(2,2,2,4),(3,1,3,2),(4,1,4,3),(5,1,5,4),(6,1,6,5),(7,1,7,6),(8,1,8,7),(9,1,9,8),(10,1,10,9),(11,1,11,10),(12,2,12,5),(13,2,13,6),(14,2,16,1),(15,2,17,2),(17,2,18,3);
/*!40000 ALTER TABLE `queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `store`
--

DROP TABLE IF EXISTS `store`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext,
  `type` tinytext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store`
--

LOCK TABLES `store` WRITE;
/*!40000 ALTER TABLE `store` DISABLE KEYS */;
INSERT INTO `store` VALUES (1,'├Р┼╕├СтВм├Р┬╛├Р┬┤├С╞Т├Р┬║├СтАЪ├Р┬╛├Р┬▓├СтА╣├Р┬╣ ├Р┬╝├Р┬░├Р┬│├Р┬░├Р┬╖├Р┬╕├Р┬╜','grocery'),(2,'├Р┬е├Р┬╛├Р┬╖├С┬П├Р┬╣├С┬Б├СтАЪ├Р┬▓├Р┬╡├Р┬╜├Р┬╜├СтА╣├Р┬╣ ├Р┬╝├Р┬░├Р┬│├Р┬░├Р┬╖├Р┬╕├Р┬╜','hardware');
/*!40000 ALTER TABLE `store` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-04-13 11:15:37
