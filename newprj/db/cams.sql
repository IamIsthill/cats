-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 03, 2023 at 05:48 AM
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
-- Database: `cams`
--

-- --------------------------------------------------------

--
-- Table structure for table `attached_org`
--

CREATE TABLE `attached_org` (
  `org_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `org` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attached_org`
--

INSERT INTO `attached_org` (`org_id`, `post_id`, `org`) VALUES
(1, 1, 'Knights'),
(3, 1, 'Flowers'),
(4, 2, 'Flowers'),
(5, 6, 'Flowers');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `remarks` text NOT NULL DEFAULT 'Attended'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `post_id`, `user_id`, `remarks`) VALUES
(1, 1, 14, 'Attended');

-- --------------------------------------------------------

--
-- Table structure for table `audit_trail`
--

CREATE TABLE `audit_trail` (
  `aid` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_trail`
--

INSERT INTO `audit_trail` (`aid`, `user_id`, `action`, `timestamp`) VALUES
(87, 11, 'Super Admin logged in', '2023-11-22 10:08:21'),
(88, 11, 'Super Admin logged in', '2023-11-22 10:08:48'),
(89, 11, 'Super Admin logged out', '2023-11-22 10:08:58'),
(90, 14, 'User logged in', '2023-11-22 10:11:23'),
(91, 14, 'User logged out', '2023-11-22 10:17:29'),
(92, 11, 'Super Admin logged in', '2023-11-22 10:17:46'),
(93, 11, 'Super Admin logged out', '2023-11-22 10:23:41'),
(94, 11, 'Super Admin logged in', '2023-11-22 10:24:00'),
(95, 11, 'Super Admin logged out', '2023-11-22 10:50:55'),
(96, 13, 'Organization Admin logged in', '2023-11-22 10:54:21'),
(97, 13, 'Organization Admin logged out', '2023-11-22 10:58:55'),
(98, 15, 'User logged in', '2023-11-22 12:01:16'),
(99, 15, 'User logged out', '2023-11-22 12:13:05'),
(100, 15, 'User logged in', '2023-11-22 12:13:17'),
(101, 16, 'User logged in', '2023-11-22 12:13:52'),
(102, 17, 'User logged in', '2023-11-22 12:21:30'),
(103, 17, 'User logged out', '2023-11-22 12:26:27'),
(104, 18, 'User logged in', '2023-11-22 12:27:09'),
(105, 18, 'User logged out', '2023-11-23 01:59:47'),
(106, 19, 'User logged in', '2023-11-23 02:16:30'),
(107, 19, 'User logged out', '2023-11-23 02:25:31'),
(108, 20, 'User logged in', '2023-11-23 02:26:04'),
(109, 20, 'User logged out', '2023-11-23 02:37:40'),
(110, 20, 'User logged in', '2023-11-23 02:37:49'),
(111, 20, 'User logged out', '2023-11-25 19:09:33'),
(112, 11, 'Super Admin logged in', '2023-11-25 19:11:27'),
(113, 11, 'Super Admin logged out', '2023-11-25 19:23:35'),
(114, 20, 'User logged in', '2023-11-25 19:23:45'),
(115, 11, 'Super Admin logged in', '2023-11-26 19:39:10'),
(116, 11, 'Super Admin logged out', '2023-11-27 15:27:48'),
(117, 12, 'Church Admin logged in', '2023-11-27 15:28:03'),
(118, 12, 'Church Admin logged out', '2023-11-27 23:34:15'),
(119, 12, 'Church Admin logged in', '2023-11-27 23:34:28'),
(120, 12, 'Church Admin logged out', '2023-11-27 23:40:23'),
(121, 11, 'Super Admin logged in', '2023-11-27 23:40:52'),
(122, 11, 'Super Admin logged out', '2023-11-27 23:55:49'),
(123, 14, 'User logged in', '2023-11-27 23:56:14'),
(124, 14, 'User logged out', '2023-12-01 06:19:05'),
(125, 21, 'User logged in', '2023-12-01 06:25:21'),
(126, 21, 'User logged out', '2023-12-01 19:33:57'),
(127, 22, 'User logged in', '2023-12-01 19:42:00'),
(128, 22, 'User logged out', '2023-12-01 20:09:21'),
(129, 23, 'User logged in', '2023-12-01 20:09:47'),
(130, 14, 'User logged in', '2023-12-01 20:47:30'),
(131, 14, 'User logged out', '2023-12-01 21:04:58'),
(132, 13, 'Organization Admin logged in', '2023-12-01 21:05:07'),
(133, 13, 'Organization Admin logged out', '2023-12-01 21:11:33'),
(134, 11, 'Super Admin logged in', '2023-12-02 08:32:40'),
(135, 11, 'User updated', '2023-12-02 08:53:36'),
(136, 11, 'Super Admin logged out', '2023-12-02 08:59:40'),
(137, 12, 'Church Admin logged in', '2023-12-02 09:06:57'),
(138, 12, 'User updated', '2023-12-02 09:07:33'),
(139, 14, 'User logged in', '2023-12-02 23:33:35'),
(140, 14, 'User updated', '2023-12-02 23:34:06'),
(141, 14, 'User logged out', '2023-12-03 01:03:24'),
(142, 12, 'Church Admin logged in', '2023-12-03 02:03:06'),
(143, 12, 'Church Admin logged out', '2023-12-03 02:16:24'),
(144, 12, 'Church Admin logged in', '2023-12-03 02:16:33'),
(145, 12, 'Church Admin logged out', '2023-12-03 02:51:51'),
(146, 14, 'User logged in', '2023-12-03 02:52:01'),
(147, 14, 'User updated', '2023-12-03 02:53:12');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `name` varchar(255) NOT NULL,
  `Time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`name`, `Time`) VALUES
