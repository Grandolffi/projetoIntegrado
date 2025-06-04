-- MySQL dump 10.13  Distrib 8.0.33, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: projetointegrado
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

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
-- Table structure for table `pessoa`
--

DROP TABLE IF EXISTS `pessoa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pessoa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `cpf` varchar(255) NOT NULL,
  `dataNasc` date DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `nomeMae` varchar(255) DEFAULT NULL,
  `numCelular` varchar(255) DEFAULT NULL,
  `genero` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pessoa`
--

/*!40000 ALTER TABLE `pessoa` DISABLE KEYS */;
INSERT INTO `pessoa` VALUES (1,'kleber','12092066927','2004-08-02','kleberjgrandolffi@gmail.com','priscila','43 98409-2304','masc'),(2,'kleber','12092066927','2004-08-02','kleberjgrandolffi@gmail.com','priscila','43 98409-2304','masc'),(3,'kleber','12092066927','2004-08-02','kleberjgrandolffi@gmail.com','priscila','43 98409-2304','masc'),(4,'kleber','12092066927','2004-08-02','kleberjgrandolffi@gmail.com','priscila','43 98409-2304','masc'),(5,'kleber','12092066927','2004-08-02','kleberjgrandolffi@gmail.com','priscila','43 98409-2304','masc'),(6,'kleber','12092066927','2004-08-02','kleberjgrandolffi@gmail.com','priscila','43 98409-2304','masc'),(7,'priscila','00054646','1974-08-18','kauanepalacio@gmail.com','priscila','43 98409-2304','femi'),(8,'caio','12345678',NULL,'abc@gmail.com','amanda','43 98409-2340','masc'),(9,'Ingrid F','02523202121',NULL,'kokokoko@gmail.com','Claudia F. Kato','43 99878-7878','femi');
/*!40000 ALTER TABLE `pessoa` ENABLE KEYS */;

--
-- Table structure for table `resultados_exames`
--

