-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 22, 2025 at 03:09 PM
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
-- Database: `db_ticketbooking`
--

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `specialization` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(100) NOT NULL,
  `image_url` varchar(250) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `specialization`, `location`, `contact_number`, `email`, `description`, `status`, `image_url`, `created_at`) VALUES
(3, 'Silverline Electronics', 'Workshop', 'hanwella', '0119203920', 'deelaka.lakpura94@gmail.com', 'sdasd', 'active', './admin/uploads/emcompanies/company_67b5e75e94c9a3.07554565.jpg', '2025-02-19 14:14:54');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` enum('pending','active','completed') DEFAULT 'pending',
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `buyer_name` varchar(255) NOT NULL,
  `buyer_email` varchar(255) NOT NULL,
  `buyer_phone` varchar(20) NOT NULL,
  `ticket_data` text NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `security_companies`
--

CREATE TABLE `security_companies` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `specialization` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `security_companies`
--

INSERT INTO `security_companies` (`id`, `name`, `specialization`, `location`, `contact_number`, `email`, `description`, `status`, `image_url`, `created_at`) VALUES
(1, 'Silverline Electronics', 'Corporate Security', 'homagama', '0119203920', 'deelaka.lakpura94@gmail.com', 'asdsadsad', 'active', './admin/uploads/securitycompanies/security_company_67b7fcdb44f5d0.25964614.jpg', '2025-02-21 04:11:07');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admins`
--

CREATE TABLE `tbl_admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admins`
--

