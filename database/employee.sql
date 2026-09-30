-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 28, 2026 at 11:12 AM
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
-- Database: `dev_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `att_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `work_date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`att_id`, `emp_id`, `work_date`, `check_in`, `check_out`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-09-14', '08:02:00', '18:10:00', '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(2, 2, '2026-09-14', '08:05:00', '17:30:00', '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(3, 3, '2026-09-14', '08:20:00', '19:00:00', '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(4, 4, '2026-09-14', '08:35:00', '17:45:00', '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(5, 5, '2026-09-14', '08:45:00', '17:20:00', '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(6, 4, '2026-09-15', '08:40:00', NULL, '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(7, 9, '2026-09-22', '09:44:00', '18:44:00', '2026-09-22 11:44:45', '2026-09-22 11:44:45'),
(8, 4, '2026-09-28', '09:25:00', NULL, '2026-09-28 15:25:08', '2026-09-28 15:25:08');

-- --------------------------------------------------------

--
-- Table structure for table `audit_log`
--

CREATE TABLE `audit_log` (
  `log_id` bigint(20) NOT NULL,
  `login_id` int(11) DEFAULT NULL,
  `module_id` int(11) NOT NULL,
  `action` enum('create','update','delete') NOT NULL,
  `target_id` int(11) DEFAULT NULL,
  `old_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_value`)),
  `new_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_value`)),
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_log`
--

INSERT INTO `audit_log` (`log_id`, `login_id`, `module_id`, `action`, `target_id`, `old_value`, `new_value`, `created_at`) VALUES
(1, 1, 2, 'create', 6, NULL, '{\"emp_name_th\":\"อรทัย\",\"emp_sname_th\":\"แสงทอง\"}', '2026-09-15 15:44:37'),
(2, 2, 4, 'update', 5, '{\"doc_status\":\"pending\"}', '{\"doc_status\":\"rejected\"}', '2026-09-15 15:44:37'),
(3, 3, 3, 'create', 1, NULL, '{\"net_pay\":81000.00,\"status\":\"paid\"}', '2026-09-15 15:44:37'),
(4, 1, 7, 'update', NULL, '{\"late_threshold_min\":\"10\"}', '{\"late_threshold_min\":\"15\"}', '2026-09-15 15:44:37');

-- --------------------------------------------------------

--
-- Table structure for table `contract`
--

