-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 03, 2025 at 10:00 AM
-- Server version: 8.0.41-0ubuntu0.22.04.1
-- PHP Version: 8.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `paradise`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `session_id`, `username`, `email`, `password`) VALUES
(1, '22fbni37pgrcni7v5a0v7nmc0b', 'admin', 'admin@gmail.com', '$2a$12$GoQKOxaw2fUCDpnN/tw3lONGjiR.ul.y8xNl/TxVtSUQto3gUqTeC');

-- --------------------------------------------------------

--
-- Table structure for table `event_halls`
--

CREATE TABLE `event_halls` (
  `hall_id` int NOT NULL,
  `hall_type` enum('Banquet Hall','Function Hall','Conference Hall','Meeting Hall','Party Hall','Rooftop Venue Hall','Wedding Hall') NOT NULL,
  `hall_number` varchar(10) NOT NULL,
  `capacity` varchar(50) NOT NULL,
  `price_per_hour` int NOT NULL,
  `status` enum('Available','Booked','Maintenance') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text NOT NULL,
  `images` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `event_halls`
--

INSERT INTO `event_halls` (`hall_id`, `hall_type`, `hall_number`, `capacity`, `price_per_hour`, `status`, `description`, `images`, `created_at`) VALUES
(1, 'Meeting Hall', 'M-01', '1000', 1000, 'Available', 'A small, professional space designed for intimate business meetings, equipped with a projector, whiteboard, and high-speed internet.', '[\"67933076bae22_benjamin-child-0sT9YhNgSEs-unsplash.jpg\"]', '2025-01-21 13:02:36'),
(2, 'Meeting Hall', 'M-02', '400', 2000, 'Available', 'A spacious and modern meeting room with ample seating, audio-visual equipment, and a comfortable environment for workshops and conferences.', '[\"caique-oliveira--uFOwkmWMDY-unsplash.jpg\"]', '2025-01-21 13:03:40'),
(3, 'Meeting Hall', 'M-03', '100', 3500, 'Available', 'A premium meeting space with a high-tech setup, soundproof walls, multiple screens, and adjustable lighting for any business or corporate event.', '[\"ferran-fusalba-rosello-FPOU7Dxi7pg-unsplash.jpg\"]', '2025-01-21 13:04:46'),
(4, 'Meeting Hall', 'M-04', '60', 2500, 'Available', 'Ideal for mid-sized meetings, this hall features ergonomic chairs, a large screen, and flexible seating arrangements to accommodate various events.', '[\"danielle-cerullo-bIZJRVBLfOM-unsplash.jpg\"]', '2025-01-21 13:05:39'),
(5, 'Banquet Hall', 'B-01', '50', 4000, 'Available', 'A cozy and elegant space perfect for small parties, celebrations, and intimate gatherings, featuring beautiful decor and a sound system.', '[\"b1.jpeg\"]', '2025-01-21 13:08:50'),
(6, 'Banquet Hall', 'B-02', '100', 5000, 'Available', 'A spacious hall with elegant interiors, ideal for weddings, receptions, and large family events. It comes with a stage, lighting, and audio-visual support.', '[\"b2.jpeg\"]', '2025-01-21 13:09:17'),
(7, 'Banquet Hall', 'B-03', '150', 6000, 'Available', 'A  grand hall with luxurious decor and a large stage, ideal for upscale events, parties, and corporate functions. The hall includes state-of-the-art sound systems and lighting.', '[\"b3.jpeg\"]', '2025-01-21 13:09:43'),
(8, 'Banquet Hall', 'B-04', '200', 7500, 'Available', 'A premium banquet hall with elegant furnishings, perfect for grand celebrations like weddings and anniversaries. It offers high-end catering options and a luxurious atmosphere.', '[\"b5.jpeg\"]', '2025-01-21 13:10:18'),
(10, 'Rooftop Venue Hall', 'R-01', '150', 12000, 'Available', 'A grand rooftop space with large seating arrangements, ideal for weddings, receptions, or large parties. It includes customized lighting, sound systems, and a catering service.', '[\"empty-floor-with-modern-business-office-building.jpg\"]', '2025-01-21 13:12:27'),
(11, 'Rooftop Venue Hall', 'R-02', '100', 9000, 'Available', 'A grand rooftop space with large seating arrangements, ideal for weddings, receptions, or large parties. It includes customized lighting, sound systems, and a catering service.', '[\"hotel-lobby-interior.jpg\"]', '2025-01-21 13:13:43'),
(12, 'Wedding Hall', 'W-01', '300', 15000, 'Available', 'A luxurious wedding hall with an expansive space, perfect for large-scale weddings. It offers premium lighting, audio-visual systems, and high-end catering options.', '[\"w4.jpeg\"]', '2025-01-21 13:16:43'),
(13, 'Wedding Hall', 'W-02', '500', 20000, 'Available', 'A grand wedding hall designed for extravagant ceremonies and receptions, featuring exquisite decor, a large stage, premium sound systems, and a dedicated wedding coordinator.', '[\"w5.jpeg\"]', '2025-01-21 13:17:20');

-- --------------------------------------------------------

--
-- Table structure for table `hall_bookings`
--

CREATE TABLE `hall_bookings` (
  `booking_id` int NOT NULL,
  `user_id` int NOT NULL,
  `hall_id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(50) NOT NULL,
  `hall_type` enum('Banquet Hall','Function Hall','Conference Hall','Meeting Hall','Party Hall','Rooftop Venue Hall','Wedding Hall') NOT NULL,
  `hall_number` varchar(10) NOT NULL,
  `capacity` varchar(50) NOT NULL,
  `price_per_hour` int NOT NULL,
  `description` text NOT NULL,
  `check_in` datetime NOT NULL,
  `check_out` datetime NOT NULL,
  `total_price` varchar(7) NOT NULL,
  `payment_status` enum('paid','pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'pending',
  `booking_status` enum('confirmed','cancelled','completed') NOT NULL,
  `created_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `hall_bookings`
--

INSERT INTO `hall_bookings` (`booking_id`, `user_id`, `hall_id`, `username`, `email`, `hall_type`, `hall_number`, `capacity`, `price_per_hour`, `description`, `check_in`, `check_out`, `total_price`, `payment_status`, `booking_status`, `created_at`) VALUES
(455178, 5, 1, 'Ankur Patel', 'ankurpatel@gmail.com', 'Meeting Hall', 'M-01', '20', 1000, 'A small, professional space designed for intimate business meetings, equipped with a projector, whiteboard, and high-speed internet.', '2025-01-27 10:00:00', '2025-01-27 13:00:00', '3000', 'pending', 'confirmed', '2025-01-22 04:25:59'),
(524944, 1, 13, 'Jay Goyani', 'jaygoyani939@gmail.com', 'Wedding Hall', 'W-02', '500', 20000, 'A grand wedding hall designed for extravagant ceremonies and receptions, featuring exquisite decor, a large stage, premium sound systems, and a dedicated wedding coordinator for personalised service.', '2025-02-15 07:00:00', '2025-02-16 00:00:00', '340000', 'pending', 'confirmed', '2025-01-22 04:21:33');

-- --------------------------------------------------------

--
-- Table structure for table `inquiry`
--

CREATE TABLE `inquiry` (
  `inquiry_id` int NOT NULL,
  `user_id` int NOT NULL,
  `login_username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `visitor_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(50) NOT NULL,
  `contact` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `subject` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `message` varchar(900) NOT NULL,
  `admin_reply` text,
  `user_reply` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inquiry`
--

INSERT INTO `inquiry` (`inquiry_id`, `user_id`, `login_username`, `visitor_name`, `email`, `contact`, `subject`, `message`, `admin_reply`, `user_reply`) VALUES
(1, 1, 'Jay Goyani', 'Hetvi Mangukiya', 'hetvimangukiya@gmail.com', '8974589698', 'Inquiry about double bed room booking', 'Inquiry about AC double bed room booking from 20 February to 28 February', 'Okay Available it\r\n', 'Thank you very much');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int NOT NULL,
  `room_type` enum('Single Room','Double Room','Standard Room','Deluxe Room','Quadruple Room','Presidential Room') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `price` int NOT NULL,
  `status` enum('Available','Booked','Maintenance') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `description` text NOT NULL,
  `images` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_type`, `room_number`, `price`, `status`, `description`, `images`, `created_at`) VALUES
(1, 'Standard Room', '104', 1200, 'Available', 'Simple and cozy room with essential amenities, ideal for budget-conscious travellers.', '[\"6799c72f1415f_pexels-pixabay-271619.jpg\"]', '2025-01-21 12:12:46'),
(20, 'Single Room', '106', 2800, 'Available', 'Stylish room with elegant interiors, high-end amenities, and a peaceful ambience for a relaxing stay.', '[\"6799c58962f4f_khadeeja-yasser-msFZE7d9KB4-unsplash.jpg\"]', '2025-01-21 12:20:20'),
(21, 'Single Room', '108', 1800, 'Available', 'Comfortable room with a single bed, modern amenities, and complimentary Wi-Fi.', '[\"67931b28db269_khadeeja-yasser-msFZE7d9KB4-unsplash.jpg\"]', '2025-01-21 12:21:19'),
(22, 'Single Room', '110', 2400, 'Available', 'Spacious room with upgraded decor, a comfortable single bed, and premium amenities.', '[\"olexandr-ignatov-w72a24brINI-unsplash.jpg\"]', '2025-01-21 12:22:06'),
(23, 'Single Room', '109', 900, 'Available', 'Affordable room with a single bed, basic amenities, and a cozy atmosphere for a pleasant stay.', '[\"pexels-pixabay-271619.jpg\"]', '2025-01-21 12:22:42'),
(24, 'Double Room', '102', 2200, 'Available', 'Comfortable room with a double bed, basic amenities, and a relaxing ambience.', '[\"pexels-suhel-vba-1749662-3659683.jpg\"]', '2025-01-21 12:26:13'),
(25, 'Double Room', '203', 2800, 'Available', 'Spacious room with a queen-size bed, modern amenities, and a work desk.', '[\"pexels-pixabay-262048.jpg\"]', '2025-01-21 12:26:55'),
(26, 'Double Room', '306', 3400, 'Available', 'Elegant room with a king-size bed, upgraded decor, and premium facilities for a luxurious stayyyy', 'null', '2025-01-21 12:27:31'),
(27, 'Double Room', '408', 1800, 'Available', 'Affordable room with a double bed and essential amenities for a comfortable stay.', '[\"Budget friendly double room.jpg\"]', '2025-01-21 12:28:09'),
(28, 'Double Room', '510', 3000, 'Available', 'Stylish room with elegant interiors, a king-size bed, a minibar, and premium amenities.', '[\"pexels-pixabay-262048.jpg\"]', '2025-01-21 12:29:05'),
(29, 'Standard Room', '301', 1200, 'Available', 'Basic room with a comfortable bed, essential amenities, and free Wi-Fi for a simple stay.', '[\"s1.jpeg\"]', '2025-01-21 12:36:06'),
(30, 'Standard Room', '302', 1500, 'Available', 'Cozy room with a double bed, air-conditioning, and complimentary toiletries for a pleasant experience.', '[\"s2.jpeg\"]', '2025-01-21 12:36:29'),
(31, 'Standard Room', '305', 1800, 'Available', 'Well-maintained room with a single bed, a flat-screen TV, and a peaceful ambience.', '[\"s3.jpeg\"]', '2025-01-21 12:37:10'),
(32, 'Standard Room', '307', 1700, 'Available', 'Functional room with modern furnishings, a comfortable double bed, and basic amenities for a budget-friendly stay.', '[\"s4.jpeg\"]', '2025-01-21 12:37:40'),
(33, 'Standard Room', '309', 1900, 'Available', 'Simple, clean room with a double bed, air-conditioning, and all essential services for a relaxing stay.', '[\"s5.jpeg\"]', '2025-01-21 12:38:11'),
(34, 'Deluxe Room', '501', 3000, 'Available', 'Elegant room with a king-size bed, luxurious furnishings, and premium amenities for a sophisticated stay.', '[\"pexels-elina-sazonova-1838554.jpg\"]', '2025-01-21 12:41:05'),
(35, 'Deluxe Room', '502', 3200, 'Available', 'Spacious room with a double bed, modern decor, and additional comforts like a minibar and a work desk.', '[\"modern-studio-apartment-design-with-bedroom-living-space.jpg\"]', '2025-01-21 12:41:44'),
(36, 'Deluxe Room', '503', 3500, 'Available', 'Tastefully designed room with a king-size bed, a balcony with scenic views, and enhanced amenities for a relaxing stay.', '[\"luxury-bedroom-hotel.jpg\"]', '2025-01-21 12:42:15'),
(37, 'Deluxe Room', '507', 4000, 'Available', 'Luxurious room with a large bed, contemporary decor, spacious layout, and a full range of premium amenities.', '[\"hotel-room-interior-with-bedroom-area-living-space-kitchen.jpg\"]', '2025-01-21 12:42:52'),
(38, 'Quadruple Room', '601', 4000, 'Available', 'Spacious room with two double beds, perfect for families or groups, equipped with all essential amenities.\r\n', '[\"q1.jpeg\"]', '2025-01-21 12:45:54'),
(39, 'Quadruple Room', '602', 4500, 'Available', 'Large room with two single beds and one double bed, ideal for small groups, with additional comforts like a mini-fridge and TV.', '[\"q2.jpeg\"]', '2025-01-21 12:46:17'),
(40, 'Quadruple Room', '603', 5500, 'Available', 'Comfortable room featuring two queen-size beds, modern decor, and spacious living for four guests.', '[\"q3.jpeg\"]', '2025-01-21 12:46:59'),
(41, 'Quadruple Room', '604', 5500, 'Available', 'Generous space with two beds, perfect for families, featuring cozy interiors and premium amenities for an enjoyable stay.', '[\"q5.jpeg\"]', '2025-01-21 12:47:40'),
(42, 'Quadruple Room', '605', 6000, 'Available', 'Ideal for group travellers, this room offers two king-size beds, luxurious furnishings, and a relaxing atmosphere.', '[\"q4.jpeg\"]', '2025-01-21 12:48:12'),
(43, 'Presidential Room', '701', 15000, 'Available', 'The epitome of luxury, featuring a king-size bed, a private terrace with panoramic views, a spacious lounge, and state-of-the-art amenities. Ideal for VIP guests seeking the finest experience.', '[\"p1.jpeg\"]', '2025-01-21 12:52:43'),
(44, 'Presidential Room', '702', 18000, 'Available', 'A grand room with a royal ambience, featuring an expansive living area, a Jacuzzi, premium furniture, and personalised concierge services for an unforgettable stay.\r\n', '[\"p2.jpeg\"]', '2025-01-21 12:53:22'),
(45, 'Presidential Room', '703', 20000, 'Available', 'Offering unmatched comfort with a private office space, a full-sized dining area, a lavish king-size bed, and top-tier entertainment options. Perfect for elite business and leisure travellers.', '[\"p3.jpeg\"]', '2025-01-21 12:53:54'),
(46, 'Presidential Room', '704', 22000, 'Available', 'This ultra-luxury suite boasts a private pool, a luxurious bathtub with a view, two spacious bedrooms, and exclusive concierge services for an unforgettable experience.', '[\"p5.jpeg\"]', '2025-01-21 12:54:21'),
(47, 'Presidential Room', '777', 25000, 'Available', 'A palatial room with a king-size bed, bespoke interiors, a private cinema room, and direct access to a secluded garden. Experience unmatched comfort and privacy.', '[\"p4.jpeg\"]', '2025-01-21 12:55:15');

-- --------------------------------------------------------

--
-- Table structure for table `room_bookings`
--

CREATE TABLE `room_bookings` (
  `booking_id` int NOT NULL,
  `user_id` int NOT NULL,
  `room_id` int NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(50) NOT NULL,
  `room_type` enum('Single Room','Double Room','Standard Room','Deluxe Room','Quadruple Room','Presidential Room') NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `price` varchar(7) NOT NULL,
  `description` text NOT NULL,
  `check_in` datetime NOT NULL,
  `check_out` datetime NOT NULL,
  `total_price` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `payment_status` enum('paid','pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'pending',
  `booking_status` enum('confirmed','cancelled','completed') NOT NULL,
  `created_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `room_bookings`
--

INSERT INTO `room_bookings` (`booking_id`, `user_id`, `room_id`, `username`, `email`, `room_type`, `room_number`, `price`, `description`, `check_in`, `check_out`, `total_price`, `payment_status`, `booking_status`, `created_at`) VALUES
(205519, 1, 21, 'Jay Goyani', 'jaygoyani939@gmail.com', 'Single Room', '108', '1800', 'Comfortable room with a single bed, modern amenities, and complimentary Wi-Fi.', '2025-02-01 00:00:00', '2025-02-05 00:00:00', '7200.00', 'pending', 'confirmed', '2025-01-22 04:20:06'),
(672609, 5, 25, 'Ankur Patel', 'ankurpatel@gmail.com', 'Double Room', '203', '2800', 'Spacious room with a queen-size bed, modern amenities, and a work desk.', '2025-01-25 00:00:00', '2025-01-27 00:00:00', '5600.00', 'pending', 'confirmed', '2025-01-22 04:25:24'),
(712302, 6, 28, 'Tina Koshiya', 'tinakoshiya@gmail.com', 'Double Room', '510', '3000', 'Stylish room with elegant interiors, a king-size bed, a minibar, and premium amenities.', '2025-02-21 00:00:00', '2025-03-01 00:00:00', '24000.00', 'pending', 'confirmed', '2025-01-28 05:00:46'),
(898186, 6, 47, 'Tina Koshiya', 'tinakoshiya@gmail.com', 'Presidential Room', '777', '25000', 'A palatial room with a king-size bed, bespoke interiors, a private cinema room, and direct access to a secluded garden. Experience unmatched comfort and privacy.', '2025-03-05 00:00:00', '2025-03-07 00:00:00', '50000.00', 'pending', 'confirmed', '2025-01-28 05:02:27');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `username` varchar(70) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  `contact` varchar(10) NOT NULL,
  `gender` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `session_id`, `username`, `email`, `password`, `contact`, `gender`) VALUES
(1, 'e7btnfrhj2mq80fq8klcj5la5d', 'Jay Goyani', 'jaygoyani939@gmail.com', '$2a$12$JN84KkVTPncsi9q56g.4iebaBkrEgrfn1sNBfmXomALrJCKifVuVO', '8238938615', 'male'),
(5, 'mhpj2ue7jscu1oalp7c6sf73k1', 'Ankur Patel', 'ankurpatel@gmail.com', '$2y$10$qd0MHHE6k7din29blviarubR5Lmz0fiyO9k6aQqSwXBRBIHlwz4.C', '9689759845', 'male'),
(6, 'anckr5l2uategpejidaes0o737', 'Tina Koshiya', 'tinakoshiya@gmail.com', '$2y$10$D0WuUDqs2G.hwbOt2IgUROW8nrb5eTCR/gyAnjV1w.8ZqLeePKPoW', '7489658739', 'male');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `event_halls`
--
ALTER TABLE `event_halls`
  ADD PRIMARY KEY (`hall_id`);

--
-- Indexes for table `hall_bookings`
--
ALTER TABLE `hall_bookings`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `inquiry`
--
ALTER TABLE `inquiry`
  ADD PRIMARY KEY (`inquiry_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `room_bookings`
--
ALTER TABLE `room_bookings`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `session_id` (`session_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `event_halls`
--
ALTER TABLE `event_halls`
  MODIFY `hall_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `hall_bookings`
--
ALTER TABLE `hall_bookings`
  MODIFY `booking_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=825119;

--
-- AUTO_INCREMENT for table `inquiry`
--
ALTER TABLE `inquiry`
  MODIFY `inquiry_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `room_bookings`
--
ALTER TABLE `room_bookings`
  MODIFY `booking_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=952938;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
