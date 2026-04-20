-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2026 at 01:54 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `fullname` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `modified_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `fullname`, `email`, `password`, `modified_date`, `created_at`) VALUES
(1, 'Ericka ', 'erickakategregorio@gmail.com', '$2y$10$Ut9KPGy2IHBiyOC5mctdZeq5F09r4G92bjVVYLnj/qc1jbuWzMz6e', NULL, '2026-03-16 06:39:38');

-- --------------------------------------------------------

--
-- Table structure for table `admin_login_history`
--

CREATE TABLE `admin_login_history` (
  `admin_login_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `login_time` datetime NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `login_status` enum('success','failed') NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `maps_link` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_login_history`
--

INSERT INTO `admin_login_history` (`admin_login_id`, `admin_id`, `login_time`, `ip_address`, `user_agent`, `latitude`, `longitude`, `login_status`, `city`, `region`, `country`, `maps_link`, `remarks`) VALUES
(1, 1, '2026-03-16 14:40:23', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '', '', 'success', NULL, NULL, NULL, NULL, 'Password verified, OTP sent'),
(2, 1, '2026-03-16 15:47:44', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '', '', 'success', NULL, NULL, NULL, NULL, 'Password verified, OTP sent'),
(3, 1, '2026-03-16 16:04:11', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '', '', 'success', NULL, NULL, NULL, NULL, 'Password verified, OTP sent'),
(4, 1, '2026-03-16 16:53:05', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '10.698937043694807', '122.54189357083308', 'failed', NULL, NULL, NULL, NULL, 'Wrong password'),
(5, 1, '2026-03-16 17:03:16', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '10.698937043694807', '122.54189357083308', 'failed', NULL, NULL, NULL, NULL, 'Wrong password'),
(6, 1, '2026-03-16 17:04:54', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '10.698915137828076', '122.54185208090088', 'failed', 'Altavas', 'Western Visayas', 'Philippines', 'https://www.google.com/maps?q=10.698915137828076,122.54185208090088', 'Wrong password'),
(7, 1, '2026-03-16 17:06:41', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '10.698810947825486', '122.54171261125853', 'failed', 'Altavas', 'Western Visayas', 'Philippines', 'https://www.google.com/maps?q=10.698810947825486,122.54171261125853', 'Wrong password'),
(8, 1, '2026-03-16 17:08:51', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '10.698810947825486', '122.54171261125853', 'success', 'Altavas', 'Western Visayas', 'Philippines', 'https://www.google.com/maps?q=10.698810947825486,122.54171261125853', 'Password verified, OTP sent'),
(9, 1, '2026-03-16 18:27:29', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '10.6994', '122.5659', 'failed', 'Altavas', 'Western Visayas', 'Philippines', 'https://www.google.com/maps?q=10.6994,122.5659', 'Wrong password'),
(10, 1, '2026-03-16 18:31:06', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '10.698804784622217', '122.54171197140751', 'success', 'Altavas', 'Western Visayas', 'Philippines', 'https://www.google.com/maps?q=10.698804784622217,122.54171197140751', 'Password verified, OTP sent'),
(11, 1, '2026-03-16 18:38:08', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '10.698802905164754', '122.54170747190821', 'success', 'Altavas', 'Western Visayas', 'Philippines', 'https://www.google.com/maps?q=10.698802905164754,122.54170747190821', 'Password verified, OTP sent'),
(12, 1, '2026-03-16 18:39:28', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '10.698794020999042', '122.54169314964754', 'success', 'Altavas', 'Western Visayas', 'Philippines', 'https://www.google.com/maps?q=10.698794020999042,122.54169314964754', 'Password verified, OTP sent');

-- --------------------------------------------------------

--
-- Table structure for table `admin_otp_history`
--

CREATE TABLE `admin_otp_history` (
  `admin_otp_history_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `otp_code` varchar(10) NOT NULL,
  `purpose` enum('login_verification','forgot_password') NOT NULL,
  `sent_at` datetime NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL,
  `verified_at` datetime DEFAULT NULL,
  `status` enum('sent','used','expired','failed') NOT NULL DEFAULT 'sent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_otp_history`
--