INSERT INTO `tbl_admins` (`admin_id`, `username`, `password_hash`, `email`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '$2y$10$PXhYp.gcvHv4ECOZg4oKG.h66uNDYLJft4NnS4d88Yz.Qh/b9Gru6', 'admin@gmail.com', '2025-02-06 08:56:20', '2025-02-22 11:43:00'),
(2, 'Prime Resort', '$2y$10$I244ZPvKYNkp6vy449efzO1JgN5LeszDIphY8ZweZKHd50ShY0FDq', 'info@softlogic.lk', '2025-02-06 09:00:04', '2025-02-06 09:00:04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bands`
--

CREATE TABLE `tbl_bands` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `genre` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_bands`
--

INSERT INTO `tbl_bands` (`id`, `name`, `genre`, `location`, `contact_number`, `email`, `description`, `status`, `image_url`, `created_at`) VALUES
(1, 'nimal', 'Rock', 'hanwesdlla', '0119203920', 'ddasda@gmail.com', 'nicce', 'active', './admin/uploads/bands/band_67b97c49247677.21915616.jpg', '2025-02-22 07:27:05');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_events`
--

CREATE TABLE `tbl_events` (
  `event_id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_datetime` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `max_capacity` int(11) NOT NULL,
  `event_type` varchar(255) NOT NULL,
  `organizer_name` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `event_description` text NOT NULL,
  `status` varchar(250) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_events`
--

INSERT INTO `tbl_events` (`event_id`, `event_name`, `event_datetime`, `location`, `max_capacity`, `event_type`, `organizer_name`, `image_path`, `event_description`, `status`, `created_at`) VALUES
(1, 'Event 01', '2025-02-08 11:22:00', 'https://maps.app.goo.gl/5JpghmhyHjyDXUxY6', 0, '', '', './uploads/events/1739253180_6.png', 'sdsfsdf', '', '2025-02-11 05:53:00'),
(2, 'Event 01', '2025-02-21 11:28:00', 'https://maps.app.goo.gl/5JpghmhyHjyDXUxY6', 6000, 'Conference', 's', './uploads/events/1739253583_2.png', 'sdadsddsdsd', '', '2025-02-11 05:59:43'),
(3, 'Event 01', '2025-02-28 11:51:00', 'https://maps.app.goo.gl/5JpghmhyHjyDXUxY6', 800, 'Workshop', 'sds', '../uploads/events/1739254921_5.png', 'dsfdsfsd', '', '2025-02-11 06:22:01'),
(4, 'Event 09', '2025-02-28 12:22:00', 'https://maps.app.goo.gl/5JpghmhyHjyDXUxY6', 5400, 'Workshop', 's', './uploads/events/1739256745_6.png', 'sdsdss', 'active', '2025-02-11 06:52:25');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_event_tickets`
--

CREATE TABLE `tbl_event_tickets` (
  `ticket_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `ticket_type` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_event_tickets`
--

INSERT INTO `tbl_event_tickets` (`ticket_id`, `event_id`, `ticket_type`, `price`) VALUES
(1, 1, 'VIP', 4300.00),
(2, 1, 'standerd', 2000.00),
(3, 2, 'VIP', 3000.00),
(4, 2, 'sf', 4300.00),
(5, 2, 'we', 4300.00),
(6, 3, 'VIP', 423.00),
(7, 3, 'sad', 3543.00),
(8, 4, 'VIP', 2700.00),
(9, 4, 'sda', 3244.00);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_messages`
--

CREATE TABLE `tbl_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_messages`
--

INSERT INTO `tbl_messages` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'deelaka', 'admin@nanotech.lk', 'dsdsds', '2025-02-11 05:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_orders`
--

CREATE TABLE `tbl_orders` (
  `order_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `buyer_name` varchar(255) NOT NULL,
  `buyer_email` varchar(255) NOT NULL,
  `buyer_phone` varchar(20) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `tickets` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`tickets`)),
  `ticket_types` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`ticket_types`)),
  `order_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_orders`
--

INSERT INTO `tbl_orders` (`order_id`, `event_id`, `event_name`, `buyer_name`, `buyer_email`, `buyer_phone`, `total_amount`, `tickets`, `ticket_types`, `order_date`) VALUES
(1, 4, 'Event 09', 'deelaka', 'deelaka@gmail.com', '0779605940', 2700.00, '{\"8\":\"1\",\"9\":\"0\"}', '{\"8\":\"VIP\",\"9\":\"sda\"}', '2025-02-22 15:05:07');

-- --------------------------------------------------------

--
-- Table structure for table `venue_rentals`
--

CREATE TABLE `venue_rentals` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL,
  `location` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL,
  `contact_number` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `price_per_hour` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venue_rentals`
--

INSERT INTO `venue_rentals` (`id`, `name`, `type`, `location`, `capacity`, `contact_number`, `email`, `price_per_hour`, `description`, `status`, `image_url`, `created_at`) VALUES
(1, 'dsdsds', 'Banquet Hall', 'hanwella', 500, '0119203920', 'deelaka.lakpura94@gmail.com', 54000.00, 'dgddgd', 'active', './admin/uploads/venues/venue_67b9799eb20db4.51103063.jpg', '2025-02-22 07:15:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `security_companies`
--
ALTER TABLE `security_companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_admins`
--
ALTER TABLE `tbl_admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tbl_bands`
--
ALTER TABLE `tbl_bands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_events`
--
ALTER TABLE `tbl_events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `tbl_event_tickets`
--
ALTER TABLE `tbl_event_tickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `tbl_messages`
--
ALTER TABLE `tbl_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `venue_rentals`
--
ALTER TABLE `venue_rentals`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `security_companies`
--
ALTER TABLE `security_companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_admins`
--
ALTER TABLE `tbl_admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_bands`
--
ALTER TABLE `tbl_bands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_events`
--
ALTER TABLE `tbl_events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_event_tickets`
--
ALTER TABLE `tbl_event_tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tbl_messages`
--
ALTER TABLE `tbl_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_orders`
--
ALTER TABLE `tbl_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `venue_rentals`
--
ALTER TABLE `venue_rentals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_event_tickets`
--
ALTER TABLE `tbl_event_tickets`
  ADD CONSTRAINT `tbl_event_tickets_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `tbl_events` (`event_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
