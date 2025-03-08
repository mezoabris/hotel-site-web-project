-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 13, 2025 at 03:12 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bif1webtechdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `Booking`
--

CREATE TABLE `Booking` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `checkin` date DEFAULT NULL,
  `checkout` date DEFAULT NULL,
  `Erwachsene` int(11) DEFAULT NULL,
  `Kinder` int(11) DEFAULT NULL,
  `Frühstück` int(11) DEFAULT 0,
  `Parkplatz` int(11) DEFAULT 0,
  `Haustiere` int(11) DEFAULT 0,
  `confirmed` tinyint(1) DEFAULT 0,
  `booked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Booking`
--

INSERT INTO `Booking` (`booking_id`, `user_id`, `room_id`, `checkin`, `checkout`, `Erwachsene`, `Kinder`, `Frühstück`, `Parkplatz`, `Haustiere`, `confirmed`, `booked_at`) VALUES
(6, 1, 2, '2024-12-10', '2024-12-14', 2, 2, 1, 1, 1, 1, '2025-01-12 15:53:26'),
(7, 1, 3, '2024-12-17', '2024-12-19', 3, 2, 0, 0, 0, 1, '2025-01-12 15:53:26'),
(8, 1, 3, '2025-02-18', '2025-02-21', 2, 0, 0, 0, 0, 1, '2025-01-12 15:53:26'),
(11, 1, 2, '2025-04-13', '2025-04-18', 2, 0, 0, 0, 0, 1, '2025-01-12 15:53:26'),
(15, 6, 3, '2024-12-20', '2025-01-02', 2, 0, 1, 0, 0, 1, '2025-01-12 15:53:26'),
(19, 8, 1, '2025-03-19', '2025-03-23', 2, 0, 1, 0, 0, 0, '2025-01-12 15:53:26'),
(20, 6, 3, '2025-05-12', '2025-05-16', 4, 2, 1, 1, 0, 0, '2025-01-12 15:53:26'),
(21, 6, 2, '2025-01-22', '2025-01-23', 3, 0, 1, 1, 1, 0, '2025-01-12 15:54:02');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `thumbnail_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `thumbnail_path`, `created_at`) VALUES
(1, 'test', 'test', 'news/uploads/thumbnail_of_uploads/thumb_sommerangebot.jpg', '2024-12-10 16:46:47'),
(4, 'test3', 'test3', 'news/uploads/thumbnail_of_uploads/thumb_mainpage_background.jpg', '2024-12-12 07:28:51'),
(7, 'test4', 'test to check upload', 'news/uploads/thumbnail_of_uploads/thumb_Hotelzimmer1_1.jpg', '2025-01-12 15:45:33');

-- --------------------------------------------------------

--
-- Table structure for table `Room`
--

CREATE TABLE `Room` (
  `room_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `availability_status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Room`
--

INSERT INTO `Room` (`room_id`, `price`, `availability_status`) VALUES
(1, 100.00, 1),
(2, 150.00, 1),
(3, 250.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE `User` (
  `user_id` int(11) NOT NULL,
  `anrede` varchar(10) DEFAULT NULL,
  `vorname` varchar(50) DEFAULT NULL,
  `nachname` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `status` varchar(255) DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`user_id`, `anrede`, `vorname`, `nachname`, `email`, `password`, `username`, `is_admin`, `status`) VALUES
(1, 'herr', 'Abris', 'Mezo', 'abris.mezo@gmail.com', '$2y$10$zf.rtH1S52XbF3sOmUXBN.4rpeL2tlIyoSZ5XPPmskaOD96ejWyNy', 'mezoabris', 0, 'active'),
(3, 'herr', 'Admin', 'Admin', 'admin@admin.com', '$2y$10$MHa.etXouSu8h5HTMoCy0.WIsD0XFIHj3StKxeXoz4ShfOXU07REq', 'admin', 1, 'active'),
(4, 'herr', 'Max', 'Mustermann', 'muster@xyz.com', '$2y$10$1zG/U6Pkk39z/qhFYKSbqO7k9cpmK1okkTEhWsAE/i.zKr1CTIVlq', 'maxmustermann', 0, 'active'),
(5, 'frau', 'kamala', 'harris', 'kamala@harris.com', '$2y$10$Jh/kX8dTLwDVDqdS1MXCJO6GNzaqQFIBrWsAoNdNK71byz5bEACIq', 'kamalaharris', 0, 'active'),
(6, 'herr', 'Dwayne', 'Johnson', 'therock@gmail.com', '$2y$10$yZBuCKqU.FOmEUXI.PsXb.2coNZtoF6lKr7wcfP5dtmdP2nrE85b.', 'therock', 0, 'active'),
(8, 'frau', 'Bela', 'Mezo', 'bela.mezo@gmail.com', '$2y$10$IovmLwjtgs9CAAQCvFxFbeqPjXoclHDtgkvehG3mCuD4gut8tr0wm', 'mezobela', 0, 'active'),
(9, 'herr', 'Mate', 'Zadori', 'mate.zadori@gmail.com', '$2y$10$um7B.x937Qhm/9jZBWM6EebZGXp569Vja1W2ZQdGRJTWorjSJZm7G', 'zadorimate', 0, 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Booking`
--
ALTER TABLE `Booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Room`
--
ALTER TABLE `Room`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Booking`
--
ALTER TABLE `Booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `Room`
--
ALTER TABLE `Room`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Booking`
--
ALTER TABLE `Booking`
  ADD CONSTRAINT `Booking_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `User` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Booking_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `Room` (`room_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
