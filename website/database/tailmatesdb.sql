-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2026 at 09:11 AM
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
-- Database: `tailmatesdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `rehoming_listings`
--

CREATE TABLE `rehoming_listings` (
  `rehoming_id` int(11) NOT NULL,
  `pet_name` varchar(50) NOT NULL,
  `age` varchar(20) DEFAULT 'Unknown',
  `type` varchar(25) NOT NULL,
  `gender` enum('male','female') NOT NULL,
  `breed` varchar(50) DEFAULT NULL,
  `pet_desc` text NOT NULL,
  `rehoming_status` enum('Verified','Unverified') NOT NULL DEFAULT 'Unverified',
  `owner_name` varchar(50) NOT NULL,
  `contact_no` varchar(20) NOT NULL,
  `reason` text NOT NULL,
  `time_listed` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rehoming_listings`
--

INSERT INTO `rehoming_listings` (`rehoming_id`, `pet_name`, `age`, `type`, `gender`, `breed`, `pet_desc`, `rehoming_status`, `owner_name`, `contact_no`, `reason`, `time_listed`, `user_id`) VALUES
(16, 'Test', 'test', 'test', 'female', 'test', 'test', 'Unverified', 'test', 'test', 'test', '2026-04-29 10:25:40', 1),
(17, 'Luna', '2 years old', 'Bird', 'female', 'Cockatiels', 'She is a very happy bird.', 'Verified', 'Aizen', '091234567890', 'I\'m poor. Extremely poor.', '2026-04-29 13:03:45', 1),
(18, 'Robin', '3 years old', 'Cat', 'male', 'Ginger', 'An orange cat with an extremely irritable personality. He pees on the couch, on carpets, everywhere. He sleeps and eats, that\'s basically everything he does. But he\'s very loving and cute.', 'Unverified', 'Aizen', '09123456789', 'Test', '2026-04-29 15:11:53', 1),
(19, 'Name', '2 years old', 'Dog', 'male', 'Aspin', 'This animal is a dog. A dog is an animal. apva;ewoifhae;fnalfnladjfhapfhalkdnfklafalkdfnalkjdnvlajfalflanflkdsn', 'Unverified', 'Me', '0929876543289', 'No reason.', '2026-04-29 15:35:23', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_email` varchar(75) NOT NULL,
  `user_password` char(255) NOT NULL,
  `register_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_password`, `register_date`) VALUES
(1, 'test', 'test', '$2y$10$YRcNZpkbSjDXO9pkRVllK.yT6b7xtBVmvHQArwCyGs2LcD4aJvQ6O', '2026-04-24 15:00:22'),
(2, 'Aizen', 'aizentest@gmail.com', '$2y$10$uOVljbj/hF06b9IXw0m6h.Lw30uKybIBw9IR.sQMKp6cvZJybFb.W', '2026-04-29 14:05:36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rehoming_listings`
--
ALTER TABLE `rehoming_listings`
  ADD PRIMARY KEY (`rehoming_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rehoming_listings`
--
ALTER TABLE `rehoming_listings`
  MODIFY `rehoming_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rehoming_listings`
--
ALTER TABLE `rehoming_listings`
  ADD CONSTRAINT `rehoming_listings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
