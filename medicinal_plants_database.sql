CREATE DATABASE IF NOT EXISTS medicinal_plants_db;
USE medicinal_plants_db;

-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: medicinal_plants_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','2026-06-27 15:40:20');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` enum('family','medicinal_use') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name_type` (`name`,`type`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (69,'Adaptogen','medicinal_use'),(8,'aids digestion.','medicinal_use'),(17,'Amaryllidaceae','family'),(15,'anti-inflammatory','medicinal_use'),(97,'anti-inflammatory.','medicinal_use'),(14,'Anti-nausea','medicinal_use'),(57,'antibacterial','medicinal_use'),(19,'antimicrobial','medicinal_use'),(42,'antioxidant','medicinal_use'),(56,'Antitussive (cough relief)','medicinal_use'),(71,'anxiety reduction','medicinal_use'),(30,'Anxiety relief','medicinal_use'),(46,'anxiolytic.','medicinal_use'),(77,'Apiaceae','family'),(25,'Araliaceae','family'),(108,'Arecaceae','family'),(5,'Asphodelaceae','family'),(9,'Asteraceae','family'),(109,'Benign prostatic hyperplasia (BPH) symptoms','medicinal_use'),(98,'Boraginaceae','family'),(99,'Bruises','medicinal_use'),(44,'Caprifoliaceae','family'),(18,'Cardiovascular health','medicinal_use'),(114,'cardiovascular support.','medicinal_use'),(117,'chronic venous insufficiency','medicinal_use'),(37,'Cognitive enhancement','medicinal_use'),(28,'cognitive enhancement.','medicinal_use'),(52,'Cognitive stimulant','medicinal_use'),(118,'cognitive support.','medicinal_use'),(49,'detoxification','medicinal_use'),(53,'digestion aid','medicinal_use'),(67,'digestion aid.','medicinal_use'),(2,'Digestive aid','medicinal_use'),(96,'digestive ulcers (DGL)','medicinal_use'),(64,'Diuretic','medicinal_use'),(26,'Energy booster','medicinal_use'),(94,'Fabaceae','family'),(89,'fever reduction.','medicinal_use'),(66,'gallbladder support','medicinal_use'),(80,'gas','medicinal_use'),(36,'Ginkgoaceae','family'),(54,'hair growth stimulation.','medicinal_use'),(4,'headache relief.','medicinal_use'),(112,'Heart failure (mild)','medicinal_use'),(113,'high blood pressure','medicinal_use'),(33,'Hypericaceae','family'),(93,'immune support.','medicinal_use'),(22,'Immune system support','medicinal_use'),(39,'improves circulation.','medicinal_use'),(61,'improves cognitive function','medicinal_use'),(72,'improves stamina.','medicinal_use'),(45,'Insomnia relief','medicinal_use'),(102,'joint pain (topical use).','medicinal_use'),(43,'joint pain relief.','medicinal_use'),(1,'Lamiaceae','family'),(65,'liver','medicinal_use'),(48,'Liver protectant','medicinal_use'),(20,'lowers blood pressure.','medicinal_use'),(38,'memory support','medicinal_use'),(104,'Migraine prevention','medicinal_use'),(10,'Mild sedative','medicinal_use'),(34,'Mild to moderate depression','medicinal_use'),(85,'minor burns.','medicinal_use'),(35,'mood swings.','medicinal_use'),(101,'muscle','medicinal_use'),(23,'prevention','medicinal_use'),(75,'promotes sleep','medicinal_use'),(106,'Ranunculaceae','family'),(88,'reduces bleeding','medicinal_use'),(62,'reduces menopausal hot flashes.','medicinal_use'),(107,'Relief of menopausal symptoms like hot flashes.','medicinal_use'),(11,'relieves anxiety','medicinal_use'),(79,'relieves bloating','medicinal_use'),(3,'relieves irritable bowel syndrome (IBS)','medicinal_use'),(58,'respiratory support.','medicinal_use'),(111,'Rosaceae','family'),(84,'skin inflammation','medicinal_use'),(7,'skin irritation','medicinal_use'),(31,'sleep aid','medicinal_use'),(68,'Solanaceae','family'),(6,'Soothes burns','medicinal_use'),(76,'soothes cold sores (topical).','medicinal_use'),(81,'soothes colic.','medicinal_use'),(95,'Soothes sore throats','medicinal_use'),(60,'Sore throat relief','medicinal_use'),(100,'sprains','medicinal_use'),(70,'stress','medicinal_use'),(27,'stress reducer','medicinal_use'),(92,'stress relief','medicinal_use'),(50,'supports liver conditions.','medicinal_use'),(24,'treatment of common cold.','medicinal_use'),(110,'urinary tract health.','medicinal_use'),(83,'Wound healing','medicinal_use'),(32,'wound healing.','medicinal_use'),(13,'Zingiberaceae','family');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compounds`
--

