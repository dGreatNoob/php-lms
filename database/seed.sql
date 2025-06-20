/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.2-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: php_lms
-- ------------------------------------------------------
-- Server version	11.8.2-MariaDB

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
-- Dumping data for table `activities`
--

LOCK TABLES `activities` WRITE;
/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `activities` VALUES
(1,1,'create','course',1,'Test course creation','2025-06-20 04:20:57'),
(2,1,'archive','topic',3,'Archived topic: Another Test Topic','2025-06-20 04:22:54'),
(3,1,'unenroll','enrollment',6,'Unenrolled Belteshazzar Marquez from Test Course 002','2025-06-20 04:23:08'),
(4,2,'submit','submission',1,'Submitted assignment for: Intro to Variables','2025-06-20 04:23:35'),
(5,1,'unenroll','enrollment',7,'Unenrolled Belteshazzar Marquez from Introduction to Computer Science','2025-06-20 04:39:28'),
(6,1,'archive','topic',4,'Archived topic: Test child topic','2025-06-20 04:39:32'),
(7,1,'archive','lecture',1,'Archived lecture: A test lecture under a child of a parent topic.','2025-06-20 04:40:49'),
(8,1,'archive','lecture',4,'Archived lecture: Intro to Variables','2025-06-20 04:41:07'),
(9,1,'unenroll','enrollment',5,'Unenrolled Belteshazzar Marquez from Test Course 001','2025-06-20 04:41:10'),
(10,1,'archive','topic',2,'Archived topic: This is a test title','2025-06-20 04:41:13'),
(11,1,'archive','topic',7,'Archived topic: Intro to Programming','2025-06-20 04:41:14'),
(12,1,'archive','course',5,'Archived course: Introduction to Computer Science','2025-06-20 04:41:18'),
(13,1,'archive','course',3,'Archived course: Test Course 002','2025-06-20 04:41:19'),
(14,1,'archive','course',2,'Archived course: Test Course 001','2025-06-20 04:41:20'),
(15,1,'create','course',6,'Created course: College Algebra and Functions','2025-06-20 05:10:51'),
(16,1,'create','course',7,'Created course: Trigonometry and Mathematical Analysis','2025-06-20 05:11:12'),
(17,1,'create','topic',8,'Created topic: Module 1: Real Numbers & Algebraic Expressions','2025-06-20 05:13:09'),
(18,1,'create','lecture',5,'Created lecture: Lecture 1.1: Properties of Real Numbers','2025-06-20 05:14:32'),
(19,1,'create','lecture',6,'Created lecture: Lecture 1.2: Order of Operations (PEMDAS)','2025-06-20 05:15:32'),
(20,1,'create','topic',9,'Created topic: Module 2: Linear Equations and Inequalities','2025-06-20 05:16:06'),
(21,1,'create','lecture',7,'Created lecture: Lecture 2.1: Solving Linear Equations','2025-06-20 05:16:36'),
(22,1,'create','topic',10,'Created topic: Module 1: Angles and Right Triangle Trigonometry','2025-06-20 05:17:31'),
(23,1,'create','lecture',8,'Created lecture: Lecture 1.1: Angles in Degrees and Radians','2025-06-20 05:18:40'),
(24,1,'create','lecture',9,'Created lecture: Lecture 1.2: Right Triangle Definitions of Trig Functions','2025-06-20 05:19:37'),
(25,1,'enroll','enrollment',8,'Enrolled Belteshazzar Marquez in College Algebra and Functions','2025-06-20 05:19:44'),
(26,1,'enroll','enrollment',9,'Enrolled Belteshazzar Marquez in Trigonometry and Mathematical Analysis','2025-06-20 05:27:11'),
(27,1,'create','lecture',NULL,'Created lecture: Lecture 1.3: Solving Right Triangles','2025-06-20 05:36:42'),
(28,1,'create','lecture',NULL,'Created lecture: Lecture 1.3: Solving Right Triangles','2025-06-20 05:37:10'),
(29,1,'create','lecture',NULL,'Created lecture: Lec 1.4: A Bonus Lecture','2025-06-20 06:07:22'),
(30,1,'create','topic',11,'Created topic: A test topic','2025-06-20 06:29:30'),
(31,1,'create','lecture',NULL,'Created lecture: another test lecture','2025-06-20 06:30:12'),
(32,1,'update','topic',11,'Updated topic: A test topic','2025-06-20 06:32:34'),
(33,1,'update','course',6,'Updated course: College Algebra and Functions','2025-06-20 06:33:57'),
(34,1,'update','course',6,'Updated course: College Algebra and Functions','2025-06-20 06:34:01'),
(35,1,'create','course',8,'Created course: 12323','2025-06-20 06:34:07'),
(36,1,'update','topic',11,'Updated topic: A test topic','2025-06-20 06:34:35'),
(37,1,'enroll','enrollment',10,'Enrolled Belteshazzar Marquez in 12323','2025-06-20 06:35:24'),
(38,1,'update','course',8,'Updated course: 123231','2025-06-20 06:49:48'),
(39,1,'create','lecture',NULL,'Created lecture: 123123123','2025-06-20 06:50:14'),
(40,1,'update','lecture',14,'Updated lecture: 123123123','2025-06-20 06:54:18'),
(41,1,'update','lecture',14,'Updated lecture: 123123123','2025-06-20 06:54:27'),
(42,1,'update','lecture',14,'Updated lecture: 123123123','2025-06-20 06:54:34'),
(43,1,'archive','course',8,'Archived course: 123231','2025-06-20 07:00:41'),
(44,1,'archive','topic',11,'Archived topic: A test topic','2025-06-20 07:03:56'),
(45,1,'create','lecture',NULL,'Created lecture: TEST','2025-06-20 07:05:14'),
(46,1,'create','topic',12,'Created topic: TEST','2025-06-20 07:05:35'),
(47,1,'archive','topic',12,'Archived topic: TEST','2025-06-20 07:05:37'),
(48,1,'create','course',9,'Created course: TESTSTST123','2025-06-20 07:07:36'),
(49,1,'archive','course',9,'Archived course: TESTSTST123','2025-06-20 07:07:42'),
(50,1,'create','course',10,'Created course: 123123','2025-06-20 07:14:58'),
(51,1,'archive','course',10,'Archived course: 123123','2025-06-20 07:15:01'),
(52,1,'create','course',11,'Created course: 123123','2025-06-20 07:26:15'),
(53,1,'archive','course',11,'Archived course: 123123','2025-06-20 07:26:18'),
(54,1,'create','course',12,'Created course: 213132312313','2025-06-20 07:29:06'),
(55,1,'update','course',12,'Updated course: 213132312313','2025-06-20 07:29:10'),
(56,1,'archive','course',12,'Archived course: 213132312313','2025-06-20 07:29:12'),
(57,1,'create','topic',13,'Created topic: asdfasf1223123','2025-06-20 07:29:36'),
(58,1,'update','topic',13,'Updated topic: asdfasf1223123','2025-06-20 07:29:43'),
(59,1,'archive','topic',13,'Archived topic: asdfasf1223123','2025-06-20 07:31:18'),
(60,1,'create','topic',14,'Created topic: asdfasdfdas','2025-06-20 07:32:30'),
(61,1,'archive','topic',14,'Archived topic: asdfasdfdas','2025-06-20 07:32:32'),
(62,1,'create','course',13,'Created course: 1e132fd','2025-06-20 07:32:38'),
(63,1,'archive','course',13,'Archived course: 1e132fd','2025-06-20 07:32:40'),
(64,1,'create','course',14,'Created course: sdiufh289y9p','2025-06-20 07:33:12'),
(65,1,'archive','course',14,'Archived course: sdiufh289y9p','2025-06-20 07:33:14'),
(66,1,'update','lecture',15,'Updated lecture: TEST','2025-06-20 07:35:07'),
(67,1,'archive','lecture',15,'Archived lecture: TEST','2025-06-20 07:35:36'),
(68,1,'archive','lecture',12,'Archived lecture: Lec 1.4: A Bonus Lecture','2025-06-20 07:39:39'),
(69,1,'update','lecture',6,'Updated lecture: Lecture 1.2: Order of Operations (PEMDAS)','2025-06-20 07:40:03'),
(70,1,'archive','lecture',6,'Archived lecture: Lecture 1.2: Order of Operations (PEMDAS)','2025-06-20 07:40:18'),
(71,1,'update','topic',8,'Updated topic: Module 1: Real Numbers & Algebraic Expressions','2025-06-20 07:48:33'),
(72,1,'update','lecture',9,'Updated lecture: Lecture 1.2: Right Triangle Definitions of Trig Functions','2025-06-20 07:48:45'),
(73,1,'create','topic',15,'Created topic: MODULE 2 TEST','2025-06-20 07:49:41'),
(74,1,'create','lecture',NULL,'Created lecture: Lecture test for submissions','2025-06-20 07:50:22'),
(75,1,'update','lecture',16,'Updated lecture: Lecture test for submissions','2025-06-20 07:50:46'),
(76,2,'submit','submission',1,'Submitted assignment for: Lecture test for submissions','2025-06-20 07:56:37'),
(77,1,'update','lecture',8,'Updated lecture: Lecture 1.1: Angles in Degrees and Radians','2025-06-20 07:57:27'),
(78,1,'update','lecture',5,'Updated lecture: Lecture 1.1: Properties of Real Numbers','2025-06-20 08:02:33'),
(79,1,'enroll','enrollment',11,'Enrolled Juan Cruz in College Algebra and Functions','2025-06-20 08:52:01'),
(80,1,'unenroll','enrollment',11,'Unenrolled Juan Cruz from College Algebra and Functions','2025-06-20 08:53:55'),
(81,1,'unenroll','enrollment',9,'Unenrolled Belteshazzar Marquez from Trigonometry and Mathematical Analysis','2025-06-20 08:54:01'),
(82,1,'grade','submission',5,'Graded Belteshazzar Marquez\'s submission for: Lecture test for submissions','2025-06-20 09:07:56'),
(83,1,'unenroll','enrollment',8,'Unenrolled Belteshazzar Marquez from College Algebra and Functions','2025-06-20 09:20:51'),
(84,1,'archive','lecture',5,'Archived lecture: Lecture 1.1: Properties of Real Numbers','2025-06-20 09:21:04'),
(85,1,'archive','lecture',7,'Archived lecture: Lecture 2.1: Solving Linear Equations','2025-06-20 09:21:05'),
(86,1,'archive','lecture',8,'Archived lecture: Lecture 1.1: Angles in Degrees and Radians','2025-06-20 09:21:07'),
(87,1,'archive','lecture',9,'Archived lecture: Lecture 1.2: Right Triangle Definitions of Trig Functions','2025-06-20 09:21:08'),
(88,1,'archive','lecture',10,'Archived lecture: Lecture 1.3: Solving Right Triangles','2025-06-20 09:21:09'),
(89,1,'archive','lecture',11,'Archived lecture: Lecture 1.3: Solving Right Triangles','2025-06-20 09:21:11'),
(90,1,'archive','lecture',16,'Archived lecture: Lecture test for submissions','2025-06-20 09:21:12'),
(91,1,'archive','topic',8,'Archived topic: Module 1: Real Numbers & Algebraic Expressions','2025-06-20 09:21:16'),
(92,1,'archive','topic',9,'Archived topic: Module 2: Linear Equations and Inequalities','2025-06-20 09:21:17'),
(93,1,'archive','topic',10,'Archived topic: Module 1: Angles and Right Triangle Trigonometry','2025-06-20 09:21:18'),
(94,1,'archive','topic',15,'Archived topic: MODULE 2 TEST','2025-06-20 09:21:20'),
(95,1,'create','course',15,'Created course: Test Course 001','2025-06-20 10:08:52'),
(96,1,'update','course',15,'Updated course: Test Course 0012','2025-06-20 10:08:59'),
(97,1,'update','course',15,'Updated course: Test Course 001','2025-06-20 10:09:03'),
(98,1,'create','topic',16,'Created topic: Test Topic for TST 100','2025-06-20 10:09:32'),
(99,1,'update','topic',16,'Updated topic: Test Topic for TST 100','2025-06-20 10:09:42'),
(100,1,'update','topic',16,'Updated topic: Test Topic for TST 100','2025-06-20 10:09:49'),
(101,1,'enroll','enrollment',12,'Enrolled Belteshazzar Marquez in Test Course 001','2025-06-20 10:10:19'),
(102,1,'enroll','enrollment',13,'Enrolled Juan Cruz in College Algebra and Functions','2025-06-20 10:10:31'),
(103,1,'enroll','enrollment',14,'Enrolled Maria Santos in College Algebra and Functions','2025-06-20 10:11:02'),
(104,1,'enroll','enrollment',15,'Enrolled Jose Garcia in Trigonometry and Mathematical Analysis','2025-06-20 10:11:10'),
(105,1,'enroll','enrollment',16,'Enrolled Mark Dela Cruz in Test Course 001','2025-06-20 10:11:19'),
(106,1,'unenroll','enrollment',16,'Unenrolled Mark Dela Cruz from Test Course 001','2025-06-20 10:11:26'),
(107,1,'unenroll','enrollment',14,'Unenrolled Maria Santos from College Algebra and Functions','2025-06-20 10:11:29'),
(108,1,'unenroll','enrollment',15,'Unenrolled Jose Garcia from Trigonometry and Mathematical Analysis','2025-06-20 10:11:33'),
(109,1,'unenroll','enrollment',13,'Unenrolled Juan Cruz from College Algebra and Functions','2025-06-20 10:11:35'),
(110,1,'create','topic',17,'Created topic: Test Topic to Archive','2025-06-20 10:12:05'),
(111,1,'archive','topic',17,'Archived topic: Test Topic to Archive','2025-06-20 10:12:10'),
(112,1,'archive','topic',17,'Archived topic: Test Topic to Archive','2025-06-20 10:12:20'),
(113,1,'create','lecture',NULL,'Created lecture: First Test Lecture for Test Topic','2025-06-20 10:18:19'),
(114,2,'submit','submission',1,'Submitted assignment for: First Test Lecture for Test Topic','2025-06-20 10:18:59'),
(115,1,'grade','submission',6,'Graded Belteshazzar Marquez\'s submission for: First Test Lecture for Test Topic','2025-06-20 10:19:20'),
(116,1,'create','lecture',NULL,'Created lecture: Another test lecture for TST 100','2025-06-20 10:22:48'),
(117,2,'submit','submission',1,'Submitted assignment for: Another test lecture for TST 100','2025-06-20 10:23:25'),
(118,1,'grade','submission',7,'Graded Belteshazzar Marquez\'s submission for: Another test lecture for TST 100','2025-06-20 10:23:54'),
(119,1,'create','topic',18,'Created topic: Another Test Topic for TST 001','2025-06-20 10:44:30'),
(120,1,'create','lecture',NULL,'Created lecture: Another Lecture for another test topic','2025-06-20 10:46:07'),
(121,2,'submit','submission',1,'Submitted assignment for: Another Lecture for another test topic','2025-06-20 10:48:45'),
(122,1,'grade','submission',8,'Graded Belteshazzar Marquez\'s submission for: Another Lecture for another test topic','2025-06-20 10:49:14'),
(123,1,'create','lecture',NULL,'Created lecture: Another test lecture for unchecked function','2025-06-20 10:53:16'),
(124,2,'submit','submission',1,'Submitted assignment for: Another test lecture for unchecked function','2025-06-20 10:53:39'),
(125,24,'update','course',6,'Updated course: College Algebra and Functions','2025-06-20 11:30:06'),
(126,24,'update','course',7,'Updated course: Trigonometry and Mathematical Analysis','2025-06-20 11:30:07'),
(127,24,'update','course',15,'Updated course: Test Course 002','2025-06-20 11:30:12'),
(128,24,'create','course',16,'Created course: Test Course 001','2025-06-20 11:30:29'),
(129,24,'archive','course',15,'Archived course: Test Course 002','2025-06-20 11:30:38'),
(130,24,'archive','course',16,'Archived course: Test Course 001','2025-06-20 11:33:10'),
(131,1,'create','course',17,'Created course: Test Course 001','2025-06-20 11:34:22'),
(132,1,'update','course',17,'Updated course: Test Course 002','2025-06-20 11:34:26'),
(133,1,'update','course',17,'Updated course: Test Course 001','2025-06-20 11:34:29'),
(134,1,'create','topic',19,'Created topic: First Topic for TST 001','2025-06-20 11:34:53'),
(135,1,'update','topic',19,'Updated topic: First Topic for TST 001','2025-06-20 11:34:59'),
(136,1,'update','topic',19,'Updated topic: First Topic for TST 001','2025-06-20 11:35:02'),
(137,1,'create','topic',20,'Created topic: A child topic for First Topic','2025-06-20 11:35:27'),
(138,1,'create','topic',21,'Created topic: Another Test topic for archive test','2025-06-20 11:35:52'),
(139,1,'archive','topic',21,'Archived topic: Another Test topic for archive test','2025-06-20 11:35:55'),
(140,1,'create','lecture',NULL,'Created lecture: A test granchild topic','2025-06-20 11:37:03'),
(141,1,'enroll','enrollment',17,'Enrolled Test Student in Test Course 001','2025-06-20 11:37:19'),
(142,1,'unenroll','enrollment',17,'Unenrolled Test Student from Test Course 001','2025-06-20 11:37:25'),
(143,1,'enroll','enrollment',18,'Enrolled Test Student in Test Course 001','2025-06-20 11:37:29'),
(144,24,'archive','topic',21,'Archived topic: Another Test topic for archive test','2025-06-20 11:38:35'),
(145,24,'archive','topic',20,'Archived topic: A child topic for First Topic','2025-06-20 11:39:25'),
(146,24,'archive','topic',19,'Archived topic: First Topic for TST 001','2025-06-20 11:39:27'),
(147,24,'unenroll','enrollment',18,'Unenrolled Test Student from Test Course 001','2025-06-20 11:40:19'),
(148,24,'archive','course',17,'Archived course: Test Course 001','2025-06-20 11:40:38'),
(149,24,'create','topic',22,'Created topic: 1. Linear and Quadratic Equations','2025-06-20 11:44:25'),
(150,24,'create','topic',23,'Created topic: 2. Polynomial and Rational Functions','2025-06-20 11:44:39'),
(151,24,'create','topic',24,'Created topic: 3. Exponential and Logarithmic Functions','2025-06-20 11:44:57'),
(152,24,'create','topic',25,'Created topic: 1. Trigonometric Functions and Identities','2025-06-20 11:45:17'),
(153,24,'create','topic',26,'Created topic: 2. Solving Trigonometric Equations and Applications','2025-06-20 11:45:31'),
(154,24,'create','topic',27,'Created topic: 3. Introduction to Mathematical Analysis','2025-06-20 11:45:45'),
(155,24,'create','lecture',NULL,'Created lecture: Solving Linear Equations ','2025-06-20 11:46:37'),
(156,24,'create','lecture',NULL,'Created lecture: Graphing Linear Functions','2025-06-20 11:47:04'),
(157,24,'create','lecture',NULL,'Created lecture: Quadratic Equations and the Parabola ','2025-06-20 11:47:22'),
(158,24,'update','lecture',22,'Updated lecture: Solving Linear Equations','2025-06-20 11:47:27'),
(159,24,'update','lecture',24,'Updated lecture: Quadratic Equations and the Parabola','2025-06-20 11:47:40'),
(160,24,'create','lecture',NULL,'Created lecture: Polynomial Functions','2025-06-20 11:48:22'),
(161,24,'create','lecture',NULL,'Created lecture: Finding Zeros and Factoring','2025-06-20 11:48:47'),
(162,24,'create','lecture',NULL,'Created lecture: Rational Functions ','2025-06-20 11:49:08'),
(163,24,'create','lecture',NULL,'Created lecture: Angles and the Unit Circle','2025-06-20 11:50:04'),
(164,24,'create','lecture',NULL,'Created lecture: Trigonometric Graphs','2025-06-20 11:50:28'),
(165,24,'create','lecture',NULL,'Created lecture: Solving Basic and Advanced Trig Equations ','2025-06-20 11:50:53'),
(166,24,'create','lecture',NULL,'Created lecture: Inverse Trigonometric Functions ','2025-06-20 11:51:16'),
(167,24,'enroll','enrollment',19,'Enrolled Belteshazzar Marquez in College Algebra and Functions','2025-06-20 11:51:44'),
(168,24,'enroll','enrollment',20,'Enrolled Belteshazzar Marquez in Trigonometry and Mathematical Analysis','2025-06-20 11:51:52'),
(169,2,'submit','submission',1,'Submitted assignment for: Solving Linear Equations','2025-06-20 11:53:46');
/*!40000 ALTER TABLE `activities` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `courses` VALUES
(6,'MPE 101','College Algebra and Functions','1st Semester 2025–2026','2025-06-20 05:10:51',0),
(7,'MPE 102','Trigonometry and Mathematical Analysis','1st Semester 2025–2026','2025-06-20 05:11:12',0);
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Dumping data for table `enrollments`
--

LOCK TABLES `enrollments` WRITE;
/*!40000 ALTER TABLE `enrollments` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `enrollments` VALUES
(19,2,6,'2025-06-20 11:51:44'),
(20,2,7,'2025-06-20 11:51:52');
/*!40000 ALTER TABLE `enrollments` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Dumping data for table `lectures`
--

LOCK TABLES `lectures` WRITE;
/*!40000 ALTER TABLE `lectures` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `lectures` VALUES
(22,22,'Solving Linear Equations','Methods for solving equations in one variable and systems of equations.',NULL,1,0,'2025-06-20 11:46:37','2025-06-22 10:00:00'),
(23,22,'Graphing Linear Functions','Interpreting slope, intercepts, and creating linear graphs.',NULL,1,0,'2025-06-20 11:47:04','2025-06-21 10:00:00'),
(24,22,'Quadratic Equations and the Parabola','Factoring, completing the square, quadratic formula, and graphing parabolas.',NULL,1,0,'2025-06-20 11:47:22','2025-06-22 10:00:00'),
(25,23,'Polynomial Functions','Degree, end behavior, and graphing techniques.',NULL,1,0,'2025-06-20 11:48:22','2025-06-23 10:00:00'),
(26,23,'Finding Zeros and Factoring','Using the Rational Root Theorem and synthetic division.',NULL,1,0,'2025-06-20 11:48:47','2025-06-21 10:00:00'),
(27,23,'Rational Functions ','Simplifying, finding asymptotes, and sketching graphs.',NULL,1,0,'2025-06-20 11:49:08',NULL),
(28,25,'Angles and the Unit Circle','Radians, degrees, and circular function values.','uploads/upload_68554aec84cb4.pdf',1,0,'2025-06-20 11:50:04','2025-06-23 19:00:00'),
(29,25,'Trigonometric Graphs','Graphing sine, cosine, and tangent functions.',NULL,1,0,'2025-06-20 11:50:28','2025-06-23 10:00:00'),
(30,26,'Solving Basic and Advanced Trig Equations ','Techniques for exact and general solutions.',NULL,1,0,'2025-06-20 11:50:53','2025-06-23 10:00:00'),
(31,26,'Inverse Trigonometric Functions ','Definitions, graphs, and solving equations.',NULL,1,0,'2025-06-20 11:51:16','2025-06-23 10:00:00');
/*!40000 ALTER TABLE `lectures` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Dumping data for table `progress`
--

LOCK TABLES `progress` WRITE;
/*!40000 ALTER TABLE `progress` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `progress` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Dumping data for table `submissions`
--

LOCK TABLES `submissions` WRITE;
/*!40000 ALTER TABLE `submissions` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `submissions` VALUES
(10,2,22,'This is a test submission',NULL,'2025-06-20 19:53:46',NULL,NULL);
/*!40000 ALTER TABLE `submissions` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Dumping data for table `topics`
--

LOCK TABLES `topics` WRITE;
/*!40000 ALTER TABLE `topics` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `topics` VALUES
(22,6,NULL,'1. Linear and Quadratic Equations','Introduction to solving equations and analyzing their graphical representations.',NULL,NULL,'2025-06-20 11:44:25',0),
(23,6,NULL,'2. Polynomial and Rational Functions','Exploration of polynomial behavior, roots, and simplification of rational expressions.',NULL,NULL,'2025-06-20 11:44:39',0),
(24,6,NULL,'3. Exponential and Logarithmic Functions','Understanding exponential growth/decay and the inverse relationship with logarithms.',NULL,NULL,'2025-06-20 11:44:57',0),
(25,7,NULL,'1. Trigonometric Functions and Identities','Foundations of trigonometry and key identities used in simplification.',NULL,NULL,'2025-06-20 11:45:17',0),
(26,7,NULL,'2. Solving Trigonometric Equations and Applications','Using trigonometric techniques to solve equations and real-world problems.',NULL,NULL,'2025-06-20 11:45:31',0),
(27,7,NULL,'3. Introduction to Mathematical Analysis','Pre-calculus concepts to prepare for higher-level mathematics.',NULL,NULL,'2025-06-20 11:45:45',0);
/*!40000 ALTER TABLE `topics` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `users` VALUES
(1,'Belteshazzar','Marquez','admin1','admin@admin.com','$2y$12$lQNhlUtwar6nEDxG7xis/O9AcRBL/hyuF7hPwuLNAqIKLlVtUI7VW','admin','2025-06-19 14:38:42',0),
(2,'Belteshazzar','Marquez','student1','student@student.com','$2y$12$ObnpRFUA2PXES0UPi/zImemIRT./ACyHVanXT3SstiW.XfZU.P0Pq','student','2025-06-19 14:55:05',0),
(3,'Test','User','testuser','test@user.com','$2y$12$1xBCpnreOqsuNAFRffhNWOa3zBAkfqJSQ8Y8ihxoH45l8f4tt92VG','admin','2025-06-20 06:16:10',0),
(24,'Test','Admin','testadmin','testadmin@email.com','$2y$12$t6v3sg1JIYpV41oiumk0MuhbmUQLN5WXO8UuQ8IatNUCc5HVXQ3Ly','admin','2025-06-20 11:29:11',0),
(26,'Juan','Cruz','jcruz','jcruz@example.com','$2y$12$3cu2pWP/YXbl2L95xy6Sgur37NAQoE9cLm4llUUshVB.zy/IOpnWC','student','2025-06-20 11:52:00',0),
(27,'Maria','Santos','msantos','msantos@example.com','$2y$12$YGd8FPhpXpnkPXqQl3fDa.RGlbzpl4Okf7U9fznJ5AcLToQ3nDqAa','student','2025-06-20 11:52:00',0),
(28,'Jose','Garcia','jgarcia','jgarcia@example.com','$2y$12$WcQBIVhcFq4wiBKLaPmuwO7wdpDKPo4pUZ3sb.uiyLbh7WD3BQ3UC','student','2025-06-20 11:52:00',0),
(29,'Ana','Reyes','areyes','areyes@example.com','$2y$12$T5V/EdvMctYwbBvHN5dFSOIcMm1nLmCfyXFUK/4jUvVVmpWXd74hu','student','2025-06-20 11:52:00',0),
(30,'Mark','Dela Cruz','mdelacruz','mdelacruz@example.com','$2y$12$mybzM3Tb4kDDE0XfEaPjiebfV70M5i0hD5RLcTXCyyZ6O8ljoqr6q','student','2025-06-20 11:52:00',0),
(31,'Luisa','Torres','ltorres','ltorres@example.com','$2y$12$Ve3DKefdKebcP3GZ5wcIR.QgBdbHAupvVrFiosjmFKbCkoW0f.phm','student','2025-06-20 11:52:00',0),
(32,'Miguel','Lopez','mlopez','mlopez@example.com','$2y$12$i7f1uGwDUVrVgWhoq5rqSOnkX9wAQc9ea3lDfEc091WFpP298vGba','student','2025-06-20 11:52:01',0),
(33,'Liza','Gonzales','lgonzales','lgonzales@example.com','$2y$12$UPqB777OBbjys4tnlEvPG.mnTquCiRd9SkRtSb/AZg88oPzw.7nQm','student','2025-06-20 11:52:01',0),
(34,'Paulo','Mendoza','pmendoza','pmendoza@example.com','$2y$12$EBv79QJNC2VU8XxLLG1DtO0LWoiy3n9/BbHMXH1xY/V8glA1Z0wUW','student','2025-06-20 11:52:01',0),
(35,'Grace','Ramos','gramos','gramos@example.com','$2y$12$Sj5ZknrHDLSGPphzjC5bsuz.Ka6iKfTYvxhyoE9zwEXP.dcswM3wm','student','2025-06-20 11:52:01',0),
(36,'Carlo','Domingo','cdomingo','cdomingo@example.com','$2y$12$EDjp8axksceS1C7X8khthONt52V7lK4NlDYe7wEzRE9GKEYygEW6K','student','2025-06-20 11:52:01',0),
(37,'Isabel','Navarro','inavarro','inavarro@example.com','$2y$12$ikgr08K0BQeCzwexOy1l.OyQJSR2w6hCeCXDRK8ApMaM9K/kxqK9C','student','2025-06-20 11:52:02',0),
(38,'Rafael','Fernandez','rfernandez','rfernandez@example.com','$2y$12$5QFAHSVLvMA9Uew2Vbc3dO54OjLN409qiEZ7Mp8dlZpJQCGBLFjPe','student','2025-06-20 11:52:02',0),
(39,'Nina','Castillo','ncastillo','ncastillo@example.com','$2y$12$bDbLRiE/bkO.qm3EoJrq6u0IA7vrXvkgcUgT5xr1fFMijMoxdbgFK','student','2025-06-20 11:52:02',0),
(40,'Marco','Bautista','mbautista','mbautista@example.com','$2y$12$szp9vA8Pr3ldpwIu./zH4.Wz5EDJ0auf80MZAiinr718U8V8Skrty','student','2025-06-20 11:52:02',0),
(41,'Ella','Morales','emorales','emorales@example.com','$2y$12$RXS2YLnuNpmllLMmifrJ6uZZ5gwTtOGTLQLEfAzSqfU5NKM2pJRPy','student','2025-06-20 11:52:02',0),
(42,'Victor','Lorenzo','vlorenzo','vlorenzo@example.com','$2y$12$oLGudxFFKM/MnX5ySBYK6ud5pMa13Joy/6ZBbaeZKaKE3M7dt1dKa','student','2025-06-20 11:52:02',0),
(43,'Carmen','Aguilar','caguilar','caguilar@example.com','$2y$12$QC28IoSjHmsuhaDG/xqnGOVlnI5TMrUUAfL53y3hHzNcVP0D5hRgm','student','2025-06-20 11:52:03',0),
(44,'Kevin','Santiago','ksantiago','ksantiago@example.com','$2y$12$quUsdowDjAl11e8UbWyRa.3G/OOw84fzNqcYulpdMacJU0hc2cv1e','student','2025-06-20 11:52:03',0),
(45,'Diana','Rivera','drivera','drivera@example.com','$2y$12$BiaB3JMQStToAh2h4lcdqu6UOWD4K1.Ttbo1MMcP9mXK4frcxk25.','student','2025-06-20 11:52:03',0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-06-20 20:01:58
