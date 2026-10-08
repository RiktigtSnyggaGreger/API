-- MariaDB dump 10.20-11.8.9-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: min_api
-- ------------------------------------------------------
-- Server version	11.8.9-MariaDB-ubu2404-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titel` varchar(255) NOT NULL,
  `sprak` varchar(10) NOT NULL,
  `tag_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `page_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `page` WRITE;
/*!40000 ALTER TABLE `page` DISABLE KEYS */;
INSERT INTO `page` VALUES
(2,'Kom igång med Docker och DDEV','sv',5),
(4,'Framtidens AI-verktyg för utvecklare','sv',2),
(5,'Kontakta support och rådgivning','sv',3),
(6,'Kundcase: Nordisk Butik AB','sv',6),
(7,'Ny version lanserad: Version 2.0','sv',1),
(8,'Optimera dina SQL-frågor','sv',5),
(9,'Webbutveckling från grunden','sv',5),
(10,'Cybersäkerhet för småföretag','sv',2),
(11,'Kundcase: Göteborgs Logistik','sv',6),
(13,'Min Nya Sida','sv',1),
(15,'test','sv',1),
(18,'Hej','sv',18);
/*!40000 ALTER TABLE `page` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `page_content`
--

DROP TABLE IF EXISTS `page_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `content_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`content_json`)),
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_id` (`page_id`),
  CONSTRAINT `page_content_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `page` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_content`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `page_content` WRITE;