DROP TABLE IF EXISTS `compounds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compounds` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compounds`
--

LOCK TABLES `compounds` WRITE;
/*!40000 ALTER TABLE `compounds` DISABLE KEYS */;
INSERT INTO `compounds` VALUES (6,'Acemannan'),(58,'Achilleine'),(14,'Ajoene'),(47,'Alkaloids'),(16,'Alkamides'),(67,'Allantoin'),(13,'Allicin'),(4,'Aloin'),(52,'Anethole'),(7,'Apigenin'),(82,'Asiatic acid'),(80,'Asiaticoside'),(26,'Bilobalide'),(8,'Bisabolol'),(42,'Camphor'),(36,'Carnosic acid'),(39,'Carvacrol'),(9,'Chamazulene'),(17,'Cichoric acid'),(72,'Cimicifugoside)'),(50,'Citronellal'),(28,'Curcumin'),(29,'Demethoxycurcumin'),(15,'Diallyl disulfide'),(18,'Echinacoside'),(5,'Emodin'),(54,'Estragole'),(37,'Eucalyptol'),(61,'Eugenol'),(55,'Faradiol esters'),(74,'Fatty acids (Lauric acid'),(53,'Fenchone'),(27,'Flavonoids'),(73,'Formononetin'),(51,'Geraniol'),(10,'Gingerol'),(25,'Ginkgolides'),(19,'Ginsenosides'),(65,'Glabridin'),(64,'Glycyrrhizin'),(24,'Hyperforin'),(23,'Hypericin'),(44,'Inulin'),(31,'Isovaleric acid'),(21,'Linalool'),(22,'Linalyl acetate'),(66,'Liquiritin'),(81,'Madecassoside'),(1,'Menthol'),(2,'Menthone'),(75,'Oleic acid)'),(77,'Oligomeric proanthocyanidins (OPCs)'),(20,'Panaxosides'),(70,'Parthenolide'),(76,'Phytosterols (Beta-sitosterol)'),(69,'Pyrrolizidine alkaloids'),(79,'Quercetin'),(3,'Rosmarinic acid'),(48,'Saponins'),(45,'Sesquiterpene lactones'),(11,'Shogaol'),(34,'Silychristin)'),(33,'Silydianin'),(32,'Silymarin (Silybin'),(43,'Taraxasterol'),(40,'Thujone'),(38,'Thymol'),(71,'Triterpene glycosides (Actein'),(57,'Triterpenoids'),(62,'Ursolic acid'),(30,'Valerenic acid'),(78,'Vitexin'),(46,'Withanolides'),(12,'Zingerone');
/*!40000 ALTER TABLE `compounds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plant_category`
--

DROP TABLE IF EXISTS `plant_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plant_category` (
  `plant_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`plant_id`,`category_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `plant_category_ibfk_1` FOREIGN KEY (`plant_id`) REFERENCES `plants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `plant_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plant_category`
--

