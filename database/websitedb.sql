-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2026 at 12:03 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `websitedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `comment_text` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT curtime(),
  `users_id` int(11) DEFAULT NULL,
  `movie_id` varchar(50) NOT NULL,
  `movie_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `username`, `comment_text`, `created_at`, `users_id`, `movie_id`, `movie_title`) VALUES
(1, 'awa', 'rlly good', '2026-03-11 14:40:19', 5, 'tt0120338', 'Titanic'),
(2, 'awa', 'amazin', '2026-03-11 19:41:57', 5, 'tt0120338', 'Titanic'),
(3, 'awa', 'hi', '2026-03-18 18:30:39', 5, 'tt0120338', 'Titanic'),
(4, 'awa', 'amazeballs', '2026-03-18 18:46:26', 5, 'tt0120338', 'Titanic'),
(5, 'awa', 'hi', '2026-03-21 17:43:36', 5, 'tt12042730', 'Project Hail Mary');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `pswd` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT curtime()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `pswd`, `email`, `created_at`) VALUES
(1, 'ffff', '$2y$10$vYue7mRUr92h8y4BZgGyxeKKBC733ls7f41MWtpDbPWaGUqqI0b6u', 'u2367783@unimail.hud.ac.uk', '2026-03-08 13:56:04'),
(2, 'ffff', '$2y$10$oO3pz8yoWJVxGXHPqDR5WuVYfehd.WtFuLUORaPHWcTAvrBCX3W3e', 'u2367783@unimail.hud.ac.uk', '2026-03-08 16:50:11'),
(3, 'u2367783@unimail.hud.ac.uk', '$2y$10$tIjoUYXiXVfxrjVGcnak8.sbZn0uMma7IeWQyk6JS874jVxwS0zr.', 'u2367783@unimail.hud.ac.uk', '2026-03-08 16:57:54'),
(4, 'u2367783@unimail.hud.ac.uk', '$2y$10$B5W1u8AaDgMoqp/a6l50BuXux6BZIjUNvTxEFHxZ2YqB9yZanMmQ6', 'u2367783@unimail.hud.ac.uk', '2026-03-08 19:09:11'),
(5, 'awa', '$2y$10$k.qXXhV/Xkk5R3PJ06fG4.TBdcH6hmuq3N5TGHef1cHH4hL5KYKIe', 'awa@gmail.com', '2026-03-08 19:09:51');

-- --------------------------------------------------------

--
-- Table structure for table `watchlist`
--

CREATE TABLE `watchlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `movie_id` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `watchlist`
--

INSERT INTO `watchlist` (`id`, `user_id`, `movie_id`, `created_at`) VALUES
(1, 5, 'tt26443597', '2026-03-22 11:15:07'),
(2, 5, 'tt15940132', '2026-03-22 11:16:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`users_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`,`username`,`pswd`,`email`,`created_at`);

--
-- Indexes for table `watchlist`
--
ALTER TABLE `watchlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `watchlist`
--
ALTER TABLE `watchlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
