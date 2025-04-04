-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 04, 2025 at 12:42 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `university`
--

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date NOT NULL,
  `enrollment_year` int DEFAULT NULL,
  `reg_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email` (`email`),
  KEY `idx_name` (`first_name`,`last_name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `first_name`, `last_name`, `email`, `phone`, `birth_date`, `enrollment_year`, `reg_date`) VALUES
(1, 'Иван', 'Петров', 'ivan.petrov@example.com', '0888123456', '1995-05-15', 2020, '2025-04-03 17:10:56'),
(2, 'Мария', 'Георгиева', 'maria.georgieva@example.com', '0877654321', '1998-08-22', 2021, '2025-04-03 17:10:56'),
(3, 'Георги', 'Димитров', 'georgi.dimitrov@example.com', '0899887766', '1997-03-10', 2019, '2025-04-03 17:10:56'),
(4, 'Karlen', 'Arabkertsyan', 'karlen01@abv.bg', '0876160067', '2005-03-14', NULL, '2025-04-03 17:45:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('user','admin') COLLATE utf8mb4_unicode_ci DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `birth_date` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`user`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user`, `password`, `email`, `first_name`, `last_name`, `phone`, `role`, `created_at`, `updated_at`, `birth_date`) VALUES
(1, 'Иван', '$2y$10$xkCuBv8dpf7eg359I1btjOlO31mJkOmyp2RuqNy/9BGm4kYSe/6Xi', '', '', '', '', 'user', '2025-04-04 06:58:19', '2025-04-04 11:20:30', 0),
(2, 'Karlen trakia', '$2y$10$n6rwwItt.nLDc7vGz4kJnuNQzujyL8Qlc5f.QAZBuol5IeUVaP1bu', 'karlen.arabkertsyan.23@trakia-uni.bg', 'Karlen', 'Arabkertsyan', '08761616', 'admin', '2025-04-04 07:01:22', '2025-04-04 11:20:30', 0),
(3, 'Adrian', '$2y$10$8KVr1WX4a7k8aIdJIN9wj.ilGbjOJ7HLDk3qiYp6HMAFz7az9o.yW', 'karlen01@abv.bg', 'Adrian', 'Adrianov', '929293923', 'admin', '2025-04-04 08:00:30', '2025-04-04 12:41:25', 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
