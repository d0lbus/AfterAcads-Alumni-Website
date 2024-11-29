-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 29, 2024 at 03:17 AM
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
-- Database: `afteracads`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `tag` varchar(255) DEFAULT NULL,
  `host` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `tag`, `host`, `date`, `time`, `location`, `image_path`, `alt_text`, `created_at`) VALUES
(6, 'General Event 1', 'Description of the general event 1', 'GENERAL', 'Host 1', '2024-11-10', '14:00:00', 'Location 1', '/images/event1.jpg', 'General Event 1 Image', '2024-10-23 10:03:13'),
(7, 'SAMCIS Conference', 'SAMCIS Annual Conference Description', 'SAMCIS', 'SAMCIS Host', '2024-11-12', '10:00:00', 'Conference Hall 2', '/images/samcis_event.jpg', 'SAMCIS Conference Image', '2024-10-23 10:03:13'),
(8, 'SOHNABS Health Workshop', 'SOHNABS Health Workshop Event Description', 'SOHNABS', 'SOHNABS Host', '2024-11-14', '09:00:00', 'Health Center 5', '/images/sohnabs_event.jpg', 'SOHNABS Workshop Image', '2024-10-23 10:03:13'),
(9, 'STELA Leadership Summit', 'STELA Leadership Summit Event Description', 'STELA', 'STELA Host', '2024-11-20', '11:00:00', 'Leadership Center', '/images/stela_event.jpg', 'STELA Summit Image', '2024-10-23 10:03:13'),
(10, 'SEA Environmental Conference', 'SEA Environmental Conference Description', 'SEA', 'SEA Host', '2024-11-22', '08:00:00', 'Environment Hall', '/images/sea_event.jpg', 'SEA Conference Image', '2024-10-23 10:03:13');

-- --------------------------------------------------------

--
-- Table structure for table `event_participants`
--

CREATE TABLE `event_participants` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('interested','going') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `id` int(11) NOT NULL,
  `user1` int(11) NOT NULL,
  `user2` int(11) NOT NULL,
  `accepted` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`id`, `user1`, `user2`, `accepted`, `created_at`) VALUES
(1, 10, 1, 0, '2024-11-28 09:42:49');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tag` enum('GENERAL','SAMCIS','SEA','SONAHBS','STELA') NOT NULL,
  `image` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `content`, `created_at`, `updated_at`, `tag`, `image`) VALUES
(9, 9, 'hello', '2024-11-07 02:03:52', '2024-11-07 02:03:52', 'SAMCIS', NULL),
(10, 6, 'hi chloe', '2024-11-07 02:05:15', '2024-11-07 02:05:15', 'GENERAL', NULL),
(11, 5, 'hiiiiiii', '2024-11-07 02:18:02', '2024-11-07 02:18:02', 'SEA', NULL),
(12, 9, '<script> alert(\"test\") </script>', '2024-11-07 02:23:44', '2024-11-07 02:23:44', 'GENERAL', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `agreed_to_terms` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `address` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `employment_status` enum('Employed','Unemployed') DEFAULT 'Unemployed',
  `status` enum('pending','approved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password_hash`, `agreed_to_terms`, `created_at`, `address`, `bio`, `employment_status`, `status`) VALUES
(1, 'Jan Dolby', 'Aquino', 'jandolbyaquino19@slu.edu.ph', '$2y$10$cPu4zpF/tM36emW9fIHrReZue5zzcvAMv1Nq5TdPxc/Gwr8SJKg0m', 1, '2024-10-15 00:33:08', '83 Malabago Mangaldan Pangasinan', 'Hi my name is Jan Dolby', 'Unemployed', 'pending'),
(2, 'Jan Dolby', 'Aquino', 'jandolbyaquino20@slu.edu.ph', '$2y$10$w4l8..AW/0jm6UUBNYI16eOx/17aJf66VwYNnaPzG/P1WoqWBP6V.', 1, '2024-10-15 00:34:02', NULL, NULL, 'Unemployed', 'pending'),
(3, 'Jan Dolby', 'Aquino', 'jandolbyaquino21@slu.edu.ph', '$2y$10$IhalskFjVnbHekNUtj3CzedWp2UqLm3gCZNe.uNJclfsba4wNXGi.', 1, '2024-10-15 00:35:46', NULL, NULL, 'Unemployed', 'pending'),
(4, 'arvin', 'dela cruz', 'arvin@gmail.com', '$2y$10$rfp98rtASqWKPmnaa36o2edm2mRlk3TObt7qM0WgE2/oXzNzcn.9u', 1, '2024-11-07 01:03:28', 'Baguio CIty', 'My name is Mark Arvin', 'Unemployed', 'pending'),
(5, 'Jerilyn', 'Cahanap', '2233913@slu.edu.ph', '$2y$10$8ciN820DhyUEuLik3J3O7uS0/.oUfpqw3cAijkeTXHcOcm2Nayi/C', 1, '2024-11-07 01:52:06', NULL, NULL, 'Unemployed', 'pending'),
(6, 'Chloe', 'San Miguel', 'chloe@gmail.com', '$2y$10$ui1f.yWS4nhFPg/b/ENrnOVYJr8n77ixWiYBf7su/dgCwmcnTBAxq', 1, '2024-11-07 01:52:10', NULL, NULL, 'Unemployed', 'pending'),
(7, 'Julianne Therese', 'Abitan', 'julianneabitan@gmail.com', '$2y$10$DqJiBrhBqQ9kD.X6AQlsYebQToQh7/IH0YgkPjMFzzrP7OH8B/H6i', 1, '2024-11-07 01:52:11', 'apt', '', 'Unemployed', 'pending'),
(8, 'marvin', '908u87y79', '0999999@google.com', '$2y$10$VFF4spsp6irhT3SF4H.L3e0yE0.ssgwJ05Mp4/aQyFxiSAyQhG0.G', 1, '2024-11-07 01:58:06', NULL, NULL, 'Unemployed', 'pending'),
(9, 'qwerty', 'cruz', 'qwerty@slu.edu.ph', '$2y$10$rKScj4PvbsnFjiZK4m7D8OEEqP9Z495Dey1byJ5cjv2acW1.Eh3tu', 1, '2024-11-07 02:00:00', 'Baguio City', 'Hi ', 'Unemployed', 'pending'),
(10, 'Dummy', 'Account', 'totoongslu@slu.edu.ph', '$2y$10$sYYhnd40PHfTh98P30VoPegfkLqcGdW1N2nybRUhVt.rw4UbTUlP2', 1, '2024-11-27 19:59:52', NULL, NULL, 'Unemployed', 'pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_relationship` (`user1`,`user2`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `event_participants`
--
ALTER TABLE `event_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD CONSTRAINT `event_participants_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
