-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 07, 2026 at 04:04 PM
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
-- Database: `photography_club`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$U8.BXq9gAYpEiXhWB1MU3u7tuT4Ya0wj3D6NFEO8xQPG0F.Klr9su', '2026-02-07 13:54:52');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `event_date` date NOT NULL,
  `folder_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `event_date`, `folder_name`, `description`, `cover_image`, `created_at`) VALUES
(4, 'initial test', '2026-02-01', 'initial_test_Feb_2026', 'qwertyuiop', 'uploads/initial_test_Feb_2026/Grave Of Fireflies.jpg', '2026-02-07 14:14:12'),
(5, 'initial test 2', '2026-01-01', 'initial_test_2_Jan_2026', 'qazwsxedc', 'uploads/initial_test_2_Jan_2026/Accolade_Knighthood.jpg', '2026-02-07 14:19:21');

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE `photos` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `upload_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`id`, `event_id`, `file_path`, `upload_date`) VALUES
(19, 4, 'uploads/initial_test_Feb_2026/Grave Of Fireflies.jpg', '2026-02-07 14:14:12'),
(20, 4, 'uploads/initial_test_Feb_2026/Spirited Away.jpg', '2026-02-07 14:14:12'),
(21, 5, 'uploads/initial_test_2_Jan_2026/Accolade_Knighthood.jpg', '2026-02-07 14:19:21'),
(22, 5, 'uploads/initial_test_2_Jan_2026/Adam.jpg', '2026-02-07 14:19:21'),
(23, 5, 'uploads/initial_test_2_Jan_2026/Broken_Eggs.jpg', '2026-02-07 14:19:21'),
(24, 5, 'uploads/initial_test_2_Jan_2026/Fallen_Angel.jpg', '2026-02-07 14:19:21'),
(25, 5, 'uploads/initial_test_2_Jan_2026/Gladiator.jpg', '2026-02-07 14:19:21'),
(26, 5, 'uploads/initial_test_2_Jan_2026/Hypatia.jpg', '2026-02-07 14:19:21'),
(27, 5, 'uploads/initial_test_2_Jan_2026/Laughing_Jestler.jpg', '2026-02-07 14:19:21'),
(28, 5, 'uploads/initial_test_2_Jan_2026/Love_or_duty.jpg', '2026-02-07 14:19:21'),
(29, 5, 'uploads/initial_test_2_Jan_2026/Napoleon.jpg', '2026-02-07 14:19:21'),
(30, 5, 'uploads/initial_test_2_Jan_2026/School_Of_Athens.webp', '2026-02-07 14:19:21'),
(31, 5, 'uploads/initial_test_2_Jan_2026/St John the Baptist by Leonardo da Vinci.jpg', '2026-02-07 14:19:21'),
(32, 5, 'uploads/initial_test_2_Jan_2026/The_Angel_of_Death.jpg', '2026-02-07 14:19:21'),
(33, 5, 'uploads/initial_test_2_Jan_2026/The_Coronation_of_Napoleon.jpg', '2026-02-07 14:19:21'),
(34, 5, 'uploads/initial_test_2_Jan_2026/The_Death_of_Juius_Ceaser.jpg', '2026-02-07 14:19:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `photos`
--
ALTER TABLE `photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
