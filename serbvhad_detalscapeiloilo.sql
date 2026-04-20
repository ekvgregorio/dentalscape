-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 12, 2026 at 10:40 PM
-- Server version: 11.4.10-MariaDB-cll-lve
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `serbvhad_detalscapeiloilo`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','doctor','secretary') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `failed_attempts` int(11) DEFAULT 0,
  `lockout_until` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_login_history`
--

CREATE TABLE `admin_login_history` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `device_info` text NOT NULL,
  `status` enum('success','failed') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `municipality` varchar(255) DEFAULT NULL,
  `neighbourhood` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_login_history`
--

--
-- Table structure for table `admin_otp_history`
--

CREATE TABLE `admin_otp_history` (
  `otp_history_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `otp_expiry` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_used` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_otp_history`
--
-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `gender` enum('Male','Female') DEFAULT 'Male',
  `birthdate` date DEFAULT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `purpose_of_appointment` varchar(255) DEFAULT NULL,
  `doctor` varchar(100) NOT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `service_type` varchar(100) DEFAULT NULL,
  `special_requests` text DEFAULT NULL,
  `status` enum('Pending','Confirmed','Canceled','Done') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_modified` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--


-- --------------------------------------------------------

--
-- Table structure for table `appointment_logs`
--

CREATE TABLE `appointment_logs` (
  `log_id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `performed_by` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `log_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_logs`
--



--
-- Dumping data for table `dental_history`
--

-- --------------------------------------------------------

--
-- Table structure for table `dental_records`
--

CREATE TABLE `dental_records` (
  `record_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `visit_date` date NOT NULL,
  `tooth_number` varchar(10) DEFAULT NULL,
  `diagnosis` text NOT NULL,
  `treatment` text NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dental_reports`
--

CREATE TABLE `dental_reports` (
  `report_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `report_type` varchar(50) NOT NULL,
  `report_title` varchar(100) DEFAULT NULL,
  `report_date` date NOT NULL,
  `issued_by` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `file` varchar(255) NOT NULL,
  `visibility` varchar(50) DEFAULT 'doctor',
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emergency_contacts`
--

CREATE TABLE `emergency_contacts` (
  `contact_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `contact_name` varchar(255) NOT NULL,
  `relationship` varchar(100) NOT NULL,
  `emergency_phone` text DEFAULT NULL,
  `alt_phone` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergency_contacts`
--

INSERT INTO `emergency_contacts` (`contact_id`, `patient_id`, `contact_name`, `relationship`, `emergency_phone`, `alt_phone`) VALUES
(101, 40, 'Rachelle Villa', 'Wife', '', ''),
(102, 43, '', '', '', ''),
(105, 53, 'a', 'a', 'a', 'a');

-- --------------------------------------------------------

--
-- Table structure for table `lockout_history`
--

CREATE TABLE `lockout_history` (
  `lockout_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lockout_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  `device_info` varchar(255) DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `neighbourhood` varchar(100) DEFAULT NULL,
  `municipality` varchar(100) DEFAULT NULL,
  `reason` varchar(255) DEFAULT 'Too many failed login attempts',
  `lockout_type` enum('1min','1hr') DEFAULT '1min'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lockout_history`
--

INSERT INTO `lockout_history` (`lockout_id`, `user_id`, `lockout_time`, `ip_address`, `device_info`, `latitude`, `longitude`, `neighbourhood`, `municipality`, `reason`, `lockout_type`) VALUES
(5, 79, '2025-10-29 02:26:04', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', '10.6987518', '122.5413792', '', '', 'Too many failed login attempts', '1min');

-- --------------------------------------------------------

--
-- Table structure for table `login_history`
--

CREATE TABLE `login_history` (
  `history_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `login_datetime` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  `device_info` varchar(255) DEFAULT NULL,
  `status` enum('success','failed') NOT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `neighbourhood` varchar(255) DEFAULT NULL,
  `municipality` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_history`
--

INSERT INTO `login_history` (`history_id`, `user_id`, `login_datetime`, `ip_address`, `device_info`, `status`, `latitude`, `longitude`, `neighbourhood`, `municipality`) VALUES
(71, 79, '2025-09-30 00:55:56', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 140.0.0.0', 'failed', '10.698830705355668', '122.541830299707', 'Molo', 'Iloilo City'),
(72, 79, '2025-09-30 00:56:05', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 140.0.0.0', 'success', '10.698830705355668', '122.541830299707', 'Molo', 'Iloilo City'),
(73, 79, '2025-10-13 13:13:06', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.69891008153209', '122.54192785009172', '', ''),
(74, 79, '2025-10-14 01:06:40', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.698857678381952', '122.541888932056', '', ''),
(75, 79, '2025-10-14 01:18:48', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'failed', '10.698833876721428', '122.54181657125017', 'Molo', 'Iloilo City'),
(76, 79, '2025-10-14 01:39:48', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.698854381789632', '122.54188533728711', 'Molo', 'Iloilo City'),
(77, 79, '2025-10-14 02:17:48', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.698860380686394', '122.54189154805347', '', ''),
(78, 79, '2025-10-14 04:46:09', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.698813010265061', '122.54180319446222', 'Molo', 'Iloilo City'),
(79, 79, '2025-10-14 04:52:42', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.698804492897327', '122.54177504168544', 'Molo', 'Iloilo City'),
(80, 79, '2025-10-20 14:49:18', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.698913956324468', '122.54194111498997', '', ''),
(81, 79, '2025-10-21 07:15:33', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.698832184916446', '122.54181221337076', 'Molo', 'Iloilo City'),
(82, 79, '2025-10-24 00:46:15', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.698825533856953', '122.54183081780134', 'Molo', 'Iloilo City'),
(83, 79, '2025-10-27 05:10:57', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.6988527', '122.5416667', '', ''),
(84, 79, '2025-10-27 06:55:05', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.7101132', '122.5514044', '', ''),
(85, 79, '2025-10-27 07:00:02', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.6987684', '122.5413599', '', ''),
(86, 79, '2025-10-27 11:05:40', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.6987564', '122.5413433', '', ''),
(87, 79, '2025-10-27 11:14:22', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.6987564', '122.5413433', '', ''),
(88, 79, '2025-10-27 13:58:50', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.7107173', '122.5549395', '', ''),
(89, 79, '2025-10-27 14:38:07', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.6987257', '122.5413645', '', ''),
(90, 79, '2025-10-27 23:02:53', '127.0.0.1', 'iPhone | iOS 18_5 | Safari 18.5', 'success', '10.6987521', '122.5413665', '', ''),
(91, 79, '2025-10-27 23:14:12', '127.0.0.1', 'iPhone | iOS 18_5 | Safari 18.5', 'success', '10.6986604', '122.5413713', '', ''),
(92, 79, '2025-10-27 23:19:26', '127.0.0.1', 'WebKit | Windows 10.0 | Edge 141.0.0.0', 'success', '10.698890679014555', '122.54197491935098', '', ''),
(93, 79, '2025-10-28 00:17:53', '127.0.0.1', 'WebKit | Windows 10.0 | Edge 141.0.0.0', 'failed', '10.698819804892004', '122.54183985022071', '', ''),
(94, 79, '2025-10-28 00:18:26', '127.0.0.1', 'WebKit | Windows 10.0 | Edge 141.0.0.0', 'failed', '10.698819804892004', '122.54183985022071', '', ''),
(95, 79, '2025-10-28 00:20:24', '127.0.0.1', 'WebKit | Windows 10.0 | Edge 141.0.0.0', 'failed', '10.698857684468251', '122.54187737477717', '', ''),
(96, 79, '2025-10-28 00:20:55', '127.0.0.1', 'WebKit | Windows 10.0 | Edge 141.0.0.0', 'failed', '10.698842689522767', '122.54186257924435', '', ''),
(97, 79, '2025-10-28 00:21:14', '127.0.0.1', 'WebKit | Windows 10.0 | Edge 141.0.0.0', 'failed', '10.698842689522767', '122.54186257924435', '', ''),
(98, 79, '2025-10-28 00:43:21', '127.0.0.1', 'WebKit | Windows 10.0 | Edge 141.0.0.0', 'success', '10.69884941020007', '122.54186808799622', '', ''),
(99, 79, '2025-10-28 01:54:58', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.7059379', '122.5556465', '', ''),
(100, 79, '2025-10-28 14:34:28', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.7059379', '122.5556465', '', ''),
(101, 79, '2025-10-28 22:38:18', '127.0.0.1', 'WebKit | AndroidOS 10 | Chrome 141.0.0.0', 'success', '10.6987436', '122.5413597', '', ''),
(102, 79, '2025-10-28 22:55:03', '127.0.0.1', 'WebKit | Windows 10.0 | Edge 141.0.0.0', 'success', '10.698925070498845', '122.54197130376224', '', ''),
(103, 79, '2025-10-28 23:11:42', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.6987417', '122.5413788', '', ''),
(104, 79, '2025-10-29 00:06:59', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.7100234', '122.5577674', '', ''),
(105, 79, '2025-10-29 00:29:48', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.7100234', '122.5577674', '', ''),
(106, 79, '2025-10-29 02:09:11', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.7100234', '122.5577674', '', ''),
(107, 79, '2025-10-29 02:14:17', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.6987484', '122.5413749', '', ''),
(108, 79, '2025-10-29 02:25:40', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'failed', '10.6987518', '122.5413792', '', ''),
(109, 79, '2025-10-29 02:25:54', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'failed', '10.6987518', '122.5413792', '', ''),
(110, 79, '2025-10-29 02:25:56', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'failed', '10.6987518', '122.5413792', '', ''),
(111, 79, '2025-10-29 02:25:58', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'failed', '10.6987518', '122.5413792', '', ''),
(112, 79, '2025-10-29 02:26:01', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'failed', '10.6987518', '122.5413792', '', ''),
(113, 79, '2025-10-30 02:05:22', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'failed', '10.6987469', '122.5413786', '', ''),
(114, 79, '2025-10-30 02:05:40', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'failed', '10.6987478', '122.5413797', '', ''),
(115, 79, '2025-10-30 02:05:47', '127.0.0.1', 'WebKit | Windows 10.0 | Edge 141.0.0.0', 'failed', '10.698925905166169', '122.54196390180587', '', ''),
(116, 79, '2025-10-30 02:07:33', '127.0.0.1', 'WebKit | Windows 10.0 | Edge 141.0.0.0', 'failed', '10.698800346063303', '122.54170005218415', '', ''),
(117, 79, '2025-10-30 02:09:59', '127.0.0.1', 'WebKit | Windows 10.0 | Edge 141.0.0.0', 'failed', '10.698952928631384', '122.54198974546078', '', ''),
(118, 79, '2025-10-30 02:25:53', '127.0.0.1', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.6987696', '122.5413663', '', ''),
(119, 79, '2025-10-30 05:46:40', '103.125.158.214', 'WebKit | AndroidOS 10 | Chrome 141.0.0.0', 'success', '10.6987425', '122.5413568', '', ''),
(120, 79, '2025-10-30 05:52:00', '103.125.158.214', 'WebKit | AndroidOS 10 | Chrome 141.0.0.0', 'success', '10.6987425', '122.5413568', '', ''),
(121, 82, '2025-10-30 05:52:27', '49.147.198.53', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.9189646', '122.5029381', '', ''),
(122, 82, '2025-10-30 06:04:42', '49.147.198.53', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.9189214', '122.5027964', '', ''),
(123, 79, '2025-10-30 06:15:28', '103.125.158.214', 'WebKit | AndroidOS 10 | Chrome 136.0.0.0', 'success', '10.6987453', '122.541357', '', ''),
(124, 84, '2025-10-30 06:16:59', '1.37.86.185', 'WebKit | AndroidOS 10 | Chrome 141.0.0.0', 'success', '10.683187', '122.5045648', '', ''),
(125, 83, '2025-10-30 06:17:15', '49.145.101.144', 'WebKit | AndroidOS 10 | Chrome 131.0.0.0', 'success', '10.9247825', '122.7154125', '', ''),
(126, 85, '2025-10-30 06:40:12', '49.145.101.144', 'WebKit | AndroidOS 10 | Chrome 141.0.0.0', 'success', '10.9247919', '122.7154115', '', ''),
(127, 79, '2025-10-30 09:07:48', '103.125.158.214', 'WebKit | AndroidOS 10 | Chrome 141.0.0.0', 'success', '10.6987456', '122.5413505', '', ''),
(132, 79, '2025-10-30 10:31:41', '103.125.158.214', 'WebKit | AndroidOS 10 | Chrome 141.0.0.0', 'failed', '10.6987449', '122.5413504', '', ''),
(133, 79, '2025-10-30 10:31:52', '103.125.158.214', 'WebKit | AndroidOS 10 | Chrome 141.0.0.0', 'success', '10.6987432', '122.5413549', '', ''),
(134, 79, '2025-10-31 00:49:25', '110.54.182.66', 'WebKit | AndroidOS 10 | Chrome 141.0.0.0', 'success', '10.8260174', '122.7123138', '', ''),
(136, 79, '2025-11-09 11:46:59', '103.125.158.214', 'WebKit | AndroidOS 10 | Chrome 142.0.0.0', 'failed', '10.6987477', '122.5413501', '', ''),
(137, 79, '2025-11-09 11:47:11', '103.125.158.214', 'WebKit | AndroidOS 10 | Chrome 142.0.0.0', 'failed', '10.6987453', '122.5413509', '', ''),
(138, 79, '2025-11-09 11:48:01', '103.125.158.214', 'WebKit | AndroidOS 10 | Chrome 142.0.0.0', 'failed', '10.6987453', '122.5413509', '', ''),
(139, 79, '2025-11-09 11:49:01', '103.125.158.214', 'WebKit | AndroidOS 10 | Chrome 142.0.0.0', 'success', '10.6987453', '122.5413509', '', ''),
(140, 79, '2025-11-11 02:16:40', '103.125.158.214', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.710121', '122.5528185', '', ''),
(141, 79, '2025-11-11 03:40:40', '103.125.158.214', 'iPhone | iOS 18_5 | Safari 18.5', 'success', '10.6987503', '122.5413715', '', ''),
(142, 79, '2025-11-12 06:18:11', '103.125.158.214', 'WebKit | AndroidOS 10 | Chrome 142.0.0.0', 'success', '10.6987405', '122.5413548', '', ''),
(143, 79, '2025-11-12 08:37:49', '103.125.158.214', 'WebKit | AndroidOS 10 | Chrome 142.0.0.0', 'failed', '10.6987455', '122.5413305', '', ''),
(144, 79, '2025-11-12 08:38:17', '103.125.158.214', 'WebKit | AndroidOS 10 | Chrome 142.0.0.0', 'success', '10.6987455', '122.5413305', '', ''),
(145, 79, '2025-11-12 13:28:43', '103.125.158.214', 'WebKit | AndroidOS 10 | Chrome 142.0.0.0', 'success', '10.6987369', '122.5413386', '', ''),
(146, 79, '2025-11-12 22:26:57', '103.125.158.214', 'WebKit | AndroidOS 10 | Chrome 142.0.0.0', 'success', '10.6987344', '122.541332', '', ''),
(147, 79, '2025-11-12 22:33:13', '103.125.158.214', 'WebKit | AndroidOS 10 | Chrome 142.0.0.0', 'success', '10.6987369', '122.5413411', '', ''),
(148, 79, '2025-11-13 00:00:49', '110.54.182.2', 'WebKit | AndroidOS 10 | Chrome 142.0.0.0', 'success', '10.6918044', '122.5700564', '', ''),
(149, 90, '2025-11-13 00:03:02', '119.93.148.179', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.6918448', '122.5700457', '', ''),
(150, 79, '2025-11-13 00:34:21', '110.54.182.2', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.6919302', '122.5699757', '', ''),
(151, 92, '2025-11-13 00:55:23', '175.176.77.200', 'iPhone | iOS 18_7 | Safari 26.0.1', 'success', '10.691831035788574', '122.57003609603838', '', ''),
(152, 92, '2025-11-13 00:55:27', '175.176.77.200', 'iPhone | iOS 18_7 | Safari 26.0.1', 'success', '10.691831035788574', '122.57003609603838', '', ''),
(153, 92, '2025-11-13 04:22:27', '143.44.196.226', 'iPhone | iOS 18_7 | Safari 26.0.1', 'success', '10.694269502547833', '122.56628499237276', '', ''),
(154, 93, '2025-11-13 05:17:52', '143.44.196.226', 'WebKit | AndroidOS 10 | Chrome 141.0.0.0', 'success', '10.6942709', '122.5663357', '', ''),
(155, 79, '2025-11-16 03:57:19', '103.125.158.214', 'WebKit | AndroidOS 10 | Chrome 142.0.0.0', 'success', '10.6987336', '122.5413306', '', ''),
(156, 79, '2025-11-16 09:43:53', '103.125.158.214', 'WebKit | AndroidOS 10 | Chrome 142.0.0.0', 'success', '10.6987333', '122.5413343', '', ''),
(157, 79, '2025-11-16 10:05:45', '103.125.158.214', 'WebKit | Windows 10.0 | Chrome 141.0.0.0', 'success', '10.7072374', '122.5514044', '', ''),
(158, 94, '2025-11-20 03:19:03', '143.44.196.54', 'WebKit | Windows 10.0 | Chrome 142.0.0.0', 'success', '10.7184128', '122.5621504', '', ''),
(159, 95, '2025-11-20 03:36:25', '143.44.196.54', 'WebKit | Windows 10.0 | Chrome 142.0.0.0', 'failed', '10.7184128', '122.5621504', '', ''),
(160, 95, '2025-11-20 03:36:48', '143.44.196.54', 'WebKit | Windows 10.0 | Chrome 142.0.0.0', 'failed', '10.7184128', '122.5621504', '', ''),
(161, 95, '2025-11-20 03:38:18', '143.44.196.54', 'WebKit | Windows 10.0 | Chrome 142.0.0.0', 'success', '10.7184128', '122.5621504', '', ''),
(162, 97, '2025-11-20 04:47:42', '124.217.16.37', 'iPhone | iOS 18_7 | Mozilla ', 'success', '10.709959967881936', '122.55198602796344', '', ''),
(163, 97, '2025-11-20 04:49:41', '124.217.16.37', 'iPhone | iOS 18_7 | Mozilla ', 'success', '10.709940583670864', '122.55198152268565', '', ''),
(164, 98, '2025-11-20 04:57:02', '49.145.121.61', 'WebKit | Windows 10.0 | Chrome 142.0.0.0', 'success', '11.993088', '120.2159616', '', ''),
(165, 96, '2025-11-20 05:06:24', '175.176.77.223', 'iPhone | iOS 17_5_1 | Mozilla ', 'success', '10.731479569878724', '122.54104714898494', '', ''),
(166, 99, '2025-11-20 05:14:17', '49.145.121.61', 'WebKit | Windows 10.0 | Chrome 142.0.0.0', 'success', '11.993088', '120.2159616', '', ''),
(167, 79, '2025-11-24 12:55:13', '103.125.158.214', 'WebKit | Windows 10.0 | Chrome 142.0.0.0', 'success', '10.6988039', '122.5413351', '', ''),
(168, 79, '2026-03-11 08:01:27', '112.198.112.136', 'WebKit | AndroidOS 10 | Chrome 145.0.0.0', 'success', '10.7150727', '122.5461817', '', ''),
(169, 79, '2026-03-11 08:59:44', '112.198.112.136', 'WebKit | AndroidOS 10 | Chrome 145.0.0.0', 'success', '10.7150929', '122.5461766', '', ''),
(170, 101, '2026-03-21 05:12:13', '49.145.128.187', 'WebKit | Windows 10.0 | Chrome 145.0.0.0', 'success', '10.690391126234172', '122.57203520689535', '', ''),
(171, 82, '2026-03-28 14:58:15', '49.147.193.16', 'WebKit | Windows 10.0 | Chrome 146.0.0.0', 'success', '10.91921476480761', '122.50275316731516', '', ''),
(172, 79, '2026-04-13 01:06:24', '103.125.158.214', 'WebKit | Windows 10.0 | Chrome 146.0.0.0', 'success', '10.698901887970777', '122.54184119106122', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `medical_history`
--

CREATE TABLE `medical_history` (
  `history_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `physician_name` varchar(150) DEFAULT NULL,
  `specialty` varchar(150) DEFAULT NULL,
  `office_address` varchar(255) DEFAULT NULL,
  `office_number` varchar(100) DEFAULT NULL,
  `good_health` enum('yes','no') DEFAULT NULL,
  `medical_treatment` enum('yes','no') DEFAULT NULL,
  `medical_condition` text DEFAULT NULL,
  `serious_illness` enum('yes','no') DEFAULT NULL,
  `serious_illness_details` text DEFAULT NULL,
  `hospitalized` enum('yes','no') DEFAULT NULL,
  `hospitalization_details` text DEFAULT NULL,
  `prescription` enum('yes','no') DEFAULT NULL,
  `prescription_details` text DEFAULT NULL,
  `tobacco` enum('yes','no') DEFAULT NULL,
  `alcohol_drugs` enum('yes','no') DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `other_allergy` text DEFAULT NULL,
  `bleeding_time` varchar(100) DEFAULT NULL,
  `pregnant` enum('yes','no') DEFAULT NULL,
  `nursing` enum('yes','no') DEFAULT NULL,
  `birth_control` enum('yes','no') DEFAULT NULL,
  `blood_type` varchar(100) DEFAULT NULL,
  `blood_pressure` varchar(100) DEFAULT NULL,
  `medical_conditions` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_history`
--

INSERT INTO `medical_history` (`history_id`, `patient_id`, `physician_name`, `specialty`, `office_address`, `office_number`, `good_health`, `medical_treatment`, `medical_condition`, `serious_illness`, `serious_illness_details`, `hospitalized`, `hospitalization_details`, `prescription`, `prescription_details`, `tobacco`, `alcohol_drugs`, `allergies`, `other_allergy`, `bleeding_time`, `pregnant`, `nursing`, `birth_control`, `blood_type`, `blood_pressure`, `medical_conditions`, `created_at`) VALUES
(27, 40, 'Xw8PrKGZBDLZYq+3EU3nIjlzWHI5ejV6ZjN6ZUFpWmFyZWxKMEE9PQ==', '90tCKQGO2HIYsFvy8f4rFDlVT01VZkJvczk0LzMzcDBWUXNnTnc9PQ==', 'Cwyzn0k5jRY3zYxnBShe7mZFaHBrZ2FTSDZtbFpXcDR6MUR2V2c9PQ==', 'wEdChE1C0xKZKRvzudXZIDhyWkZHQlBSckVHa2QzWm8vYVN4cUE9PQ==', 'yes', 'no', 'cJMmq3eaAvs9B4vVj/lmTVhkUisvQkRkWStVaFZVNjZDNC9IVEE9PQ==', 'no', 'ed1a61yEGHAM0m8U9FBXyEF1bDJlSVhvazZhb1JEeHZpZWlpelE9PQ==', 'no', 'zcclXDJmtR6HzBe0lpE1NUhaU2RJK2VRTzhBR25vblN2emtTK1E9PQ==', 'no', 'Km5vT0aLD4+xzpqOEmAQd0JBT1h0N2hsMzczWjVsWFdLbVRMdWc9PQ==', 'no', 'no', 'DVOanVZoB2NHzYNIkJBCvVFPZHZscWdlcy9ZOGx1Tmg5aVRIV0E9PQ==', 'YgZ6JhuFWPe8F3vgs/RXh2xVN2crL09tTG15SHp4WU5WNTZIanc9PQ==', 'Ysq2vb7kQ2GwOysIk+HcLDBoZUpkSVVIZVUyWGtXVzVoTWRCbGc9PQ==', 'no', 'no', 'no', 'eW1uN92aTQUT0+i+l0connh6WEtodnFIRjZFRWZ0WnBCWEF3RGc9PQ==', 'WRPJyEEjE29AycLSHuKGCFFGMWZhS0ZlWWZPWkRWM3FxRUpJWXc9PQ==', 'PGwAcx4IlyCt6HP4mYBlHU5wWis3WmlzMHZlYlBzSU84UzEvK3c9PQ==', '2025-11-13 04:56:40'),
(28, 43, 'KUGq+8X427S88A+aI/RGITJ5WUZMeVpWN010KzFmRU8vVGZTc0E9PQ==', 'bsrl+qjrRkzy5N9LdELZd3E2VHhhNkdCL2ZwcTUwVHNzMGtQWXc9PQ==', 'jFg+CYEo2lJBSHoGokIKcFIvQlR1UGhmVmtOTXNUdmxscVJUb0E9PQ==', 'KBLoL1UXt7iUujO+GWbRF05uTWVzNmVZNW0vVmI5N3RzQndpeGc9PQ==', 'yes', 'no', '7DWiW6TccG00TxtGuPftE0ZCSUJkNE1obE8zOFltN01qMHpDZVE9PQ==', 'no', 'uuC2atE4J9OKZ5ZiUUzQ4WIxOGVKRDZuNEI1Q2l5QTBvTVM2T1E9PQ==', 'no', 'GFjcQn56AfmJkK4D5xyOcDV4Y0Jnb3RzNjVXQXNxcWlNem9hdXc9PQ==', 'no', 'BEwKaqWka2nMPvUHmY3d2FVUdURSTnFQb0tsR0k0RVAyRGppV3c9PQ==', 'no', 'no', '90JL74IZC9ONQvwCUSI3i0xleldVVFFGOGZwczVjR011a2d4Rnc9PQ==', '6p/gg1sAJ2dLJCgGiKOXqnRwTDhXSWdncFppNkxXbUlUMFZkTXc9PQ==', 'XtD5z41rxjsP5yFiN8Vd6jNydXRxRldJTTJvcFJVaTEwSEdrVEE9PQ==', 'no', 'no', 'no', 'IV1uhShFmz9CbHb+PSgmZm8rcVlKUzhjbEhHb0FTeWRxT25qZXc9PQ==', 'ZfeJg7qm30QPUDIojtev4EhTYTlhZlIyTzNCdFptWTJnNTY3dUE9PQ==', 'F2LgJVnM1eopZ6FJzM1NBzVFUzRyY1U4ZnMxaVZKcDFaV1hhQWc9PQ==', '2025-11-13 05:26:53'),
(31, 53, 'j5VDQ5xHNP8phS4aUBKMkjFPN0s0c09LTERzTWU0NW5rTmdFanc9PQ==', '8pP5KTNoqcdH2GAC3nQqE2JmSzZpTU5FNUdUQUdVOWgxdVc5WWc9PQ==', 'FzMLPTYUk0cUNLlTiwKyu1p5RWtOQjk1NFE0cGRITWUwUEFWUWc9PQ==', 'Xn5P0iSfyIr2knTDNX2cclhnVkpITVdKUGVuMDcvTDV2eEQrV2c9PQ==', 'no', 'no', 'DOMgU1l7kPMI/zQhf0N1+jFYRVlaQm5zZVo3Y3pMRzlBY1pqdUE9PQ==', 'no', 'gX0mBOLTq8zsN32RIK3az2x6NzFoSGs0RTg4MlhUandySmFSRVE9PQ==', 'no', 'd7tbtl5bq4WIgApp2cmoOG8rbmZIbWY1MlpnRFZSem5lYmRxdXc9PQ==', 'no', 'RH1EPs0qOPRpwcQRMxDRl3hyQlBRUWVUbkhTaWROZ3FpQVROTUE9PQ==', 'no', 'no', 'oSBgKAeliRpgGyvARn3pETBhTUt5dlRpcE1wellLdEdVOENzMmZIMmpMdms4YmRrQzBXb2l2aWdGams9', 'EZmE3OQ0DAbfYBAbuQrsOktibEFwUmlzWEZvaHNBRzkyZWFoaFE9PQ==', '4lnzIBtFGy1xGic9tMrpbTdGYytTTGdHV003M3VKck13SElSekE9PQ==', 'no', 'no', 'no', 'rd1AOuDHRfSsjR6LYc2RFXNLRURLQnBRNk92MWI5YVFJOWxlT3c9PQ==', 'CXHrDnvgGA/O5wzUXhVTSEhRMFEzSkpGckNvbkJSUWxhb0ZXb3c9PQ==', 'm+YwSQ+z92HY/qkQc0128jNaeUZRMDBocDRUU3FKa3loa3pkZ2c9PQ==', '2025-11-16 09:40:24');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otp_history`
--

CREATE TABLE `otp_history` (
  `otp_history_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `otp_type` enum('signup','login','password_reset','email_change') NOT NULL,
  `otp_expiry` datetime NOT NULL,
  `is_used` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp_history`
--

INSERT INTO `otp_history` (`otp_history_id`, `user_id`, `otp`, `otp_type`, `otp_expiry`, `is_used`, `created_at`) VALUES
(185, 79, 'dBxcS8', 'signup', '2025-09-30 09:05:15', 1, '2025-09-30 00:55:15'),
(186, 79, 'bZZ3nk', 'login', '2025-09-30 09:06:05', 1, '2025-09-30 00:56:05'),
(187, 79, 'PX5Y0h', 'login', '2025-10-13 21:23:06', 1, '2025-10-13 13:13:06'),
(188, 79, 'LI8kZu', 'login', '2025-10-14 09:16:40', 1, '2025-10-14 01:06:40'),
(189, 79, 'wF21wD', 'password_reset', '2025-10-14 09:21:55', 1, '2025-10-14 01:11:55'),
(190, 79, 'CeJ8V0', 'password_reset', '2025-10-14 09:22:37', 1, '2025-10-14 01:12:37'),
(191, 79, 'T7bYGm', 'password_reset', '2025-10-14 09:30:44', 1, '2025-10-14 01:20:44'),
(193, 79, 'SH7kxr', 'password_reset', '2025-10-14 09:46:57', 1, '2025-10-14 01:36:57'),
(194, 79, '0sSnRZ', 'login', '2025-10-14 09:49:48', 1, '2025-10-14 01:39:48'),
(195, 79, 'n7AJ7W', 'login', '2025-10-14 10:27:48', 1, '2025-10-14 02:17:48'),
(196, 79, 'g5jFPF', 'login', '2025-10-14 12:56:09', 1, '2025-10-14 04:46:09'),
(197, 79, 'i7QyXI', 'login', '2025-10-14 13:02:42', 1, '2025-10-14 04:52:42'),
(198, 79, '05ncY6', 'login', '2025-10-20 22:59:18', 1, '2025-10-20 14:49:18'),
(199, 79, '8S1Cxd', 'login', '2025-10-21 15:25:33', 1, '2025-10-21 07:15:33'),
(200, 79, 'c8wjIH', 'login', '2025-10-24 08:56:15', 1, '2025-10-24 00:46:15'),
(201, 79, 'viya4e', 'login', '2025-10-27 13:20:57', 1, '2025-10-27 05:10:57'),
(202, 79, 'rOl2QT', 'login', '2025-10-27 15:05:05', 1, '2025-10-27 06:55:05'),
(203, 79, 'Ck9R0U', 'login', '2025-10-27 15:10:02', 1, '2025-10-27 07:00:02'),
(204, 79, 'mV4o1R', 'login', '2025-10-27 19:15:40', 1, '2025-10-27 11:05:40'),
(205, 79, 'K3UiNs', 'login', '2025-10-27 19:24:22', 1, '2025-10-27 11:14:22'),
(206, 79, 'C4sthr', 'login', '2025-10-27 22:08:50', 1, '2025-10-27 13:58:50'),
(207, 79, 'mauOs8', 'login', '2025-10-27 22:48:07', 1, '2025-10-27 14:38:07'),
(208, 79, 'k1RO20', 'login', '2025-10-28 07:12:53', 1, '2025-10-27 23:02:53'),
(209, 79, 'CGLWU1', 'login', '2025-10-28 07:24:12', 1, '2025-10-27 23:14:12'),
(210, 79, 'Xefp1O', 'login', '2025-10-28 07:29:26', 1, '2025-10-27 23:19:26'),
(211, 79, 'bvW5O8', 'password_reset', '2025-10-28 08:11:05', 1, '2025-10-28 00:01:05'),
(212, 79, 'z6DFSH', 'password_reset', '2025-10-28 08:25:12', 1, '2025-10-28 00:15:12'),
(213, 79, 'K4Lx85', 'login', '2025-10-28 08:53:21', 1, '2025-10-28 00:43:21'),
(214, 79, 'S8uvaQ', 'login', '2025-10-28 10:04:58', 1, '2025-10-28 01:54:58'),
(215, 79, 'VTF2hE', 'login', '2025-10-28 22:44:28', 1, '2025-10-28 14:34:28'),
(216, 79, '5bnfKv', 'login', '2025-10-29 06:48:18', 1, '2025-10-28 22:38:18'),
(217, 79, 'lkxtc9', 'login', '2025-10-29 07:05:03', 1, '2025-10-28 22:55:03'),
(218, 79, '8FtWgi', 'login', '2025-10-29 07:21:42', 1, '2025-10-28 23:11:42'),
(219, 79, '0vV0ld', 'login', '2025-10-29 08:16:59', 1, '2025-10-29 00:06:59'),
(220, 79, 'eWS3NX', 'login', '2025-10-29 08:39:48', 1, '2025-10-29 00:29:48'),
(221, 79, '201861', '', '2025-10-29 09:04:17', 0, '2025-10-29 00:54:17'),
(222, 79, '867397', '', '2025-10-29 09:07:38', 0, '2025-10-29 00:57:38'),
(223, 79, '559014', '', '2025-10-29 09:11:43', 0, '2025-10-29 01:01:43'),
(224, 79, '136893', '', '2025-10-29 09:15:53', 0, '2025-10-29 01:05:53'),
(225, 79, '844977', '', '2025-10-29 09:17:43', 0, '2025-10-29 01:07:43'),
(226, 79, '376028', '', '2025-10-29 09:22:40', 0, '2025-10-29 01:12:40'),
(227, 79, '902209', '', '2025-10-29 09:23:26', 0, '2025-10-29 01:13:26'),
(228, 79, '235570', '', '2025-10-29 09:28:16', 0, '2025-10-29 01:18:16'),
(229, 79, '636812', 'email_change', '2025-10-29 09:30:08', 1, '2025-10-29 01:20:08'),
(230, 79, '917382', 'email_change', '2025-10-29 09:45:46', 0, '2025-10-29 01:35:46'),
(231, 79, '994794', 'email_change', '2025-10-29 09:48:57', 0, '2025-10-29 01:38:57'),
(232, 79, '708216', 'email_change', '2025-10-29 09:50:16', 0, '2025-10-29 01:40:16'),
(233, 79, '521868', 'email_change', '2025-10-29 09:51:19', 0, '2025-10-29 01:41:19'),
(234, 79, '539268', 'email_change', '2025-10-29 09:52:13', 1, '2025-10-29 01:42:13'),
(235, 79, '369322', '', '2025-10-29 09:52:49', 0, '2025-10-29 01:42:49'),
(236, 79, '836476', '', '2025-10-29 10:01:29', 0, '2025-10-29 01:51:29'),
(237, 79, '190608', '', '2025-10-29 10:02:00', 0, '2025-10-29 01:52:00'),
(238, 79, 's05XdF', 'login', '2025-10-29 10:19:11', 1, '2025-10-29 02:09:11'),
(239, 79, 'XpO0ZH', 'login', '2025-10-29 10:24:17', 1, '2025-10-29 02:14:17'),
(242, 79, '3ZVQp6', 'login', '2025-10-30 10:35:53', 1, '2025-10-30 02:25:53'),
(243, 82, 'on4rCs', 'signup', '2025-10-30 13:53:41', 1, '2025-10-30 05:43:41'),
(244, 79, 'AYgb1O', 'login', '2025-10-30 13:56:40', 1, '2025-10-30 05:46:40'),
(245, 82, 'hW7j7F', 'signup', '2025-10-30 14:01:20', 1, '2025-10-30 05:51:20'),
(246, 79, '3JyGo9', 'login', '2025-10-30 14:02:00', 1, '2025-10-30 05:52:00'),
(247, 82, 'Y4ofej', 'login', '2025-10-30 14:02:27', 1, '2025-10-30 05:52:27'),
(248, 83, 'mIo4jE', 'signup', '2025-10-30 14:12:34', 1, '2025-10-30 06:02:34'),
(249, 82, 'FGq28E', 'login', '2025-10-30 14:14:42', 1, '2025-10-30 06:04:42'),
(250, 79, 'N6Q9KH', 'login', '2025-10-30 14:25:28', 1, '2025-10-30 06:15:28'),
(251, 84, 'xUTeJ7', 'signup', '2025-10-30 14:25:47', 1, '2025-10-30 06:15:47'),
(252, 84, 'Mn15bq', 'login', '2025-10-30 14:26:59', 1, '2025-10-30 06:16:59'),
(253, 83, 'kYg4R2', 'login', '2025-10-30 14:27:15', 1, '2025-10-30 06:17:15'),
(254, 85, 'BszVW0', 'signup', '2025-10-30 14:48:57', 1, '2025-10-30 06:38:57'),
(255, 85, 'JYw2xF', 'login', '2025-10-30 14:50:12', 1, '2025-10-30 06:40:12'),
(256, 79, 'M63WBg', 'password_reset', '2025-10-30 17:16:49', 1, '2025-10-30 09:06:49'),
(257, 79, 'th7lot', 'login', '2025-10-30 17:17:48', 1, '2025-10-30 09:07:48'),
(263, 79, 'JTu5L7', 'login', '2025-10-30 18:41:52', 1, '2025-10-30 10:31:52'),
(264, 79, '539pjB', 'login', '2025-10-31 08:59:25', 1, '2025-10-31 00:49:25'),
(265, 88, 'b5UAa1', 'signup', '2025-11-06 12:57:02', 0, '2025-11-06 04:47:02'),
(269, 79, 'Gk2Q81', 'password_reset', '2025-11-09 19:58:07', 1, '2025-11-09 11:48:07'),
(270, 79, 'vO62Qd', 'login', '2025-11-09 19:59:01', 1, '2025-11-09 11:49:01'),
(271, 79, 'n0MuBQ', 'login', '2025-11-11 10:26:40', 1, '2025-11-11 02:16:40'),
(272, 79, 'ZHt9Mn', 'login', '2025-11-11 11:50:40', 1, '2025-11-11 03:40:40'),
(273, 79, 'pCR89t', 'login', '2025-11-12 14:28:11', 1, '2025-11-12 06:18:11'),
(274, 79, '5yjxz0', 'login', '2025-11-12 16:48:17', 1, '2025-11-12 08:38:17'),
(275, 79, 'O6xN1M', 'login', '2025-11-12 21:38:43', 1, '2025-11-12 13:28:43'),
(276, 79, 'oH8Hd1', 'login', '2025-11-13 06:36:57', 1, '2025-11-12 22:26:57'),
(277, 79, '0Q96jr', 'login', '2025-11-13 06:43:13', 1, '2025-11-12 22:33:13'),
(278, 79, '1ffZjp', 'login', '2025-11-13 08:10:49', 1, '2025-11-13 00:00:49'),
(279, 90, 'bAats1', 'signup', '2025-11-13 08:12:35', 1, '2025-11-13 00:02:35'),
(280, 90, 'CQdeM6', 'login', '2025-11-13 08:13:02', 1, '2025-11-13 00:03:02'),
(281, 79, '3Sg0cY', 'login', '2025-11-13 08:44:21', 1, '2025-11-13 00:34:21'),
(283, 92, 'aT9J61', 'signup', '2025-11-13 08:57:19', 1, '2025-11-13 00:47:19'),
(284, 92, 'T2T3gz', 'login', '2025-11-13 09:05:23', 1, '2025-11-13 00:55:23'),
(285, 92, '3kbMhq', 'login', '2025-11-13 09:05:27', 1, '2025-11-13 00:55:27'),
(286, 92, '2wmLLJ', 'login', '2025-11-13 12:32:27', 1, '2025-11-13 04:22:27'),
(287, 93, 'rF9cLX', 'signup', '2025-11-13 13:26:30', 1, '2025-11-13 05:16:30'),
(288, 93, 'pWkVT3', 'login', '2025-11-13 13:27:52', 1, '2025-11-13 05:17:52'),
(289, 79, 'Tw8b54', 'login', '2025-11-16 12:07:19', 1, '2025-11-16 03:57:19'),
(290, 79, 'J3doE7', 'login', '2025-11-16 17:53:53', 1, '2025-11-16 09:43:53'),
(291, 79, '5zJtxj', 'login', '2025-11-16 18:15:45', 1, '2025-11-16 10:05:45'),
(292, 94, 'I3hCFC', 'signup', '2025-11-20 11:27:54', 1, '2025-11-20 03:17:54'),
(293, 94, '5DIH1i', 'login', '2025-11-20 11:29:03', 1, '2025-11-20 03:19:03'),
(294, 95, 'loOr8E', 'signup', '2025-11-20 11:45:32', 1, '2025-11-20 03:35:32'),
(295, 95, '2UTW6E', 'password_reset', '2025-11-20 11:47:11', 1, '2025-11-20 03:37:11'),
(296, 95, 'DMi9yJ', 'login', '2025-11-20 11:48:18', 1, '2025-11-20 03:38:18'),
(297, 96, 'vRP2dV', 'signup', '2025-11-20 12:21:22', 1, '2025-11-20 04:11:22'),
(298, 97, 'k8K4s6', 'signup', '2025-11-20 12:56:06', 1, '2025-11-20 04:46:06'),
(299, 97, '3f2XNK', 'login', '2025-11-20 12:57:42', 1, '2025-11-20 04:47:42'),
(300, 97, 'D8XgZF', 'login', '2025-11-20 12:59:41', 1, '2025-11-20 04:49:41'),
(301, 98, 'Tk8kKH', 'signup', '2025-11-20 13:05:52', 1, '2025-11-20 04:55:52'),
(302, 98, 'Z4C1MY', 'login', '2025-11-20 13:07:02', 1, '2025-11-20 04:57:02'),
(303, 96, 'dbq9pz', 'login', '2025-11-20 13:16:24', 1, '2025-11-20 05:06:24'),
(304, 99, '5PRx1j', 'signup', '2025-11-20 13:21:11', 1, '2025-11-20 05:11:11'),
(305, 99, '67f0t7', 'signup', '2025-11-20 13:22:00', 1, '2025-11-20 05:12:00'),
(306, 99, 'HoteJ1', 'signup', '2025-11-20 13:22:04', 1, '2025-11-20 05:12:04'),
(307, 99, 'Ho9Chh', 'login', '2025-11-20 13:24:17', 1, '2025-11-20 05:14:17'),
(308, 100, 'gZre24', 'signup', '2025-11-20 18:00:59', 0, '2025-11-20 09:50:59'),
(309, 79, 'W7mUjq', 'login', '2025-11-24 21:05:13', 1, '2025-11-24 12:55:13'),
(310, 79, '3AJ2kY', 'login', '2026-03-11 16:11:27', 1, '2026-03-11 08:01:27'),
(311, 79, 'UX2M8i', 'login', '2026-03-11 17:09:44', 1, '2026-03-11 08:59:44'),
(312, 101, '4wo7Yr', 'signup', '2026-03-20 20:54:37', 1, '2026-03-20 12:44:37'),
(313, 101, 'xSJb4l', 'signup', '2026-03-20 20:57:12', 1, '2026-03-20 12:47:12'),
(314, 101, '22pXE9', 'signup', '2026-03-21 13:21:06', 1, '2026-03-21 05:11:06'),
(315, 101, 'dLlj2n', 'login', '2026-03-21 13:22:13', 1, '2026-03-21 05:12:13'),
(316, 82, 'HiV1q0', 'password_reset', '2026-03-28 23:04:50', 1, '2026-03-28 14:54:50'),
(317, 82, 'jPvmB8', 'login', '2026-03-28 23:08:15', 1, '2026-03-28 14:58:15'),
(318, 79, 'ti4hUt', 'login', '2026-04-13 09:16:24', 1, '2026-04-13 01:06:24');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `full_name` varchar(200) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `dob` text DEFAULT NULL,
  `gender` text DEFAULT NULL,
  `phone` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `civil_status` varchar(50) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `dental_insurance` varchar(100) NOT NULL,
  `effective_date` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `user_id`, `full_name`, `nickname`, `dob`, `gender`, `phone`, `email`, `address`, `created_at`, `updated_at`, `civil_status`, `occupation`, `nationality`, `religion`, `dental_insurance`, `effective_date`) VALUES
(40, 85, 'gh+S8S/jDYAOXSsyXcQ4GkNydkt1bFVnZWdUbGVObVhFYWxVaVE9PQ==', 'naX5RauzIhCzDXwEINhoMHQ4ZnRFaDZCdzFSVVR3VWUzd2lMMXc9PQ==', 'vVfOqjHgNYUDTcREKqE/ojJ3cUdPOE9VNlRjeTI3bWwzRXF4VVE9PQ==', 'zBs/L96dXXXWDrpTJ5MrHTd6cUlSWEVZdDMxc1BHY3lUSktMQlE9PQ==', 'FmJboyMb69caotIOAD7U2lZtM0pYU1JZbk9TWDE2MCtXWlpPSlE9PQ==', 'qwCH/MsHwVQMqhZWy2uIDW9uZHRyS0pQS2kvaTkva21ITE9jYzR0UXNYT0N5UEdnUXFBWGUxWWM1SVk9', 'hO4+hDbJbDg22wU1BqTBGkpLRm9lNTBDd2ZVeVptenpscWkxbTRrZzZBTitrUU1jZFN6UVJBVXdpNHM9', '2025-11-13 04:56:40', '2025-11-13 04:56:40', 'RjGxHViGG5bn9geK7oX50m9YODh1QkNveHhkaTJwbU43UVFLNn', 'Crdsr3t3iSAt/2awCneSHW1qUlhETDJvUnFhVDdFMmZwY1hCL3c9PQ==', '1/KLOwqoXk4BQ2qFIdMjaWhQYkc1Y1lGU1llaXdUcS9kL3F5WHc9PQ==', 'em0jfsiWoCB6uzWj6DCa1Gh3RmZ0WlN5c3RCZzk3MTcyTVRoOVE9PQ==', 'c1xOpvX5OWyEQ4N2lJwqmXNnKzVLS2lFVllNTEdFL2RNR0RBM3c9PQ==', 'CMpr9fex7Z3MG8Ryndn0J01uQnhWbVBIaS9tUHloeklKSnRrUEE9PQ=='),
(43, 93, 'G+d+0vROpvqh7vhv+GuZZ1ZvQ2NJSU9vcWxXWG5SbGVxSlh1eVB0MTFGdXpDd0ZtMDRuM29VZStsVmc9', 'f3gM2aSn0bqlThlQJsGHnjNzeUVBUmhpWVRiS2F6bDJRTzdPUnc9PQ==', 'XGpDJVlcK6ve025IdQqbrlIzSkVRZ2M0akx3YWRQaU9QVnUzdVE9PQ==', 'gIcIB+FwVyno6egJy5kO20ZXaXgyTlFUNWZVME44OEJzUE9vZEE9PQ==', '8WH65lujZ2vQ6ZCCcNMhhU01TG9tYWoxZmRtRzhlMk5tb2FTcXc9PQ==', 'TlbBjm4I+w4EGmSFHunk5nRueWlEaEFPTzZYUzFXU3Vxb3Z3cmc9PQ==', 'rHRbjGJRrIrhFEyt4O+mkGNZcWxDRCswUGgweGhaNjZnNVRBbXBZRFYxM0s2R1g1OGRCS3ZERU9PSmx0MTczUVZUWjNlZ2ZZeFNlYno3S3I=', '2025-11-13 05:26:53', '2025-11-13 05:26:53', 'wqk8d75XEkyxawUoNwQVaDJoRnFWNFAybFlQcmpZNVQyY3luUU', 'uQJlNcEbMZB22uXXsl9asDFzdWdESEtxdUQ1VmFBZTA4UkgzQmc9PQ==', 'OIAlbjD3H3+gxGyW1hRWsUJyMkoxbm56NjhKcEJJcDhPZDhncFE9PQ==', 'yZmGd5iypCzX22+YtKfzgUZwTVc1MTM0WDlNR09aSkFOWDQ4L0E9PQ==', 'xwqlczaMnyHC5pyT4P3hCHVkOUlFa3IxTVFCRVZoU2x0eU1RbWc9PQ==', '+g6v3mSoKEqId58G+eZ2GWRRUmxsVFRmU0J1R0J2YjQ3bnlLWEE9PQ=='),
(53, 79, 'C+PJ9+oZU2nU4xA9ueO39GZCNGFUZGlmbGtFNk01TWtxVmhIM2c9PQ==', 'c8ONFTJB4o5XnLgqqYPpBmU3WWgvQmxTN0JDK0FDQjZ1TEQzR0E9PQ==', 'rtybG6M2K8waaLYDwN/T5Hg3V3B1UTRndlVMOG1zRzRtSjRrZVE9PQ==', 'BMikh20ydkigAu59pX8cG3VjaVZDOTZPV1dSS2ZIL1FUd1VHbkE9PQ==', 'RdduWdOcZTPBz45GdHU/mXVLVlRhTTZIQVlXYTJhOFlHUzRDdUE9PQ==', '64GmVBrlPzwNxJxx3Y6Dq3BBZGhRdzdWVDZRZVBrOEV5ZGR2a0E9PQ==', 'rxb4zm8Utj9H1cnTcQsmfEZJcE41RW5nZEZ0dU91aDZndHdSZEE9PQ==', '2025-11-16 09:40:24', '2025-11-24 13:42:13', 'iE0mwBfYUVeXx/dKBWpHHitoQ3VaSmVBWWhsL0dhOGx3aVVHdX', 'GFKMO/BW2XFfiGWtGhUrvVloSFRDTWFPN2NYNlNFUWw4Y0Q5a1E9PQ==', 'nwnola7ClD43ytEiAGreeERVNlhmODYxeVkyRG5UWFJpSEUya3c9PQ==', 'df8V+zbKW2jt3DFcIj2Cwy8yV1IrUTFKNEVEMzZqUmxBbU1sUWc9PQ==', 'BfW/Tt9Yo/xHLSHHViJVI1owL3VVMVkwYjB3LzJKWG96bzZoS1E9PQ==', 'HaaKLav7VHH4HCCQyfWJY3N1MWl5NmU4akZSSTBoaHFiUU1raEE9PQ==');

-- --------------------------------------------------------

--
-- Table structure for table `patient_logs`
--

CREATE TABLE `patient_logs` (
  `log_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `added_by` int(11) NOT NULL,
  `role` enum('admin','secretary','doctor') NOT NULL,
  `action` varchar(50) NOT NULL DEFAULT 'add_patient',
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_logs`
--

INSERT INTO `patient_logs` (`log_id`, `patient_id`, `added_by`, `role`, `action`, `timestamp`) VALUES
(81, 40, 11, 'admin', 'Add Patient', '2025-11-13 12:56:40'),
(82, 43, 11, 'admin', 'Add Patient', '2025-11-13 13:26:53'),
(85, 53, 1, 'admin', 'Add Patient', '2025-11-16 17:40:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','blocked','deleted','deactivated') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `fullname`, `email`, `password`, `is_verified`, `created_at`, `status`) VALUES
(79, 'Ericka Kate Gregorio', 'erickakategregorio@gmail.com', '$2y$10$DKINBBVylgZr5dY30J6FfOviAk/iNRceAnn/CTwKzCqPsv8WhKQh2', 1, '2025-09-30 00:55:15', 'active'),
(82, 'Sayrelle', 'sayrelle81@gmail.com', '$2y$10$3jsk0qlayPsv3DXKMKLgy.3DKo7ZcuF7WUH.Pd3u225VfpmYJDtJu', 1, '2025-10-30 05:43:41', 'active'),
(83, 'Therence Hilcee Rubite', 'erenceedefante@gmail.com', '$2y$10$JKQpaeEAZnym3phwVvyT7.KB3AVTIEpEiuCDqxdcvmGPdbJdoiIL6', 1, '2025-10-30 06:02:34', 'active'),
(84, 'Carl Edciel Abrot', 'cara.abrot.ui@phinmaed.com', '$2y$10$bLYwBgvKRfmHUqHAznhRcOc5bd5xjLb/icow0mVUNwBH79YA7KjwG', 1, '2025-10-30 06:15:47', 'active'),
(85, 'Justine Siaotong Galve', 'galvejustine21.svc@gmail.com', '$2y$10$H0LzJUKnJ5LndrxwtlMd1.KExfc4o85mjMfZFauZ3qE/J8I0HKLta', 1, '2025-10-30 06:38:57', 'active'),
(88, 'Concepcion', 'nocirametibur@gmail.com', '$2y$10$6Fu9z4dfKXZ8c.nW8uf.RuhrmTay0LINfYgyRSuN9Oed955iNBie6', 0, '2025-11-06 04:47:02', 'active'),
(90, 'Chery Nicole Pastilloso', 'chgo.pastilloso.ui@phinmaed.com', '$2y$10$A0/fzFmKn.AWDNpBMlKOweoWyaABR2Xgj1uLsNBosU8trYIcxvtAm', 1, '2025-11-13 00:02:35', 'active'),
(92, 'James Candido', 'bon2.0workspace1994@gmail.com', '$2y$10$FzlyFWz8Oewa9prtPNx6S..HI8G2wYcsT4pfo4t.ScrOe0pmh88VO', 1, '2025-11-13 00:47:19', 'active'),
(93, 'Barbie Joy Bayot', 'barbiyoj04@gmail.com', '$2y$10$zXFO1cG.H8.caLZrrbnZU.omdHLEEi9s.feXMZfdt316nF8S50XBu', 1, '2025-11-13 05:16:30', 'active'),
(94, 'Charry Mae Magbanua', 'vcmcmv03@gmail.com', '$2y$10$8V0LgcxnqpKGIVMUlIH6TOJ24Ax/OsZgdCYKVVZwZGt2MuA4c18eO', 1, '2025-11-20 03:17:54', 'active'),
(95, 'Aj Sibulangca', 'ajsibulangca@gmail.com', '$2y$10$n426WgcS.WIWsWMD65UEAesySt8WJHylVJbvSsielhBojNjImwfgS', 1, '2025-11-20 03:35:32', 'active'),
(96, 'Nicris Genova', 'nicrisgenova17@gmail.com', '$2y$10$TDLXrdTRPuHqwKBzm0/Hre.eEL1U/rpCaPmKiM1hj0QcWiv5Ygsti', 1, '2025-11-20 04:11:22', 'active'),
(97, 'Ronnel Villarin', 'villarinronnel1125@gmail.com', '$2y$10$F./QhkuCj462v/djkmZ7uOYMa2xSswK37YrQLcxRLSNIjjAEvf/82', 1, '2025-11-20 04:46:06', 'active'),
(98, 'jessa samson', 'jessasamson21@gmail.com', '$2y$10$W92aJLLn7jApOw4WYlNeDexco1tTvOoJx5b9G2yT0SezmDyny8hTq', 1, '2025-11-20 04:55:52', 'active'),
(99, 'RUFA MAE', 'rudo.nunal.ui@phinmaed.com', '$2y$10$tjQ84Bta88d77D91NPCa4.D/V39PIHoL4DATrOi6qWqGYW.v/iASm', 1, '2025-11-20 05:11:11', 'active'),
(100, 'kamille joyce celiz', 'kamillejoyceceliz1@gmail.com', '$2y$10$9EUUDEuaMtq6nj6QL2uQeuiIkNJIVkJZcYUmUN0oi9ld0knn82Vsq', 0, '2025-11-20 09:50:59', 'active'),
(101, 'Carl Edciel Abrot', 'abrotcarl@gmail.com', '$2y$10$GVUk5Nfv2MaHVTYEgXuzR.w60L8UPOpaJQYInVRCXGamukr5lFhSW', 1, '2026-03-20 12:44:37', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `user_password_history`
--

CREATE TABLE `user_password_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `change_type` enum('change','reset') NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `device_info` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_password_history`
--

INSERT INTO `user_password_history` (`id`, `user_id`, `change_type`, `ip_address`, `device_info`, `timestamp`) VALUES
(4, 79, 'reset', '127.0.0.1', 'Samsung | AndroidOS 13 | Edge 141.0.0.0', '2025-10-28 00:11:29'),
(5, 79, 'reset', '127.0.0.1', 'Samsung | AndroidOS 13 | Edge 141.0.0.0', '2025-10-28 00:16:56'),
(6, 79, 'reset', '103.125.158.214', 'WebKit | AndroidOS 10 | Chrome 141.0.0.0', '2025-10-30 09:07:30'),
(8, 79, 'reset', '103.125.158.214', 'WebKit | AndroidOS 10 | Chrome 142.0.0.0', '2025-11-09 11:48:49'),
(9, 95, 'reset', '143.44.196.54', 'WebKit | Windows 10.0 | Chrome 142.0.0.0', '2025-11-20 03:37:54'),
(10, 82, 'reset', '49.147.193.16', 'WebKit | Windows 10.0 | Chrome 146.0.0.0', '2026-03-28 14:58:06');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `birthdate` date NOT NULL,
  `civil_status` enum('Single','Married','Widowed','Separated','Divorced') DEFAULT NULL,
  `barangay` varchar(100) NOT NULL,
  `municipality` varchar(100) NOT NULL DEFAULT 'Dueñas',
  `province` varchar(100) NOT NULL DEFAULT 'Iloilo',
  `zip_code` varchar(10) DEFAULT NULL,
  `contact_number` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `id_type` enum('National ID','Driver’s License','Passport','Voter’s ID','SSS','Other') NOT NULL,
  `id_number` varchar(50) NOT NULL,
  `id_image` varchar(255) NOT NULL,
  `selfie_with_id` varchar(255) NOT NULL,
  `blood_type` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') DEFAULT NULL,
  `emergency_contact_name` varchar(100) DEFAULT NULL,
  `emergency_contact_number` varchar(20) DEFAULT NULL,
  `relationship_to_emergency_contact` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ocr_result` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_login_history`
--
ALTER TABLE `admin_login_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_admin_login` (`admin_id`);

--
-- Indexes for table `admin_otp_history`
--
ALTER TABLE `admin_otp_history`
  ADD PRIMARY KEY (`otp_history_id`),
  ADD KEY `fk_admin_otp` (`admin_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `appointment_logs`
--
ALTER TABLE `appointment_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `dental_history`
--
ALTER TABLE `dental_history`
  ADD PRIMARY KEY (`dental_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `dental_records`
--
ALTER TABLE `dental_records`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `fk_dental_patient` (`patient_id`);

--
-- Indexes for table `dental_reports`
--
ALTER TABLE `dental_reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `emergency_contacts`
--
ALTER TABLE `emergency_contacts`
  ADD PRIMARY KEY (`contact_id`),
  ADD UNIQUE KEY `unique_patient_contact_name` (`patient_id`,`contact_name`);

--
-- Indexes for table `lockout_history`
--
ALTER TABLE `lockout_history`
  ADD PRIMARY KEY (`lockout_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `login_history`
--
ALTER TABLE `login_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `fk_medical_history_patient` (`patient_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `otp_history`
--
ALTER TABLE `otp_history`
  ADD PRIMARY KEY (`otp_history_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `patient_logs`
--
ALTER TABLE `patient_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `added_by` (`added_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_password_history`
--
ALTER TABLE `user_password_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`profile_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `admin_login_history`
--
ALTER TABLE `admin_login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT for table `admin_otp_history`
--
ALTER TABLE `admin_otp_history`
  MODIFY `otp_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `appointment_logs`
--
ALTER TABLE `appointment_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `dental_history`
--
ALTER TABLE `dental_history`
  MODIFY `dental_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `dental_records`
--
ALTER TABLE `dental_records`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `dental_reports`
--
ALTER TABLE `dental_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `emergency_contacts`
--
ALTER TABLE `emergency_contacts`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `lockout_history`
--
ALTER TABLE `lockout_history`
  MODIFY `lockout_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `login_history`
--
ALTER TABLE `login_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- AUTO_INCREMENT for table `medical_history`
--
ALTER TABLE `medical_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otp_history`
--
ALTER TABLE `otp_history`
  MODIFY `otp_history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=319;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `patient_logs`
--
ALTER TABLE `patient_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `user_password_history`
--
ALTER TABLE `user_password_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_login_history`
--
ALTER TABLE `admin_login_history`
  ADD CONSTRAINT `fk_admin_login` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `admin_otp_history`
--
ALTER TABLE `admin_otp_history`
  ADD CONSTRAINT `fk_admin_otp` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `appointment_logs`
--
ALTER TABLE `appointment_logs`
  ADD CONSTRAINT `appointment_logs_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_logs_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `dental_history`
--
ALTER TABLE `dental_history`
  ADD CONSTRAINT `dental_history_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE;

--
-- Constraints for table `dental_records`
--
ALTER TABLE `dental_records`
  ADD CONSTRAINT `fk_dental_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE;

--
-- Constraints for table `dental_reports`
--
ALTER TABLE `dental_reports`
  ADD CONSTRAINT `dental_reports_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE;

--
-- Constraints for table `emergency_contacts`
--
ALTER TABLE `emergency_contacts`
  ADD CONSTRAINT `emergency_contacts_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE;

--
-- Constraints for table `lockout_history`
--
ALTER TABLE `lockout_history`
  ADD CONSTRAINT `lockout_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `login_history`
--
ALTER TABLE `login_history`
  ADD CONSTRAINT `login_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `medical_history`
--
ALTER TABLE `medical_history`
  ADD CONSTRAINT `fk_medical_history_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `otp_history`
--
ALTER TABLE `otp_history`
  ADD CONSTRAINT `otp_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `patient_logs`
--
ALTER TABLE `patient_logs`
  ADD CONSTRAINT `patient_logs_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`),
  ADD CONSTRAINT `patient_logs_ibfk_2` FOREIGN KEY (`added_by`) REFERENCES `admin` (`id`);

--
-- Constraints for table `user_password_history`
--
ALTER TABLE `user_password_history`
  ADD CONSTRAINT `user_password_history_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