('C', '2022-03-27 23:37:02'),
('Calvin john placioBarangay papaya, Nasugbu,BatangasCisco network specialist Ulagain', '2022-03-27 23:37:15'),
('Rodelyn F pondare', '2022-03-27 23:46:17'),
('https://dione.batstate-u.edu.ph/student/backend/public/view/id_copy?jwt=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzY2hvb2x5ZWFyIjoiMjAyMS0yMDIyIiwic2VtZXN0ZXIiOiJTRUNPTkQiLCJzcmNvZGUiOiIxOC0yOTgwOCJ9.F9YaT0lKJUdtqWReGCHVm0QmoosW_gXKGWt3VP1OWsU', '2022-03-27 23:53:31');

-- --------------------------------------------------------

--
-- Table structure for table `postlist`
--

CREATE TABLE `postlist` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event` text NOT NULL,
  `date` date NOT NULL,
  `location` text NOT NULL,
  `date_posted` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `qr` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `postlist`
--

INSERT INTO `postlist` (`post_id`, `user_id`, `event`, `date`, `location`, `date_posted`, `qr`) VALUES
(1, 12, 'skdjf', '2023-11-08', 'sdf', '2023-12-03 12:01:36', ''),
(2, 12, 'dfsd', '2023-12-22', 'sdf', '2023-12-03 08:10:26', ''),
(3, 12, 'sd', '2023-11-27', 'sdf', '2023-11-27 23:31:01', ''),
(4, 12, 'qweqw', '2023-12-15', 'manila', '0000-00-00 00:00:00', 'images/qr/1701508887.png'),
(5, 12, 'eat', '2023-12-16', 'cogon', '2023-12-02 17:23:01', 'images/qr/1701508981.png'),
(6, 12, 'Church', '2023-12-22', 'Church', '2023-12-03 08:08:55', ''),
(7, 14, 'Eating', '2023-12-28', 'House', '2023-12-03 11:10:48', 'images/qr/1701573048.png');

-- --------------------------------------------------------

--
-- Table structure for table `userlist`
--

CREATE TABLE `userlist` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `access` varchar(50) NOT NULL,
  `church_member` varchar(3) NOT NULL,
  `email` varchar(50) NOT NULL,
  `organization` text DEFAULT NULL,
  `contact` double DEFAULT NULL,
  `profile_picture` text DEFAULT NULL,
  `password` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userlist`
--

INSERT INTO `userlist` (`id`, `username`, `access`, `church_member`, `email`, `organization`, `contact`, `profile_picture`, `password`) VALUES
(11, 'charles', 'superadmin', 'no', '2021305504@dhvsu.edu.ph', '', 9876543211, 'images/profile/656af090b7f03.jpg', '$2y$10$v5CDYiW61LgXJvSsU9JzhOvKWsyady23gVm/59NrVIslsrt/mINBe'),
(12, 'miko', 'churchadmin', 'yes', 'asd@ads.com', 'Knights', 9876543211, 'images/profile/656af3d5ce803.jpg', '$2y$10$Wdis5za98Jkcsh8.RfixcesS3vRDpmk6HwrXXrV8q6CbBcE6KyfX6'),
(13, 'new', 'orgadmin', 'yes', 'bercasiocharles14@gmail.com', 'Knights', 9876543211, 'images/profile/656a4b1628b31.png', '$2y$10$QYykTGc3SFDIiW1LsWpjvOnlXOCgteL4KZjbFybaNIpTvkbOEJ.4e'),
(14, 'ely', 'user', 'yes', 'asd@as', 'Flowers', 9876543211, 'images/profile/656bed97ef1d3.png', '$2y$10$WSV53hDjbEEpzUQsBFED9uWiPq4AXqpc3FhfuVQh016uUGYUqB2p6'),
(23, 'weer', 'user', '1', 'bercasiocharles14@gmail.comc', NULL, 9876543211, 'images/profile/656a44f08f69f.png', '$2y$10$YfPnOskbcIcFLCyfSHzs..bNSuaCV.Yi5XgnA04apCpYLsjajgehq');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attached_org`
--
ALTER TABLE `attached_org`
  ADD PRIMARY KEY (`org_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`);

--
-- Indexes for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `postlist`
--
ALTER TABLE `postlist`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `userlist`
--
ALTER TABLE `userlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attached_org`
--
ALTER TABLE `attached_org`
  MODIFY `org_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `audit_trail`
--
ALTER TABLE `audit_trail`
  MODIFY `aid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=148;

--
-- AUTO_INCREMENT for table `postlist`
--
ALTER TABLE `postlist`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `userlist`
--
ALTER TABLE `userlist`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