INSERT INTO `admin_otp_history` (`admin_otp_history_id`, `admin_id`, `otp_code`, `purpose`, `sent_at`, `expires_at`, `verified_at`, `status`) VALUES
(1, 1, '947510', 'login_verification', '2026-03-16 14:40:17', '2026-03-16 14:50:17', '2026-03-16 14:40:53', 'used'),
(2, 1, '947851', 'login_verification', '2026-03-16 15:47:39', '2026-03-16 15:57:39', '2026-03-16 15:48:11', 'used'),
(3, 1, '332519', 'login_verification', '2026-03-16 16:04:05', '2026-03-16 16:14:05', '2026-03-16 16:04:28', 'used'),
(4, 1, '461017', 'login_verification', '2026-03-16 17:08:47', '2026-03-16 17:18:47', '2026-03-16 17:09:08', 'used'),
(5, 1, '936643', 'login_verification', '2026-03-16 18:31:00', '2026-03-16 18:41:00', NULL, 'sent'),
(6, 1, '083155', 'login_verification', '2026-03-16 18:38:03', '2026-03-16 18:48:03', NULL, 'sent'),
(7, 1, '619823', 'login_verification', '2026-03-16 18:39:23', '2026-03-16 18:49:23', NULL, 'sent');

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `instructor_id` int(11) NOT NULL,
  `fullname` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `modified_date` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`instructor_id`, `fullname`, `email`, `password`, `modified_date`, `created_at`) VALUES
(1, 'Carl', 'abrotcarl@gmail.com', '$2y$10$Ut9KPGy2IHBiyOC5mctdZeq5F09r4G92bjVVYLnj/qc1jbuWzMz6e', NULL, '2026-03-16 11:08:26');

-- --------------------------------------------------------

--
-- Table structure for table `instructor_login_history`
--

