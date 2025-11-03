-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2025 at 05:49 PM
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
-- Database: `rentbase`
--

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('open','in_progress','closed') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `tenant_id`, `room_id`, `subject`, `description`, `status`, `created_at`) VALUES
(1, 6, 1, 'i have no electricity', 'please send electrician to check, thank  you', 'open', '2025-10-16 16:20:55'),
(3, 6, 1, 'need food', 'pls food...', 'closed', '2025-10-29 22:32:12'),
(4, 10, 3, 'wifi not working', 'please fix my wifi i have work', 'in_progress', '2025-10-29 22:57:58'),
(5, 11, 4, 'i need power', 'i cant pay now please', 'open', '2025-10-29 23:16:09');

-- --------------------------------------------------------

--
-- Table structure for table `complaint_replies`
--

CREATE TABLE `complaint_replies` (
  `id` int(11) NOT NULL,
  `complaint_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaint_replies`
--

INSERT INTO `complaint_replies` (`id`, `complaint_id`, `user_id`, `message`, `created_at`) VALUES
(1, 5, 7, 'what time are you available?', '2025-11-02 07:49:08'),
(2, 4, 1, 'okay ill be there', '2025-11-02 07:50:18'),
(3, 1, 6, 'hello?\r\n', '2025-11-02 07:51:37');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parking_slots`
--

CREATE TABLE `parking_slots` (
  `id` int(11) NOT NULL,
  `slot_number` varchar(20) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `tenant_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `parking_slots`
--

INSERT INTO `parking_slots` (`id`, `slot_number`, `owner_id`, `tenant_id`) VALUES
(1, 'Slot 1 Block A', 1, 6),
(3, 'Slot 2 Block A', 1, 10),
(4, 'Slot 3 Block A', 1, 2),
(5, 'Slot 1 Block B', 7, 11),
(6, 'Slot 1 Block B', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `tenant_id`, `amount`, `payment_method`, `reference_number`, `payment_date`, `notes`) VALUES
(1, 6, 99999999.99, NULL, NULL, '2025-10-16 00:00:00', 'keep the change'),
(2, 6, 0.10, NULL, NULL, '2025-10-22 00:00:00', 'pabili po ng candy'),
(5, 6, 100.00, NULL, NULL, '2025-10-30 06:30:01', NULL),
(6, 6, 10000.00, NULL, NULL, '2025-10-30 06:30:54', NULL),
(7, 10, 1.00, 'cash', 'PAY202510300657024756', '2025-10-30 06:57:02', 'keep the change'),
(8, 10, 10000.00, 'cash', 'PAY202510300657358659', '2025-10-30 06:57:35', 'give receipt'),
(9, 11, 99.00, 'cash', 'PAY202510300715434027', '2025-10-30 07:15:43', 'payment to owner2');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_number` varchar(20) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `owner_id`, `tenant_id`, `details`) VALUES
(1, '101', 1, 6, 'previous tenant broke the AC, needs fixing meowww'),
(2, '102', 1, 2, 'new aircon'),
(3, '103', 1, 10, 'for marcos'),
(4, '201', 7, 11, 'for quezon');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('owner','tenant','employee') NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `owner_id`, `created_at`) VALUES
(1, 'owner1', '$2y$10$bSnsCrbKputoSDGbxOVTMO9Yj/SotlXyZdRzH8jhCQ4M.olr0ttiq', 'owner', NULL, '2025-10-16 16:00:17'),
(2, 'tenant1', '$2y$10$bSnsCrbKputoSDGbxOVTMO9Yj/SotlXyZdRzH8jhCQ4M.olr0ttiq', 'tenant', 1, '2025-10-16 16:00:17'),
(3, 'employee1', '$2y$10$bSnsCrbKputoSDGbxOVTMO9Yj/SotlXyZdRzH8jhCQ4M.olr0ttiq', 'employee', 1, '2025-10-16 16:00:17'),
(6, 'kikob', '$2y$10$Vm9GK8C7/VPg5MgNqP.Bge3Dc5vrf9ds/rz6tFBxqCXuY4JgTs1gq', 'tenant', 1, '2025-10-16 16:19:06'),
(7, 'owner2', '$2y$10$.mKz1nxwISiWvMjXby9rDuf8Nv3uP796bATFmrtQmWB.LzQB5QIM2', 'owner', NULL, '2025-10-22 09:09:48'),
(9, 'owner3', '$2y$10$NCzFuNBBhFcAq/VdO19HV.ie4cJxE.2uaWKcgnoisZ/UYMcJ3cd1G', 'owner', NULL, '2025-10-29 22:12:06'),
(10, 'marcos', '$2y$10$I4WgVRuX4B7bApH6pUEha.QlNAFnTdYjheXzxHB9DxtzRUuUkhKTi', 'tenant', 1, '2025-10-29 22:33:18'),
(11, 'quezon', '$2y$10$agmlZy7T5ki7dL4d/hqhEO3ircFvbEgrgDC7lXv6FPoVJLrNuIHi2', 'tenant', 7, '2025-10-29 23:14:44'),
(12, 'employee2', '$2y$10$cwErW0F/g9F9gdP6qr0XS.0kEL4wkj77HySm5bISZz8pCnJehC4le', 'employee', 1, '2025-11-02 07:17:15');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`user_id`, `full_name`, `email`, `phone_number`) VALUES
(2, 'Tenant Name', 'tenant1@email.com', '09999999999'),
(3, 'Luffy Jr.', 'luffy@email.com', '09999999999'),
(6, 'Kiko Barzaga', 'kiko@admin.com', '09999999999'),
(10, 'Marcos Jr', 'marcos@email.com', '09999999999'),
(11, 'Quezon Street', 'quezon@email.com', '09999999999'),
(12, 'Employee Owner1', 'employee2@email.com', '09999999998');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tenant_id` (`tenant_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `complaint_replies`
--
ALTER TABLE `complaint_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaint_id` (`complaint_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parking_slots`
--
ALTER TABLE `parking_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `tenant_id` (`tenant_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference_number` (`reference_number`),
  ADD KEY `tenant_id` (`tenant_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `tenant_id` (`tenant_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `complaint_replies`
--
ALTER TABLE `complaint_replies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `parking_slots`
--
ALTER TABLE `parking_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complaints_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `complaint_replies`
--
ALTER TABLE `complaint_replies`
  ADD CONSTRAINT `complaint_replies_ibfk_1` FOREIGN KEY (`complaint_id`) REFERENCES `complaints` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `complaint_replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `parking_slots`
--
ALTER TABLE `parking_slots`
  ADD CONSTRAINT `parking_slots_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `parking_slots_ibfk_2` FOREIGN KEY (`tenant_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rooms_ibfk_2` FOREIGN KEY (`tenant_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_owner_fk` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