LOCK TABLES `plant_category` WRITE;
/*!40000 ALTER TABLE `plant_category` DISABLE KEYS */;
INSERT INTO `plant_category` VALUES (1,1),(1,2),(1,3),(1,4),(2,5),(2,6),(2,7),(2,8),(3,8),(3,9),(3,10),(3,11),(4,8),(4,13),(4,14),(4,15),(5,17),(5,18),(5,19),(5,20),(6,9),(6,22),(6,23),(6,24),(7,25),(7,26),(7,27),(7,28),(8,1),(8,30),(8,31),(8,32),(9,33),(9,34),(9,35),(10,36),(10,37),(10,38),(10,39),(11,13),(11,15),(11,42),(11,43),(12,44),(12,45),(12,46),(13,9),(13,48),(13,49),(13,50),(14,1),(14,52),(14,53),(14,54),(15,1),(15,56),(15,57),(15,58),(16,1),(16,60),(16,61),(16,62),(17,9),(17,64),(17,65),(17,66),(17,67),(18,68),(18,69),(18,70),(18,71),(18,72),(19,1),(19,30),(19,75),(19,76),(20,2),(20,77),(20,79),(20,80),(20,81),(21,9),(21,83),(21,84),(21,85),(22,9),(22,83),(22,88),(22,89),(23,1),(23,69),(23,92),(23,93),(24,94),(24,95),(24,96),(24,97),(25,98),(25,99),(25,100),(25,101),(25,102),(26,9),(26,97),(26,104),(27,106),(27,107),(28,108),(28,109),(28,110),(29,111),(29,112),(29,113),(29,114),(30,77),(30,83),(30,117),(30,118);
/*!40000 ALTER TABLE `plant_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plant_compound`
--

DROP TABLE IF EXISTS `plant_compound`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plant_compound` (
  `plant_id` int(11) NOT NULL,
  `compound_id` int(11) NOT NULL,
  PRIMARY KEY (`plant_id`,`compound_id`),
  KEY `compound_id` (`compound_id`),
  CONSTRAINT `plant_compound_ibfk_1` FOREIGN KEY (`plant_id`) REFERENCES `plants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `plant_compound_ibfk_2` FOREIGN KEY (`compound_id`) REFERENCES `compounds` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plant_compound`
--

LOCK TABLES `plant_compound` WRITE;
/*!40000 ALTER TABLE `plant_compound` DISABLE KEYS */;
INSERT INTO `plant_compound` VALUES (1,1),(1,2),(1,3),(2,4),(2,5),(2,6),(3,7),(3,8),(3,9),(4,10),(4,11),(4,12),(5,13),(5,14),(5,15),(6,16),(6,17),(6,18),(7,19),(7,20),(8,21),(8,22),(9,23),(9,24),(10,25),(10,26),(10,27),(11,28),(11,29),(12,30),(12,31),(13,32),(13,33),(13,34),(14,3),(14,36),(14,37),(15,38),(15,39),(16,3),(16,40),(16,42),(17,43),(17,44),(17,45),(18,46),(18,47),(18,48),(19,3),(19,50),(19,51),(20,52),(20,53),(20,54),(21,27),(21,55),(21,57),(22,7),(22,9),(22,58),(23,3),(23,61),(23,62),(24,64),(24,65),(24,66),(25,3),(25,67),(25,69),(26,70),(27,71),(27,72),(27,73),(28,74),(28,75),(28,76),(29,77),(29,78),(29,79),(30,80),(30,81),(30,82);
/*!40000 ALTER TABLE `plant_compound` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plants`
--

DROP TABLE IF EXISTS `plants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `common_name` varchar(100) NOT NULL,
  `botanical_name` varchar(100) NOT NULL,
  `habitat` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `preparation_methods` text DEFAULT NULL,
  `dosages` text DEFAULT NULL,
  `precautions` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plants`
--

LOCK TABLES `plants` WRITE;
/*!40000 ALTER TABLE `plants` DISABLE KEYS */;
INSERT INTO `plants` VALUES (1,'Peppermint','Mentha piperita','Europe, Middle East','A hybrid mint, a cross between watermint and spearmint, widely used for its aromatic properties.','Infusion (Tea), Essential Oil, Extracts','Drink 2-3 times daily as a tea, or 0.2 to 0.4 mL of oil in capsules.','May cause heartburn in individuals with gastroesophageal reflux disease (GERD).',NULL,'2026-06-28 13:15:06'),(2,'Aloe Vera','Aloe vera','Arabian Peninsula, cultivated worldwide in tropical and arid climates','A succulent plant species with thick, fleshy, green leaves containing a clear gel.','Topical gel, Juice','Apply gel generously to skin as needed; drink 1-2 ounces of juice daily.','Oral ingestion of aloe latex can cause severe cramping and diarrhea.',NULL,'2026-06-28 13:15:06'),(3,'Chamomile','Matricaria chamomilla','Europe, Temperate Asia, North America','An annual plant of the daisy family, known for its small, daisy-like white and yellow flowers.','Infusion (Tea), Liquid Extract, Ointment','Drink 3-4 cups of tea daily.','May cause allergic reactions in individuals sensitive to the Asteraceae family (e.g., ragweed).',NULL,'2026-06-28 13:15:06'),(4,'Ginger','Zingiber officinale','Southeast Asia, cultivated in tropical regions','A flowering plant whose rhizome, ginger root, is widely used as a spice and folk medicine.','Fresh root, Powder, Decoction, Tincture','1-3 grams of dried extract daily, or multiple slices of fresh root in tea.','High doses may cause mild heartburn, diarrhea, and irritation of the mouth.',NULL,'2026-06-28 13:15:06'),(5,'Garlic','Allium sativum','Central Asia, Iran, cultivated globally','A bulbous flowering plant closely related to the onion, widely used for flavoring in cooking.','Raw cloves, Powder, Extract, Oil','1-2 cloves of raw garlic daily, or 300 mg of dried powder 2-3 times daily.','Can increase bleeding risk; avoid taking before surgery. May cause bad breath.',NULL,'2026-06-28 13:15:06'),(6,'Echinacea','Echinacea purpurea','North America','A group of herbaceous flowering plants in the daisy family, recognized by their purple coneflowers.','Tincture, Tea, Capsules','300-500 mg extract 3 times daily during acute illness.','May cause allergic reactions; avoid in individuals with autoimmune diseases.',NULL,'2026-06-28 13:15:06'),(7,'Ginseng','Panax ginseng','Eastern Asia, particularly Korea and northeastern China','A slow-growing perennial plant with fleshy roots, traditionally used in Chinese medicine.','Decoction, Tincture, Powder','200-400 mg standardized extract daily.','May cause insomnia, headaches, and digestive issues; interacts with blood thinners.',NULL,'2026-06-28 13:15:06'),(8,'Lavender','Lavandula angustifolia','Mediterranean region, cultivated worldwide','A strongly aromatic shrub with distinct pale purple flowers.','Essential oil, Infusion (Tea)','Inhalation of essential oil as needed; 1-2 teaspoons of dried flowers per cup of tea.','Essential oil is toxic if ingested; topical use may cause skin irritation in some.',NULL,'2026-06-28 13:15:06'),(9,'St. John\'s Wort','Hypericum perforatum','Europe, Asia, introduced to North America','A flowering plant with bright yellow flowers, traditionally used to treat mood disorders.','Capsules, Tincture, Tea','300 mg of standardized extract 3 times daily.','Interacts with many medications (e.g., SSRIs, birth control); may cause photosensitivity.',NULL,'2026-06-28 13:15:06'),(10,'Ginkgo Biloba','Ginkgo biloba','Native to China, cultivated globally','A large tree with fan-shaped leaves, one of the oldest living tree species.','Standardized leaf extract (Capsules/Tablets)','120-240 mg daily in divided doses.','Can increase bleeding risk; avoid with anticoagulants. Seeds are toxic.',NULL,'2026-06-28 13:15:06'),(11,'Turmeric','Curcuma longa','Indian subcontinent, Southeast Asia','A flowering plant whose rhizomes are dried and ground into a deep orange-yellow powder.','Powder in food/drinks, Extract capsules, Golden milk','500-2000 mg of extract daily, often paired with black pepper for absorption.','High doses may cause stomach upset; may increase bleeding risk.',NULL,'2026-06-28 13:15:06'),(12,'Valerian','Valeriana officinalis','Europe, Asia','A perennial flowering plant with heads of sweetly scented pink or white flowers.','Root extract, Tincture, Tea','300-600 mg of extract 30-120 minutes before bedtime.','May cause drowsiness; avoid combining with alcohol or other sedatives.',NULL,'2026-06-28 13:15:06'),(13,'Milk Thistle','Silybum marianum','Mediterranean region, widely naturalized','A thorny plant with distinctive purple flowers and white veins on its leaves.','Extract capsules, Tincture','140 mg of silymarin extract 2-3 times daily.','May cause mild laxative effect; can cause allergic reactions in Asteraceae-sensitive individuals.',NULL,'2026-06-28 13:15:06'),(14,'Rosemary','Salvia rosmarinus','Mediterranean region','A fragrant evergreen herb with needle-like leaves and white, pink, purple, or blue flowers.','Infusion (Tea), Essential oil, Culinary spice','1-2 grams of dried leaf in tea daily.','Large quantities can cause stomach irritation or spasms. Essential oil is not for oral use.',NULL,'2026-06-28 13:15:06'),(15,'Thyme','Thymus vulgaris','Mediterranean region','An aromatic evergreen herb with tiny leaves, used extensively in cooking and medicine.','Infusion (Tea), Essential oil, Syrup','1-2 grams of dried leaf in tea 3 times daily for coughs.','Safe in food amounts; large medicinal doses may cause stomach upset. Avoid during pregnancy in large amounts.',NULL,'2026-06-28 13:15:06'),(16,'Sage','Salvia officinalis','Mediterranean region','An evergreen subshrub with woody stems, grayish leaves, and blue to purplish flowers.','Infusion (Tea), Extract, Mouthwash','1-3 grams of dried leaf in tea daily.','Contains thujone, which can be toxic in high, prolonged doses (can cause seizures).',NULL,'2026-06-28 13:15:06'),(17,'Dandelion','Taraxacum officinale','Temperate regions of the Northern Hemisphere','A common weed with a rosette of basal leaves and large yellow flowers.','Tea (leaves or roasted root), Tincture','2-8 grams of dried root or 4-10 grams of dried leaves daily in tea.','May cause heartburn or skin irritation. Can act as a diuretic and affect kidney function medications.',NULL,'2026-06-28 13:15:06'),(18,'Ashwagandha','Withania somnifera','India, Middle East, parts of Africa','A small evergreen shrub in the nightshade family, known as Indian ginseng.','Root powder, Extract capsules','300-600 mg of standardized extract daily.','May lower blood pressure and blood sugar. Avoid in pregnancy.',NULL,'2026-06-28 13:15:06'),(19,'Lemon Balm','Melissa officinalis','South-central Europe, the Mediterranean Basin, Iran, Central Asia','A bushy perennial herb with lemon-scented leaves.','Infusion (Tea), Extract, Essential oil topical','1.5-4.5 grams of dried herb in tea, multiple times a day.','Generally safe; high doses may cause nausea or interact with thyroid medications.',NULL,'2026-06-28 13:15:06'),(20,'Fennel','Foeniculum vulgare','Mediterranean shores, widely naturalized','A highly aromatic and flavorful herb with yellow flowers and feathery leaves.','Chewing seeds, Tea, Essential oil','1-1.5 teaspoons of crushed seeds in tea, 2-3 times daily.','May cause allergic reactions in those allergic to celery or carrots. Avoid excessive doses in pregnancy.',NULL,'2026-06-28 13:15:06'),(21,'Calendula','Calendula officinalis','Southern Europe, widespread in cultivation','Also known as pot marigold, it has bright orange or yellow flowers.','Ointment, Cream, Infusion (Tea)','Apply 2-5% ointment topically to skin as needed.','Allergic reactions possible for those sensitive to the Asteraceae family.',NULL,'2026-06-28 13:15:06'),(22,'Yarrow','Achillea millefolium','Temperate regions of the Northern Hemisphere','A flowering plant with clusters of small white or pink flowers and feathery leaves.','Infusion (Tea), Tincture, Poultice','1-2 teaspoons of dried herb in tea, 3 times daily.','May cause contact dermatitis. Avoid during pregnancy due to potential uterine stimulation.',NULL,'2026-06-28 13:15:06'),(23,'Holy Basil','Ocimum tenuiflorum','Indian subcontinent, widespread as a cultivated plant throughout the Southeast Asian tropics','An aromatic perennial plant known as Tulsi in Ayurveda, highly revered.','Tea, Extract capsules','300-600 mg of dried extract daily, or 2-3 cups of tea.','May slow blood clotting; use caution before surgery or with blood thinners.',NULL,'2026-06-28 13:15:06'),(24,'Licorice','Glycyrrhiza glabra','Western Asia, southern Europe','A herbaceous perennial legume from whose roots a sweet flavor is extracted.','Root decoction, DGL (Deglycyrrhizinated licorice) chewables','1-5 grams of dried root daily, or 380-400 mg DGL before meals.','High intake can cause high blood pressure, low potassium levels, and edema.',NULL,'2026-06-28 13:15:06'),(25,'Comfrey','Symphytum officinale','Europe, growing in damp, grassy places','A perennial herb with hairy broad leaves and bell-shaped flowers.','Ointment, Poultice (Topical only)','Apply 5-20% ointment topically up to 3-4 times daily for short periods.','Contains hepatotoxic pyrrolizidine alkaloids (PAs). Do not take internally or use on broken skin.',NULL,'2026-06-28 13:15:06'),(26,'Feverfew','Tanacetum parthenium','Balkan Peninsula, widespread cultivation','A traditional medicinal herb which resembles chamomile, with citrus-scented leaves.','Extract capsules, Tea','50-150 mg daily for migraine prevention.','Chewing raw leaves can cause mouth ulcers. Avoid during pregnancy. Do not stop abruptly.',NULL,'2026-06-28 13:15:06'),(27,'Black Cohosh','Actaea racemosa','Eastern North America','A perennial woodland plant producing tall spikes of white flowers.','Extract capsules, Tincture','20-40 mg of standardized extract twice daily.','Rare reports of liver damage. Avoid if pregnant or have a history of hormone-sensitive cancer.',NULL,'2026-06-28 13:15:06'),(28,'Saw Palmetto','Serenoa repens','Southeastern United States','A small palm tree with fan-shaped leaves and dark red-black berries.','Lipidosterolic extract capsules','320 mg of standardized extract daily.','May cause mild stomach upset. Can interact with antiplatelet drugs.',NULL,'2026-06-28 13:15:06'),(29,'Hawthorn','Crataegus monogyna','Europe, Northwest Africa, West Asia','A shrub or small tree with thorns, producing red berry-like fruits called haws.','Extract (leaf/flower), Tincture, Tea','160-900 mg extract in divided doses daily.','Can interact with heart medications (e.g., digoxin, beta-blockers). Use under medical supervision.',NULL,'2026-06-28 13:15:06'),(30,'Gotu Kola','Centella asiatica','Wetlands in Asia','A small herbaceous frost-tender perennial plant used in Ayurvedic and traditional Chinese medicine.','Extract capsules, Tea, Topical creams','60-120 mg standardized extract daily.','May cause liver issues in very high doses. Avoid if pregnant or breastfeeding.',NULL,'2026-06-28 13:15:06');
/*!40000 ALTER TABLE `plants` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-28 21:23:27