CREATE TABLE `instructor_login_history` (
  `instructor_login_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `login_time` datetime NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `login_status` enum('success','failed') NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `maps_link` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructor_login_history`
--

INSERT INTO `instructor_login_history` (`instructor_login_id`, `instructor_id`, `login_time`, `ip_address`, `user_agent`, `latitude`, `longitude`, `login_status`, `city`, `region`, `country`, `maps_link`, `remarks`) VALUES
(1, 1, '2026-03-16 19:12:59', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '10.698800411384369', '122.54170630680633', 'failed', 'Altavas', 'Western Visayas', 'Philippines', 'https://www.google.com/maps?q=10.698800411384369,122.54170630680633', 'Wrong password'),
(2, 1, '2026-03-16 19:13:25', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '10.698909551688143', '122.54185535383283', 'success', 'Altavas', 'Western Visayas', 'Philippines', 'https://www.google.com/maps?q=10.698909551688143,122.54185535383283', 'Password verified, OTP sent'),
(3, 1, '2026-03-16 19:16:01', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '10.698809740627274', '122.54170791147457', 'success', 'Altavas', 'Western Visayas', 'Philippines', 'https://www.google.com/maps?q=10.698809740627274,122.54170791147457', 'Password verified, OTP sent'),
(4, 1, '2026-03-16 19:18:41', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', '10.698802937810179', '122.54170210434256', 'success', 'Altavas', 'Western Visayas', 'Philippines', 'https://www.google.com/maps?q=10.698802937810179,122.54170210434256', 'Password verified, OTP sent');

-- --------------------------------------------------------

--
-- Table structure for table `instructor_otp_history`
--

CREATE TABLE `instructor_otp_history` (
  `instructor_otp_history_id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `otp_code` varchar(10) NOT NULL,
  `purpose` enum('login_verification','forgot_password') NOT NULL,
  `sent_at` datetime NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL,
  `verified_at` datetime DEFAULT NULL,
  `status` enum('sent','used','expired','failed') NOT NULL DEFAULT 'sent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructor_otp_history`
--

INSERT INTO `instructor_otp_history` (`instructor_otp_history_id`, `instructor_id`, `otp_code`, `purpose`, `sent_at`, `expires_at`, `verified_at`, `status`) VALUES
(1, 1, '947733', 'login_verification', '2026-03-16 19:13:19', '2026-03-16 19:23:19', NULL, 'sent'),
(2, 1, '420086', 'login_verification', '2026-03-16 19:15:56', '2026-03-16 19:25:56', NULL, 'sent'),
(3, 1, '041568', 'login_verification', '2026-03-16 19:18:36', '2026-03-16 19:28:36', '2026-03-16 19:19:00', 'used');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `fullname` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `fullname`, `email`, `password_hash`, `is_verified`, `created_at`) VALUES
(5, 'Abrot', 'cara.abrot.ui@phinmaed.com', '$2y$10$QrtBatg5j.qhVLJTxqPbEOcp2vWJ9UjeTNjX9na//m3mw/IVnfnv6', 0, '2026-03-13 11:24:57'),
(26, 'Ericka Kate Valenzuela Gregorio', 'erickakategregorio@gmail.com', '$2y$10$Ut9KPGy2IHBiyOC5mctdZeq5F09r4G92bjVVYLnj/qc1jbuWzMz6e', 1, '2026-03-15 07:47:28');

-- --------------------------------------------------------

--
-- Table structure for table `student_lockout_history`
--

CREATE TABLE `student_lockout_history` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `device_info` text NOT NULL,
  `lockout_type` enum('1min','1hr') NOT NULL,
  `lockout_time` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_lockout_history`
--

INSERT INTO `student_lockout_history` (`id`, `student_id`, `ip_address`, `device_info`, `lockout_type`, `lockout_time`) VALUES
(2, 26, '127.0.0.1', 'WebKit | Windows 10.0 | Edge 145.0.0.0', '1min', '2026-03-15 16:26:52');

-- --------------------------------------------------------

--
-- Table structure for table `student_login_history`
--

CREATE TABLE `student_login_history` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `device_info` text NOT NULL,
  `status` enum('success','failed') NOT NULL,
  `login_datetime` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_login_history`
--

INSERT INTO `student_login_history` (`id`, `student_id`, `ip_address`, `device_info`, `status`, `login_datetime`) VALUES
(17, 26, '127.0.0.1', 'WebKit | Windows 10.0 | Edge 145.0.0.0', 'success', '2026-03-15 15:48:12'),
(19, 26, '127.0.0.1', 'WebKit | Windows 10.0 | Edge 145.0.0.0', 'success', '2026-03-15 15:52:09'),
(22, 26, '127.0.0.1', 'WebKit | Windows 10.0 | Edge 145.0.0.0', 'success', '2026-03-15 16:26:09'),
(30, 26, '127.0.0.1', 'WebKit | Windows 10.0 | Edge 146.0.0.0', 'success', '2026-03-16 08:53:15'),
(31, 26, '127.0.0.1', 'WebKit | Windows 10.0 | Edge 146.0.0.0', 'success', '2026-03-16 09:31:04'),
(32, 26, '127.0.0.1', 'WebKit | Windows 10.0 | Edge 146.0.0.0', 'success', '2026-03-16 16:48:10');

-- --------------------------------------------------------

--
-- Table structure for table `student_otp_history`
--

CREATE TABLE `student_otp_history` (
  `otp_history_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `otp_type` enum('signup','login','password_reset') NOT NULL,
  `otp_expiry` datetime NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_otp_history`
--

INSERT INTO `student_otp_history` (`otp_history_id`, `student_id`, `otp`, `otp_type`, `otp_expiry`, `is_used`, `created_at`) VALUES
(19, 26, '083906', 'signup', '2026-03-15 15:57:28', 1, '2026-03-15 07:47:28'),
(20, 26, '219342', 'login', '2026-03-15 16:01:46', 1, '2026-03-15 07:51:46'),
(21, 26, '083236', 'login', '2026-03-15 16:28:37', 1, '2026-03-15 08:18:37'),
(22, 26, '515436', 'login', '2026-03-15 19:16:51', 1, '2026-03-15 11:06:51'),
(23, 26, '330960', 'password_reset', '2026-03-16 07:50:56', 1, '2026-03-15 23:40:56'),
(24, 26, '000094', 'password_reset', '2026-03-16 08:06:17', 1, '2026-03-15 23:56:17'),
(25, 26, '923821', 'login', '2026-03-16 08:10:41', 1, '2026-03-16 00:00:42'),
(26, 26, '927024', 'password_reset', '2026-03-16 08:11:00', 1, '2026-03-16 00:01:00'),
(27, 26, '584887', 'password_reset', '2026-03-16 08:14:55', 1, '2026-03-16 00:04:55'),
(28, 26, '764561', 'password_reset', '2026-03-16 09:01:34', 1, '2026-03-16 00:51:34'),
(29, 26, '461881', 'login', '2026-03-16 09:02:58', 1, '2026-03-16 00:52:58'),
(30, 26, '456358', 'password_reset', '2026-03-16 09:20:33', 1, '2026-03-16 01:10:33'),
(31, 26, '031622', 'password_reset', '2026-03-16 09:27:06', 1, '2026-03-16 01:17:06'),
(32, 26, '496889', 'login', '2026-03-16 09:40:46', 1, '2026-03-16 01:30:46'),
(33, 26, '484112', 'signup', '2026-03-16 09:45:13', 1, '2026-03-16 01:35:13'),
(34, 26, '776300', 'login', '2026-03-16 16:57:52', 1, '2026-03-16 08:47:52');

-- --------------------------------------------------------

--
-- Table structure for table `student_password_history`
--

CREATE TABLE `student_password_history` (
  `history_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `change_type` enum('reset','change') NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `device_info` text DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_password_history`
--

INSERT INTO `student_password_history` (`history_id`, `student_id`, `change_type`, `ip_address`, `device_info`, `changed_at`) VALUES
(1, 26, 'reset', '127.0.0.1', 'WebKit | Windows 10.0 | Edge 146.0.0.0', '2026-03-16 00:52:17'),
(2, 26, 'reset', '127.0.0.1', 'WebKit | Windows 10.0 | Edge 146.0.0.0', '2026-03-16 01:17:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `admin_login_history`
--
ALTER TABLE `admin_login_history`
  ADD PRIMARY KEY (`admin_login_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `admin_otp_history`
--
ALTER TABLE `admin_otp_history`
  ADD PRIMARY KEY (`admin_otp_history_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`instructor_id`);

--
-- Indexes for table `instructor_login_history`
--
ALTER TABLE `instructor_login_history`
  ADD PRIMARY KEY (`instructor_login_id`),
  ADD KEY `fk_instructor_login_history_instructor` (`instructor_id`);

--
-- Indexes for table `instructor_otp_history`
--
ALTER TABLE `instructor_otp_history`
  ADD PRIMARY KEY (`instructor_otp_history_id`),
  ADD KEY `fk_instructor_otp_history_instructor` (`instructor_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `student_lockout_history`
--
ALTER TABLE `student_lockout_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `student_login_history`
--
ALTER TABLE `student_login_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `student_otp_history`
--
ALTER TABLE `student_otp_history`
  ADD PRIMARY KEY (`otp_history_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `student_password_history`
--
ALTER TABLE `student_password_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `fk_student_password_history_student` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_login_history`
--
ALTER TABLE `admin_login_history`
  MODIFY `admin_login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `admin_otp_history`
--
ALTER TABLE `admin_otp_history`
  MODIFY `admin_otp_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `instructor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `instructor_login_history`
--
ALTER TABLE `instructor_login_history`
  MODIFY `instructor_login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `instructor_otp_history`
--
ALTER TABLE `instructor_otp_history`
  MODIFY `instructor_otp_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `student_lockout_history`
--
ALTER TABLE `student_lockout_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student_login_history`
--
ALTER TABLE `student_login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `student_otp_history`
--
ALTER TABLE `student_otp_history`
  MODIFY `otp_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `student_password_history`
--
ALTER TABLE `student_password_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_login_history`
--
ALTER TABLE `admin_login_history`
  ADD CONSTRAINT `admin_login_history_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`) ON DELETE CASCADE;

--
-- Constraints for table `admin_otp_history`
--
ALTER TABLE `admin_otp_history`
  ADD CONSTRAINT `admin_otp_history_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`admin_id`) ON DELETE CASCADE;

--
-- Constraints for table `instructor_login_history`
--
ALTER TABLE `instructor_login_history`
  ADD CONSTRAINT `fk_instructor_login_history_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`instructor_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `instructor_otp_history`
--
ALTER TABLE `instructor_otp_history`
  ADD CONSTRAINT `fk_instructor_otp_history_instructor` FOREIGN KEY (`instructor_id`) REFERENCES `instructors` (`instructor_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student_lockout_history`
--
ALTER TABLE `student_lockout_history`
  ADD CONSTRAINT `student_lockout_history_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_login_history`
--
ALTER TABLE `student_login_history`
  ADD CONSTRAINT `student_login_history_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_otp_history`
--
ALTER TABLE `student_otp_history`
  ADD CONSTRAINT `fk_student_otp_history_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `student_password_history`
--
ALTER TABLE `student_password_history`
  ADD CONSTRAINT `fk_student_password_history_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
