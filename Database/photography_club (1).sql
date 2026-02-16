-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 10, 2026 at 01:58 AM
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
-- Table structure for table `backgrounds`
--

CREATE TABLE `backgrounds` (
  `id` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `backgrounds`
--

INSERT INTO `backgrounds` (`id`, `photo_id`, `added_at`) VALUES
(4, 47, '2026-02-08 02:56:59');

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
(6, 'initial test ', '2025-09-01', 'initial_test_3_Sep_2025', 'qazxedfghn', 'uploads/initial_test_3_Sep_2025/parametric2.png', '2026-02-08 01:55:07'),
(7, 'Bhavan Wall Magazine inauguration ', '2025-09-01', 'bhavan_wall_magazine_inauguration__Sep_2025', 'Bhavan Wall Magazine inauguration ', 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0030.JPG', '2026-02-08 02:24:03'),
(8, 'Friendly Cricket Match', '2026-01-01', 'friendly_cricket_match_Jan_2026', 'Friendly Cricket Match (Arts vs Science Teachers)', 'uploads/friendly_cricket_match_Jan_2026/IMG_0333.JPG', '2026-02-08 07:04:22'),
(9, 'Pilgrimage to Kamarpukur & Jayrambati', '2026-01-01', 'pilgrimage_to_kamarpukur___jayrambati_Jan_2026', 'Pilgrimage to Kamarpukur & Jayrambati', 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0005.JPG', '2026-02-08 07:05:08'),
(10, 'SARODUTSAV 2025', '2025-09-01', 'sarodutsav_2025_Sep_2025', 'SARODUTSAV 2025', 'uploads/sarodutsav_2025_Sep_2025/4f2f16191efc43389e6c05ea935b7f23.jpg', '2026-02-08 07:05:58');

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
(35, 6, 'uploads/initial_test_3_Sep_2025/file plotting.png', '2026-02-08 01:55:07'),
(36, 6, 'uploads/initial_test_3_Sep_2025/fit.png', '2026-02-08 01:55:07'),
(37, 6, 'uploads/initial_test_3_Sep_2025/gravity.png', '2026-02-08 01:55:07'),
(38, 6, 'uploads/initial_test_3_Sep_2025/parametric.png', '2026-02-08 01:55:07'),
(39, 6, 'uploads/initial_test_3_Sep_2025/parametric2.png', '2026-02-08 01:55:07'),
(40, 6, 'uploads/initial_test_3_Sep_2025/polar.png', '2026-02-08 01:55:07'),
(41, 6, 'uploads/initial_test_3_Sep_2025/sawtooth vs square wave.png', '2026-02-08 01:55:07'),
(42, 6, 'uploads/initial_test_3_Sep_2025/sawtooth vs square wave2.png', '2026-02-08 01:55:07'),
(43, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/DSC05411.JPG', '2026-02-08 02:24:03'),
(44, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/DSC05417.JPG', '2026-02-08 02:24:03'),
(45, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/DSC05420.JPG', '2026-02-08 02:24:03'),
(46, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/DSC05423.JPG', '2026-02-08 02:24:03'),
(47, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0004.JPG', '2026-02-08 02:24:03'),
(48, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0008.JPG', '2026-02-08 02:24:03'),
(49, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0011.JPG', '2026-02-08 02:24:03'),
(50, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0014.JPG', '2026-02-08 02:24:03'),
(51, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0015.JPG', '2026-02-08 02:24:03'),
(52, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0020.JPG', '2026-02-08 02:24:03'),
(53, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0022.JPG', '2026-02-08 02:24:03'),
(54, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0026.JPG', '2026-02-08 02:24:03'),
(55, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0027.JPG', '2026-02-08 02:24:03'),
(56, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0030.JPG', '2026-02-08 02:24:03'),
(57, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0034.JPG', '2026-02-08 02:24:03'),
(58, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0035.JPG', '2026-02-08 02:24:03'),
(59, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0038.JPG', '2026-02-08 02:24:03'),
(60, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0041.JPG', '2026-02-08 02:24:03'),
(61, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0042.JPG', '2026-02-08 02:24:03'),
(62, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0047.JPG', '2026-02-08 02:24:03'),
(63, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0050.JPG', '2026-02-08 02:24:03'),
(64, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0054.JPG', '2026-02-08 02:24:03'),
(65, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0061.JPG', '2026-02-08 02:24:03'),
(66, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0063.JPG', '2026-02-08 02:24:03'),
(67, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0064.JPG', '2026-02-08 02:24:03'),
(68, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0065.JPG', '2026-02-08 02:24:03'),
(69, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0066.JPG', '2026-02-08 02:24:03'),
(70, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0069.JPG', '2026-02-08 02:24:03'),
(71, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0075.JPG', '2026-02-08 02:24:03'),
(72, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0077.JPG', '2026-02-08 02:24:03'),
(73, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0079.JPG', '2026-02-08 02:24:03'),
(74, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0080.JPG', '2026-02-08 02:24:03'),
(75, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0082.JPG', '2026-02-08 02:24:03'),
(76, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0084.JPG', '2026-02-08 02:24:03'),
(77, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0085.JPG', '2026-02-08 02:24:03'),
(78, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0087.JPG', '2026-02-08 02:24:03'),
(79, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0099.JPG', '2026-02-08 02:24:03'),
(80, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0102.JPG', '2026-02-08 02:24:03'),
(81, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0110.JPG', '2026-02-08 02:24:03'),
(82, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0113.JPG', '2026-02-08 02:24:03'),
(83, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0115.JPG', '2026-02-08 02:24:03'),
(84, 7, 'uploads/bhavan_wall_magazine_inauguration__Sep_2025/IMG_0124.JPG', '2026-02-08 02:24:03'),
(85, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0189.JPG', '2026-02-08 07:04:22'),
(86, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0193 (2).JPG', '2026-02-08 07:04:22'),
(87, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0199 (2).JPG', '2026-02-08 07:04:22'),
(88, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0200.JPG', '2026-02-08 07:04:22'),
(89, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0203.JPG', '2026-02-08 07:04:22'),
(90, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0209.JPG', '2026-02-08 07:04:22'),
(91, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0210.JPG', '2026-02-08 07:04:22'),
(92, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0212 (2).JPG', '2026-02-08 07:04:22'),
(93, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0212.JPG', '2026-02-08 07:04:22'),
(94, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0214 (2).JPG', '2026-02-08 07:04:22'),
(95, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0215.JPG', '2026-02-08 07:04:22'),
(96, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0216 (2).JPG', '2026-02-08 07:04:22'),
(97, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0218.JPG', '2026-02-08 07:04:22'),
(98, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0219.JPG', '2026-02-08 07:04:22'),
(99, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0220.JPG', '2026-02-08 07:04:22'),
(100, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0223.JPG', '2026-02-08 07:04:22'),
(101, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0230.JPG', '2026-02-08 07:04:22'),
(102, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0248.JPG', '2026-02-08 07:04:22'),
(103, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0251.JPG', '2026-02-08 07:04:22'),
(104, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0262.JPG', '2026-02-08 07:04:22'),
(105, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0265.JPG', '2026-02-08 07:04:22'),
(106, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0267 (2).JPG', '2026-02-08 07:04:22'),
(107, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0269.JPG', '2026-02-08 07:04:22'),
(108, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0272 (2).JPG', '2026-02-08 07:04:22'),
(109, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0276.JPG', '2026-02-08 07:04:22'),
(110, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0277.JPG', '2026-02-08 07:04:22'),
(111, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0284.JPG', '2026-02-08 07:04:22'),
(112, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0285.JPG', '2026-02-08 07:04:22'),
(113, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0288 (2).JPG', '2026-02-08 07:04:22'),
(114, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0295 (2).JPG', '2026-02-08 07:04:22'),
(115, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0296.JPG', '2026-02-08 07:04:22'),
(116, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0299.JPG', '2026-02-08 07:04:22'),
(117, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0301 (2).JPG', '2026-02-08 07:04:22'),
(118, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0302.JPG', '2026-02-08 07:04:22'),
(119, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0318 (2).JPG', '2026-02-08 07:04:22'),
(120, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0322.JPG', '2026-02-08 07:04:22'),
(121, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0325.JPG', '2026-02-08 07:04:22'),
(122, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0327.JPG', '2026-02-08 07:04:22'),
(123, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0328.JPG', '2026-02-08 07:04:22'),
(124, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0331.JPG', '2026-02-08 07:04:22'),
(125, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0333.JPG', '2026-02-08 07:04:22'),
(126, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0335 (2).JPG', '2026-02-08 07:04:22'),
(127, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0340 (2).JPG', '2026-02-08 07:04:22'),
(128, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0353 (2).JPG', '2026-02-08 07:04:22'),
(129, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0354 (2).JPG', '2026-02-08 07:04:22'),
(130, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0360.JPG', '2026-02-08 07:04:22'),
(131, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0361.JPG', '2026-02-08 07:04:22'),
(132, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0373.JPG', '2026-02-08 07:04:22'),
(133, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0376.JPG', '2026-02-08 07:04:22'),
(134, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0389.JPG', '2026-02-08 07:04:22'),
(135, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0393.JPG', '2026-02-08 07:04:22'),
(136, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0400.JPG', '2026-02-08 07:04:22'),
(137, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0401.JPG', '2026-02-08 07:04:22'),
(138, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0406.JPG', '2026-02-08 07:04:22'),
(139, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0407.JPG', '2026-02-08 07:04:22'),
(140, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0437.JPG', '2026-02-08 07:04:22'),
(141, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0446.JPG', '2026-02-08 07:04:22'),
(142, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0449 (2).JPG', '2026-02-08 07:04:22'),
(143, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0449.JPG', '2026-02-08 07:04:22'),
(144, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0453.JPG', '2026-02-08 07:04:22'),
(145, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0455.JPG', '2026-02-08 07:04:22'),
(146, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0457.JPG', '2026-02-08 07:04:22'),
(147, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0458.JPG', '2026-02-08 07:04:22'),
(148, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0460.JPG', '2026-02-08 07:04:22'),
(149, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0467 (2).JPG', '2026-02-08 07:04:22'),
(150, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0471.JPG', '2026-02-08 07:04:22'),
(151, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0472.JPG', '2026-02-08 07:04:22'),
(152, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0502.JPG', '2026-02-08 07:04:22'),
(153, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_0503.JPG', '2026-02-08 07:04:22'),
(154, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6479.JPG', '2026-02-08 07:04:22'),
(155, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6482.JPG', '2026-02-08 07:04:22'),
(156, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6485.JPG', '2026-02-08 07:04:22'),
(157, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6492.JPG', '2026-02-08 07:04:22'),
(158, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6533.JPG', '2026-02-08 07:04:22'),
(159, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6537.JPG', '2026-02-08 07:04:22'),
(160, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6545.JPG', '2026-02-08 07:04:22'),
(161, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6560.JPG', '2026-02-08 07:04:22'),
(162, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6562.JPG', '2026-02-08 07:04:22'),
(163, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6564.JPG', '2026-02-08 07:04:22'),
(164, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6628.JPG', '2026-02-08 07:04:22'),
(165, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6630.JPG', '2026-02-08 07:04:22'),
(166, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6648.JPG', '2026-02-08 07:04:22'),
(167, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6659.JPG', '2026-02-08 07:04:22'),
(168, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6667.JPG', '2026-02-08 07:04:22'),
(169, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6711.JPG', '2026-02-08 07:04:22'),
(170, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6712.JPG', '2026-02-08 07:04:22'),
(171, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6720.JPG', '2026-02-08 07:04:22'),
(172, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6724.JPG', '2026-02-08 07:04:22'),
(173, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6740.JPG', '2026-02-08 07:04:22'),
(174, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6743.JPG', '2026-02-08 07:04:22'),
(175, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6747.JPG', '2026-02-08 07:04:22'),
(176, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6751.JPG', '2026-02-08 07:04:22'),
(177, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6754.JPG', '2026-02-08 07:04:22'),
(178, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6759.JPG', '2026-02-08 07:04:22'),
(179, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6774.JPG', '2026-02-08 07:04:22'),
(180, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6782.JPG', '2026-02-08 07:04:22'),
(181, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6802.JPG', '2026-02-08 07:04:22'),
(182, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6811.JPG', '2026-02-08 07:04:22'),
(183, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6812.JPG', '2026-02-08 07:04:22'),
(184, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6815.JPG', '2026-02-08 07:04:22'),
(185, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6817.JPG', '2026-02-08 07:04:22'),
(186, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6818.JPG', '2026-02-08 07:04:22'),
(187, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6820.JPG', '2026-02-08 07:04:22'),
(188, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6822.JPG', '2026-02-08 07:04:22'),
(189, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6829.JPG', '2026-02-08 07:04:22'),
(190, 8, 'uploads/friendly_cricket_match_Jan_2026/IMG_6841.JPG', '2026-02-08 07:04:22'),
(191, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0005.JPG', '2026-02-08 07:05:08'),
(192, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0007.JPG', '2026-02-08 07:05:08'),
(193, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0008.JPG', '2026-02-08 07:05:08'),
(194, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0010.JPG', '2026-02-08 07:05:08'),
(195, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0011.JPG', '2026-02-08 07:05:08'),
(196, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0012.JPG', '2026-02-08 07:05:08'),
(197, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0032.JPG', '2026-02-08 07:05:08'),
(198, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0033.JPG', '2026-02-08 07:05:08'),
(199, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0037.JPG', '2026-02-08 07:05:08'),
(200, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0038.JPG', '2026-02-08 07:05:08'),
(201, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0046.JPG', '2026-02-08 07:05:08'),
(202, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0047.JPG', '2026-02-08 07:05:08'),
(203, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0050.JPG', '2026-02-08 07:05:08'),
(204, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0051.JPG', '2026-02-08 07:05:08'),
(205, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0052.JPG', '2026-02-08 07:05:08'),
(206, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0053.JPG', '2026-02-08 07:05:08'),
(207, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0057.JPG', '2026-02-08 07:05:08'),
(208, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0059.JPG', '2026-02-08 07:05:08'),
(209, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0060.JPG', '2026-02-08 07:05:08'),
(210, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0066.JPG', '2026-02-08 07:05:08'),
(211, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0068.JPG', '2026-02-08 07:05:08'),
(212, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0069.JPG', '2026-02-08 07:05:08'),
(213, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0073.JPG', '2026-02-08 07:05:08'),
(214, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0075.JPG', '2026-02-08 07:05:08'),
(215, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0077.JPG', '2026-02-08 07:05:08'),
(216, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0088.JPG', '2026-02-08 07:05:08'),
(217, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0091.JPG', '2026-02-08 07:05:08'),
(218, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0092.JPG', '2026-02-08 07:05:08'),
(219, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0110.JPG', '2026-02-08 07:05:08'),
(220, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0112.JPG', '2026-02-08 07:05:08'),
(221, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0113.JPG', '2026-02-08 07:05:08'),
(222, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0115.JPG', '2026-02-08 07:05:08'),
(223, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0138.JPG', '2026-02-08 07:05:08'),
(224, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0140.JPG', '2026-02-08 07:05:08'),
(225, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0149.JPG', '2026-02-08 07:05:08'),
(226, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0804.JPG', '2026-02-08 07:05:08'),
(227, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0809.JPG', '2026-02-08 07:05:08'),
(228, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0810.JPG', '2026-02-08 07:05:08'),
(229, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0811.JPG', '2026-02-08 07:05:08'),
(230, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0813.JPG', '2026-02-08 07:05:08'),
(231, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0814.JPG', '2026-02-08 07:05:08'),
(232, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0815.JPG', '2026-02-08 07:05:08'),
(233, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0818.JPG', '2026-02-08 07:05:08'),
(234, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0821.JPG', '2026-02-08 07:05:08'),
(235, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0823.JPG', '2026-02-08 07:05:08'),
(236, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0827.JPG', '2026-02-08 07:05:08'),
(237, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0829.JPG', '2026-02-08 07:05:08'),
(238, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0832.JPG', '2026-02-08 07:05:08'),
(239, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0834.JPG', '2026-02-08 07:05:08'),
(240, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0835.JPG', '2026-02-08 07:05:08'),
(241, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0836.JPG', '2026-02-08 07:05:08'),
(242, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0839.JPG', '2026-02-08 07:05:08'),
(243, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0841.JPG', '2026-02-08 07:05:08'),
(244, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0844.JPG', '2026-02-08 07:05:08'),
(245, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0845.JPG', '2026-02-08 07:05:08'),
(246, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0846.JPG', '2026-02-08 07:05:08'),
(247, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0847.JPG', '2026-02-08 07:05:08'),
(248, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0849.JPG', '2026-02-08 07:05:08'),
(249, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0851.JPG', '2026-02-08 07:05:08'),
(250, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0852.JPG', '2026-02-08 07:05:08'),
(251, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0856.JPG', '2026-02-08 07:05:08'),
(252, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0862.JPG', '2026-02-08 07:05:08'),
(253, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0863.JPG', '2026-02-08 07:05:08'),
(254, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0866.JPG', '2026-02-08 07:05:08'),
(255, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0868.JPG', '2026-02-08 07:05:08'),
(256, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0870.JPG', '2026-02-08 07:05:08'),
(257, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0872.JPG', '2026-02-08 07:05:08'),
(258, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0875.JPG', '2026-02-08 07:05:08'),
(259, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0876.JPG', '2026-02-08 07:05:08'),
(260, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0879.JPG', '2026-02-08 07:05:08'),
(261, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0880.JPG', '2026-02-08 07:05:08'),
(262, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0882.JPG', '2026-02-08 07:05:08'),
(263, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0884.JPG', '2026-02-08 07:05:08'),
(264, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0911.JPG', '2026-02-08 07:05:08'),
(265, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0916.JPG', '2026-02-08 07:05:08'),
(266, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0928.JPG', '2026-02-08 07:05:08'),
(267, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0929.JPG', '2026-02-08 07:05:08'),
(268, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0935.JPG', '2026-02-08 07:05:08'),
(269, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_0945.JPG', '2026-02-08 07:05:08'),
(270, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9657.JPG', '2026-02-08 07:05:08'),
(271, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9661.JPG', '2026-02-08 07:05:08'),
(272, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9669.JPG', '2026-02-08 07:05:08'),
(273, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9671.JPG', '2026-02-08 07:05:08'),
(274, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9674.JPG', '2026-02-08 07:05:08'),
(275, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9681.JPG', '2026-02-08 07:05:08'),
(276, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9685.JPG', '2026-02-08 07:05:08'),
(277, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9686.JPG', '2026-02-08 07:05:08'),
(278, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9687.JPG', '2026-02-08 07:05:08'),
(279, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9690.JPG', '2026-02-08 07:05:08'),
(280, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9705.JPG', '2026-02-08 07:05:08'),
(281, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9706.JPG', '2026-02-08 07:05:08'),
(282, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9708.JPG', '2026-02-08 07:05:08'),
(283, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9709.JPG', '2026-02-08 07:05:08'),
(284, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9710.JPG', '2026-02-08 07:05:08'),
(285, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9716.JPG', '2026-02-08 07:05:08'),
(286, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9719.JPG', '2026-02-08 07:05:08'),
(287, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9720.JPG', '2026-02-08 07:05:09'),
(288, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9721.JPG', '2026-02-08 07:05:09'),
(289, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9722.JPG', '2026-02-08 07:05:09'),
(290, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9724.JPG', '2026-02-08 07:05:09'),
(291, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9726.JPG', '2026-02-08 07:05:09'),
(292, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9727.JPG', '2026-02-08 07:05:09'),
(293, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9729.JPG', '2026-02-08 07:05:09'),
(294, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9745.JPG', '2026-02-08 07:05:09'),
(295, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9756.JPG', '2026-02-08 07:05:09'),
(296, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9759.JPG', '2026-02-08 07:05:09'),
(297, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9761.JPG', '2026-02-08 07:05:09'),
(298, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9762.JPG', '2026-02-08 07:05:09'),
(299, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9767.JPG', '2026-02-08 07:05:09'),
(300, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9769.JPG', '2026-02-08 07:05:09'),
(301, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9772 (2).JPG', '2026-02-08 07:05:09'),
(302, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9777.JPG', '2026-02-08 07:05:09'),
(303, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9780.JPG', '2026-02-08 07:05:09'),
(304, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9782 - Copy.JPG', '2026-02-08 07:05:09'),
(305, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9786 (2).JPG', '2026-02-08 07:05:09'),
(306, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9788.JPG', '2026-02-08 07:05:09'),
(307, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9791.JPG', '2026-02-08 07:05:09'),
(308, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9793.JPG', '2026-02-08 07:05:09'),
(309, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9794 (2).JPG', '2026-02-08 07:05:09'),
(310, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9795.JPG', '2026-02-08 07:05:09'),
(311, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9797.JPG', '2026-02-08 07:05:09'),
(312, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9798.JPG', '2026-02-08 07:05:09'),
(313, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9806.JPG', '2026-02-08 07:05:09'),
(314, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9807.JPG', '2026-02-08 07:05:09'),
(315, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9811.JPG', '2026-02-08 07:05:09'),
(316, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9812.JPG', '2026-02-08 07:05:09'),
(317, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9813.JPG', '2026-02-08 07:05:09'),
(318, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9818 (2).JPG', '2026-02-08 07:05:09'),
(319, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9818.JPG', '2026-02-08 07:05:09'),
(320, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9819.JPG', '2026-02-08 07:05:09'),
(321, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9822.JPG', '2026-02-08 07:05:09'),
(322, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9829.JPG', '2026-02-08 07:05:09'),
(323, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9842.JPG', '2026-02-08 07:05:09'),
(324, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9845.JPG', '2026-02-08 07:05:09'),
(325, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9848.JPG', '2026-02-08 07:05:09'),
(326, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9849.JPG', '2026-02-08 07:05:09'),
(327, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9859.JPG', '2026-02-08 07:05:09'),
(328, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9862.JPG', '2026-02-08 07:05:09'),
(329, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9864.JPG', '2026-02-08 07:05:09'),
(330, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9867.JPG', '2026-02-08 07:05:09'),
(331, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9878.JPG', '2026-02-08 07:05:09'),
(332, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9880 (2).JPG', '2026-02-08 07:05:09'),
(333, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9892 (2).JPG', '2026-02-08 07:05:09'),
(334, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9895 (2).JPG', '2026-02-08 07:05:09'),
(335, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9896.JPG', '2026-02-08 07:05:09'),
(336, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9898.JPG', '2026-02-08 07:05:09'),
(337, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9901 (2).JPG', '2026-02-08 07:05:09'),
(338, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9905 (2).JPG', '2026-02-08 07:05:09'),
(339, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9907 (2).JPG', '2026-02-08 07:05:09'),
(340, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9911.JPG', '2026-02-08 07:05:09'),
(341, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9918.JPG', '2026-02-08 07:05:09'),
(342, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9919.JPG', '2026-02-08 07:05:09'),
(343, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9920.JPG', '2026-02-08 07:05:09'),
(344, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9921.JPG', '2026-02-08 07:05:09'),
(345, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9935.JPG', '2026-02-08 07:05:09'),
(346, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9938 (2).JPG', '2026-02-08 07:05:09'),
(347, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9939.JPG', '2026-02-08 07:05:09'),
(348, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9947.JPG', '2026-02-08 07:05:09'),
(349, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9948 (2).JPG', '2026-02-08 07:05:09'),
(350, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9949.JPG', '2026-02-08 07:05:09'),
(351, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9951.JPG', '2026-02-08 07:05:09'),
(352, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9954.JPG', '2026-02-08 07:05:09'),
(353, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9963.JPG', '2026-02-08 07:05:09'),
(354, 9, 'uploads/pilgrimage_to_kamarpukur___jayrambati_Jan_2026/IMG_9977.JPG', '2026-02-08 07:05:09'),
(355, 10, 'uploads/sarodutsav_2025_Sep_2025/4f2f16191efc43389e6c05ea935b7f23.jpg', '2026-02-08 07:05:58'),
(356, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0054.JPG', '2026-02-08 07:05:58'),
(357, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0055.JPG', '2026-02-08 07:05:58'),
(358, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0058.JPG', '2026-02-08 07:05:58'),
(359, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0060.JPG', '2026-02-08 07:05:58'),
(360, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0067.JPG', '2026-02-08 07:05:58'),
(361, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0068.JPG', '2026-02-08 07:05:58'),
(362, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0070.JPG', '2026-02-08 07:05:58'),
(363, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0077.JPG', '2026-02-08 07:05:58'),
(364, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0082.JPG', '2026-02-08 07:05:58'),
(365, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0083.JPG', '2026-02-08 07:05:58'),
(366, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0084.JPG', '2026-02-08 07:05:58'),
(367, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0088.JPG', '2026-02-08 07:05:58'),
(368, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0099.JPG', '2026-02-08 07:05:58'),
(369, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0108.JPG', '2026-02-08 07:05:58'),
(370, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0110.JPG', '2026-02-08 07:05:58'),
(371, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0115.JPG', '2026-02-08 07:05:58'),
(372, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0116.JPG', '2026-02-08 07:05:58'),
(373, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0120.JPG', '2026-02-08 07:05:58'),
(374, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0121.JPG', '2026-02-08 07:05:58'),
(375, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0122.JPG', '2026-02-08 07:05:58'),
(376, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0123.JPG', '2026-02-08 07:05:58'),
(377, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0126.JPG', '2026-02-08 07:05:58'),
(378, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0127.JPG', '2026-02-08 07:05:58'),
(379, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0128.JPG', '2026-02-08 07:05:58'),
(380, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0135.JPG', '2026-02-08 07:05:58'),
(381, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0137.JPG', '2026-02-08 07:05:58'),
(382, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0139.JPG', '2026-02-08 07:05:58'),
(383, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0140.JPG', '2026-02-08 07:05:58'),
(384, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0145.JPG', '2026-02-08 07:05:58'),
(385, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0164.JPG', '2026-02-08 07:05:58'),
(386, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0166.JPG', '2026-02-08 07:05:58'),
(387, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0168.JPG', '2026-02-08 07:05:58'),
(388, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0170.JPG', '2026-02-08 07:05:58'),
(389, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0172.JPG', '2026-02-08 07:05:58'),
(390, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0181.JPG', '2026-02-08 07:05:58'),
(391, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0182.JPG', '2026-02-08 07:05:58'),
(392, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0184.JPG', '2026-02-08 07:05:58'),
(393, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0185.JPG', '2026-02-08 07:05:58'),
(394, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0186.JPG', '2026-02-08 07:05:58'),
(395, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0187.JPG', '2026-02-08 07:05:58'),
(396, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0188.JPG', '2026-02-08 07:05:58'),
(397, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0189.JPG', '2026-02-08 07:05:58'),
(398, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0190.JPG', '2026-02-08 07:05:58'),
(399, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0191.JPG', '2026-02-08 07:05:58'),
(400, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0192.JPG', '2026-02-08 07:05:58'),
(401, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0194.JPG', '2026-02-08 07:05:58'),
(402, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0195.JPG', '2026-02-08 07:05:58'),
(403, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0197.JPG', '2026-02-08 07:05:58'),
(404, 10, 'uploads/sarodutsav_2025_Sep_2025/IMG_0198.JPG', '2026-02-08 07:05:58'),
(405, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3347.JPG', '2026-02-08 07:05:58'),
(406, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3349.JPG', '2026-02-08 07:05:58'),
(407, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3352.JPG', '2026-02-08 07:05:58'),
(408, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3359.JPG', '2026-02-08 07:05:58'),
(409, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3360.JPG', '2026-02-08 07:05:58'),
(410, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3364.JPG', '2026-02-08 07:05:58'),
(411, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3366.JPG', '2026-02-08 07:05:58'),
(412, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3367.JPG', '2026-02-08 07:05:58'),
(413, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3374.JPG', '2026-02-08 07:05:58'),
(414, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3375.JPG', '2026-02-08 07:05:58'),
(415, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3378.JPG', '2026-02-08 07:05:58'),
(416, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3381.JPG', '2026-02-08 07:05:58'),
(417, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3392.JPG', '2026-02-08 07:05:58'),
(418, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3401.JPG', '2026-02-08 07:05:58'),
(419, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3403.JPG', '2026-02-08 07:05:58'),
(420, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3422.JPG', '2026-02-08 07:05:58'),
(421, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3426.JPG', '2026-02-08 07:05:58'),
(422, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3433.JPG', '2026-02-08 07:05:58'),
(423, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3441.JPG', '2026-02-08 07:05:58'),
(424, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3462.JPG', '2026-02-08 07:05:58'),
(425, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3463.JPG', '2026-02-08 07:05:58'),
(426, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3464.JPG', '2026-02-08 07:05:58'),
(427, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3465.JPG', '2026-02-08 07:05:58'),
(428, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3481.JPG', '2026-02-08 07:05:58'),
(429, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3487.JPG', '2026-02-08 07:05:58'),
(430, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3492.JPG', '2026-02-08 07:05:58'),
(431, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3494.JPG', '2026-02-08 07:05:58'),
(432, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3498.JPG', '2026-02-08 07:05:58'),
(433, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3519.JPG', '2026-02-08 07:05:58'),
(434, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3529.JPG', '2026-02-08 07:05:58'),
(435, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3530.JPG', '2026-02-08 07:05:58'),
(436, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3531.JPG', '2026-02-08 07:05:58'),
(437, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3532.JPG', '2026-02-08 07:05:58'),
(438, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3533.JPG', '2026-02-08 07:05:58'),
(439, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3535.JPG', '2026-02-08 07:05:58'),
(440, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3538.JPG', '2026-02-08 07:05:58'),
(441, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3539.JPG', '2026-02-08 07:05:58'),
(442, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3540.JPG', '2026-02-08 07:05:58'),
(443, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3541.JPG', '2026-02-08 07:05:58'),
(444, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3545.JPG', '2026-02-08 07:05:58'),
(445, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3549.JPG', '2026-02-08 07:05:58'),
(446, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3551.JPG', '2026-02-08 07:05:58'),
(447, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3555.JPG', '2026-02-08 07:05:58'),
(448, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3556.JPG', '2026-02-08 07:05:58'),
(449, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3558.JPG', '2026-02-08 07:05:58'),
(450, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3559.JPG', '2026-02-08 07:05:58'),
(451, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3560.JPG', '2026-02-08 07:05:58'),
(452, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3565.JPG', '2026-02-08 07:05:58'),
(453, 10, 'uploads/sarodutsav_2025_Sep_2025/_MG_3570.JPG', '2026-02-08 07:05:58');

-- --------------------------------------------------------

--
-- Table structure for table `social_links`
--

CREATE TABLE `social_links` (
  `id` int(11) NOT NULL,
  `platform` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `social_links`
--

INSERT INTO `social_links` (`id`, `platform`, `url`, `created_at`) VALUES
(1, 'Instagram', 'https://www.instagram.com/alanwalkermusic', '2026-02-08 07:30:38'),
(2, 'Facebook', 'https://www.facebook.com/alanwalkermusic/', '2026-02-08 07:31:05'),
(3, 'Twitter', 'https://x.com/IAmAlanWalker?lang=en', '2026-02-08 07:31:50');

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
-- Indexes for table `backgrounds`
--
ALTER TABLE `backgrounds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `photo_id` (`photo_id`);

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
-- Indexes for table `social_links`
--
ALTER TABLE `social_links`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `backgrounds`
--
ALTER TABLE `backgrounds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `photos`
--
ALTER TABLE `photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=454;

--
-- AUTO_INCREMENT for table `social_links`
--
ALTER TABLE `social_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `backgrounds`
--
ALTER TABLE `backgrounds`
  ADD CONSTRAINT `backgrounds_ibfk_1` FOREIGN KEY (`photo_id`) REFERENCES `photos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