CREATE TABLE `contract` (
  `cont_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `cont_position` int(11) DEFAULT NULL,
  `cont_start_date` date DEFAULT NULL,
  `cont_duration_time` int(11) DEFAULT NULL COMMENT 'duration in months',
  `cont_status` enum('พนักงานประจำ','ทดลองงาน','สัญญาจ้าง') DEFAULT NULL,
  `cont_salary` decimal(12,2) DEFAULT NULL,
  `cont_bank` varchar(100) DEFAULT NULL,
  `cont_bank_no` varchar(50) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contract`
--

INSERT INTO `contract` (`cont_id`, `emp_id`, `cont_position`, `cont_start_date`, `cont_duration_time`, `cont_status`, `cont_salary`, `cont_bank`, `cont_bank_no`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2020-01-05', NULL, 'พนักงานประจำ', 80000.00, 'กสิกรไทย', '123-4-56789-0', '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(2, 2, 2, '2021-03-01', NULL, 'พนักงานประจำ', 45000.00, 'ไทยพาณิชย์', '234-5-67890-1', '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(3, 3, 10, '2019-06-15', NULL, 'พนักงานประจำ', 55000.00, 'กรุงไทย', '345-6-78901-2', '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(4, 4, 3, '2024-09-01', 6, 'ทดลองงาน', 28000.00, 'กสิกรไทย', '456-7-89012-3', '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(5, 5, 6, '2023-02-10', NULL, 'พนักงานประจำ', 26000.00, 'กรุงศรี', '567-8-90123-4', '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(6, 6, 11, '2025-01-20', 3, 'สัญญาจ้าง', 22000.00, 'ไทยพาณิชย์', '678-9-01234-5', '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(8, 8, 1, '2016-02-04', 0, 'พนักงานประจำ', 0.00, '', '', '2026-09-21 16:17:07', '2026-09-21 16:17:07'),
(9, 9, 2, '2016-03-01', 0, 'พนักงานประจำ', 0.00, '', '', '2026-09-21 16:21:47', '2026-09-21 16:21:47');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `doc_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `doc_name` varchar(255) DEFAULT NULL,
  `doc_url` text NOT NULL,
  `doc_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`doc_id`, `emp_id`, `type_id`, `doc_name`, `doc_url`, `doc_status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, '/uploads/docs/emp001_idcard.pdf', 'approved', '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(2, 2, 3, NULL, '/uploads/docs/emp002_degree.pdf', 'approved', '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(3, 3, 4, NULL, '/uploads/docs/emp003_medcert.pdf', 'pending', '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(4, 4, 1, NULL, '/uploads/docs/emp004_idcard.pdf', 'pending', '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(5, 5, 2, NULL, '/uploads/docs/emp005_household.pdf', 'rejected', '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(6, 6, 5, NULL, '/uploads/docs/emp006_other.pdf', 'pending', '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(11, 9, 5, 'Test', '2026/9464091ca8fc99103066c603b0c92dd0.pdf', 'approved', '2026-09-25 12:27:23', '2026-09-25 13:01:31'),
(13, 9, 5, 'PDPA', '2026/78ed80e43079c7da2afe9d2e875cd95b.pdf', 'pending', '2026-09-25 12:57:15', '2026-09-25 12:57:15');

-- --------------------------------------------------------

--
-- Table structure for table `document_type`
--

CREATE TABLE `document_type` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `document_type`
--

INSERT INTO `document_type` (`type_id`, `type_name`) VALUES
(1, 'บัตรประชาชน'),
(2, 'สำเนาทะเบียนบ้าน'),
(3, 'วุฒิการศึกษา'),
(4, 'ใบรับรองแพทย์'),
(5, 'อื่นๆ');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `emp_id` int(11) NOT NULL,
  `emp_no` varchar(50) NOT NULL,
  `position_id` int(11) DEFAULT NULL,
  `emp_prefix_th` varchar(20) DEFAULT NULL,
  `emp_name_th` varchar(255) NOT NULL,
  `emp_sname_th` varchar(255) NOT NULL,
  `emp_nickname_th` varchar(100) DEFAULT NULL,
  `emp_prefix_en` varchar(20) DEFAULT NULL,
  `emp_name_en` varchar(255) DEFAULT NULL,
  `emp_sname_en` varchar(255) DEFAULT NULL,
  `emp_nickname_en` varchar(100) DEFAULT NULL,
  `emp_idcard` varchar(20) DEFAULT NULL,
  `emp_idss` varchar(20) DEFAULT NULL,
  `emp_birthday` date DEFAULT NULL,
  `emp_tel` varchar(20) DEFAULT NULL,
  `emp_email` varchar(255) DEFAULT NULL,
  `emp_address` text DEFAULT NULL,
  `emp_line` varchar(100) DEFAULT NULL,
  `url` varchar(20) DEFAULT NULL,
  `emp_cancel` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`emp_id`, `emp_no`, `position_id`, `emp_prefix_th`, `emp_name_th`, `emp_sname_th`, `emp_nickname_th`, `emp_prefix_en`, `emp_name_en`, `emp_sname_en`, `emp_nickname_en`, `emp_idcard`, `emp_idss`, `emp_birthday`, `emp_tel`, `emp_email`, `emp_address`, `emp_line`, `url`, `emp_cancel`, `created_at`, `updated_at`) VALUES
(1, 'EMP-001', 1, 'นาย', 'สมชาย', 'จัยดี', 'ชาย', NULL, NULL, NULL, NULL, '1100100000001', NULL, '1985-03-12', '0891112222', 'somchai.j@ktn-system.local', '123 ถ.สุขุมวิท กทม.', NULL, NULL, 1, '2026-09-15 15:44:37', '2026-09-15 17:34:41'),
(2, 'EMP-002', 2, 'นาง', 'สมหญิง', 'รักเรียน', 'หญิง', NULL, NULL, NULL, NULL, '1100100000002', NULL, '1988-07-20', '0892223333', 'somying.r@ktn-system.local', '45 ถ.รัชดา กทม.', NULL, NULL, 1, '2026-09-15 15:44:37', '2026-09-15 17:34:45'),
(3, 'EMP-003', 10, 'นาย', 'วิชัย', 'เก่งกาจ', 'ชัย', NULL, NULL, NULL, NULL, '1100100000003', NULL, '1990-01-05', '0893334444', 'wichai.k@ktn-system.local', '78 ถ.ลาดพร้าว กทม.', NULL, NULL, 0, '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(4, 'EMP-004', 3, 'นางสาว', 'มาลี', 'ดอกไม้', 'มาลี', NULL, NULL, NULL, NULL, '1100100000004', NULL, '1995-11-18', '0894445555', 'malee.d@ktn-system.local', '9 ถ.พระราม 9 กทม.', NULL, NULL, 1, '2026-09-15 15:44:37', '2026-09-15 17:34:54'),
(5, 'EMP-005', 6, 'นาย', 'ประยุทธ', 'มั่นคง', 'ยุทธ', NULL, NULL, NULL, NULL, '1100100000005', NULL, '1993-05-30', '0895556666', 'prayuth.m@ktn-system.local', '21 ถ.งามวงศ์วาน นนทบุรี', NULL, NULL, 1, '2026-09-15 15:44:37', '2026-09-15 17:34:48'),
(6, 'EMP-006', 11, 'นางสาว', 'อรทัย', 'แสงทอง', 'ทัย', NULL, NULL, NULL, NULL, '1100100000006', NULL, '1997-09-09', '0896667777', 'orathai.s@ktn-system.local', '33 ถ.แจ้งวัฒนะ นนทบุรี', NULL, NULL, 0, '2026-09-15 15:44:37', '2026-09-28 10:58:21'),
(8, 'ktn001', 1, 'นาย', 'ณัฏฐยศ', 'สุริยเสนีย์', 'บอสตั๋ง', 'Mr.', '', '', '', '', '', '0000-00-00', '062-426-1964', 'ceo@ktndevelop.com', '', '', NULL, 1, '2026-09-21 16:17:07', '2026-09-21 16:17:49'),
(9, 'ktn002', NULL, 'นางสาว', 'วรรณดี', 'สีทอง', 'พี่เอิง', 'Miss', 'Wandee', '', 'Eang', '1-1014-00462-19-1', '', '0000-00-00', '098-416-2655', 'wandee.ktn@gmail.com', '', '', NULL, 1, '2026-09-21 16:21:47', '2026-09-21 16:21:47');

-- --------------------------------------------------------

--
-- Table structure for table `employee_contact`
--

CREATE TABLE `employee_contact` (
  `contact_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `contact_type` enum('emergency','reference','family') NOT NULL DEFAULT 'emergency',
  `name` varchar(255) NOT NULL,
  `relationship` varchar(100) DEFAULT NULL,
  `tel` varchar(20) NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_contact`
--

INSERT INTO `employee_contact` (`contact_id`, `emp_id`, `contact_type`, `name`, `relationship`, `tel`, `is_primary`, `created_at`) VALUES
(1, 1, 'emergency', 'สมศรี จัยดี', 'มารดา', '0881112222', 1, '2026-09-15 15:44:37'),
(2, 2, 'emergency', 'สมพงษ์ รักเรียน', 'บิดา', '0882223333', 1, '2026-09-15 15:44:37'),
(3, 3, 'emergency', 'วิภา เก่งกาจ', 'คู่สมรส', '0883334444', 1, '2026-09-15 15:44:37'),
(4, 4, 'family', 'สมหมาย ดอกไม้', 'บิดา', '0884445555', 1, '2026-09-15 15:44:37'),
(5, 5, 'emergency', 'ประไพ มั่นคง', 'มารดา', '0885556666', 1, '2026-09-15 15:44:37'),
(6, 6, 'reference', 'มานพ แสงทอง', 'พี่ชาย', '0886667777', 1, '2026-09-15 15:44:37'),
(7, 8, 'emergency', '', '', '', 0, '2026-09-21 16:17:08'),
(8, 9, 'emergency', '', '', '', 0, '2026-09-21 16:21:48');

-- --------------------------------------------------------

--
-- Table structure for table `employee_login`
--

CREATE TABLE `employee_login` (
  `login_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_login`
--

INSERT INTO `employee_login` (`login_id`, `emp_id`, `role_id`, `username`, `password_hash`, `last_login`, `created_at`) VALUES
(1, 1, 1, 'admin', '$2b$10$X1hd0WgwrkLW4fx6krN2Tu1wP1DRzNfK0m4o5e6j1T5bZnN8FnLUS', '2026-09-28 15:24:57', '2026-09-15 15:44:37'),
(2, 2, 1, 'somying.admin', '$2b$10$X1hd0WgwrkLW4fx6krN2Tu1wP1DRzNfK0m4o5e6j1T5bZnN8FnLUS', '2026-09-14 09:00:00', '2026-09-15 15:44:37'),
(3, 3, 1, 'wichai.admin', '$2b$10$X1hd0WgwrkLW4fx6krN2Tu1wP1DRzNfK0m4o5e6j1T5bZnN8FnLUS', '2026-09-13 17:45:00', '2026-09-15 15:44:37'),
(4, 4, 2, 'staff', '$2b$10$X1hd0WgwrkLW4fx6krN2Tu1wP1DRzNfK0m4o5e6j1T5bZnN8FnLUS', '2026-09-28 15:25:24', '2026-09-15 15:44:37'),
(5, 5, 2, 'prayuth.staff', '$2b$10$X1hd0WgwrkLW4fx6krN2Tu1wP1DRzNfK0m4o5e6j1T5bZnN8FnLUS', NULL, '2026-09-15 15:44:37'),
(6, 6, 3, 'assist', '$2b$10$X1hd0WgwrkLW4fx6krN2Tu1wP1DRzNfK0m4o5e6j1T5bZnN8FnLUS', '2026-09-12 10:20:00', '2026-09-15 15:44:37'),
(7, 8, 1, 'Nuttayos', '$2y$12$FvWhRWw.nspktW/dXGr1sOXrgDfYnoClaXKRZVhqyjAUTDN34R.ba', NULL, '2026-09-21 16:17:08'),
(8, 9, 1, 'Wandee', '$2y$12$CZy0dMUIyNEbLQrjAt31MeDzjRlkOGQoXNBuuPjSmbmSju3wNeC9i', '2026-09-21 16:28:32', '2026-09-21 16:21:47');

-- --------------------------------------------------------

--
-- Table structure for table `feature`
--

CREATE TABLE `feature` (
  `feature_id` int(11) NOT NULL,
  `feature_key` varchar(100) NOT NULL,
  `feature_name` varchar(255) NOT NULL,
  `default_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feature`
--

INSERT INTO `feature` (`feature_id`, `feature_key`, `feature_name`, `default_enabled`, `created_at`) VALUES
(1, 'dark_mode', 'ธีมมืด', 0, '2026-09-15 15:44:37'),
(2, 'export_excel', 'ส่งออกรายงานเป็น Excel', 1, '2026-09-15 15:44:37'),
(3, 'email_notify', 'แจ้งเตือนผ่านอีเมล', 1, '2026-09-15 15:44:37'),
(4, 'beta_dashboard', 'แดชบอร์ดเวอร์ชันทดลอง', 0, '2026-09-15 15:44:37');

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `leave_type_id` int(11) NOT NULL,
  `leave_type_name` varchar(100) NOT NULL,
  `max_days_per_year` int(11) DEFAULT NULL COMMENT 'สิทธิ์ลาสูงสุดต่อปี NULL = ไม่จำกัด',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`leave_type_id`, `leave_type_name`, `max_days_per_year`, `created_at`, `updated_at`) VALUES
(1, 'ลาพักร้อน', NULL, '2026-09-15 08:44:14', '2026-09-15 08:44:14'),
(2, 'ลาป่วย', NULL, '2026-09-15 08:44:14', '2026-09-15 08:44:14'),
(3, 'ลากิจ', NULL, '2026-09-15 08:44:14', '2026-09-15 08:44:14'),
(4, 'พบลูกค้า', NULL, '2026-09-15 08:44:14', '2026-09-15 08:44:14'),
(5, 'WFH', NULL, '2026-09-15 08:44:14', '2026-09-15 08:44:14'),
(6, 'LWP', NULL, '2026-09-15 08:44:14', '2026-09-15 08:44:14'),
(7, 'ปรับเวลาทำงาน', NULL, '2026-09-15 08:44:14', '2026-09-15 08:44:14'),
(8, 'ลาอื่นๆ', NULL, '2026-09-15 08:44:14', '2026-09-15 08:44:14');

-- --------------------------------------------------------

--
-- Table structure for table `lucky_employee`
--

CREATE TABLE `lucky_employee` (
  `id` int(11) NOT NULL,
  `lucky_date` date NOT NULL,
  `emp_id` int(11) NOT NULL,
  `picked_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_revealed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lucky_employee`
--

INSERT INTO `lucky_employee` (`id`, `lucky_date`, `emp_id`, `picked_at`, `is_revealed`) VALUES
(1, '2026-09-01', 4, '2026-09-15 15:44:37', 1),
(2, '2026-09-08', 2, '2026-09-15 15:44:37', 1),
(3, '2026-09-15', 5, '2026-09-15 15:44:37', 0);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `module_id` int(11) NOT NULL,
  `module_key` varchar(50) NOT NULL,
  `module_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`module_id`, `module_key`, `module_name`) VALUES
(1, 'dashboard', 'แดชบอร์ด'),
(2, 'employee', 'ข้อมูลพนักงาน'),
(3, 'payroll', 'เงินเดือน'),
(4, 'documents', 'เอกสาร'),
(5, 'attendance', 'เวลาเข้างาน'),
(6, 'leave', 'การลา'),
(7, 'settings', 'ตั้งค่าระบบ');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `id` int(11) NOT NULL,
  `login_id` int(11) NOT NULL,
  `token_hash` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `payroll_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `period_month` tinyint(2) NOT NULL,
  `period_year` smallint(4) NOT NULL,
  `base_salary` decimal(12,2) NOT NULL DEFAULT 0.00,
  `ot_hours` decimal(6,2) NOT NULL DEFAULT 0.00,
  `ot_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `deduction` decimal(12,2) NOT NULL DEFAULT 0.00,
  `bonus` decimal(12,2) NOT NULL DEFAULT 0.00,
  `net_pay` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','approved','paid') NOT NULL DEFAULT 'draft',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`payroll_id`, `emp_id`, `period_month`, `period_year`, `base_salary`, `ot_hours`, `ot_amount`, `deduction`, `bonus`, `net_pay`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 8, 2026, 80000.00, 0.00, 0.00, 4000.00, 5000.00, 81000.00, 'paid', 3, '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(2, 4, 8, 2026, 28000.00, 3.00, 1050.00, 1400.00, 0.00, 27650.00, 'paid', 3, '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(3, 5, 8, 2026, 26000.00, 5.00, 1625.00, 1300.00, 500.00, 26825.00, 'approved', 3, '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(4, 4, 9, 2026, 28000.00, 0.00, 0.00, 1400.00, 0.00, 26600.00, 'draft', 3, '2026-09-15 15:44:37', '2026-09-15 15:44:37');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_item`
--

CREATE TABLE `payroll_item` (
  `item_id` int(11) NOT NULL,
  `payroll_id` int(11) NOT NULL,
  `item_type` enum('addition','deduction') NOT NULL,
  `label` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll_item`
--

INSERT INTO `payroll_item` (`item_id`, `payroll_id`, `item_type`, `label`, `amount`) VALUES
(1, 1, 'deduction', 'ประกันสังคม', 750.00),
(2, 1, 'deduction', 'ภาษี ณ ที่จ่าย', 3250.00),
(3, 1, 'addition', 'โบนัสประจำปี', 5000.00),
(4, 2, 'deduction', 'ประกันสังคม', 750.00),
(5, 2, 'deduction', 'มาสาย 2 ครั้ง', 650.00),
(6, 2, 'addition', 'ค่าล่วงเวลา', 1050.00),
(7, 3, 'deduction', 'ประกันสังคม', 750.00),
(8, 3, 'deduction', 'ขาดงาน 1 วัน', 550.00),
(9, 4, 'deduction', 'ประกันสังคม', 750.00),
(10, 4, 'deduction', 'ภาษี ณ ที่จ่าย', 650.00);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `perm_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `can_create` tinyint(1) NOT NULL DEFAULT 0,
  `can_read` tinyint(1) NOT NULL DEFAULT 0,
  `can_update` tinyint(1) NOT NULL DEFAULT 0,
  `can_delete` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`perm_id`, `role_id`, `module_id`, `can_create`, `can_read`, `can_update`, `can_delete`) VALUES
(1, 1, 5, 1, 1, 1, 1),
(2, 1, 1, 1, 1, 1, 1),
(3, 1, 4, 1, 1, 1, 1),
(4, 1, 2, 1, 1, 1, 1),
(5, 1, 6, 1, 1, 1, 1),
(6, 1, 3, 1, 1, 1, 1),
(7, 1, 7, 1, 1, 1, 1),
(8, 2, 1, 0, 1, 0, 0),
(9, 2, 2, 0, 1, 0, 0),
(10, 2, 3, 0, 1, 0, 0),
(11, 2, 4, 1, 1, 0, 0),
(12, 2, 5, 0, 1, 0, 0),
(13, 2, 6, 1, 1, 0, 0),
(15, 3, 1, 0, 1, 0, 0),
(16, 3, 2, 0, 1, 0, 0),
(17, 3, 4, 1, 1, 1, 0),
(18, 3, 5, 0, 1, 1, 0),
(19, 3, 6, 0, 1, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `position_id` int(11) NOT NULL,
  `position_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`position_id`, `position_name`, `created_at`, `updated_at`) VALUES
(1, 'CEO', '2026-09-15 08:44:14', '2026-09-15 08:44:14'),
(2, 'Manager', '2026-09-15 08:44:14', '2026-09-15 08:44:14'),
(3, 'Programmer', '2026-09-15 08:44:14', '2026-09-15 08:44:14'),
(4, 'Ads Specialist', '2026-09-15 08:44:14', '2026-09-15 08:44:14'),
(5, 'Creative', '2026-09-15 08:44:14', '2026-09-15 08:44:14'),
(6, 'Graphic Design', '2026-09-15 08:44:14', '2026-09-15 08:44:14'),
(7, 'Web Design', '2026-09-15 08:44:14', '2026-09-15 08:44:14'),
(8, 'Video Editor', '2026-09-15 08:44:14', '2026-09-15 08:44:14'),
(9, 'Webmaster and SEO', '2026-09-15 08:44:14', '2026-09-15 08:44:14'),
(10, 'Accounting Executive', '2026-09-15 08:44:14', '2026-09-15 08:44:14'),
(11, 'Secretary', '2026-09-15 08:44:14', '2026-09-15 08:44:14'),
(12, 'Trainee', '2026-09-15 08:44:14', '2026-09-15 08:44:14'),
(13, 'Coordinator', '2026-09-15 08:44:14', '2026-09-15 08:44:14'),
(14, 'Admin', '2026-09-15 08:44:14', '2026-09-15 08:44:14');

-- --------------------------------------------------------

--
-- Table structure for table `rfid_tag`
--

CREATE TABLE `rfid_tag` (
  `tag_id` int(11) NOT NULL,
  `uid` varchar(50) NOT NULL,
  `emp_id` int(11) DEFAULT NULL,
  `start_time` time NOT NULL DEFAULT '10:00:00',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rfid_tag`
--

INSERT INTO `rfid_tag` (`tag_id`, `uid`, `emp_id`, `start_time`, `created_at`) VALUES
(1, 'RFID-0001', 1, '08:00:00', '2026-09-15 15:44:37'),
(2, 'RFID-0002', 2, '08:00:00', '2026-09-15 15:44:37'),
(3, 'RFID-0003', 3, '08:00:00', '2026-09-15 15:44:37'),
(4, 'RFID-0004', 4, '08:30:00', '2026-09-15 15:44:37'),
(5, 'RFID-0005', 5, '08:30:00', '2026-09-15 15:44:37'),
(6, 'RFID-0006', 6, '09:00:00', '2026-09-15 15:44:37'),
(7, 'RFID-0099', NULL, '08:00:00', '2026-09-15 15:44:37'),
(9, 'EMP-0008', 9, '09:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `created_at`) VALUES
(1, 'admin', '2026-09-15 08:44:14'),
(2, 'staff', '2026-09-15 08:44:14'),
(3, 'assist', '2026-09-15 08:44:14');

-- --------------------------------------------------------

--
-- Table structure for table `scan_logs`
--

CREATE TABLE `scan_logs` (
  `id` bigint(20) NOT NULL,
  `rfid_id` int(11) DEFAULT NULL,
  `emp_id` int(11) DEFAULT NULL,
  `attendance_id` int(11) DEFAULT NULL,
  `scanned_at` datetime NOT NULL DEFAULT current_timestamp(),
  `action` enum('check_in','check_out','duplicate_scan','unknown_card','inactive_employee') NOT NULL DEFAULT 'check_in',
  `status` enum('success','ignored','error') NOT NULL DEFAULT 'success',
  `note` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `scan_logs`
--

INSERT INTO `scan_logs` (`id`, `rfid_id`, `emp_id`, `attendance_id`, `scanned_at`, `action`, `status`, `note`, `created_at`) VALUES
(1, 1, 1, 1, '2026-09-14 08:02:00', 'check_in', 'success', NULL, '2026-09-15 15:44:37'),
(2, 1, 1, 1, '2026-09-14 18:10:00', 'check_out', 'success', NULL, '2026-09-15 15:44:37'),
(3, 4, 4, 4, '2026-09-14 08:35:00', 'check_in', 'success', 'มาสาย 5 นาที', '2026-09-15 15:44:37'),
(4, 4, 4, 4, '2026-09-14 17:45:00', 'check_out', 'success', NULL, '2026-09-15 15:44:37'),
(5, 4, 4, 6, '2026-09-15 08:40:00', 'check_in', 'success', NULL, '2026-09-15 15:44:37'),
(6, NULL, NULL, NULL, '2026-09-13 10:15:00', 'unknown_card', 'ignored', 'บัตร RFID-0099 ยังไม่ผูกกับพนักงาน', '2026-09-15 15:44:37');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_key` varchar(100) NOT NULL,
  `setting_value` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`setting_key`, `setting_value`, `description`, `updated_by`, `updated_at`) VALUES
('late_threshold_min', '15', 'นับสายหลังเวลาเข้างานเกินกี่นาที', NULL, '2026-09-15 08:44:14');

-- --------------------------------------------------------

--
-- Table structure for table `time_leave`
--

CREATE TABLE `time_leave` (
  `leave_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `leave_date` date NOT NULL,
  `leave_day` varchar(50) NOT NULL,
  `leave_days` decimal(3,1) NOT NULL DEFAULT 1.0 COMMENT 'จำนวนวันลา เช่น 1, 0.5',
  `leave_comment` text DEFAULT NULL,
  `leave_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time_leave`
--

INSERT INTO `time_leave` (`leave_id`, `emp_id`, `leave_type_id`, `leave_date`, `leave_day`, `leave_days`, `leave_comment`, `leave_status`, `created_at`, `updated_at`) VALUES
(1, 4, 1, '2026-09-20', 'เต็มวัน', 1.0, 'ลาพักร้อนประจำปี', 'approved', '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(2, 4, 2, '2026-09-18', 'ครึ่งเช้า', 0.5, 'ไม่สบาย', 'pending', '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(3, 5, 3, '2026-09-19', 'เต็มวัน', 1.0, 'ทำธุระส่วนตัว', 'rejected', '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(4, 6, 5, '2026-09-16', 'เต็มวัน', 1.0, 'ทำงานที่บ้าน', 'approved', '2026-09-15 15:44:37', '2026-09-15 15:44:37'),
(5, 2, 1, '2026-09-29', 'เต็มวัน', 1.0, '', 'approved', '2026-09-28 12:00:15', '2026-09-28 12:08:50');

-- --------------------------------------------------------

--
-- Table structure for table `user_feature_toggle`
--

CREATE TABLE `user_feature_toggle` (
  `toggle_id` int(11) NOT NULL,
  `login_id` int(11) NOT NULL,
  `feature_id` int(11) NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_feature_toggle`
--

INSERT INTO `user_feature_toggle` (`toggle_id`, `login_id`, `feature_id`, `is_enabled`) VALUES
(1, 1, 1, 1),
(2, 1, 4, 1),
(3, 4, 1, 0),
(4, 6, 2, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`att_id`),
  ADD UNIQUE KEY `uk_emp_date` (`emp_id`,`work_date`);

--
-- Indexes for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `fk_audit_login` (`login_id`),
  ADD KEY `fk_audit_module` (`module_id`);

--
-- Indexes for table `contract`
--
ALTER TABLE `contract`
  ADD PRIMARY KEY (`cont_id`),
  ADD KEY `fk_contract_emp` (`emp_id`),
  ADD KEY `fk_contract_position` (`cont_position`);

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`doc_id`),
  ADD KEY `fk_documents_emp` (`emp_id`),
  ADD KEY `fk_documents_type` (`type_id`);

--
-- Indexes for table `document_type`
--
ALTER TABLE `document_type`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`emp_id`),
  ADD UNIQUE KEY `emp_no` (`emp_no`),
  ADD KEY `fk_emp_position` (`position_id`);

--
-- Indexes for table `employee_contact`
--
ALTER TABLE `employee_contact`
  ADD PRIMARY KEY (`contact_id`),
  ADD KEY `fk_contact_emp` (`emp_id`);

--
-- Indexes for table `employee_login`
--
ALTER TABLE `employee_login`
  ADD PRIMARY KEY (`login_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_login_emp` (`emp_id`),
  ADD KEY `fk_login_role` (`role_id`);

--
-- Indexes for table `feature`
--
ALTER TABLE `feature`
  ADD PRIMARY KEY (`feature_id`),
  ADD UNIQUE KEY `feature_key` (`feature_key`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`leave_type_id`);

--
-- Indexes for table `lucky_employee`
--
ALTER TABLE `lucky_employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lucky_date` (`lucky_date`),
  ADD KEY `idx_lucky_emp` (`emp_id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`module_id`),
  ADD UNIQUE KEY `module_key` (`module_key`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_prt_login` (`login_id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`payroll_id`),
  ADD UNIQUE KEY `uk_emp_period` (`emp_id`,`period_month`,`period_year`),
  ADD KEY `idx_payroll_created_by` (`created_by`);

--
-- Indexes for table `payroll_item`
--
ALTER TABLE `payroll_item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `idx_item_payroll` (`payroll_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`perm_id`),
  ADD UNIQUE KEY `uniq_role_module` (`role_id`,`module_id`),
  ADD KEY `fk_perm_module` (`module_id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`position_id`);

--
-- Indexes for table `rfid_tag`
--
ALTER TABLE `rfid_tag`
  ADD PRIMARY KEY (`tag_id`),
  ADD UNIQUE KEY `uid` (`uid`),
  ADD KEY `fk_rfid_emp` (`emp_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `scan_logs`
--
ALTER TABLE `scan_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_rfid_scanned` (`rfid_id`,`scanned_at`),
  ADD KEY `idx_emp_date` (`emp_id`,`scanned_at`),
  ADD KEY `idx_action_status` (`action`,`status`),
  ADD KEY `fk_scan_attendance` (`attendance_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_key`),
  ADD KEY `fk_settings_updated_by` (`updated_by`);

--
-- Indexes for table `time_leave`
--
ALTER TABLE `time_leave`
  ADD PRIMARY KEY (`leave_id`),
  ADD KEY `fk_leave_emp` (`emp_id`),
  ADD KEY `fk_leave_type` (`leave_type_id`);

--
-- Indexes for table `user_feature_toggle`
--
ALTER TABLE `user_feature_toggle`
  ADD PRIMARY KEY (`toggle_id`),
  ADD UNIQUE KEY `uk_login_feature` (`login_id`,`feature_id`),
  ADD KEY `fk_toggle_feature` (`feature_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `att_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `audit_log`
--
ALTER TABLE `audit_log`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contract`
--
ALTER TABLE `contract`
  MODIFY `cont_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `document_type`
--
ALTER TABLE `document_type`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `employee_contact`
--
ALTER TABLE `employee_contact`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `employee_login`
--
ALTER TABLE `employee_login`
  MODIFY `login_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `feature`
--
ALTER TABLE `feature`
  MODIFY `feature_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `leave_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `lucky_employee`
--
ALTER TABLE `lucky_employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `module_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `payroll_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payroll_item`
--
ALTER TABLE `payroll_item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `perm_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `position_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `rfid_tag`
--
ALTER TABLE `rfid_tag`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `scan_logs`
--
ALTER TABLE `scan_logs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `time_leave`
--
ALTER TABLE `time_leave`
  MODIFY `leave_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_feature_toggle`
--
ALTER TABLE `user_feature_toggle`
  MODIFY `toggle_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `fk_attendance_emp` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`emp_id`) ON DELETE CASCADE;

--
-- Constraints for table `audit_log`
--
ALTER TABLE `audit_log`
  ADD CONSTRAINT `fk_audit_login` FOREIGN KEY (`login_id`) REFERENCES `employee_login` (`login_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_audit_module` FOREIGN KEY (`module_id`) REFERENCES `modules` (`module_id`);

--
-- Constraints for table `contract`
--
ALTER TABLE `contract`
  ADD CONSTRAINT `fk_contract_emp` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`emp_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_contract_position` FOREIGN KEY (`cont_position`) REFERENCES `positions` (`position_id`) ON DELETE SET NULL;

--
-- Constraints for table `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `fk_documents_emp` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`emp_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_documents_type` FOREIGN KEY (`type_id`) REFERENCES `document_type` (`type_id`) ON DELETE SET NULL;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `fk_emp_position` FOREIGN KEY (`position_id`) REFERENCES `positions` (`position_id`) ON DELETE SET NULL;

--
-- Constraints for table `employee_contact`
--
ALTER TABLE `employee_contact`
  ADD CONSTRAINT `fk_contact_emp` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`emp_id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_login`
--
ALTER TABLE `employee_login`
  ADD CONSTRAINT `fk_login_emp` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`emp_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_login_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);

--
-- Constraints for table `lucky_employee`
--
ALTER TABLE `lucky_employee`
  ADD CONSTRAINT `fk_lucky_emp` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`emp_id`) ON DELETE CASCADE;

--
-- Constraints for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD CONSTRAINT `fk_prt_login` FOREIGN KEY (`login_id`) REFERENCES `employee_login` (`login_id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll`
--
ALTER TABLE `payroll`
  ADD CONSTRAINT `fk_payroll_created_by` FOREIGN KEY (`created_by`) REFERENCES `employee_login` (`login_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_payroll_emp` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`emp_id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll_item`
--
ALTER TABLE `payroll_item`
  ADD CONSTRAINT `fk_payroll_item_payroll` FOREIGN KEY (`payroll_id`) REFERENCES `payroll` (`payroll_id`) ON DELETE CASCADE;

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `fk_perm_module` FOREIGN KEY (`module_id`) REFERENCES `modules` (`module_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_perm_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE;

--
-- Constraints for table `rfid_tag`
--
ALTER TABLE `rfid_tag`
  ADD CONSTRAINT `fk_rfid_emp` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`emp_id`) ON DELETE SET NULL;

--
-- Constraints for table `scan_logs`
--
ALTER TABLE `scan_logs`
  ADD CONSTRAINT `fk_scan_attendance` FOREIGN KEY (`attendance_id`) REFERENCES `attendance` (`att_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_scan_emp` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`emp_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_scan_rfid` FOREIGN KEY (`rfid_id`) REFERENCES `rfid_tag` (`tag_id`) ON DELETE SET NULL;

--
-- Constraints for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD CONSTRAINT `fk_settings_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `employee_login` (`login_id`) ON DELETE SET NULL;

--
-- Constraints for table `time_leave`
--
ALTER TABLE `time_leave`
  ADD CONSTRAINT `fk_leave_emp` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`emp_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_leave_type` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`leave_type_id`);

--
-- Constraints for table `user_feature_toggle`
--
ALTER TABLE `user_feature_toggle`
  ADD CONSTRAINT `fk_toggle_feature` FOREIGN KEY (`feature_id`) REFERENCES `feature` (`feature_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_toggle_login` FOREIGN KEY (`login_id`) REFERENCES `employee_login` (`login_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
