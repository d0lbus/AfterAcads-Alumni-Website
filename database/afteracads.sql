-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 18, 2024 at 01:59 AM
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

INSERT INTO `events` (`id`, `title`, `description`, `host`, `date`, `time`, `location`, `image_path`, `alt_text`, `created_at`) VALUES
(1, 'Annual Alumni Meetup', 'Join us for the Annual Alumni Meetup to reconnect with old friends and make new connections. Don\'t miss this exciting event with live entertainment and dinner.', 'SLU Alumni Association', '2024-12-01', '18:00:00', 'SLU Main Hall', '/assets/events/alumni_meetup.jpg', 'Annual Alumni Meetup', '2024-10-17 23:54:54'),
(2, 'Tech Summit 2024', 'A summit bringing together industry leaders to discuss the future of technology, innovation, and the role of AI in business.', 'SLU Computer Science Department', '2024-11-15', '09:00:00', 'SLU Auditorium', '/assets/events/tech_summit.jpg', 'Tech Summit 2024', '2024-10-17 23:54:54'),
(3, 'Sports Fest 2024', 'Get ready for an action-packed day at the SLU Sports Fest 2024. Compete in various sports activities and win exciting prizes!', 'SLU Sports Committee', '2024-12-20', '08:00:00', 'SLU Sports Complex', '/assets/events/sports_fest.jpg', 'Sports Fest 2024', '2024-10-17 23:54:54'),
(4, 'Cultural Night', 'Experience the diverse cultural performances by students and special guests at the SLU Cultural Night. Free entry for students!', 'SLU Cultural Committee', '2024-10-25', '19:00:00', 'SLU Cultural Hall', '/assets/events/cultural_night.jpg', 'Cultural Night', '2024-10-17 23:54:54'),
(5, 'Entrepreneurship Workshop', 'An interactive workshop for aspiring entrepreneurs to learn the basics of starting a business and funding it. Industry experts will share tips and experiences.', 'SLU Business Department', '2024-11-05', '10:00:00', 'SLU Business Center', '/assets/events/entrepreneurship_workshop.jpg', 'Entrepreneurship Workshop', '2024-10-17 23:54:54');

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
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `content`, `created_at`, `updated_at`) VALUES
(1, 1, 'hi', '2024-10-15 00:44:40', '2024-10-15 00:44:40');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password_hash`, `agreed_to_terms`, `created_at`) VALUES
(1, 'Jan Dolby', 'Aquino', 'jandolbyaquino19@slu.edu.ph', '$2y$10$b5az/EOJAu3UpJM2pMCfeeAA4UDTQ5E95P5ej9Fa506qxncuMTwQO', 1, '2024-10-15 00:33:08'),
(2, 'Jan Dolby', 'Aquino', 'jandolbyaquino20@slu.edu.ph', '$2y$10$w4l8..AW/0jm6UUBNYI16eOx/17aJf66VwYNnaPzG/P1WoqWBP6V.', 1, '2024-10-15 00:34:02'),
(3, 'Jan Dolby', 'Aquino', 'jandolbyaquino21@slu.edu.ph', '$2y$10$IhalskFjVnbHekNUtj3CzedWp2UqLm3gCZNe.uNJclfsba4wNXGi.', 1, '2024-10-15 00:35:46');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `event_participants`
--
ALTER TABLE `event_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