DROP TABLE IF EXISTS `resultados_exames`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `resultados_exames` (
  `id_exame` int(11) NOT NULL AUTO_INCREMENT,
  `nome_exame` varchar(255) NOT NULL,
  `tipo_exame` varchar(100) NOT NULL,
  `valor_absoluto` varchar(50) DEFAULT NULL,
  `valor_referencia` text DEFAULT NULL,
  `paciente_registro` varchar(100) NOT NULL,
  `data_hora_exame` datetime NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_exame`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resultados_exames`
--

/*!40000 ALTER TABLE `resultados_exames` DISABLE KEYS */;
INSERT INTO `resultados_exames` VALUES (1,'Bilirrubina Total (mg/dL)','bilirrubina_total','0.2','0,2 – 1,2 mg/dL','1545465','2025-06-03 16:30:00','2025-06-03 19:43:46'),(2,'Bilirrubina Direta (mg/dL)','bilirrubina_direta','0.1','0,0 – 0,2 mg/dL','1545465','2025-06-03 16:30:00','2025-06-03 19:43:46'),(3,'Proteína Total','proteina_total','12','','1545465','2025-06-03 16:30:00','2025-06-03 19:43:46'),(4,'TGO - Transaminase Glutamico Oxalacética (U/L)','tgo','20','5 – 34 U/L','1545465','2025-06-03 16:30:00','2025-06-03 19:43:46'),(5,'TGP - Transaminase Glutamico Piruvica (U/L)','tgp','70','Masculino: 21 – 72 U/L<br>Feminino: 9 – 52 U/L','1545465','2025-06-03 16:30:00','2025-06-03 19:43:46'),(6,'Gama GT - Glutamiltransferase (U/L)','gama_gt','45','Masculino: 15 – 73 U/L<br>Feminino: 12 – 43 U/L','1545465','2025-06-03 16:30:00','2025-06-03 19:43:46'),(7,'Fosfatase Alcalina (U/L)','fosfatase_alcalina','100','38 – 126 U/L','1545465','2025-06-03 16:30:00','2025-06-03 19:43:46'),(8,'Creatinina (mg/dL)','creatinina','1','Masculino: 0,70 – 1,25 mg/dL<br>Feminino: 0,57 – 1,11 mg/dL','1545465','2025-06-03 16:30:00','2025-06-03 19:43:46'),(9,'Glicose (mg/dL)','glicose','56','75 – 99 mg/dL','1545465','2025-06-03 16:30:00','2025-06-03 19:43:46'),(10,'Colesterol Total (mg/dL)','colesterol_total','150','Adultos (acima de 20 anos): menor que 190 mg/dL<br>Crianças e adolescentes (menores de 20 anos): menor que 170 mg/dL','1545465','2025-06-03 16:30:00','2025-06-03 19:43:46'),(11,'Triglicerídeos (mg/dL)','triglicerideos','155','Adultos (acima de 20 anos): menor que 150 mg/dL<br>Crianças de 0 a 9 anos: menor que 75 mg/dL<br>Crianças e adolescentes de 10 a 19 anos: menor que 90 mg/dL','1545465','2025-06-03 16:30:00','2025-06-03 19:43:46'),(12,'Ureia (mg/dL)','ureia','12','','1545465','2025-06-03 16:30:00','2025-06-03 19:43:46'),(13,'Ácido Úrico (mg/dL)','acido_urico','12','','1545465','2025-06-03 16:30:00','2025-06-03 19:43:46'),(14,'PCR - Proteína C Reativa (mg/dL)','pcr','15','Inferior a 1,0 mg/dL','1545465','2025-06-03 16:30:00','2025-06-03 19:43:46'),(15,'Cálcio','calcio','45','','1545465','2025-06-03 16:30:00','2025-06-03 19:43:46'),(16,'Bilirrubina Total (mg/dL)','bilirrubina_total','0.2','0,2 – 1,2 mg/dL','1545465','2025-06-01 20:15:00','2025-06-03 19:50:59'),(17,'Bilirrubina Direta (mg/dL)','bilirrubina_direta','0.2','0,0 – 0,2 mg/dL','1545465','2025-06-01 20:15:00','2025-06-03 19:50:59'),(18,'Proteína Total','proteina_total','123','','1545465','2025-06-01 20:15:00','2025-06-03 19:50:59'),(19,'TGO - Transaminase Glutamico Oxalacética (U/L)','tgo','15','5 – 34 U/L','1545465','2025-06-01 20:15:00','2025-06-03 19:50:59'),(20,'TGP - Transaminase Glutamico Piruvica (U/L)','tgp','70','Masculino: 21 – 72 U/L<br>Feminino: 9 – 52 U/L','1545465','2025-06-01 20:15:00','2025-06-03 19:50:59'),(21,'Gama GT - Glutamiltransferase (U/L)','gama_gt','45','Masculino: 15 – 73 U/L<br>Feminino: 12 – 43 U/L','1545465','2025-06-01 20:15:00','2025-06-03 19:50:59'),(22,'Fosfatase Alcalina (U/L)','fosfatase_alcalina','100','38 – 126 U/L','1545465','2025-06-01 20:15:00','2025-06-03 19:50:59'),(23,'Creatinina (mg/dL)','creatinina','1.2','Masculino: 0,70 – 1,25 mg/dL<br>Feminino: 0,57 – 1,11 mg/dL','1545465','2025-06-01 20:15:00','2025-06-03 19:50:59'),(24,'Glicose (mg/dL)','glicose','45','75 – 99 mg/dL','1545465','2025-06-01 20:15:00','2025-06-03 19:50:59'),(25,'Colesterol Total (mg/dL)','colesterol_total','165','Adultos (acima de 20 anos): menor que 190 mg/dL<br>Crianças e adolescentes (menores de 20 anos): menor que 170 mg/dL','1545465','2025-06-01 20:15:00','2025-06-03 19:50:59'),(26,'Triglicerídeos (mg/dL)','triglicerideos','150','Adultos (acima de 20 anos): menor que 150 mg/dL<br>Crianças de 0 a 9 anos: menor que 75 mg/dL<br>Crianças e adolescentes de 10 a 19 anos: menor que 90 mg/dL','1545465','2025-06-01 20:15:00','2025-06-03 19:50:59'),(27,'Ureia (mg/dL)','ureia','125','','1545465','2025-06-01 20:15:00','2025-06-03 19:50:59'),(28,'Ácido Úrico (mg/dL)','acido_urico','145','','1545465','2025-06-01 20:15:00','2025-06-03 19:50:59'),(29,'PCR - Proteína C Reativa (mg/dL)','pcr','1','Inferior a 1,0 mg/dL','1545465','2025-06-01 20:15:00','2025-06-03 19:50:59'),(30,'Cálcio','calcio','15','','1545465','2025-06-01 20:15:00','2025-06-03 19:50:59'),(31,'LDH','ldh','456','','1545465','2025-06-01 20:15:00','2025-06-03 19:50:59');
/*!40000 ALTER TABLE `resultados_exames` ENABLE KEYS */;

--
-- Dumping routines for database 'projetointegrado'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-03 23:02:25
