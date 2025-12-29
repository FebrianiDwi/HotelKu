-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 28, 2025 at 05:14 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_hotel`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `author_id` int NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published','archived') COLLATE utf8mb4_unicode_ci DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `views` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cancellations`
--

CREATE TABLE `cancellations` (
  `id` int NOT NULL,
  `reservation_id` int NOT NULL,
  `booking_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `submission_date` date NOT NULL,
  `reason` enum('change_plans','found_cheaper','emergency','dissatisfied','other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `admin_response` text COLLATE utf8mb4_unicode_ci,
  `refund_amount` decimal(12,2) DEFAULT '0.00',
  `processed_by` int DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cancellations`
--

INSERT INTO `cancellations` (`id`, `reservation_id`, `booking_code`, `submission_date`, `reason`, `details`, `status`, `admin_response`, `refund_amount`, `processed_by`, `processed_at`, `created_at`, `updated_at`) VALUES
(1, 3, 'RS202507D122', '2025-12-26', 'found_cheaper', 'sads', 'approved', 'Pembatalan disetujui. Dana akan dikembalikan sesuai kebijakan.', '0.00', NULL, NULL, '2025-12-26 02:17:56', '2025-12-28 04:54:09'),
(2, 2, 'RS20253D7907', '2025-12-28', 'dissatisfied', 'asd', 'rejected', 'y', '0.00', NULL, NULL, '2025-12-28 02:44:13', '2025-12-28 04:54:04');

-- --------------------------------------------------------

--
-- Table structure for table `checkins`
--

CREATE TABLE `checkins` (
  `id` int NOT NULL,
  `reservation_id` int NOT NULL,
  `booking_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `checkin_time` timestamp NULL DEFAULT NULL,
  `checkout_time` timestamp NULL DEFAULT NULL,
  `status` enum('pending','checked_in','checked_out') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int NOT NULL,
  `reservation_id` int NOT NULL,
  `payment_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` enum('pending','processing','paid','failed','refunded') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `payment_date` timestamp NULL DEFAULT NULL,
  `transaction_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int NOT NULL,
  `booking_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int DEFAULT NULL,
  `room_type_id` int NOT NULL,
  `room_count` int NOT NULL DEFAULT '1',
  `checkin_date` date NOT NULL,
  `checkout_date` date NOT NULL,
  `guest_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guest_email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guest_phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `special_requests` text COLLATE utf8mb4_unicode_ci,
  `total_price` decimal(12,2) NOT NULL,
  `status` enum('pending','confirmed','checked_in','checked_out','completed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `payment_status` enum('pending','paid','refunded','failed') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `booking_code`, `user_id`, `room_type_id`, `room_count`, `checkin_date`, `checkout_date`, `guest_name`, `guest_email`, `guest_phone`, `special_requests`, `total_price`, `status`, `payment_status`, `payment_method`, `created_at`, `updated_at`) VALUES
(1, 'RS2025E6E045', 5, 3, 1, '2025-12-23', '2025-12-25', 'dump Dummy', 'geger@gmail.com', '1212121212', 'ada deh', '3000000.00', 'checked_in', 'pending', NULL, '2025-12-23 02:00:30', '2025-12-24 02:05:55'),
(2, 'RS20253D7907', 5, 2, 1, '2025-12-25', '2026-01-01', 'dump Dummy', 'geger@gmail.com', '2113231212312', 'ssas', '5950000.00', 'checked_in', 'pending', NULL, '2025-12-24 01:47:41', '2025-12-24 02:06:39'),
(3, 'RS202507D122', 5, 2, 1, '2025-12-24', '2025-12-26', 'dump Dummy', 'geger@gmal.com', '012104563421', 's', '1700000.00', 'cancelled', 'pending', NULL, '2025-12-24 02:00:07', '2025-12-28 04:54:09'),
(4, 'RS202520C178', 5, 4, 1, '2025-12-26', '2025-12-27', 'dump Dummy', 'geger@gmail.com', '324234234342', 'sdf', '1200000.00', 'checked_in', 'pending', NULL, '2025-12-26 02:09:53', '2025-12-26 02:10:06'),
(5, 'RS2025054550', 5, 2, 1, '2025-12-26', '2025-12-27', 'dump Dummy', 'geger@gmail.com', '13242312342', 'sdsd', '850000.00', 'pending', 'pending', NULL, '2025-12-26 02:10:36', '2025-12-26 02:10:36');

--
-- Triggers `reservations`
--
DELIMITER $$
CREATE TRIGGER `trg_reservation_status_update` AFTER UPDATE ON `reservations` FOR EACH ROW BEGIN
    IF NEW.status = 'checked_in' AND OLD.status != 'checked_in' THEN
        UPDATE rooms r
        INNER JOIN reservation_rooms rr ON r.id = rr.room_id
        SET r.status = 'occupied'
        WHERE rr.reservation_id = NEW.id;
    END IF;
    
    IF NEW.status = 'checked_out' OR NEW.status = 'completed' THEN
        UPDATE rooms r
        INNER JOIN reservation_rooms rr ON r.id = rr.room_id
        SET r.status = 'available'
        WHERE rr.reservation_id = NEW.id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `reservation_rooms`
--

CREATE TABLE `reservation_rooms` (
  `id` int NOT NULL,
  `reservation_id` int NOT NULL,
  `room_id` int NOT NULL,
  `checkin_date` date NOT NULL,
  `checkout_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int NOT NULL,
  `room_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_type_id` int NOT NULL,
  `floor_number` int DEFAULT NULL,
  `status` enum('available','occupied','maintenance','reserved') COLLATE utf8mb4_unicode_ci DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `room_type_id`, `floor_number`, `status`, `created_at`, `updated_at`) VALUES
(1, '101', 1, 1, 'available', '2025-12-22 01:39:59', '2025-12-22 01:39:59'),
(2, '102', 1, 1, 'available', '2025-12-22 01:39:59', '2025-12-22 01:39:59'),
(3, '201', 2, 2, 'available', '2025-12-22 01:39:59', '2025-12-22 01:39:59'),
(4, '202', 2, 2, 'available', '2025-12-22 01:39:59', '2025-12-22 01:39:59'),
(5, '301', 3, 3, 'available', '2025-12-22 01:39:59', '2025-12-22 01:39:59'),
(6, '401', 4, 4, 'available', '2025-12-22 01:39:59', '2025-12-22 01:39:59');

-- --------------------------------------------------------

--
-- Table structure for table `room_types`
--

CREATE TABLE `room_types` (
  `id` int NOT NULL,
  `type_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price_per_night` decimal(12,2) NOT NULL,
  `max_occupancy` int DEFAULT '2',
  `features` text COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `room_types`
--

INSERT INTO `room_types` (`id`, `type_code`, `type_name`, `description`, `price_per_night`, `max_occupancy`, `features`, `image_url`, `status`, `created_at`, `updated_at`) VALUES
(1, 'standard', 'Standard Room', 'Kamar standar dengan fasilitas lengkap untuk kenyamanan Anda', '500000.00', 2, 'WiFi Gratis, AC, TV Kabel, Sarapan', NULL, 'active', '2025-12-22 01:39:59', '2025-12-22 01:39:59'),
(2, 'deluxe', 'Deluxe Room', 'Kamar deluxe dengan fasilitas premium dan pemandangan yang indah', '850000.00', 2, 'WiFi Gratis, AC, TV Kabel, Sarapan, Mini Bar, Bathtub', NULL, 'active', '2025-12-22 01:39:59', '2025-12-22 01:39:59'),
(3, 'suite', 'Suite Room', 'Kamar suite mewah dengan ruang tamu terpisah dan fasilitas lengkap', '1500000.00', 4, 'WiFi Gratis, AC, TV Kabel, Sarapan, Ruang Tamu, City View, Jacuzzi', NULL, 'active', '2025-12-22 01:39:59', '2025-12-22 01:39:59'),
(4, 'executive', 'Executive Room', 'Kamar executive untuk kebutuhan bisnis dengan fasilitas premium', '1200000.00', 2, 'WiFi Gratis, AC, TV Kabel, Sarapan, Ruang Kerja, Mini Bar', NULL, 'active', '2025-12-22 01:39:59', '2025-12-22 01:39:59');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int NOT NULL,
  `setting_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  `setting_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'text',
  `description` text COLLATE utf8mb4_unicode_ci,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `updated_at`) VALUES
(1, 'hotel_name', 'HotelKu', 'text', 'Nama Hotel', '2025-12-22 01:40:00'),
(2, 'hotel_address', 'Jl. Akomodasi No. 123, Jakarta', 'text', 'Alamat Hotel', '2025-12-22 01:40:00'),
(3, 'hotel_phone', '(021) 1234-5678', 'text', 'Nomor Telepon Hotel', '2025-12-22 01:40:00'),
(4, 'hotel_email', 'info@hotelku.com', 'text', 'Email Hotel', '2025-12-22 01:40:00'),
(5, 'cancellation_policy', 'Pembatalan dapat dilakukan maksimal 24 jam sebelum check-in', 'text', 'Kebijakan Pembatalan', '2025-12-22 01:40:00'),
(6, 'checkin_time', '14:00', 'time', 'Waktu Check-in Default', '2025-12-22 01:40:00'),
(7, 'checkout_time', '12:00', 'time', 'Waktu Check-out Default', '2025-12-22 01:40:00');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int NOT NULL,
  `reservation_id` int DEFAULT NULL,
  `guest_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guest_email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` int NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `role` enum('user','admin') COLLATE utf8mb4_unicode_ci DEFAULT 'user',
  `join_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `phone`, `status`, `role`, `join_date`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'System', 'admin@hotelku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '08123456789', 'active', 'admin', '2025-12-22', '2025-12-22 01:39:59', '2025-12-22 01:39:59'),
(5, 'dump', 'Dummy', 'geger@gma', '$2y$10$tpY.efs.M8PcYTmBWjUKfexOnP12dFqzH/jv5qvFJw/fmL0OXVgHm', '012104563421', 'active', 'user', '2025-12-23', '2025-12-23 01:59:41', '2025-12-23 01:59:41'),
(6, 'hahu', 'uhusg', 'hausg@gmail.com', '$2y$10$5yRf4ug3WKuzsJJKKj0tCe4w96D1SyYsVAADKa2wahpJBJoTL8Ine', '827637162', 'active', 'user', '2025-12-24', '2025-12-24 01:43:04', '2025-12-24 01:43:04'),
(7, 'John', 'Steels', 'User@gmail.com', '$2y$10$id.zrP6GYy0cISKqsQbj9uIX1FGI.5C6R.KmbKtP4OC6X5Vp6SLNm', '012104563421', 'active', 'admin', '2025-12-28', '2025-12-28 03:46:29', '2025-12-28 03:47:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_author` (`author_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_published_at` (`published_at`);

--
-- Indexes for table `cancellations`
--
ALTER TABLE `cancellations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `processed_by` (`processed_by`),
  ADD KEY `idx_reservation` (`reservation_id`),
  ADD KEY `idx_booking_code` (`booking_code`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `checkins`
--
ALTER TABLE `checkins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reservation` (`reservation_id`),
  ADD KEY `idx_booking_code` (`booking_code`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_code` (`payment_code`),
  ADD KEY `idx_reservation` (`reservation_id`),
  ADD KEY `idx_payment_code` (`payment_code`),
  ADD KEY `idx_payment_status` (`payment_status`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_code` (`booking_code`),
  ADD KEY `idx_booking_code` (`booking_code`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_room_type` (`room_type_id`),
  ADD KEY `idx_checkin_date` (`checkin_date`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_payment_status` (`payment_status`),
  ADD KEY `idx_reservations_dates` (`checkin_date`,`checkout_date`),
  ADD KEY `idx_reservations_created` (`created_at`);

--
-- Indexes for table `reservation_rooms`
--
ALTER TABLE `reservation_rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reservation` (`reservation_id`),
  ADD KEY `idx_room` (`room_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_number` (`room_number`),
  ADD KEY `idx_room_type` (`room_type_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_room_number` (`room_number`);

--
-- Indexes for table `room_types`
--
ALTER TABLE `room_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type_code` (`type_code`),
  ADD KEY `idx_type_code` (`type_code`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `idx_setting_key` (`setting_key`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reservation` (`reservation_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_rating` (`rating`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_status` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cancellations`
--
ALTER TABLE `cancellations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `checkins`
--
ALTER TABLE `checkins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reservation_rooms`
--
ALTER TABLE `reservation_rooms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `room_types`
--
ALTER TABLE `room_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cancellations`
--
ALTER TABLE `cancellations`
  ADD CONSTRAINT `cancellations_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cancellations_ibfk_2` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `checkins`
--
ALTER TABLE `checkins`
  ADD CONSTRAINT `checkins_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reservation_rooms`
--
ALTER TABLE `reservation_rooms`
  ADD CONSTRAINT `reservation_rooms_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservation_rooms_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD CONSTRAINT `testimonials_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