/*!40000 ALTER TABLE `page_content` DISABLE KEYS */;
INSERT INTO `page_content` VALUES
(2,2,'{\n  \"hero_bild\": \"/assets/img/guides/docker-ddev.png\",\n  \"lastid_minuter\": 7,\n  \"rubrik\": {\n    \"sv\": \"Komplett guide: Utveckla lokalt med Docker & DDEV\",\n    \"en\": \"Complete Guide: Local Development with Docker & DDEV\",\n    \"de\": \"Vollständiger Leitfaden: Lokale Entwicklung mit Docker & DDEV\"\n  },\n  \"introduktion\": {\n    \"sv\": \"Slipp problem med olika lokala PHP-versioner genom att containerisera din miljö.\",\n    \"en\": \"Avoid local PHP version conflicts by containerizing your workflow.\",\n    \"de\": \"Vermeiden Sie lokale PHP-Konflikte durch die Containerisierung Ihrer Umgebung.\"\n  },\n  \"steg\": [\n    {\n      \"ordning\": 1,\n      \"titel\": {\n        \"sv\": \"Installera Docker Desktop\",\n        \"en\": \"Install Docker Desktop\",\n        \"de\": \"Docker Desktop installieren\"\n      },\n      \"skarmdump\": \"/assets/img/guides/docker-settings.png\"\n    },\n    {\n      \"ordning\": 2,\n      \"titel\": {\n        \"sv\": \"Initiera DDEV\",\n        \"en\": \"Initialize DDEV\",\n        \"de\": \"DDEV initialisieren\"\n      },\n      \"skarmdump\": \"/assets/img/guides/terminal-config.png\"\n    }\n  ]\n}'),
(4,4,'{\n  \"hero_bild\": \"/assets/img/tech/ai-cloud-network.jpg\",\n  \"skribent\": \"Marcus Ek\",\n  \"publicerad\": \"2026-03-25\",\n  \"rubrik\": {\n    \"sv\": \"Praktisk maskininlärning för backend-utvecklare\",\n    \"en\": \"Applied Machine Learning for Backend Engineers\",\n    \"de\": \"Angewandtes maschinelles Lernen für Backend-Entwickler\"\n  },\n  \"sammanfattning\": {\n    \"sv\": \"Hur du integrerar analysmodeller direkt i dina PHP-endpoints.\",\n    \"en\": \"How to integrate inference models directly into your PHP endpoints.\",\n    \"de\": \"So integrieren Sie Analysemodelle direkt in Ihre PHP-Endpunkte.\"\n  }\n}'),
(5,5,'{\n  \"hero_bild\": \"/assets/img/contact/support-desk.jpg\",\n  \"kontaktinformation\": {\n    \"epost\": \"kundservice@webbstudion.se\",\n    \"telefon\": \"031-789 45 00\",\n    \"karta_bild\": \"/assets/img/contact/karta-kontor.png\"\n  },\n  \"rubrik\": {\n    \"sv\": \"Kontakta oss\",\n    \"en\": \"Contact Us\",\n    \"de\": \"Kontaktieren Sie uns\"\n  },\n  \"oppettider\": {\n    \"sv\": \"Vardagar 08:30 - 16:30\",\n    \"en\": \"Monday - Friday 08:30 - 16:30\",\n    \"de\": \"Montag - Freitag 08:30 - 16:30\"\n  }\n}'),
(6,6,'{\n  \"hero_bild\": \"/assets/img/cases/nordisk-butik-hero.jpg\",\n  \"kund\": \"Nordisk Butik AB\",\n  \"ar\": 2025,\n  \"rubrik\": {\n    \"sv\": \"Case: 90% snabbare laddtid för Nordisk Butik AB\",\n    \"en\": \"Case Study: 90% Faster Load Time for Nordisk Butik AB\",\n    \"de\": \"Fallstudie: 90% schnellere Ladezeit für Nordisk Butik AB\"\n  },\n  \"utmaning\": {\n    \"sv\": \"Långsamma svarstider orsakade av ostrukturerade databasfrågor.\",\n    \"en\": \"High response latencies caused by unoptimized database queries.\",\n    \"de\": \"Hohe Antwortzeiten durch unoptimierte Datenbankabfragen.\"\n  }\n}'),
(7,7,'{\n  \"hero_bild\": \"/assets/img/news/update-v2.jpg\",\n  \"datum\": \"2026-02-15\",\n  \"rubrik\": {\n    \"sv\": \"Release Notes: Version 2.0 är här\",\n    \"en\": \"Release Notes: Version 2.0 is Here\",\n    \"de\": \"Versionshinweise: Version 2.0 ist da\"\n  },\n  \"meddelande\": {\n    \"sv\": \"Vi har lanserat inbyggt stöd för flerspråkig JSON-data och förbättrad prestanda.\",\n    \"en\": \"We have shipped native multilingual JSON payload support and optimized caching.\",\n    \"de\": \"Wir haben native mehrsprachige JSON-Unterstützung und optimiertes Caching veröffentlicht.\"\n  }\n}'),
(8,8,'{\n  \"hero_bild\": \"/assets/img/guides/database-index.jpg\",\n  \"lastid_minuter\": 6,\n  \"rubrik\": {\n    \"sv\": \"Optimera dina SQL-frågor: Från minuter till millisekunder\",\n    \"en\": \"Optimize SQL Queries: From Minutes to Milliseconds\",\n    \"de\": \"SQL-Abfragen optimieren: Von Minuten zu Millisekunden\"\n  },\n  \"tips\": {\n    \"sv\": \"Sätt index på främmande nycklar och undvik SELECT *.\",\n    \"en\": \"Add indexes to foreign keys and avoid SELECT *.\",\n    \"de\": \"Indizieren Sie Fremdschlüssel und vermeiden Sie SELECT *.\"\n  }\n}'),
(9,9,'{\n  \"hero_bild\": \"/assets/img/guides/coding-screen.jpg\",\n  \"skribent\": \"Jonas Berg\",\n  \"rubrik\": {\n    \"sv\": \"Webbutveckling från grunden: HTML, CSS och PHP\",\n    \"en\": \"Full-Stack Fundamentals: HTML, CSS, and PHP\",\n    \"de\": \"Grundlagen der Webentwicklung: HTML, CSS und PHP\"\n  },\n  \"beskrivning\": {\n    \"sv\": \"Lär dig hur du strukturerar semantisk kod och kopplar den till ett modernt API.\",\n    \"en\": \"Learn how to structure semantic markup and bind it to a modern REST backend.\",\n    \"de\": \"Lernen Sie, wie Sie semantischen Code strukturieren und mit einem modernen REST-Backend verbinden.\"\n  }\n}'),
(10,10,'{\n  \"hero_bild\": \"/assets/img/tech/security-shield.jpg\",\n  \"lastid_minuter\": 4,\n  \"rubrik\": {\n    \"sv\": \"Enkla steg till robust IT-säkerhet\",\n    \"en\": \"Actionable Steps Toward Strong IT Security\",\n    \"de\": \"Wichtige Schritte für robuste IT-Sicherheit\"\n  },\n  \"princip\": {\n    \"sv\": \"Använd alltid parametriserade frågor för att förhindra SQL-injektioner.\",\n    \"en\": \"Always enforce parameterized queries to eliminate SQL injection vulnerabilities.\",\n    \"de\": \"Verwenden Sie immer parametrisierte Abfragen, um SQL-Injection zu verhindern.\"\n  }\n}'),
(11,11,'{\n  \"hero_bild\": \"/assets/img/cases/logistics-trucks.jpg\",\n  \"kund\": \"Göteborgs Logistik & Transport\",\n  \"ar\": 2026,\n  \"rubrik\": {\n    \"sv\": \"Case: Realtidsspårning för Göteborgs Logistik\",\n    \"en\": \"Case Study: Real-Time Fleet Tracking for Göteborg Logistics\",\n    \"de\": \"Fallstudie: Echtzeit-Flottenverfolgung für Göteborg Logistik\"\n  },\n  \"resultat\": {\n    \"sv\": \"Svarstider under 50 ms för över 5 000 anrop per minut.\",\n    \"en\": \"Sub-50ms latencies handling over 5,000 requests per minute.\",\n    \"de\": \"Latenzen unter 50 ms bei über 5.000 Anfragen pro Minute.\"\n  }\n}'),
(13,13,'{\"rubrik\":{\"sv\":\"Svensk rubrik\"},\"hero_bild\":\"/assets/img/default.jpg\"}'),
(15,15,'{\"text\":{\"sv\":\"Hej\",\"en\":\"Hello\",\"de\":\"Hallo\"}}'),
(18,18,'{\"text\":{\"sv\":\"Hej\",\"en\":\"Hello\",\"de\":\"Hallo\"}}');
/*!40000 ALTER TABLE `page_content` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `tag`
--

DROP TABLE IF EXISTS `tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `namn` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `namn` (`namn`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tag`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `tag` WRITE;
/*!40000 ALTER TABLE `tag` DISABLE KEYS */;
INSERT INTO `tag` VALUES
(11,'apputveckling'),
(18,'blogg'),
(12,'design'),
(16,'faq'),
(5,'Guider'),
(24,'hallbarhet'),
(21,'integritetspolicy'),
(17,'karriar'),
(9,'kontakt'),
(20,'kundcase'),
(1,'Nyheter'),
(3,'Om oss'),
(8,'om-oss'),
(14,'portfolio'),
(25,'press'),
(15,'priser'),
(6,'Projekt'),
(13,'seo'),
(7,'startsida'),
(23,'support'),
(19,'team'),
(2,'Teknik'),
(4,'tjanster'),
(22,'villkor'),
(10,'webbutveckling');
/*!40000 ALTER TABLE `tag` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-10-08 13:36:29
