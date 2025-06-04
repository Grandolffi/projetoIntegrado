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
  `dtnasc` date DEFAULT NULL,
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
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Lucas Almeida', '12345678901', '120398', 'lucas.almeida@gmail.com', 'Maria Almeida', '11987654321', 'masculino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Juliana Costa', '23456789012', '150690', 'juliana.costa@hotmail.com', 'Ana Costa', '21998765432', 'feminino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Rafael Souza', '34567890123', '200184', 'rafael.souza@yahoo.com', 'Claudia Souza', '31991234567', 'masculino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Fernanda Lima', '45678901234', '090795', 'fernanda.lima@gmail.com', 'Patricia Lima', '41992345678', 'feminino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Bruno Oliveira', '56789012345', '250692', 'bruno.oliveira@outlook.com', 'Sandra Oliveira', '51993456789', 'masculino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Mariana Rocha', '67890123456', '180883', 'mariana.rocha@gmail.com', 'Lucia Rocha', '61994567890', 'feminino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Carlos Pereira', '78901234567', '100376', 'carlos.pereira@hotmail.com', 'Helena Pereira', '71995678901', 'masculino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Patricia Gomes', '89012345678', '040990', 'patricia.gomes@yahoo.com', 'Rosa Gomes', '81996789012', 'feminino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Eduardo Fernandes', '90123456789', '220580', 'eduardo.fernandes@gmail.com', 'Beatriz Fernandes', '92997890123', 'masculino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Camila Ribeiro', '11223344556', '140792', 'camila.ribeiro@outlook.com', 'Marta Ribeiro', '83998901234', 'feminino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Felipe Martins', '22334455667', '101295', 'felipe.martins@gmail.com', 'Teresa Martins', '11999012345', 'masculino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Aline Mendes', '33445566778', '171087', 'aline.mendes@hotmail.com', 'Regina Mendes', '21990123456', 'feminino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Diego Castro', '44556677889', '080593', 'diego.castro@yahoo.com', 'Elaine Castro', '31991234567', 'masculino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Larissa Silva', '55667788990', '230284', 'larissa.silva@gmail.com', 'Juliana Silva', '41992345678', 'feminino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Marcelo Barbosa', '66778899001', '110692', 'marcelo.barbosa@outlook.com', 'Gloria Barbosa', '51993456789', 'masculino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Tatiane Cardoso', '77889900112', '060884', 'tatiane.cardoso@gmail.com', 'Cecilia Cardoso', '61994567890', 'feminino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('André Nascimento', '88990011223', '190378', 'andre.nascimento@hotmail.com', 'Natalia Nascimento', '71995678901', 'masculino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Renata Cunha', '99001122334', '020191', 'renata.cunha@yahoo.com', 'Ivone Cunha', '81996789012', 'feminino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Thiago Teixeira', '10111213141', '270580', 'thiago.teixeira@gmail.com', 'Angela Teixeira', '92997890123', 'masculino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Bianca Lopes', '12131415161', '310792', 'bianca.lopes@outlook.com', 'Vera Lopes', '83998901234', 'feminino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Gustavo Araujo', '13141516171', '070695', 'gustavo.araujo@gmail.com', 'Marcia Araujo', '11999012345', 'masculino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Isabela Martins', '14151617181', '221187', 'isabela.martins@hotmail.com', 'Silvia Martins', '21990123456', 'feminino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Pedro Henrique', '15161718191', '120293', 'pedro.henrique@yahoo.com', 'Josefa Henrique', '31991234567', 'masculino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Amanda Freitas', '16171819202', '160284', 'amanda.freitas@gmail.com', 'Cristina Freitas', '41992345678', 'feminino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Leandro Melo', '17181920212', '090692', 'leandro.melo@outlook.com', 'Marinalva Melo', '51993456789', 'masculino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Leticia Duarte', '18192021222', '300184', 'leticia.duarte@gmail.com', 'Paula Duarte', '61994567890', 'feminino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Rodrigo Lima', '19202122232', '050376', 'rodrigo.lima@hotmail.com', 'Tania Lima', '71995678901', 'masculino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Vanessa Souza', '20212223242', '101291', 'vanessa.souza@yahoo.com', 'Aparecida Souza', '81996789012', 'feminino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Fábio Reis', '21222324352', '210580', 'fabio.reis@gmail.com', 'Sueli Reis', '92997890123', 'masculino');
INSERT INTO pessoa (nome, cpf, dtnasc, email, nomemae, numCelular, genero) VALUES ('Carla Monteiro', '22232425362', '280792', 'carla.monteiro@outlook.com', 'Neide Monteiro', '83998901234', 'feminino');

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
