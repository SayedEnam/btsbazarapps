-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 23, 2026 at 04:53 AM
-- Server version: 10.11.19-MariaDB
-- PHP Version: 8.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `latestba_montly_bazar`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `module` varchar(50) NOT NULL,
  `model_type` varchar(255) DEFAULT NULL,
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(500) NOT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `module`, `model_type`, `model_id`, `description`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, NULL, 'approved', 'Payroll', 'App\\Models\\Payroll', 1, 'System Administrator marked payroll for July 2026 as Approved', '{\"status\":\"draft\"}', '{\"status\":\"approved\"}', '127.0.0.1', 'Symfony', '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(2, NULL, 'paid', 'Payroll', 'App\\Models\\Payroll', 1, 'System Administrator marked payroll for July 2026 as Paid', '{\"status\":\"approved\"}', '{\"status\":\"paid\"}', '127.0.0.1', 'Symfony', '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(3, 1, 'created', 'Users', 'App\\Models\\User', 26, 'System Administrator created user Newadmin', NULL, '{\"status\":\"active\"}', '103.175.17.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-25 21:30:20', '2026-08-25 21:30:20'),
(4, 1, 'updated', 'Users', 'App\\Models\\User', 26, 'System Administrator updated user Newadmin', '{\"status\":\"active\"}', '{\"status\":\"active\"}', '103.175.17.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-25 21:32:35', '2026-08-25 21:32:35'),
(5, 1, 'status_changed', 'Customers', 'App\\Models\\Customer', 10, 'Test Name\'s membership status was changed from Pending to Active', '{\"status\":\"pending\"}', '{\"status\":\"active\"}', '103.175.17.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-25 21:38:11', '2026-08-25 21:38:11'),
(6, 1, 'approved', 'Applications', 'App\\Models\\Application', 10, 'System Administrator approved Application #APP-2026-000010', '{\"status\":\"pending\"}', '{\"status\":\"approved\"}', '103.175.17.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-25 21:38:43', '2026-08-25 21:38:43'),
(7, 1, 'status_changed', 'Customers', 'App\\Models\\Customer', 11, 'hridoy\'s membership status was changed from Pending to Active', '{\"status\":\"pending\"}', '{\"status\":\"active\"}', '103.150.255.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-26 08:34:42', '2026-08-26 08:34:42'),
(8, 1, 'approved', 'Payroll', 'App\\Models\\Payroll', 2, 'System Administrator marked payroll for August 2026 as Approved', '{\"status\":\"draft\"}', '{\"status\":\"approved\"}', '103.150.255.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-26 09:23:18', '2026-08-26 09:23:18'),
(9, 1, 'approved', 'Applications', 'App\\Models\\Application', 11, 'System Administrator approved Application #APP-2026-000011', '{\"status\":\"pending\"}', '{\"status\":\"approved\"}', '103.150.255.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-26 11:18:25', '2026-08-26 11:18:25'),
(10, 1, 'approved', 'Applications', 'App\\Models\\Application', 12, 'System Administrator approved Application #APP-2026-000012', '{\"status\":\"pending\"}', '{\"status\":\"approved\"}', '103.150.255.24', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-26 11:18:46', '2026-08-26 11:18:46'),
(11, 1, 'updated', 'Users', 'App\\Models\\User', 27, 'System Administrator updated user Test Name', '{\"status\":\"active\"}', '{\"status\":\"active\"}', '103.175.17.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-26 11:34:11', '2026-08-26 11:34:11'),
(12, 1, 'under_review', 'Applications', 'App\\Models\\Application', 3, 'System Administrator marked under review Application #APP-2026-000003', '{\"status\":\"pending\"}', '{\"status\":\"under_review\"}', '103.175.17.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-26 11:34:55', '2026-08-26 11:34:55'),
(13, 1, 'approved', 'Applications', 'App\\Models\\Application', 3, 'System Administrator approved Application #APP-2026-000003', '{\"status\":\"under_review\"}', '{\"status\":\"approved\"}', '182.48.83.30', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', '2026-08-26 12:40:26', '2026-08-26 12:40:26'),
(14, 1, 'approved', 'Applications', 'App\\Models\\Application', 13, 'System Administrator approved Application #APP-2026-000013', '{\"status\":\"pending\"}', '{\"status\":\"approved\"}', '210.87.70.8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-26 19:59:12', '2026-08-26 19:59:12'),
(15, 1, 'approved', 'Applications', 'App\\Models\\Application', 5, 'System Administrator approved Application #APP-2026-000005', '{\"status\":\"under_review\"}', '{\"status\":\"approved\"}', '210.87.70.8', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-26 19:59:13', '2026-08-26 19:59:13'),
(16, 1, 'approved', 'Applications', 'App\\Models\\Application', 14, 'System Administrator approved Application #APP-2026-000014', '{\"status\":\"pending\"}', '{\"status\":\"approved\"}', '210.87.70.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-30 09:22:40', '2026-08-30 09:22:40'),
(17, 1, 'status_changed', 'Customers', 'App\\Models\\Customer', 16, 'hridoy\'s membership status was changed from Pending to Active', '{\"status\":\"pending\"}', '{\"status\":\"active\"}', '210.87.70.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-30 20:58:55', '2026-08-30 20:58:55'),
(18, 1, 'approved', 'Applications', 'App\\Models\\Application', 15, 'System Administrator approved Application #APP-2026-000015', '{\"status\":\"pending\"}', '{\"status\":\"approved\"}', '210.87.70.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-30 20:59:39', '2026-08-30 20:59:39'),
(19, 1, 'status_changed', 'Customers', 'App\\Models\\Customer', 17, 'biplob\'s membership status was changed from Pending to Active', '{\"status\":\"pending\"}', '{\"status\":\"active\"}', '210.87.70.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-30 21:10:10', '2026-08-30 21:10:10'),
(20, 1, 'approved', 'Applications', 'App\\Models\\Application', 16, 'System Administrator approved Application #APP-2026-000016', '{\"status\":\"pending\"}', '{\"status\":\"approved\"}', '210.87.70.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-08-30 21:10:20', '2026-08-30 21:10:20'),
(21, 1, 'cancelled', 'Applications', 'App\\Models\\Application', 9, 'System Administrator cancelled Application #APP-2026-000009', '{\"status\":\"approved\"}', '{\"status\":\"cancelled\"}', '210.87.70.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', '2026-09-01 13:04:01', '2026-09-01 13:04:01'),
(22, 1, 'created', 'Users', 'App\\Models\\User', 57, 'System Administrator created user syed', NULL, '{\"status\":\"active\"}', '210.87.70.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-08 20:52:07', '2026-09-08 20:52:07'),
(23, 57, 'status_changed', 'Customers', 'App\\Models\\Customer', 27, 'sayedasm\'s membership status was changed from Pending to Active', '{\"status\":\"pending\"}', '{\"status\":\"active\"}', '210.87.70.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-08 21:04:35', '2026-09-08 21:04:35'),
(24, 57, 'approved', 'Applications', 'App\\Models\\Application', 24, 'syed approved Application #APP-2026-000024', '{\"status\":\"pending\"}', '{\"status\":\"approved\"}', '210.87.70.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-08 21:06:37', '2026-09-08 21:06:37'),
(25, 57, 'updated', 'Users', 'App\\Models\\User', 61, 'syed updated user abdur rahman', '{\"status\":\"active\"}', '{\"status\":\"active\"}', '103.150.255.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-13 17:35:18', '2026-09-13 17:35:18'),
(26, 57, 'approved', 'Applications', 'App\\Models\\Application', 22, 'syed approved Application #APP-2026-000022', '{\"status\":\"pending\"}', '{\"status\":\"approved\"}', '103.150.255.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-13 17:43:43', '2026-09-13 17:43:43'),
(27, 57, 'status_changed', 'Customers', 'App\\Models\\Customer', 29, 'abdur rahman\'s membership status was changed from Pending to Active', '{\"status\":\"pending\"}', '{\"status\":\"active\"}', '103.150.255.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-13 17:44:38', '2026-09-13 17:44:38'),
(28, 57, 'status_changed', 'Customers', 'App\\Models\\Customer', 29, 'abdur rahman\'s membership status was changed from Active to Inactive', '{\"status\":\"active\"}', '{\"status\":\"inactive\"}', '103.150.255.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-13 17:59:57', '2026-09-13 17:59:57'),
(29, 57, 'status_changed', 'Customers', 'App\\Models\\Customer', 29, 'abdur rahman\'s membership status was changed from Inactive to Active', '{\"status\":\"inactive\"}', '{\"status\":\"active\"}', '103.150.255.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-13 18:01:05', '2026-09-13 18:01:05'),
(30, 57, 'approved', 'Applications', 'App\\Models\\Application', 23, 'syed approved Application #APP-2026-000023', '{\"status\":\"pending\"}', '{\"status\":\"approved\"}', '103.150.255.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 13:07:46', '2026-09-14 13:07:46'),
(31, 57, 'updated', 'Users', 'App\\Models\\User', 62, 'syed updated user rerw', '{\"status\":\"active\"}', '{\"status\":\"inactive\"}', '103.150.255.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 13:14:08', '2026-09-14 13:14:08'),
(32, 57, 'updated', 'Users', 'App\\Models\\User', 62, 'syed updated user rerw', '{\"status\":\"inactive\"}', '{\"status\":\"active\"}', '103.150.255.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-14 13:14:19', '2026-09-14 13:14:19'),
(33, 57, 'status_changed', 'Customers', 'App\\Models\\Customer', 30, 'rerw\'s membership status was changed from Pending to Active', '{\"status\":\"pending\"}', '{\"status\":\"active\"}', '103.150.255.24', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36', '2026-09-15 21:40:20', '2026-09-15 21:40:20');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `application_number` varchar(20) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `officer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `package_id` bigint(20) UNSIGNED NOT NULL,
  `package_price` decimal(15,2) NOT NULL,
  `application_date` date NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` varchar(500) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `application_number`, `customer_id`, `officer_id`, `package_id`, `package_price`, `application_date`, `status`, `reviewed_by`, `reviewed_at`, `rejection_reason`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'APP-2026-000001', 1, 5, 1, 1000.00, '2026-08-05', 'approved', 5, '2026-08-07 19:23:55', NULL, NULL, '2026-08-25 19:23:55', '2026-08-25 19:23:55', NULL),
(2, 'APP-2026-000002', 2, 5, 1, 1000.00, '2026-08-07', 'approved', 5, '2026-08-09 19:23:55', NULL, NULL, '2026-08-25 19:23:55', '2026-08-25 19:23:55', NULL),
(3, 'APP-2026-000003', 3, 6, 1, 1000.00, '2026-08-24', 'approved', 1, '2026-08-26 12:40:26', NULL, NULL, '2026-08-25 19:23:55', '2026-08-26 12:40:26', NULL),
(4, 'APP-2026-000004', 4, NULL, 1, 1000.00, '2026-07-31', 'approved', 1, '2026-08-02 19:23:55', NULL, NULL, '2026-08-25 19:23:55', '2026-08-25 19:23:55', NULL),
(5, 'APP-2026-000005', 5, 7, 1, 1000.00, '2026-08-22', 'approved', 1, '2026-08-26 19:59:13', NULL, NULL, '2026-08-25 19:23:55', '2026-08-26 19:59:13', NULL),
(6, 'APP-2026-000006', 6, NULL, 1, 1000.00, '2026-08-10', 'rejected', 1, '2026-08-12 19:23:55', 'Submitted NID could not be verified.', NULL, '2026-08-25 19:23:55', '2026-08-25 19:23:55', NULL),
(7, 'APP-2026-000007', 7, 8, 1, 1000.00, '2026-08-15', 'approved', 8, '2026-08-17 19:23:55', NULL, NULL, '2026-08-25 19:23:55', '2026-08-25 19:23:55', NULL),
(8, 'APP-2026-000008', 8, 41, 1, 1000.00, '2026-08-13', 'cancelled', 1, '2026-08-15 19:23:55', NULL, NULL, '2026-08-25 19:23:55', '2026-08-26 19:59:31', NULL),
(9, 'APP-2026-000009', 9, 11, 1, 1000.00, '2026-08-18', 'cancelled', 1, '2026-09-01 13:04:01', NULL, NULL, '2026-08-25 19:23:55', '2026-09-01 13:04:01', NULL),
(10, 'APP-2026-000010', 10, NULL, 1, 1000.00, '2026-08-25', 'approved', 1, '2026-08-25 21:38:43', NULL, NULL, '2026-08-25 21:37:57', '2026-08-25 21:38:43', NULL),
(11, 'APP-2026-000011', 11, NULL, 1, 1000.00, '2026-08-26', 'approved', 1, '2026-08-26 11:18:25', NULL, NULL, '2026-08-26 08:32:19', '2026-08-26 11:18:25', NULL),
(12, 'APP-2026-000012', 12, NULL, 1, 1000.00, '2026-08-26', 'approved', 1, '2026-08-26 11:18:46', NULL, NULL, '2026-08-26 11:17:51', '2026-08-26 11:18:46', NULL),
(13, 'APP-2026-000013', 13, 41, 2, 2000.00, '2026-08-26', 'approved', 1, '2026-08-26 19:59:12', NULL, NULL, '2026-08-26 19:57:24', '2026-08-26 19:59:12', NULL),
(14, 'APP-2026-000014', 15, NULL, 1, 1000.00, '2026-08-27', 'approved', 1, '2026-08-30 09:22:40', NULL, NULL, '2026-08-27 12:12:31', '2026-08-30 09:22:40', NULL),
(15, 'APP-2026-000015', 16, 45, 1, 1000.00, '2026-08-30', 'approved', 1, '2026-08-30 20:59:39', NULL, NULL, '2026-08-30 20:59:12', '2026-08-30 20:59:39', NULL),
(16, 'APP-2026-000016', 17, 45, 1, 12000.00, '2026-08-30', 'approved', 1, '2026-08-30 21:10:20', NULL, NULL, '2026-08-30 21:09:53', '2026-08-30 21:10:20', NULL),
(17, 'APP-2026-000017', 19, NULL, 1, 12000.00, '2026-09-05', 'pending', NULL, NULL, NULL, NULL, '2026-09-05 13:10:52', '2026-09-05 13:10:52', NULL),
(18, 'APP-2026-000018', 20, NULL, 1, 12000.00, '2026-09-05', 'pending', NULL, NULL, NULL, NULL, '2026-09-05 13:27:16', '2026-09-05 13:27:16', NULL),
(19, 'APP-2026-000019', 21, NULL, 1, 12000.00, '2026-09-05', 'pending', NULL, NULL, NULL, NULL, '2026-09-05 15:19:05', '2026-09-05 15:19:05', NULL),
(20, 'APP-2026-000020', 22, NULL, 1, 12000.00, '2026-09-05', 'pending', NULL, NULL, NULL, NULL, '2026-09-05 22:25:10', '2026-09-05 22:25:10', NULL),
(21, 'APP-2026-000021', 23, NULL, 1, 12000.00, '2026-09-05', 'pending', NULL, NULL, NULL, NULL, '2026-09-06 00:45:27', '2026-09-06 00:45:27', NULL),
(22, 'APP-2026-000022', 24, NULL, 1, 12000.00, '2026-09-07', 'approved', 57, '2026-09-13 17:43:43', NULL, NULL, '2026-09-07 08:37:23', '2026-09-13 17:43:43', NULL),
(23, 'APP-2026-000023', 25, NULL, 1, 12000.00, '2026-09-07', 'approved', 57, '2026-09-14 13:07:46', NULL, NULL, '2026-09-07 12:51:51', '2026-09-14 13:07:46', NULL),
(24, 'APP-2026-000024', 27, 58, 1, 12000.00, '2026-09-08', 'approved', 57, '2026-09-08 21:06:37', NULL, NULL, '2026-09-08 21:05:59', '2026-09-08 21:06:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `application_status_histories`
--

CREATE TABLE `application_status_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `application_id` bigint(20) UNSIGNED NOT NULL,
  `old_status` varchar(20) DEFAULT NULL,
  `new_status` varchar(20) NOT NULL,
  `changed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reason` varchar(500) DEFAULT NULL,
  `changed_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `application_status_histories`
--

INSERT INTO `application_status_histories` (`id`, `application_id`, `old_status`, `new_status`, `changed_by`, `reason`, `changed_at`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'pending', 13, NULL, '2026-08-05 19:23:55', NULL, NULL),
(2, 1, 'pending', 'approved', 5, NULL, '2026-08-07 19:23:55', NULL, NULL),
(3, 2, NULL, 'pending', 14, NULL, '2026-08-07 19:23:55', NULL, NULL),
(4, 2, 'pending', 'approved', 5, NULL, '2026-08-09 19:23:55', NULL, NULL),
(5, 3, NULL, 'pending', 15, NULL, '2026-08-24 19:23:55', NULL, NULL),
(6, 4, NULL, 'pending', 16, NULL, '2026-07-31 19:23:55', NULL, NULL),
(7, 4, 'pending', 'approved', 1, NULL, '2026-08-02 19:23:55', NULL, NULL),
(8, 5, NULL, 'pending', 17, NULL, '2026-08-22 19:23:55', NULL, NULL),
(9, 5, 'pending', 'under_review', 1, NULL, '2026-08-22 23:23:55', NULL, NULL),
(10, 6, NULL, 'pending', 18, NULL, '2026-08-10 19:23:55', NULL, NULL),
(11, 6, 'pending', 'rejected', 1, 'Submitted NID could not be verified.', '2026-08-12 19:23:55', NULL, NULL),
(12, 7, NULL, 'pending', 19, NULL, '2026-08-15 19:23:55', NULL, NULL),
(13, 7, 'pending', 'approved', 8, NULL, '2026-08-17 19:23:55', NULL, NULL),
(14, 8, NULL, 'pending', 20, NULL, '2026-08-13 19:23:55', NULL, NULL),
(15, 8, 'pending', 'cancelled', 1, 'Customer requested cancellation.', '2026-08-15 19:23:55', NULL, NULL),
(16, 9, NULL, 'pending', 21, NULL, '2026-08-18 19:23:55', NULL, NULL),
(17, 9, 'pending', 'approved', 11, NULL, '2026-08-20 19:23:55', NULL, NULL),
(18, 10, NULL, 'pending', 27, NULL, '2026-08-25 21:37:57', NULL, NULL),
(19, 10, 'pending', 'approved', 1, NULL, '2026-08-25 21:38:43', NULL, NULL),
(20, 11, NULL, 'pending', 32, NULL, '2026-08-26 08:32:19', NULL, NULL),
(21, 12, NULL, 'pending', 38, NULL, '2026-08-26 11:17:51', NULL, NULL),
(22, 11, 'pending', 'approved', 1, NULL, '2026-08-26 11:18:25', NULL, NULL),
(23, 12, 'pending', 'approved', 1, NULL, '2026-08-26 11:18:46', NULL, NULL),
(29, 3, 'pending', 'under_review', 1, NULL, '2026-08-26 11:34:55', NULL, NULL),
(31, 3, 'under_review', 'approved', 1, NULL, '2026-08-26 12:40:26', NULL, NULL),
(32, 13, NULL, 'pending', 42, NULL, '2026-08-26 19:57:24', NULL, NULL),
(33, 13, 'pending', 'approved', 1, NULL, '2026-08-26 19:59:12', NULL, NULL),
(34, 5, 'under_review', 'approved', 1, NULL, '2026-08-26 19:59:13', NULL, NULL),
(35, 14, NULL, 'pending', 44, NULL, '2026-08-27 12:12:31', NULL, NULL),
(36, 14, 'pending', 'approved', 1, NULL, '2026-08-30 09:22:40', NULL, NULL),
(37, 15, NULL, 'pending', 46, NULL, '2026-08-30 20:59:12', NULL, NULL),
(38, 15, 'pending', 'approved', 1, NULL, '2026-08-30 20:59:39', NULL, NULL),
(39, 16, NULL, 'pending', 47, NULL, '2026-08-30 21:09:53', NULL, NULL),
(40, 16, 'pending', 'approved', 1, NULL, '2026-08-30 21:10:20', NULL, NULL),
(41, 9, 'approved', 'cancelled', 1, NULL, '2026-09-01 13:04:01', NULL, NULL),
(42, 17, NULL, 'pending', 49, NULL, '2026-09-05 13:10:52', NULL, NULL),
(43, 18, NULL, 'pending', 50, NULL, '2026-09-05 13:27:16', NULL, NULL),
(44, 19, NULL, 'pending', 51, NULL, '2026-09-05 15:19:05', NULL, NULL),
(45, 20, NULL, 'pending', 52, NULL, '2026-09-05 22:25:10', NULL, NULL),
(46, 21, NULL, 'pending', 53, NULL, '2026-09-06 00:45:27', NULL, NULL),
(47, 22, NULL, 'pending', 54, NULL, '2026-09-07 08:37:23', NULL, NULL),
(48, 23, NULL, 'pending', 55, NULL, '2026-09-07 12:51:51', NULL, NULL),
(49, 24, NULL, 'pending', 59, NULL, '2026-09-08 21:05:59', NULL, NULL),
(50, 24, 'pending', 'approved', 57, NULL, '2026-09-08 21:06:37', NULL, NULL),
(51, 22, 'pending', 'approved', 57, NULL, '2026-09-13 17:43:43', NULL, NULL),
(52, 23, 'pending', 'approved', 57, NULL, '2026-09-14 13:07:46', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('btsbazar-cache-settings.all', 'O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:26:{s:12:\"company_name\";s:9:\"BTS Bazar\";s:7:\"tagline\";s:52:\"Simple, transparent monthly membership for everyone.\";s:5:\"phone\";s:16:\"+880 01953059064\";s:5:\"email\";s:27:\"btsmasikbazar.com@gmail.com\";s:7:\"address\";s:51:\"House 12, Road 5, Dhanmondi, Dhaka-1209, Bangladesh\";s:16:\"google_map_embed\";s:0:\"\";s:12:\"facebook_url\";s:36:\"https://www.facebook.com/masikbazzar\";s:11:\"youtube_url\";s:31:\"https://youtube.com/masikbazzar\";s:15:\"whatsapp_number\";s:11:\"01953059064\";s:11:\"footer_text\";s:33:\"© BTSbazar. All rights reserved.\";s:15:\"currency_symbol\";s:3:\"৳\";s:8:\"timezone\";s:10:\"Asia/Dhaka\";s:9:\"logo_path\";s:53:\"branding/5Hq9ecyB7xwwdX1eGYI8AaLNQFFoheOghIPV6ymp.jpg\";s:12:\"favicon_path\";s:53:\"branding/epirblwQPH8rsbbYCG3uKgLL4h2SGkacHgSOaHeJ.jpg\";s:19:\"theme_primary_color\";s:7:\"#22183a\";s:24:\"theme_primary_dark_color\";s:7:\"#0f3d3e\";s:25:\"theme_primary_light_color\";s:7:\"#1b8a6b\";s:21:\"theme_footer_bg_color\";s:7:\"#0f3d3e\";s:23:\"theme_footer_text_color\";s:7:\"#e6e6e6\";s:13:\"theme_h1_size\";s:6:\"2.5rem\";s:13:\"theme_h2_size\";s:4:\"2rem\";s:13:\"theme_h3_size\";s:7:\"1.75rem\";s:13:\"theme_h4_size\";s:6:\"1.5rem\";s:13:\"theme_h5_size\";s:7:\"1.25rem\";s:13:\"theme_h6_size\";s:4:\"1rem\";s:20:\"theme_body_font_size\";s:4:\"1rem\";}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 2105454416),
('laravel-cache- admin@monthlybazar.test|210.87.70.15', 'i:2;', 1787984985),
('laravel-cache- admin@monthlybazar.test|210.87.70.15:timer', 'i:1787984985;', 1787984985),
('laravel-cache-0bcd3b0eaa707d04bebf185443de787c042a67cc', 'i:1;', 1787718787),
('laravel-cache-0bcd3b0eaa707d04bebf185443de787c042a67cc:timer', 'i:1787718787;', 1787718787),
('laravel-cache-24d5a3d272a97886f707f33425dafc1a67db4092', 'i:1;', 1788641128),
('laravel-cache-24d5a3d272a97886f707f33425dafc1a67db4092:timer', 'i:1788641128;', 1788641128),
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab', 'i:2;', 1788253876),
('laravel-cache-356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1788253876;', 1788253876),
('laravel-cache-617e3f885586eac5fc02d2b6f82e5c0af5dfe604', 'i:1;', 1788703812),
('laravel-cache-617e3f885586eac5fc02d2b6f82e5c0af5dfe604:timer', 'i:1788703812;', 1788703812),
('laravel-cache-9109c85a45b703f87f1413a405549a2cea9ab556', 'i:2;', 1788886952),
('laravel-cache-9109c85a45b703f87f1413a405549a2cea9ab556:timer', 'i:1788886952;', 1788886952),
('laravel-cache-ejnd|103.31.154.205', 'i:1;', 1789014003),
('laravel-cache-ejnd|103.31.154.205:timer', 'i:1789014003;', 1789014003),
('laravel-cache-newadmin|103.175.17.67', 'i:1;', 1787679138),
('laravel-cache-newadmin|103.175.17.67:timer', 'i:1787679138;', 1787679138),
('laravel-cache-register:103.133.5.97', 'i:1;', 1788641668),
('laravel-cache-register:103.133.5.97:timer', 'i:1788641668;', 1788641668),
('laravel-cache-register:103.150.255.24', 'i:1;', 1789309665),
('laravel-cache-register:103.150.255.24:timer', 'i:1789309665;', 1789309665),
('laravel-cache-register:103.150.255.25', 'i:1;', 1788880519),
('laravel-cache-register:103.150.255.25:timer', 'i:1788880519;', 1788880519),
('laravel-cache-register:103.175.17.67', 'i:1;', 1787680031),
('laravel-cache-register:103.175.17.67:timer', 'i:1787680031;', 1787680031),
('laravel-cache-register:103.31.154.205', 'i:1;', 1789014629),
('laravel-cache-register:103.31.154.205:timer', 'i:1789014629;', 1789014629),
('laravel-cache-register:103.58.72.164', 'i:1;', 1788241104),
('laravel-cache-register:103.58.72.164:timer', 'i:1788241104;', 1788241104),
('laravel-cache-register:103.58.72.167', 'i:1;', 1788633263),
('laravel-cache-register:103.58.72.167:timer', 'i:1788633263;', 1788633263),
('laravel-cache-register:160.250.82.111', 'i:1;', 1788607709),
('laravel-cache-register:160.250.82.111:timer', 'i:1788607709;', 1788607709),
('laravel-cache-register:210.87.70.15', 'i:1;', 1788887637),
('laravel-cache-register:210.87.70.15:timer', 'i:1788887637;', 1788887637),
('laravel-cache-register:210.87.70.8', 'i:1;', 1787760400),
('laravel-cache-register:210.87.70.8:timer', 'i:1787760400;', 1787760400),
('laravel-cache-register:37.111.207.51', 'i:1;', 1788756396),
('laravel-cache-register:37.111.207.51:timer', 'i:1788756396;', 1788756396),
('laravel-cache-settings.all', 'O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:26:{s:12:\"company_name\";s:9:\"BTS Bazar\";s:7:\"tagline\";s:52:\"Simple, transparent monthly membership for everyone.\";s:5:\"phone\";s:16:\"+880 01953059064\";s:5:\"email\";s:27:\"btsmasikbazar.com@gmail.com\";s:7:\"address\";s:51:\"House 12, Road 5, Dhanmondi, Dhaka-1209, Bangladesh\";s:16:\"google_map_embed\";s:0:\"\";s:12:\"facebook_url\";s:36:\"https://www.facebook.com/masikbazzar\";s:11:\"youtube_url\";s:31:\"https://youtube.com/masikbazzar\";s:15:\"whatsapp_number\";s:11:\"01953059064\";s:11:\"footer_text\";s:33:\"© BTSbazar. All rights reserved.\";s:15:\"currency_symbol\";s:3:\"৳\";s:8:\"timezone\";s:10:\"Asia/Dhaka\";s:9:\"logo_path\";s:53:\"branding/5Hq9ecyB7xwwdX1eGYI8AaLNQFFoheOghIPV6ymp.jpg\";s:12:\"favicon_path\";s:53:\"branding/epirblwQPH8rsbbYCG3uKgLL4h2SGkacHgSOaHeJ.jpg\";s:19:\"theme_primary_color\";s:7:\"#22183a\";s:24:\"theme_primary_dark_color\";s:7:\"#0f3d3e\";s:25:\"theme_primary_light_color\";s:7:\"#1b8a6b\";s:21:\"theme_footer_bg_color\";s:7:\"#0f3d3e\";s:23:\"theme_footer_text_color\";s:7:\"#e6e6e6\";s:13:\"theme_h1_size\";s:6:\"2.5rem\";s:13:\"theme_h2_size\";s:4:\"2rem\";s:13:\"theme_h3_size\";s:7:\"1.75rem\";s:13:\"theme_h4_size\";s:6:\"1.5rem\";s:13:\"theme_h5_size\";s:7:\"1.25rem\";s:13:\"theme_h6_size\";s:4:\"1rem\";s:20:\"theme_body_font_size\";s:4:\"1rem\";}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 2104677698),
('laravel-cache-sh02|103.150.255.24', 'i:1;', 1787818391),
('laravel-cache-sh02|103.150.255.24:timer', 'i:1787818391;', 1787818391),
('laravel-cache-sm|103.150.255.24', 'i:1;', 1789307338),
('laravel-cache-sm|103.150.255.24:timer', 'i:1789307338;', 1789307338),
('laravel-cache-sm9|103.150.255.24', 'i:2;', 1789383724),
('laravel-cache-sm9|103.150.255.24:timer', 'i:1789383724;', 1789383724),
('laravel-cache-syedenamshohag@gmail.com12|103.150.255.24', 'i:1;', 1789655029),
('laravel-cache-syedenamshohag@gmail.com12|103.150.255.24:timer', 'i:1789655029;', 1789655029);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `nid_number` varchar(30) DEFAULT NULL,
  `profession` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `user_id`, `father_name`, `mother_name`, `address`, `nid_number`, `profession`, `date_of_birth`, `gender`, `profile_photo`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 13, 'Abul Kalam', 'Rahima Begum', NULL, '1990123456001', 'Teacher', NULL, 'female', NULL, 'active', '2026-08-25 19:23:55', '2026-08-25 19:23:55', NULL),
(2, 14, 'Aziz Mia', 'Halima Khatun', NULL, '1988123456002', 'Businessman', NULL, 'male', NULL, 'active', '2026-08-25 19:23:55', '2026-08-25 19:23:55', NULL),
(3, 15, 'Mokbul Hossain', 'Jesmin Akter', NULL, '1995123456003', 'Student', NULL, 'female', NULL, 'active', '2026-08-25 19:23:55', '2026-08-26 12:40:26', NULL),
(4, 16, 'Sirajul Islam', 'Rokeya Begum', NULL, '1985123456004', 'Farmer', NULL, 'male', NULL, 'active', '2026-08-25 19:23:55', '2026-08-25 19:23:55', NULL),
(5, 17, 'Nurul Amin', 'Sufia Khatun', NULL, '1992123456005', 'Tailor', NULL, 'female', NULL, 'active', '2026-08-25 19:23:55', '2026-08-26 19:59:13', NULL),
(6, 18, 'Jamal Uddin', 'Amena Khatun', NULL, '1980123456006', 'Driver', NULL, 'male', NULL, 'suspended', '2026-08-25 19:23:55', '2026-08-25 19:23:55', NULL),
(7, 19, 'Golam Rabbani', 'Nasima Begum', NULL, '1993123456007', 'Nurse', NULL, 'female', NULL, 'active', '2026-08-25 19:23:55', '2026-08-25 19:23:55', NULL),
(8, 20, 'Fazlur Rahman', 'Shirin Akter', NULL, '1987123456008', 'Shop Owner', NULL, 'male', NULL, 'inactive', '2026-08-25 19:23:55', '2026-08-25 19:23:55', NULL),
(9, 21, 'Habibur Rahman', 'Rashida Begum', NULL, '1991123456009', 'Homemaker', NULL, 'female', NULL, 'active', '2026-08-25 19:23:55', '2026-08-25 19:23:55', NULL),
(10, 27, NULL, NULL, NULL, NULL, NULL, '1994-05-02', 'male', NULL, 'active', '2026-08-25 21:37:11', '2026-08-25 21:38:11', NULL),
(11, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'customers/Vy2bTGxwsg3brOSAjNbHI9xtGUghSth3jefLYIgH.jpg', 'active', '2026-08-26 08:32:10', '2026-08-26 08:34:42', NULL),
(12, 38, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-08-26 11:17:37', '2026-08-26 11:18:46', NULL),
(13, 42, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-08-26 19:56:40', '2026-08-26 19:59:12', NULL),
(14, 43, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2026-08-27 08:57:12', '2026-08-27 08:57:12', NULL),
(15, 44, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-08-27 09:06:15', '2026-08-30 09:22:40', NULL),
(16, 46, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-08-30 20:58:17', '2026-08-30 20:58:55', NULL),
(17, 47, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-08-30 21:08:19', '2026-08-30 21:10:10', NULL),
(18, 48, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2026-09-01 09:28:25', '2026-09-01 09:28:25', NULL),
(19, 49, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2026-09-05 13:10:26', '2026-09-05 13:10:26', NULL),
(20, 50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2026-09-05 13:26:58', '2026-09-05 13:26:58', NULL),
(21, 51, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2026-09-05 15:18:29', '2026-09-05 15:18:29', NULL),
(22, 52, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2026-09-05 22:24:23', '2026-09-05 22:24:23', NULL),
(23, 53, 'Md. Shukur Ali Gazi', 'Momena Begum ', 'Al- Joynal Trade Centre,  Shop-412, Lift-3, Chasara, Narayanganj Sadar, Narayanganj. ', '4168728659', 'Business ', '1990-10-25', 'male', NULL, 'pending', '2026-09-06 00:44:28', '2026-09-06 00:51:59', NULL),
(24, 54, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-09-07 08:36:36', '2026-09-13 17:43:43', NULL),
(25, 55, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-09-07 12:51:27', '2026-09-14 13:07:46', NULL),
(26, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2026-09-08 19:05:20', '2026-09-08 19:05:20', NULL),
(27, 59, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-09-08 21:03:57', '2026-09-08 21:04:35', NULL),
(28, 60, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '2026-09-10 08:20:29', '2026-09-10 08:20:29', NULL),
(29, 61, 'trytrtye hgfgh', 'hfgh jghjg', 'sfsfs,hghfhgd,jhghfhf,hjghfry', '23454345675453', 'bugfg', '1989-02-01', 'male', NULL, 'active', '2026-09-13 17:25:48', '2026-09-13 18:01:05', NULL),
(30, 62, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'active', '2026-09-13 18:17:45', '2026-09-15 21:40:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `parent_id`, `name`, `created_at`, `updated_at`) VALUES
(5, NULL, 'SM', '2026-08-25 21:20:48', '2026-08-25 21:20:48'),
(6, NULL, 'ASM', '2026-08-25 21:20:56', '2026-08-25 21:20:56'),
(7, NULL, 'DSM', '2026-08-30 20:54:12', '2026-08-30 20:54:12'),
(8, NULL, 'RSM', '2026-08-30 20:54:18', '2026-08-30 20:54:18'),
(9, NULL, 'ZSM', '2026-08-30 20:54:23', '2026-08-30 20:54:23'),
(10, NULL, 'MO', '2026-08-30 20:54:28', '2026-08-30 20:54:28');

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

CREATE TABLE `designations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `designations`
--

INSERT INTO `designations` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Marketing Officer', '2026-08-25 19:23:55', '2026-08-25 19:23:55'),
(2, 'Senior Marketing Officer', '2026-08-25 19:23:55', '2026-08-25 19:23:55'),
(3, 'Team Lead', '2026-08-25 19:23:55', '2026-08-25 19:23:55'),
(4, 'Area Manager', '2026-08-25 19:23:55', '2026-08-25 19:23:55');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `expense_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `paid_by` varchar(255) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `category_id`, `amount`, `expense_date`, `description`, `paid_by`, `reference`, `attachment`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3, 4500.00, '2026-08-20', 'Office supplies for the month.', 'Dhaka Stationery House', NULL, NULL, 1, '2026-08-25 19:23:56', '2026-08-25 19:23:56', NULL),
(2, 6, 2000.00, '2026-08-15', 'Monthly office internet bill.', 'Link3 Technologies', NULL, NULL, 1, '2026-08-25 19:23:56', '2026-08-25 19:23:56', NULL),
(3, 7, 1500.00, '2026-08-15', 'Office mobile connections.', 'Grameenphone', NULL, NULL, 1, '2026-08-25 19:23:56', '2026-08-25 19:23:56', NULL),
(4, 2, 3200.00, '2026-08-10', 'Field visit transport costs.', NULL, NULL, NULL, 1, '2026-08-25 19:23:56', '2026-08-25 19:23:56', NULL),
(5, 4, 8000.00, '2026-08-05', 'Local leaflet printing and distribution.', 'Print Media BD', NULL, NULL, 1, '2026-08-25 19:23:56', '2026-08-25 19:23:56', NULL),
(6, 5, 12000.00, '2026-07-31', 'Social media ad campaign.', 'Facebook Ads', NULL, NULL, 1, '2026-08-25 19:23:56', '2026-08-25 19:23:56', NULL),
(7, 8, 1800.00, '2026-08-17', 'Membership forms and brochures.', 'Quick Print', NULL, NULL, 1, '2026-08-25 19:23:56', '2026-08-25 19:23:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expense_categories`
--

INSERT INTO `expense_categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Officer Salary', '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(2, 'Transport', '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(3, 'Office Expense', '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(4, 'Marketing Expense', '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(5, 'Advertisement', '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(6, 'Internet', '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(7, 'Mobile Bill', '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(8, 'Printing', '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(9, 'Other', '2026-08-25 19:23:56', '2026-08-25 19:23:56');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `financial_transactions`
--

CREATE TABLE `financial_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_type` varchar(10) NOT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `transaction_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hero_slides`
--

CREATE TABLE `hero_slides` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `display_mode` varchar(20) NOT NULL DEFAULT 'text_and_button',
  `button_text` varchar(255) DEFAULT NULL,
  `button_url` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hero_slides`
--

INSERT INTO `hero_slides` (`id`, `title`, `subtitle`, `image`, `display_mode`, `button_text`, `button_url`, `status`, `sort_order`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '1', NULL, 'hero-slides/M7UQnoBbYg5webubLM5ASdLn7cRI5oiFOWBcPULG.jpg', 'text_and_button', 'Apply', 'https://masikbazzar.com/contact', 'active', 1, 1, 1, '2026-08-30 21:39:33', '2026-08-31 22:23:31', NULL),
(2, '2', NULL, 'hero-slides/IDdQ3VEgfGgmrwDaKj0W60ZcEy5gkTMeukgUQ3LW.jpg', 'image_only', NULL, NULL, 'active', 0, 1, 1, '2026-08-30 21:41:03', '2026-08-31 21:12:28', NULL),
(3, '3', NULL, 'hero-slides/TXl8tuwvOkCeNLxdMxNp8iqhJHDHLTpbWhKZWuan.jpg', 'image_only', NULL, NULL, 'active', 0, 1, 1, '2026-08-30 21:41:12', '2026-08-31 21:12:40', NULL),
(4, '4', NULL, 'hero-slides/as4rJuXAw3eC8C82Hw9AghV4GUvsuJyRwS5a0hws.jpg', 'image_only', NULL, NULL, 'active', 0, 1, 1, '2026-08-30 21:41:20', '2026-08-31 21:12:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(29, '0001_01_01_000000_create_users_table', 1),
(30, '0001_01_01_000001_create_cache_table', 1),
(31, '0001_01_01_000002_create_jobs_table', 1),
(32, '2026_08_22_151338_create_roles_table', 1),
(33, '2026_08_22_151339_create_permissions_table', 1),
(34, '2026_08_22_151340_create_permission_role_table', 1),
(35, '2026_08_22_151340_create_role_user_table', 1),
(36, '2026_08_22_155810_create_settings_table', 1),
(37, '2026_08_22_155811_create_packages_table', 1),
(38, '2026_08_22_155811_create_pages_table', 1),
(39, '2026_08_22_155812_create_contact_messages_table', 1),
(40, '2026_08_22_161706_add_referral_code_to_users_table', 1),
(41, '2026_08_22_161707_create_customers_table', 1),
(42, '2026_08_22_161707_create_referrals_table', 1),
(43, '2026_08_22_162947_create_departments_table', 1),
(44, '2026_08_22_162947_create_designations_table', 1),
(45, '2026_08_22_162948_create_officers_table', 1),
(46, '2026_08_23_161916_create_applications_table', 1),
(47, '2026_08_23_161917_create_application_status_histories_table', 1),
(48, '2026_08_23_161918_create_notifications_table', 1),
(49, '2026_08_24_081746_create_payrolls_table', 1),
(50, '2026_08_24_081746_create_salary_profiles_table', 1),
(51, '2026_08_24_081747_create_payroll_items_table', 1),
(52, '2026_08_24_081747_create_salary_payments_table', 1),
(53, '2026_08_24_083056_create_expense_categories_table', 1),
(54, '2026_08_24_083056_create_expenses_table', 1),
(55, '2026_08_24_083057_create_financial_transactions_table', 1),
(56, '2026_08_24_084000_create_activity_logs_table', 1),
(57, '2026_08_25_090000_add_username_to_users_table', 2),
(58, '2026_08_26_160603_strip_ref_prefix_from_referral_codes', 3),
(59, '2026_08_30_155310_create_hero_slides_table', 4),
(60, '2026_08_30_155311_create_testimonials_table', 4),
(61, '2026_08_31_162218_add_display_mode_to_hero_slides_table', 5),
(62, '2026_08_31_170119_add_parent_id_to_departments_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('00addb1c-fdb3-4a6c-9b1f-616e96454d1f', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 57, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000022 for Standard Membership is now Approved.\",\"application_id\":22,\"application_number\":\"APP-2026-000022\",\"status\":\"approved\"}', '2026-09-14 14:56:22', '2026-09-13 17:43:43', '2026-09-14 14:56:22'),
('07cddad8-b2e9-40a4-8046-c9f4dc49e67c', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 1, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000010 for Standard Membership is now Approved.\",\"application_id\":10,\"application_number\":\"APP-2026-000010\",\"status\":\"approved\"}', '2026-08-26 09:23:51', '2026-08-25 21:38:43', '2026-08-26 09:23:51'),
('0feb9e63-a4a4-4fcf-b136-5d936ef214e9', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 1, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000013 for Test Package is now Approved.\",\"application_id\":13,\"application_number\":\"APP-2026-000013\",\"status\":\"approved\"}', NULL, '2026-08-26 19:59:12', '2026-08-26 19:59:12'),
('183cedcb-a206-4d5a-bd10-87411d80989e', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 46, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000015 for Standard Membership is now Approved.\",\"application_id\":15,\"application_number\":\"APP-2026-000015\",\"status\":\"approved\"}', NULL, '2026-08-30 20:59:39', '2026-08-30 20:59:39'),
('1ce21140-5a72-4ffa-a10b-5be8dcc2126c', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 27, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000010 for Standard Membership is now Approved.\",\"application_id\":10,\"application_number\":\"APP-2026-000010\",\"status\":\"approved\"}', NULL, '2026-08-25 21:38:43', '2026-08-25 21:38:43'),
('1fe77cf6-9b59-415b-a8e8-e9f671b82487', 'App\\Notifications\\NewApplicationSubmitted', 'App\\Models\\User', 58, '{\"title\":\"New application received\",\"message\":\"sayedasm applied for Standard Membership.\",\"application_id\":24,\"application_number\":\"APP-2026-000024\"}', NULL, '2026-09-08 21:05:59', '2026-09-08 21:05:59'),
('20cd278c-533a-4662-9f3b-a2980200fa1f', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 1, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000003 for Standard Membership is now Approved.\",\"application_id\":3,\"application_number\":\"APP-2026-000003\",\"status\":\"approved\"}', NULL, '2026-08-26 12:40:26', '2026-08-26 12:40:26'),
('25edb3db-8998-4d4e-84f5-e041a835cd94', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 1, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000005 for Standard Membership is now Approved.\",\"application_id\":5,\"application_number\":\"APP-2026-000005\",\"status\":\"approved\"}', NULL, '2026-08-26 19:59:13', '2026-08-26 19:59:13'),
('2b001dad-fb94-46ba-9752-855c677692f4', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 47, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000016 for Standard Membership is now Approved.\",\"application_id\":16,\"application_number\":\"APP-2026-000016\",\"status\":\"approved\"}', NULL, '2026-08-30 21:10:20', '2026-08-30 21:10:20'),
('3bc4a310-0b60-4fc4-a318-47863457a548', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 57, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000023 for Standard Membership is now Approved.\",\"application_id\":23,\"application_number\":\"APP-2026-000023\",\"status\":\"approved\"}', '2026-09-14 14:56:17', '2026-09-14 13:07:46', '2026-09-14 14:56:17'),
('43974ccf-b88d-4fb3-9024-f7f08f78a080', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 1, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000024 for Standard Membership is now Approved.\",\"application_id\":24,\"application_number\":\"APP-2026-000024\",\"status\":\"approved\"}', NULL, '2026-09-08 21:06:37', '2026-09-08 21:06:37'),
('49cc3b08-14a9-4010-bf6b-b5a066c2d12a', 'App\\Notifications\\NewApplicationSubmitted', 'App\\Models\\User', 45, '{\"title\":\"New application received\",\"message\":\"hridoy applied for Standard Membership.\",\"application_id\":15,\"application_number\":\"APP-2026-000015\"}', NULL, '2026-08-30 20:59:12', '2026-08-30 20:59:12'),
('5e5c8614-84a9-42f5-a598-976e1a99ba13', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 1, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000014 for Standard Membership is now Approved.\",\"application_id\":14,\"application_number\":\"APP-2026-000014\",\"status\":\"approved\"}', NULL, '2026-08-30 09:22:40', '2026-08-30 09:22:40'),
('6248a9ae-ee30-41b7-963c-f2bcda432a38', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 38, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000012 for Standard Membership is now Approved.\",\"application_id\":12,\"application_number\":\"APP-2026-000012\",\"status\":\"approved\"}', NULL, '2026-08-26 11:18:46', '2026-08-26 11:18:46'),
('652d9b40-3d56-4236-82ca-4a0c7aca937d', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 1, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000023 for Standard Membership is now Approved.\",\"application_id\":23,\"application_number\":\"APP-2026-000023\",\"status\":\"approved\"}', NULL, '2026-09-14 13:07:46', '2026-09-14 13:07:46'),
('72b090b8-303f-4cdd-b23f-a985e563dcd0', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 1, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000022 for Standard Membership is now Approved.\",\"application_id\":22,\"application_number\":\"APP-2026-000022\",\"status\":\"approved\"}', NULL, '2026-09-13 17:43:43', '2026-09-13 17:43:43'),
('72fb4e9c-e0fe-4ffc-bad8-f27d9aa4e5a5', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 1, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000012 for Standard Membership is now Approved.\",\"application_id\":12,\"application_number\":\"APP-2026-000012\",\"status\":\"approved\"}', NULL, '2026-08-26 11:18:46', '2026-08-26 11:18:46'),
('7a49c2a3-8bfc-41e7-bf19-f92cc9dc19ef', 'App\\Notifications\\NewApplicationSubmitted', 'App\\Models\\User', 45, '{\"title\":\"New application received\",\"message\":\"biplob applied for Standard Membership.\",\"application_id\":16,\"application_number\":\"APP-2026-000016\"}', NULL, '2026-08-30 21:09:53', '2026-08-30 21:09:53'),
('89a69767-5b37-44f9-9200-8e38c4d9a734', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 1, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000016 for Standard Membership is now Approved.\",\"application_id\":16,\"application_number\":\"APP-2026-000016\",\"status\":\"approved\"}', NULL, '2026-08-30 21:10:20', '2026-08-30 21:10:20'),
('92a44007-43d5-450b-9871-e3f3845fbb2b', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 59, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000024 for Standard Membership is now Approved.\",\"application_id\":24,\"application_number\":\"APP-2026-000024\",\"status\":\"approved\"}', NULL, '2026-09-08 21:06:37', '2026-09-08 21:06:37'),
('94d2c110-168c-423c-b1c0-be9851ee5d39', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 44, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000014 for Standard Membership is now Approved.\",\"application_id\":14,\"application_number\":\"APP-2026-000014\",\"status\":\"approved\"}', NULL, '2026-08-30 09:22:40', '2026-08-30 09:22:40'),
('9b8a4a81-a631-4395-98df-0d0f396f3d1f', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 32, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000011 for Standard Membership is now Approved.\",\"application_id\":11,\"application_number\":\"APP-2026-000011\",\"status\":\"approved\"}', NULL, '2026-08-26 11:18:25', '2026-08-26 11:18:25'),
('c6c45ad6-cd30-4822-b36e-f6a103cd193b', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 57, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000024 for Standard Membership is now Approved.\",\"application_id\":24,\"application_number\":\"APP-2026-000024\",\"status\":\"approved\"}', '2026-09-14 14:56:22', '2026-09-08 21:06:37', '2026-09-14 14:56:22'),
('cea4d730-71d7-4897-9960-0d86a2b66ab2', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 42, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000013 for Test Package is now Approved.\",\"application_id\":13,\"application_number\":\"APP-2026-000013\",\"status\":\"approved\"}', '2026-08-26 20:01:40', '2026-08-26 19:59:12', '2026-08-26 20:01:40'),
('cecb1e53-a941-427d-8989-48b7463cae83', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 1, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000015 for Standard Membership is now Approved.\",\"application_id\":15,\"application_number\":\"APP-2026-000015\",\"status\":\"approved\"}', NULL, '2026-08-30 20:59:39', '2026-08-30 20:59:39'),
('d833da26-d847-4cac-8baf-487b6c1c08bf', 'App\\Notifications\\NewApplicationSubmitted', 'App\\Models\\User', 41, '{\"title\":\"New application received\",\"message\":\"Ratul Islan applied for Test Package.\",\"application_id\":13,\"application_number\":\"APP-2026-000013\"}', NULL, '2026-08-26 19:57:24', '2026-08-26 19:57:24'),
('e13d24a3-6872-4e73-859a-067b0c55ae15', 'App\\Notifications\\ApplicationStatusChanged', 'App\\Models\\User', 1, '{\"title\":\"Your application has been approved\",\"message\":\"Application APP-2026-000011 for Standard Membership is now Approved.\",\"application_id\":11,\"application_number\":\"APP-2026-000011\",\"status\":\"approved\"}', NULL, '2026-08-26 11:18:25', '2026-08-26 11:18:25');

-- --------------------------------------------------------

--
-- Table structure for table `officers`
--

CREATE TABLE `officers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(30) NOT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `designation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `address` text DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `basic_salary` decimal(15,2) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `officers`
--

INSERT INTO `officers` (`id`, `user_id`, `employee_id`, `department_id`, `designation_id`, `address`, `joining_date`, `basic_salary`, `profile_picture`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 5, 'EMP-0001', NULL, 2, NULL, '2023-02-01', 35000.00, NULL, 'active', '2026-08-25 19:23:55', '2026-08-25 19:42:34', '2026-08-25 19:42:34'),
(2, 6, 'EMP-0002', NULL, 1, NULL, '2023-05-15', 28000.00, NULL, 'active', '2026-08-25 19:23:55', '2026-08-25 19:43:00', '2026-08-25 19:43:00'),
(3, 7, 'EMP-0003', NULL, 1, NULL, '2023-08-10', 28000.00, NULL, 'active', '2026-08-25 19:23:55', '2026-08-25 19:43:14', '2026-08-25 19:43:14'),
(4, 8, 'EMP-0004', NULL, 3, NULL, '2022-11-20', 42000.00, NULL, 'active', '2026-08-25 19:23:55', '2026-08-25 19:43:19', '2026-08-25 19:43:19'),
(5, 9, 'EMP-0005', NULL, 1, NULL, '2024-01-05', 27000.00, NULL, 'inactive', '2026-08-25 19:23:55', '2026-08-25 19:43:28', '2026-08-25 19:43:28'),
(6, 10, 'EMP-0006', NULL, 1, NULL, '2024-03-18', 27000.00, NULL, 'inactive', '2026-08-25 19:23:55', '2026-08-25 19:43:32', '2026-08-25 19:43:32'),
(7, 11, 'EMP-0007', NULL, 4, NULL, '2022-06-01', 45000.00, NULL, 'active', '2026-08-25 19:23:55', '2026-08-25 19:43:37', '2026-08-25 19:43:37'),
(8, 12, 'EMP-0008', NULL, 1, NULL, '2024-06-22', 28000.00, NULL, 'active', '2026-08-25 19:23:55', '2026-08-25 19:43:41', '2026-08-25 19:43:41'),
(9, 40, 'EMP-0009', 6, 4, '1 Sib bari more', NULL, NULL, NULL, 'active', '2026-08-26 12:42:22', '2026-08-30 20:54:50', '2026-08-30 20:54:50'),
(10, 41, 'EMP-0010', 6, 4, NULL, '2026-08-26', 100.00, NULL, 'active', '2026-08-26 19:51:52', '2026-08-30 20:54:48', '2026-08-30 20:54:48'),
(11, 45, 'EMP-0011', 5, 2, NULL, '2026-08-30', 2000.00, NULL, 'active', '2026-08-30 20:56:14', '2026-08-30 20:56:14', NULL),
(12, 58, 'EMP-001', 5, 1, 'dhaka', '2026-09-08', 20000.00, NULL, 'active', '2026-09-08 21:01:55', '2026-09-08 21:01:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `name`, `slug`, `code`, `description`, `price`, `duration`, `status`, `image`, `sort_order`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Standard Membership', 'standard-membership', 'STD-1000', 'Our core membership package — a fixed Yearly value, full access to your member dashboard, and support from your referral officer.', 12000.00, 'yearly', 'active', 'packages/f7Y6s5SsmgkLxSivxUDQi7jykX5j1fAQOGJMuGs2.png', 1, NULL, 57, '2026-08-25 19:23:55', '2026-09-08 20:59:21', NULL),
(2, 'Test Package', 'test-package', 'STD-1001', 'Deeeeeeeeeee', 2000.00, 'Mounth', 'active', NULL, 2, 1, 1, '2026-08-26 11:41:10', '2026-08-29 10:36:49', '2026-08-29 10:36:49'),
(3, 'Gold Membership', 'test', 'PKG-0001', 'gjfkgjklfdg\nffdjgkfd\ngnjfdhgjd', 2000.00, 'Monthly', 'active', 'packages/rpF2XUrJVZKmwiKajJn1tWEEJfOueV0U3braaGXX.webp', 2, 1, 1, '2026-08-29 20:44:29', '2026-08-30 21:09:32', '2026-08-30 21:09:32'),
(4, 'Platinum Membership', 'platinum-membership', 'PKG-0002', 'flkfretore\noiretire\nerhreiufreo', 300.00, 'Yearly', 'active', NULL, 3, 1, 1, '2026-08-29 20:47:46', '2026-08-30 21:09:30', '2026-08-30 21:09:30'),
(5, 'Premium Membership', 'premium-membership', 'PKG-0003', 'Our core membership package — a fixed monthly value, full access to your member dashboard, and support from your referral officer.', 35000.00, 'Monthly', 'active', 'packages/T6cZVOL60EaAUTfmtfyBI622jFlH0ya0FWjDpjmx.jpg', 3, 1, 1, '2026-08-30 21:49:33', '2026-09-01 13:07:08', '2026-09-01 13:07:08'),
(6, 'Gold Membership', 'gold-membership', 'PKG-0004', 'Our core membership package — a fixed monthly value, full access to your member dashboard, and support from your referral officer.', 20000.00, 'Monthly', 'active', 'packages/G6uqzRsJiuWe8kENvwIPiAh7G7nVKR5L2ZlNo4op.webp', 2, 1, 1, '2026-08-30 21:50:52', '2026-09-01 13:07:11', '2026-09-01 13:07:11'),
(7, 'etreytrere', 'etreytrere', 'PKG-0005', NULL, 122233.00, NULL, 'inactive', NULL, 0, 57, 57, '2026-09-14 13:15:26', '2026-09-14 13:15:47', '2026-09-14 13:15:47');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `meta_description` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `slug`, `meta_description`, `content`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'About Us', 'about', 'Learn about Monthly Bazar, our mission, and how our membership platform works.', '<p>Monthly Bazar is a membership platform that makes it simple and transparent for\npeople to join, pay a fixed monthly package value, and become part of a growing\ncommunity — supported by a dedicated network of Marketing Officers across the country.</p><p>\n\n</p><p>We started with one goal: remove the confusion and paperwork that usually comes\nwith membership programs. Every package price is fixed and published up front, every\napplication is tracked from submission to approval, and every member can see exactly\nwhere they stand at any time from their own dashboard.</p><p>\n\n</p><h3>Our Mission</h3><p>\n</p><p>To build a transparent, technology-driven membership system that is easy to join,\neasy to manage, and fair to everyone — members and officers alike.</p><p>\n\n</p><h3>What Makes Us Different.</h3><p><br></p>', 1, '2026-08-25 19:23:55', '2026-08-28 18:27:42'),
(2, 'Terms & Conditions', 'terms', 'The terms and conditions governing membership with Monthly Bazar.', '<p>These Terms &amp; Conditions govern your use of Monthly Bazar and your membership\napplication. By registering or applying for a package, you agree to the terms below.</p>\n\n<h3>1. Membership Application</h3>\n<p>Submitting an application does not guarantee approval. Applications are reviewed by\nan authorized Marketing Officer or Administrator and may be approved, rejected, or\nreturned for more information.</p>\n\n<h3>2. Package Pricing</h3>\n<p>The package value shown on your application is fixed at the time you apply and will\nnot change even if the published price changes afterward.</p>\n\n<h3>3. Payment</h3>\n<p>Monthly Bazar does not process online payments through this platform. Payment\narrangements are handled separately and are recorded in your account once confirmed.</p>\n\n<h3>4. Referral Relationships</h3>\n<p>If you registered through a referral link, that referral relationship is permanent\nand will not change, regardless of future activity.</p>\n\n<h3>5. Account Status</h3>\n<p>Monthly Bazar reserves the right to suspend or deactivate an account that violates\nthese terms or provides false information during registration.</p>\n\n<h3>6. Changes to These Terms</h3>\n<p>We may update these terms from time to time. Continued use of the platform after an\nupdate constitutes acceptance of the revised terms.</p>', NULL, '2026-08-25 19:23:55', '2026-08-25 19:23:55'),
(3, 'Privacy Policy', 'privacy', 'How Monthly Bazar collects, uses, and protects your personal information.', '<p>This Privacy Policy explains how Monthly Bazar collects, uses, and protects the\npersonal information you provide when you register, apply for a package, or contact us.</p>\n\n<h3>Information We Collect</h3>\n<p>Name, contact details, address, national ID number, and other information you submit\nthrough registration, application, or contact forms.</p>\n\n<h3>How We Use Your Information</h3>\n<ul>\n    <li>To process your membership application and manage your account.</li>\n    <li>To connect your account with the correct Marketing Officer, when applicable.</li>\n    <li>To respond to inquiries submitted through our Contact page.</li>\n    <li>To maintain accurate records for administrative and reporting purposes.</li>\n</ul>\n\n<h3>How We Protect Your Information</h3>\n<p>Access to member data is restricted by role-based permissions, and all administrative\nactions are logged for accountability.</p>\n\n<h3>Your Rights</h3>\n<p>You may contact us at any time to review, correct, or request removal of your\npersonal information, subject to our record-keeping obligations.</p>', NULL, '2026-08-25 19:23:55', '2026-08-25 19:23:55');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payrolls`
--

CREATE TABLE `payrolls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `month` date NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `generated_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payrolls`
--

INSERT INTO `payrolls` (`id`, `month`, `status`, `generated_by`, `approved_by`, `approved_at`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, '2026-07-01', 'paid', 1, 1, '2026-08-25 19:23:56', '2026-08-25 19:23:56', '2026-08-25 19:23:55', '2026-08-25 19:23:56'),
(2, '2026-08-01', 'approved', 1, 1, '2026-08-26 09:23:18', NULL, '2026-08-25 19:23:56', '2026-08-26 09:23:18');

-- --------------------------------------------------------

--
-- Table structure for table `payroll_items`
--

CREATE TABLE `payroll_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payroll_id` bigint(20) UNSIGNED NOT NULL,
  `officer_id` bigint(20) UNSIGNED NOT NULL,
  `salary_profile_id` bigint(20) UNSIGNED DEFAULT NULL,
  `basic_salary` decimal(15,2) NOT NULL,
  `total_allowance` decimal(15,2) NOT NULL,
  `total_deduction` decimal(15,2) NOT NULL,
  `net_salary` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payroll_items`
--

INSERT INTO `payroll_items` (`id`, `payroll_id`, `officer_id`, `salary_profile_id`, `basic_salary`, `total_allowance`, `total_deduction`, `net_salary`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 1, 35000.00, 12000.00, 0.00, 47000.00, '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(2, 1, 6, 2, 28000.00, 9500.00, 0.00, 37500.00, '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(3, 1, 7, 3, 28000.00, 9500.00, 0.00, 37500.00, '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(4, 1, 8, 4, 42000.00, 15000.00, 0.00, 57000.00, '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(5, 1, 9, 5, 27000.00, 8500.00, 0.00, 35500.00, '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(6, 1, 10, 6, 27000.00, 8500.00, 0.00, 35500.00, '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(7, 1, 11, 7, 45000.00, 16500.00, 0.00, 61500.00, '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(8, 1, 12, 8, 28000.00, 9500.00, 0.00, 37500.00, '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(9, 2, 5, 1, 35000.00, 12000.00, 0.00, 47000.00, '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(10, 2, 6, 2, 28000.00, 9500.00, 0.00, 37500.00, '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(11, 2, 7, 3, 28000.00, 9500.00, 0.00, 37500.00, '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(12, 2, 8, 4, 42000.00, 15000.00, 0.00, 57000.00, '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(13, 2, 9, 5, 27000.00, 8500.00, 0.00, 35500.00, '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(14, 2, 10, 6, 27000.00, 8500.00, 0.00, 35500.00, '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(15, 2, 11, 7, 45000.00, 16500.00, 0.00, 61500.00, '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(16, 2, 12, 8, 28000.00, 9500.00, 0.00, 37500.00, '2026-08-25 19:23:56', '2026-08-25 19:23:56');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `group` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `slug`, `group`, `description`, `created_at`, `updated_at`) VALUES
(1, 'View Users', 'users.view', 'Users', 'Allows the user to view users.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(2, 'Create Users', 'users.create', 'Users', 'Allows the user to create users.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(3, 'Edit Users', 'users.edit', 'Users', 'Allows the user to edit users.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(4, 'Delete Users', 'users.delete', 'Users', 'Allows the user to delete users.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(5, 'View Roles', 'roles.view', 'Roles', 'Allows the user to view roles.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(6, 'Create Roles', 'roles.create', 'Roles', 'Allows the user to create roles.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(7, 'Edit Roles', 'roles.edit', 'Roles', 'Allows the user to edit roles.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(8, 'Delete Roles', 'roles.delete', 'Roles', 'Allows the user to delete roles.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(9, 'View Permissions', 'permissions.view', 'Permissions', 'Allows the user to view permissions.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(10, 'Create Permissions', 'permissions.create', 'Permissions', 'Allows the user to create permissions.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(11, 'Edit Permissions', 'permissions.edit', 'Permissions', 'Allows the user to edit permissions.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(12, 'Delete Permissions', 'permissions.delete', 'Permissions', 'Allows the user to delete permissions.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(13, 'View Officers', 'officers.view', 'Officers', 'Allows the user to view officers.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(14, 'Create Officers', 'officers.create', 'Officers', 'Allows the user to create officers.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(15, 'Edit Officers', 'officers.edit', 'Officers', 'Allows the user to edit officers.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(16, 'Delete Officers', 'officers.delete', 'Officers', 'Allows the user to delete officers.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(17, 'View Departments', 'departments.view', 'Departments', 'Allows the user to view departments.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(18, 'Create Departments', 'departments.create', 'Departments', 'Allows the user to create departments.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(19, 'Edit Departments', 'departments.edit', 'Departments', 'Allows the user to edit departments.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(20, 'Delete Departments', 'departments.delete', 'Departments', 'Allows the user to delete departments.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(21, 'View Designations', 'designations.view', 'Designations', 'Allows the user to view designations.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(22, 'Create Designations', 'designations.create', 'Designations', 'Allows the user to create designations.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(23, 'Edit Designations', 'designations.edit', 'Designations', 'Allows the user to edit designations.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(24, 'Delete Designations', 'designations.delete', 'Designations', 'Allows the user to delete designations.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(25, 'View Customers', 'customers.view', 'Customers', 'Allows the user to view customers.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(26, 'Create Customers', 'customers.create', 'Customers', 'Allows the user to create customers.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(27, 'Edit Customers', 'customers.edit', 'Customers', 'Allows the user to edit customers.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(28, 'Delete Customers', 'customers.delete', 'Customers', 'Allows the user to delete customers.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(29, 'View Referrals', 'referrals.view', 'Referrals', 'Allows the user to view referrals.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(30, 'View Packages', 'packages.view', 'Packages', 'Allows the user to view packages.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(31, 'Create Packages', 'packages.create', 'Packages', 'Allows the user to create packages.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(32, 'Edit Packages', 'packages.edit', 'Packages', 'Allows the user to edit packages.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(33, 'Delete Packages', 'packages.delete', 'Packages', 'Allows the user to delete packages.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(34, 'View Applications', 'applications.view', 'Applications', 'Allows the user to view applications.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(35, 'Approve Applications', 'applications.approve', 'Applications', 'Allows the user to approve applications.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(36, 'Reject Applications', 'applications.reject', 'Applications', 'Allows the user to reject applications.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(37, 'Cancel Applications', 'applications.cancel', 'Applications', 'Allows the user to cancel applications.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(38, 'Reassign Applications', 'applications.reassign', 'Applications', 'Allows the user to reassign applications.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(39, 'View Salary Profiles', 'salary-profiles.view', 'Salary Profiles', 'Allows the user to view salary-profiles.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(40, 'Create Salary Profiles', 'salary-profiles.create', 'Salary Profiles', 'Allows the user to create salary-profiles.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(41, 'Edit Salary Profiles', 'salary-profiles.edit', 'Salary Profiles', 'Allows the user to edit salary-profiles.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(42, 'Delete Salary Profiles', 'salary-profiles.delete', 'Salary Profiles', 'Allows the user to delete salary-profiles.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(43, 'View Payroll', 'payroll.view', 'Payroll', 'Allows the user to view payroll.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(44, 'Create Payroll', 'payroll.create', 'Payroll', 'Allows the user to create payroll.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(45, 'Edit Payroll', 'payroll.edit', 'Payroll', 'Allows the user to edit payroll.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(46, 'Approve Payroll', 'payroll.approve', 'Payroll', 'Allows the user to approve payroll.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(47, 'Pay Payroll', 'payroll.pay', 'Payroll', 'Allows the user to pay payroll.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(48, 'View Expense Categories', 'expense-categories.view', 'Expense Categories', 'Allows the user to view expense-categories.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(49, 'Create Expense Categories', 'expense-categories.create', 'Expense Categories', 'Allows the user to create expense-categories.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(50, 'Edit Expense Categories', 'expense-categories.edit', 'Expense Categories', 'Allows the user to edit expense-categories.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(51, 'Delete Expense Categories', 'expense-categories.delete', 'Expense Categories', 'Allows the user to delete expense-categories.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(52, 'View Expenses', 'expenses.view', 'Expenses', 'Allows the user to view expenses.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(53, 'Create Expenses', 'expenses.create', 'Expenses', 'Allows the user to create expenses.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(54, 'Edit Expenses', 'expenses.edit', 'Expenses', 'Allows the user to edit expenses.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(55, 'Delete Expenses', 'expenses.delete', 'Expenses', 'Allows the user to delete expenses.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(56, 'View Financial Transactions', 'financial-transactions.view', 'Financial Transactions', 'Allows the user to view financial-transactions.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(57, 'Create Financial Transactions', 'financial-transactions.create', 'Financial Transactions', 'Allows the user to create financial-transactions.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(58, 'Edit Financial Transactions', 'financial-transactions.edit', 'Financial Transactions', 'Allows the user to edit financial-transactions.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(59, 'Delete Financial Transactions', 'financial-transactions.delete', 'Financial Transactions', 'Allows the user to delete financial-transactions.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(60, 'View Accounts', 'accounts.view', 'Accounts', 'Allows the user to view accounts.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(61, 'View Reports', 'reports.view', 'Reports', 'Allows the user to view reports.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(62, 'Export Reports', 'reports.export', 'Reports', 'Allows the user to export reports.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(63, 'View Activity Logs', 'activity-logs.view', 'Activity Logs', 'Allows the user to view activity-logs.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(64, 'View Pages', 'pages.view', 'Pages', 'Allows the user to view pages.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(65, 'Edit Pages', 'pages.edit', 'Pages', 'Allows the user to edit pages.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(66, 'View Settings', 'settings.view', 'Settings', 'Allows the user to view settings.', '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(67, 'Edit Settings', 'settings.edit', 'Settings', 'Allows the user to edit settings.', '2026-08-25 19:23:50', '2026-08-25 19:23:50');

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`id`, `permission_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 60, 2, NULL, NULL),
(2, 63, 2, NULL, NULL),
(3, 34, 2, NULL, NULL),
(4, 35, 2, NULL, NULL),
(5, 36, 2, NULL, NULL),
(6, 37, 2, NULL, NULL),
(7, 38, 2, NULL, NULL),
(8, 25, 2, NULL, NULL),
(9, 26, 2, NULL, NULL),
(11, 28, 2, NULL, NULL),
(12, 17, 2, NULL, NULL),
(13, 18, 2, NULL, NULL),
(14, 19, 2, NULL, NULL),
(15, 20, 2, NULL, NULL),
(16, 21, 2, NULL, NULL),
(17, 22, 2, NULL, NULL),
(18, 23, 2, NULL, NULL),
(19, 24, 2, NULL, NULL),
(20, 48, 2, NULL, NULL),
(21, 49, 2, NULL, NULL),
(22, 50, 2, NULL, NULL),
(23, 51, 2, NULL, NULL),
(24, 52, 2, NULL, NULL),
(25, 53, 2, NULL, NULL),
(26, 54, 2, NULL, NULL),
(27, 55, 2, NULL, NULL),
(28, 56, 2, NULL, NULL),
(29, 57, 2, NULL, NULL),
(30, 58, 2, NULL, NULL),
(31, 59, 2, NULL, NULL),
(32, 13, 2, NULL, NULL),
(33, 14, 2, NULL, NULL),
(34, 15, 2, NULL, NULL),
(35, 16, 2, NULL, NULL),
(36, 30, 2, NULL, NULL),
(37, 31, 2, NULL, NULL),
(38, 32, 2, NULL, NULL),
(39, 33, 2, NULL, NULL),
(40, 64, 2, NULL, NULL),
(41, 65, 2, NULL, NULL),
(42, 43, 2, NULL, NULL),
(43, 44, 2, NULL, NULL),
(44, 45, 2, NULL, NULL),
(45, 46, 2, NULL, NULL),
(46, 47, 2, NULL, NULL),
(47, 9, 2, NULL, NULL),
(48, 10, 2, NULL, NULL),
(49, 11, 2, NULL, NULL),
(50, 12, 2, NULL, NULL),
(51, 29, 2, NULL, NULL),
(52, 61, 2, NULL, NULL),
(53, 62, 2, NULL, NULL),
(54, 5, 2, NULL, NULL),
(55, 6, 2, NULL, NULL),
(56, 7, 2, NULL, NULL),
(57, 8, 2, NULL, NULL),
(58, 39, 2, NULL, NULL),
(59, 40, 2, NULL, NULL),
(60, 41, 2, NULL, NULL),
(61, 42, 2, NULL, NULL),
(62, 66, 2, NULL, NULL),
(63, 67, 2, NULL, NULL),
(64, 1, 2, NULL, NULL),
(65, 2, 2, NULL, NULL),
(66, 3, 2, NULL, NULL),
(67, 4, 2, NULL, NULL),
(68, 35, 3, NULL, NULL),
(69, 36, 3, NULL, NULL),
(70, 34, 3, NULL, NULL),
(71, 25, 3, NULL, NULL),
(72, 60, 4, NULL, NULL),
(73, 63, 4, NULL, NULL),
(74, 2, 1, NULL, NULL),
(75, 4, 1, NULL, NULL),
(76, 3, 1, NULL, NULL),
(77, 1, 1, NULL, NULL),
(78, 66, 1, NULL, NULL),
(79, 67, 1, NULL, NULL),
(80, 40, 1, NULL, NULL),
(81, 42, 1, NULL, NULL),
(82, 41, 1, NULL, NULL),
(83, 39, 1, NULL, NULL),
(84, 60, 1, NULL, NULL),
(85, 63, 1, NULL, NULL),
(86, 35, 1, NULL, NULL),
(87, 37, 1, NULL, NULL),
(88, 38, 1, NULL, NULL),
(89, 34, 1, NULL, NULL),
(90, 26, 1, NULL, NULL),
(91, 28, 1, NULL, NULL),
(92, 27, 1, NULL, NULL),
(93, 25, 1, NULL, NULL),
(94, 17, 1, NULL, NULL),
(95, 19, 1, NULL, NULL),
(96, 20, 1, NULL, NULL),
(97, 18, 1, NULL, NULL),
(98, 22, 1, NULL, NULL),
(99, 24, 1, NULL, NULL),
(100, 23, 1, NULL, NULL),
(101, 21, 1, NULL, NULL),
(102, 49, 1, NULL, NULL),
(103, 48, 1, NULL, NULL),
(104, 51, 1, NULL, NULL),
(105, 50, 1, NULL, NULL),
(106, 52, 1, NULL, NULL),
(107, 54, 1, NULL, NULL),
(108, 55, 1, NULL, NULL),
(109, 53, 1, NULL, NULL),
(110, 57, 1, NULL, NULL),
(111, 59, 1, NULL, NULL),
(112, 56, 1, NULL, NULL),
(113, 58, 1, NULL, NULL),
(114, 13, 1, NULL, NULL),
(115, 15, 1, NULL, NULL),
(116, 16, 1, NULL, NULL),
(117, 14, 1, NULL, NULL),
(118, 31, 1, NULL, NULL),
(119, 33, 1, NULL, NULL),
(120, 32, 1, NULL, NULL),
(121, 30, 1, NULL, NULL),
(122, 64, 1, NULL, NULL),
(123, 65, 1, NULL, NULL),
(124, 46, 1, NULL, NULL),
(125, 44, 1, NULL, NULL),
(126, 45, 1, NULL, NULL),
(127, 47, 1, NULL, NULL),
(128, 43, 1, NULL, NULL),
(129, 10, 1, NULL, NULL),
(130, 12, 1, NULL, NULL),
(131, 11, 1, NULL, NULL),
(132, 9, 1, NULL, NULL),
(133, 29, 1, NULL, NULL),
(134, 62, 1, NULL, NULL),
(135, 61, 1, NULL, NULL),
(136, 8, 1, NULL, NULL),
(137, 6, 1, NULL, NULL),
(138, 7, 1, NULL, NULL),
(139, 5, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `officer_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `referral_code` varchar(20) NOT NULL,
  `registered_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `referrals`
--

INSERT INTO `referrals` (`id`, `officer_id`, `customer_id`, `referral_code`, `registered_at`, `created_at`, `updated_at`) VALUES
(1, 5, 1, '0003', '2026-08-25 19:23:55', '2026-08-25 19:23:55', '2026-08-25 19:23:55'),
(2, 5, 2, '0003', '2026-08-25 19:23:55', '2026-08-25 19:23:55', '2026-08-25 19:23:55'),
(3, 6, 3, '0007', '2026-08-25 19:23:55', '2026-08-25 19:23:55', '2026-08-25 19:23:55'),
(4, 7, 5, '0005', '2026-08-25 19:23:55', '2026-08-25 19:23:55', '2026-08-25 19:23:55'),
(5, 8, 7, '0001', '2026-08-25 19:23:55', '2026-08-25 19:23:55', '2026-08-25 19:23:55'),
(6, 11, 9, '0008', '2026-08-25 19:23:55', '2026-08-25 19:23:55', '2026-08-25 19:23:55'),
(7, 41, 13, '0010', '2026-08-26 19:56:40', '2026-08-26 19:56:40', '2026-08-26 19:56:40'),
(8, 45, 16, '0011', '2026-08-30 20:58:17', '2026-08-30 20:58:17', '2026-08-30 20:58:17'),
(9, 45, 17, '0011', '2026-08-30 21:08:19', '2026-08-30 21:08:19', '2026-08-30 21:08:19'),
(10, 58, 27, '0012', '2026-09-08 21:03:57', '2026-09-08 21:03:57', '2026-09-08 21:03:57');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `is_system`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'super-admin', 'Full, unrestricted system access.', 1, '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(2, 'Admin', 'admin', 'Manages assigned administrative and financial modules.', 1, '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(3, 'Marketing Officer', 'marketing-officer', 'Manages own referrals and reviews referred applications.', 1, '2026-08-25 19:23:50', '2026-08-25 19:23:50'),
(4, 'Customer', 'customer', 'Member of the platform holding a package application.', 1, '2026-08-25 19:23:50', '2026-08-25 19:23:50');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`id`, `role_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 2, 2, NULL, NULL),
(3, 2, 3, NULL, NULL),
(4, 2, 4, NULL, NULL),
(5, 3, 5, NULL, NULL),
(6, 3, 6, NULL, NULL),
(7, 3, 7, NULL, NULL),
(8, 3, 8, NULL, NULL),
(9, 3, 9, NULL, NULL),
(10, 3, 10, NULL, NULL),
(11, 3, 11, NULL, NULL),
(12, 3, 12, NULL, NULL),
(13, 4, 13, NULL, NULL),
(14, 4, 14, NULL, NULL),
(15, 4, 15, NULL, NULL),
(16, 4, 16, NULL, NULL),
(17, 4, 17, NULL, NULL),
(18, 4, 18, NULL, NULL),
(19, 4, 19, NULL, NULL),
(20, 4, 20, NULL, NULL),
(21, 4, 21, NULL, NULL),
(26, 1, 26, NULL, NULL),
(27, 4, 27, NULL, NULL),
(32, 4, 32, NULL, NULL),
(38, 4, 38, NULL, NULL),
(40, 3, 40, NULL, NULL),
(41, 3, 41, NULL, NULL),
(42, 4, 42, NULL, NULL),
(43, 4, 43, NULL, NULL),
(44, 4, 44, NULL, NULL),
(45, 3, 45, NULL, NULL),
(46, 4, 46, NULL, NULL),
(47, 4, 47, NULL, NULL),
(48, 4, 48, NULL, NULL),
(49, 4, 49, NULL, NULL),
(50, 4, 50, NULL, NULL),
(51, 4, 51, NULL, NULL),
(52, 4, 52, NULL, NULL),
(53, 4, 53, NULL, NULL),
(54, 4, 54, NULL, NULL),
(55, 4, 55, NULL, NULL),
(56, 4, 56, NULL, NULL),
(57, 1, 57, NULL, NULL),
(58, 3, 58, NULL, NULL),
(59, 4, 59, NULL, NULL),
(60, 4, 60, NULL, NULL),
(61, 4, 61, NULL, NULL),
(62, 4, 62, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `salary_payments`
--

CREATE TABLE `salary_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `officer_id` bigint(20) UNSIGNED NOT NULL,
  `payroll_id` bigint(20) UNSIGNED DEFAULT NULL,
  `month` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `transaction_reference` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `paid_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salary_payments`
--

INSERT INTO `salary_payments` (`id`, `officer_id`, `payroll_id`, `month`, `amount`, `payment_date`, `payment_method`, `transaction_reference`, `note`, `paid_by`, `created_at`, `updated_at`) VALUES
(1, 5, 1, '2026-07-01', 47000.00, '2026-07-29', 'bank', 'TXN-202607-5', NULL, 1, '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(2, 6, 1, '2026-07-01', 37500.00, '2026-07-29', 'bank', 'TXN-202607-6', NULL, 1, '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(3, 7, 1, '2026-07-01', 37500.00, '2026-07-29', 'bank', 'TXN-202607-7', NULL, 1, '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(4, 8, 1, '2026-07-01', 57000.00, '2026-07-29', 'bank', 'TXN-202607-8', NULL, 1, '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(5, 9, 1, '2026-07-01', 35500.00, '2026-07-29', 'bank', 'TXN-202607-9', NULL, 1, '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(6, 10, 1, '2026-07-01', 35500.00, '2026-07-29', 'bank', 'TXN-202607-10', NULL, 1, '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(7, 11, 1, '2026-07-01', 61500.00, '2026-07-29', 'bank', 'TXN-202607-11', NULL, 1, '2026-08-25 19:23:56', '2026-08-25 19:23:56'),
(8, 12, 1, '2026-07-01', 37500.00, '2026-07-29', 'bank', 'TXN-202607-12', NULL, 1, '2026-08-25 19:23:56', '2026-08-25 19:23:56');

-- --------------------------------------------------------

--
-- Table structure for table `salary_profiles`
--

CREATE TABLE `salary_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `officer_id` bigint(20) UNSIGNED NOT NULL,
  `basic_salary` decimal(15,2) NOT NULL,
  `house_allowance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `transport_allowance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `mobile_allowance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `other_allowance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `deduction` decimal(15,2) NOT NULL DEFAULT 0.00,
  `effective_from` date NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salary_profiles`
--

INSERT INTO `salary_profiles` (`id`, `officer_id`, `basic_salary`, `house_allowance`, `transport_allowance`, `mobile_allowance`, `other_allowance`, `deduction`, `effective_from`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 5, 35000.00, 8000.00, 3000.00, 1000.00, 0.00, 0.00, '2026-05-01', 'active', '2026-08-25 19:23:55', '2026-08-25 19:23:55', NULL),
(2, 6, 28000.00, 6000.00, 2500.00, 1000.00, 0.00, 0.00, '2026-05-01', 'active', '2026-08-25 19:23:55', '2026-08-25 19:23:55', NULL),
(3, 7, 28000.00, 6000.00, 2500.00, 1000.00, 0.00, 0.00, '2026-05-01', 'active', '2026-08-25 19:23:55', '2026-08-25 19:23:55', NULL),
(4, 8, 42000.00, 10000.00, 3500.00, 1500.00, 0.00, 0.00, '2026-05-01', 'active', '2026-08-25 19:23:55', '2026-08-25 19:23:55', NULL),
(5, 9, 27000.00, 5500.00, 2000.00, 1000.00, 0.00, 0.00, '2026-05-01', 'active', '2026-08-25 19:23:55', '2026-08-25 19:23:55', NULL),
(6, 10, 27000.00, 5500.00, 2000.00, 1000.00, 0.00, 0.00, '2026-05-01', 'active', '2026-08-25 19:23:55', '2026-08-25 19:23:55', NULL),
(7, 11, 45000.00, 11000.00, 4000.00, 1500.00, 0.00, 0.00, '2026-05-01', 'active', '2026-08-25 19:23:55', '2026-08-25 19:23:55', NULL),
(8, 12, 28000.00, 6000.00, 2500.00, 1000.00, 0.00, 0.00, '2026-05-01', 'active', '2026-08-25 19:23:55', '2026-08-25 19:23:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0JpSnqjUydyTp90LLPEAETZ31ysmSMVLBNhvyvFQ', NULL, '185.129.26.135', 'Mozilla/5.0 (Linux; U; Android 13; sk-sk; Xiaomi 11T Pro Build/TKQ1.220829.002) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/112.0.5615.136 Mobile Safari/537.36 XiaoMi/MiuiBrowser/14.4.0-g', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidjVpUUV6V0R5ZXRrMWlUVDdaNGNrWVBZY2Z4aXhlcjBMc21xSkV6VyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly93d3cuYnRzYmF6YXIuY29tLmxhdGVzdGJhbmdsYS5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790132561),
('0paoGGvKvUjeITyN7PlQxjGtaJa0Oby9Jnj3HIL4', NULL, '52.167.144.156', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRk9OTUNaT2E4YzZQRGZ3eU5aMzhnQjQ3OEJFOG1HSUpwcW1WRGZQQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9tYXNpa2Jhenphci5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1789931874),
('1rTuODaM6bOSpb9VAIYBCgW8i0cq9dgjVTGmcP9A', NULL, '3.84.44.51', 'Lynx/2.8.7dev.4 libwww-FM/2.14 SSL-MM/1.4.1 OpenSSL/0.9.8d', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOWp5dFZWV01RT3lOc0VpTDgyaHVtWHRGb0VLd3V2ZGtYdW5Cb2hETyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9tYXNpa2Jhenphci5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790088029),
('2IdAVuKPugzBfUpWmOb7uF5cjAGuuf6fsutX2Ii9', NULL, '149.50.96.188', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZXpFZFNvY09EQ0xvWUk3N2U4bW5uN0hHRWZNN0YxN3VQQ3czUE1kYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vd3d3LmJ0c2JhemFyLmNvbS5sYXRlc3RiYW5nbGEuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790096881),
('2kgN0k5UQJCZ3W8Ju3SWxYlOLfFdZ7Swm8OUo1Fv', NULL, '24.144.93.14', 'Mozilla/5.0 (X11; Linux x86_64; rv:142.0) Gecko/20100101 Firefox/142.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWDlodUZRNUhhRnB2RFVFVTBHZm4xMzR6bDdVMlY1elpZaXY0SFdYdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1789960064),
('2uiw0hUR7HNVLTWCEXZzKeJcCWjFXCI5fe2UVd4l', NULL, '44.243.243.140', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.8010.52 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYTQ4WVRLbUVYUWJkcGc4M21BV3ZUOFJsazVtdnJ6dFJadmJ1YUt4VCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vd3d3LmJ0c2JhemFyLmNvbS5sYXRlc3RiYW5nbGEuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790102869),
('42fiUJVbYpiM4krdJYZG2IprdT2CQo5CHmzocHq8', NULL, '34.91.64.141', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiREZaU0Q1VUt0R1RGYnRCTXllSWJ2Y0N3dXBGVTVCcXpGc3JnQmt3UiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1789927129),
('48KG1g7uIfkxedvdfhi78txIqTRXbLAW4MfvmN59', NULL, '136.111.110.148', 'Mozilla/5.0 (compatible; CMS-Checker/1.0; +https://example.com)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVmZLODVuMk5pc1VmZEVlQjVPVks5SG40MnRsRU92SGpHeVlMa0hFSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9tYXNpa2Jhenphci5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1789926252),
('4zweCRYVAtRfCwCyF6WlkeyvAlyHool48056pxMW', NULL, '159.223.200.92', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSjhyaFQ1aEs5WEh6ZHA3Y0lsSDFiTXZVMW9FcVBJcVhldDJzRlZONyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9tYXNpa2Jhenphci5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1789924783),
('6fg4aQ09yj0CyPscrsPLA4nlhBAQU3aPQbnNs666', NULL, '114.119.149.66', 'Mozilla/5.0 (Linux; Android 7.0;) AppleWebKit/537.36 (HTML, like Gecko) Mobile Safari/537.36 (compatible; PetalBot;+https://webmaster.petalsearch.com/site/petalbot)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWnE3b1Q1aEkxVzV4WEk5N0Z0dnBJeWVwOHlFQUZCN3hocmxkZm81YiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tL2NvbnRhY3QiO3M6NToicm91dGUiO3M6NzoiY29udGFjdCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790071586),
('8aW0QCUQVlkdhgFbJ6ESeIw3XMkBaLqaeIbU1fL5', NULL, '206.189.6.222', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36 ForestEngine/1.0 (+https://forestengine.net/#opt-out)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieGlEd0I2MzlFMUU3cWtxRTNPa3U3MHFqU0h2TElzM1U2UjBScUhYQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790031556),
('8EtYzyABHCwOZKFedQ5dVrDdILdQCDX4b57PjuoB', NULL, '52.203.107.76', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWlVWa0xoQXJVOU1PTU1LUXdKV3dOYXNLSXlNaHdyWUxSa3dISGk1TiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vd3d3LmJ0c2JhemFyLmNvbS5sYXRlc3RiYW5nbGEuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790096757),
('8OLNW709WZXKCaanGmmOSnTCaETxoLXmXA5hrHYB', NULL, '66.249.65.96', 'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.84 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWXliNGhNU3NrM2Q0T0dqZ01UM3lPdXJyeWxsQTdtbEVmMDRUNVBJaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789891703),
('8W1TTeOqPKgPqIi1Wfy4EuC4V2eymiyY9VwMxPZD', NULL, '143.244.182.223', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiejNKc1pyTW11MFBSTUM3WE9JczNWTDA5Vm15MzJNdWprNGZFNjRITyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vd3d3LmJ0c2JhemFyLmNvbS5sYXRlc3RiYW5nbGEuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790094489),
('9H6VL3QLz09rzguSLFFjlr82K2ifxoG4wxDgTw1f', NULL, '143.244.182.223', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieXNCSmFaSEJvQXd1WExYNkpQZDJoTXk1dUtaYXRLem1DamRXY09UcyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly93d3cuYnRzYmF6YXIuY29tLmxhdGVzdGJhbmdsYS5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790094487),
('As6hXIgCuVBJDctRLSeCMRbjrk8GmPLl6F8U5AnU', NULL, '114.119.141.35', 'Mozilla/5.0 (Linux; Android 7.0;) AppleWebKit/537.36 (HTML, like Gecko) Mobile Safari/537.36 (compatible; PetalBot;+https://webmaster.petalsearch.com/site/petalbot)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRGZsSTltZDlvWGxNMEp3RXpsaDlVdVlvMFkyaTRPc2dkdWt6WWxiNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tL3ByaXZhY3kiO3M6NToicm91dGUiO3M6NzoicHJpdmFjeSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1789885300),
('CsGmLv3WZDTkrmRvNf9v2MAiESXBf26l5012v2de', NULL, '66.249.65.105', 'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 5X Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.8010.52 Mobile Safari/537.36 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR25FN1FXcTVYS0piR2h0cXBwWHl3Y0g3N0hwYzZiak54dmZrSWFLUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790034426),
('EcpMGI34SaiSMX7JlV2PzTaOOp1YLnDmLdYtCMRJ', NULL, '35.187.57.215', 'Mozilla/5.0 (compatible; CMS-Checker/1.0; +https://example.com)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTGV4dzN2Z1N0VjQ0T3NxcE5tam1wV3ZmQ0pLa2pXb3ZzNEdpMWltaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9tYXNpa2Jhenphci5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790000238),
('ewgFo544pmRaQqyJa7uzWFOASrICIcsTL8T6Wb86', NULL, '114.119.159.62', 'Mozilla/5.0 (Linux; Android 7.0;) AppleWebKit/537.36 (HTML, like Gecko) Mobile Safari/537.36 (compatible; PetalBot;+https://webmaster.petalsearch.com/site/petalbot)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ1NHalVnSGJsNFVTUFdHWm5ZdHlNR2g4aUh1cTMxZXk0cUFnYzhYcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tL3JlZ2lzdGVyIjtzOjU6InJvdXRlIjtzOjg6InJlZ2lzdGVyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789885000),
('f9bnPXil2wL84tg83V95CArnLL5B6GsaEAf7gwdM', NULL, '34.248.137.227', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.152 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYWp2ZmV0eEIzN1g1eHNTak9qakpJcEtiS0gzVkRQMlhUdXl4TkdJUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vd3d3LmJ0c2JhemFyLmNvbS5sYXRlc3RiYW5nbGEuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790107631),
('feLn3XlQu7BZwnEChKQaVtAsIq8FhItyCcPISiWN', NULL, '103.196.9.115', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_3_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/144.0.7559.95 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTnlvYmlDTUpiQ3BmOTBXMzk0alFLYkVBcXUzTlBXNVJISmw0VVRDViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vd3d3LmJ0c2JhemFyLmNvbS5sYXRlc3RiYW5nbGEuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790094525),
('fIF1lhHkiBZqhSBKGc6xEYiM0gjbdP6R9fChEzst', NULL, '66.249.71.6', 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ29oYmk3aHdXYWNFa1phdkRqWGlzWXdCRGpsS0ZnVHR0N3ByalpmdCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789891703),
('fqrBJ7CEWXVvXkYS0JuyGqqEbblU6ODoGmpkVZ2D', NULL, '3.84.44.51', 'Opera/9.60 (J2ME/MIDP; Opera Mini/4.2.14320/554; U; cs) Presto/2.2.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNVVUZ3dGeWFlbUNsN0VCYzd2Zk5XemdjSFRaYk1vWE9SMUk3eHZqZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790088029),
('gaC4yXLf7XJzJOAkGF7fgAuL71V0j7GxBfnkGitk', NULL, '54.164.153.189', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.127 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVUxPWWt1WEpZcUF1dW1FUTdnWnpXRHNiYTBkUEIwbjg4WjBLOW5ReiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1789912648),
('gDSl12FufK3uWjzDA8pxlQZ4QUbSvTzIkz7E0H1v', NULL, '34.79.142.115', 'Mozilla/5.0 (compatible; CMS-Checker/1.0; +https://example.com)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaTJ3Z1YwbmJpb0g2NmxNcFdaRFR4ZE9rZXJ3dkRiMFUzUkxVR2dWOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9tYXNpa2Jhenphci5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1789937089),
('gnPgYsc9KWKZSsEBRguZYKJdlVHXnmYYxiElSZle', NULL, '34.118.46.56', 'Mozilla/5.0 (iPhone13,2; U; CPU iPhone OS 14_0 like Mac OS X) AppleWebKit/602.1.50 (KHTML, like Gecko) Version/10.0 Mobile/15E148 Safari/602.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVnozUVpXMXJlUTJkd2ZKZjc4N0VuTXdsV0FjdnR3NUlXUVZEMGNQUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDY6Imh0dHA6Ly93d3cuYnRzYmF6YXIuY29tLmxhdGVzdGJhbmdsYS5jb20vbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790094518),
('gRWKltfF88kUUtANc6Y5m9ydm5PFSFwudNmUgX1G', NULL, '204.168.135.70', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYkV6SVJMSVFRVnJJSmNWdWZNY09OS2NSNUdFclRoMWVIelJyNFNmRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790020297),
('gv9gjSsYThLARxbOjc2Sr5g3SGyTlIZ95RvXnoT6', NULL, '136.112.211.63', 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1467.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidFE4YVF1Mm01SWJiaURCcXBRMHNSYkFCOW91RlZOa3AzbnlacTVvZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9tYXNpa2Jhenphci5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1789871467),
('GXSdCLQK06WGOVwWuA813ODARNgb74sZT1aHa4tZ', NULL, '40.77.167.130', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNjk5MU9XVTA5a05vR29XOTF4bmt0M0tZbWR1TlhlQlN1dHBDSjRHbyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tL2hvdy1pdC13b3JrcyI7czo1OiJyb3V0ZSI7czoxMjoiaG93LWl0LXdvcmtzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789916632),
('H8xK8q9u5MpZ9XNU2LDAiyZLApBZIvlk3UU72F1h', NULL, '34.248.137.227', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZHJuYkdSemd0TUdoOGJzQUdFRkYxT3RrTXIwbVpRZVZCUFhseGRjMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vd3d3LmJ0c2JhemFyLmNvbS5sYXRlc3RiYW5nbGEuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790107629),
('i1oI19cdCzdON9fVyUxZta5vyyXOwSrrd4NIBahn', NULL, '143.244.182.223', 'Mozilla/5.0 (X11; Linux x86_64; rv:142.0) Gecko/20100101 Firefox/142.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ1VDb2U4MklMVms1QWY4Z3hTSUxEckhvNFhtUFJ4Y21lUWpiS0oxaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vYnRzYmF6YXIuY29tLmxhdGVzdGJhbmdsYS5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790094763),
('ilKumrSkrujENfidQklDsmWqhxOr1w6mW6isyDEp', NULL, '114.119.151.146', 'Mozilla/5.0 (Linux; Android 7.0;) AppleWebKit/537.36 (HTML, like Gecko) Mobile Safari/537.36 (compatible; PetalBot;+https://webmaster.petalsearch.com/site/petalbot)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoid1dkcHgzb1AzSGJpZEd1UDFWMzBydHpKUEJpTWl0SkFKcTd1TGxOMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tL3Rlcm1zIjtzOjU6InJvdXRlIjtzOjU6InRlcm1zIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790069798),
('inr4Q29EwTrnq1Z4c6p1qo1cIlIN1nyA1TFG8TQH', NULL, '24.144.93.14', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVUJqM0tJdHdES0toQ3pEZkg5SERHOTVZVGtabzFzSmRUZW9PTEpiWiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9tYXNpa2Jhenphci5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1789960063),
('jllZBAE26REyPxmtrSGa2inRPQ1FUxTOQmjRKTcN', NULL, '188.166.75.16', 'Mozilla/5.0 (compatible; ForestEngine/1.0; +https://forestengine.net/)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ1plSVpVT0VLWnNJQ0kzbEM5RVd4anFHSWttaHhjMDJhUTIzRlhPYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vd3d3LmJ0c2JhemFyLmNvbS5sYXRlc3RiYW5nbGEuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790094147),
('JtBD6kmeDRIDfOMBvJrxS7zKiP9K8LtzBXhhkc8U', NULL, '91.230.168.227', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:134.0) Gecko/20100101 Firefox/134.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZEliUklHRWxWcHNxTXhTR04yeGx6WEl2UkRvOTg0N3BCTFN2aGF1QiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly93d3cuYnRzYmF6YXIuY29tLmxhdGVzdGJhbmdsYS5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790094811),
('K3cowYkv3lOn5xvPiDdSJE1VKd6ptztbZWnq0VPh', NULL, '114.119.136.249', 'Mozilla/5.0 (Linux; Android 7.0;) AppleWebKit/537.36 (HTML, like Gecko) Mobile Safari/537.36 (compatible; PetalBot;+https://webmaster.petalsearch.com/site/petalbot)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibUJTblVKamRFaXRFS01sYTNqUzVxNVA2Z05HM1FFNndWNmwyejdkUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tL3BhY2thZ2VzIjtzOjU6InJvdXRlIjtzOjg6InBhY2thZ2VzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789929155),
('Ka9i5cArmaSJ1qW0nXlsn030v1s9lHpeGwDdjobM', NULL, '150.129.93.38', 'Mozilla/5.0 (iPhone; CPU iPhone OS 26_3_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/144.0.7559.95 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU2JBN2MzVnR3RUlnaGZ5ODh3bDVuNWVaTU5QR294YVlZNjN4MmFaMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vd3d3LmJ0c2JhemFyLmNvbS5sYXRlc3RiYW5nbGEuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790094438),
('kGoo2Ncxz2kZlZauqvPvOuqZzb7pmiIt8alDRGRD', NULL, '210.87.70.11', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRHZxMFFIa0dEZmo3YmxmOGxYR0lDdkFqWHlJbmdzcmt4M0tJMzJsciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1789974337),
('l1L0wOi2VTZegZnxVzLlVXbrfCMutyNTTCF7xqJj', NULL, '57.129.136.57', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZFEyVzZXaWpXTjFqYnM0UkFNY3ZXaHZtV25jUmhRREwxUlFXSVBlRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9idHNiYXphci5jb20ubGF0ZXN0YmFuZ2xhLmNvbSI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790094249),
('LIijkQI9NvU2wDFHMbPh1BlxMPJdaxALSYgmMTdx', NULL, '185.116.243.7', 'Mozilla/5.0 (Android 14; Mobile; rv:123.0) Gecko/123.0 Firefox/123', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZElLYWZNa0swYUVhM0ZOZUJOWmtTM0tMNUtTbXd6WmxHcFp2N3FDeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9tYXNpa2Jhenphci5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1789865426),
('LMwxrca5c4Hn4hxG4H1WT5lt0mjCtD9uDZf3lgWm', NULL, '100.24.208.151', 'Mozilla/5.0 (Linux; Android 12; Pixel 4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/101.0.4951.61 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR0RVU0FOZk5jNUtFSFBVNjV3STZaOTlNY2c3a0JidW1hVjJ6Ujh2TyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9tYXNpa2Jhenphci5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790012540),
('MGO9VZ6kKYGTLx9fnCTcEkasOBc0rZynL3pGpyzs', NULL, '100.24.208.151', 'Mozilla/5.0 (Linux; U; Android 2.2; en-us; Nexus One Build/FRF91) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidW1yeFpnVzBSRGFHc3loR0plczhkajVmV2F2YUJ5dHRvSlpkbnMxUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790012540),
('mJPdJZlotpLsr1AzPimDU2YsjT7szxpOrb7O5BtH', NULL, '44.244.100.201', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNnA1OElNQUJyNHFnd2xUSjRpNjhtQjZ2dmhRSHRmYnVxa1p0MDBORyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly93d3cuYnRzYmF6YXIuY29tLmxhdGVzdGJhbmdsYS5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790102202),
('nfJbYj4xzZdthSf5Fmo2wyJnhnyOel49KxRenQuW', NULL, '44.244.206.121', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.8010.52 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTmdxRkVqa0VGUGJ5UWdiWEtqMlZRemdTblRNbzVvQmF1TXdGRmh1UiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vYnRzYmF6YXIuY29tLmxhdGVzdGJhbmdsYS5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790103731),
('NhrZPxqCPgBUXuydaQnZcBKy3vg8kXirv5qrAN0I', NULL, '40.77.167.230', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoic1g0VjFQZXIwWVFBM3dwV01RamZrOEQzQlo4OXJCUmtLcnlGNTQ0WCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789995801),
('nPWkIYBMbIg7cZzmrXcs7O1ic4Kk5jflexJYqOfj', NULL, '34.176.97.94', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiclVyeFM4MHdjWWdxQ2NNbzFaZWRIM0lWekphczhzbnJ2dndqdUU4WCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1789983235),
('nRcRnruA3Ia8TMbkYP3T58AbqKvvsPTD5BlZoYoL', NULL, '199.45.155.36', 'Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoianJyVTVtSWdRSzJiZ29yY0JDY2ZrTHhqV21ldkNOR1oyZHZ1TmM2MCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1789868477),
('O9Jw6ivQj5dASc5UdIf666WDUXwDUmScQpx0ZCEz', NULL, '44.244.100.201', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSVhUVGhrNU8xZ2FUdlVTakRPcThFMmdORGVGSTJuM1BDZ1NNenMwNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly93d3cuYnRzYmF6YXIuY29tLmxhdGVzdGJhbmdsYS5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790102201),
('oeO4tv8CbubnC8LOMp1uHK8T0sTVaCvNvHzOqFCX', NULL, '52.167.144.23', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSVJKbzNYeENzS2V3VHBNdTYzQXVGYnZBdUoxZXZCdHVuUGdOYlY4USI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tL3JlZ2lzdGVyIjtzOjU6InJvdXRlIjtzOjg6InJlZ2lzdGVyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789897993),
('pd5P5PcksFOfHxWvEDHUTQsuvFN0F9Szp9B1dwcg', NULL, '40.77.167.28', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWlpCREVyeFUxRGlFcjdwMllOVGdPZGNjYVdabHJoeHl2R2x5c3YzSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tL3JlZ2lzdGVyIjtzOjU6InJvdXRlIjtzOjg6InJlZ2lzdGVyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790016519),
('q4qJysXCUcTUKGsp79TtaVP23qjCGVC70Osm3RMP', NULL, '57.129.136.57', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQkxkUlRVREQ3WnF0QlJzZmdwN2RtdklQNzlsc0NYekZCZTBjRDhKayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9idHNiYXphci5jb20ubGF0ZXN0YmFuZ2xhLmNvbSI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790094228),
('Q85qZRGDp7xULlUEGnOhKoKhcNB2n2IceH4DTgPJ', NULL, '34.248.137.227', 'Mozilla/5.0 (X11; Linux x86_64; rv:83.0) Gecko/20100101 Firefox/83.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRmk3MGZuUlJyeHJUYmZFSnNnT01ZQWRjb0g5eGRuc3Foak5DYzB2WiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vd3d3LmJ0c2JhemFyLmNvbS5sYXRlc3RiYW5nbGEuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790107627),
('R6Cs6EIHn1tqc5HuuKcSclrhTFYl7Q1uiyGQfn5Y', NULL, '52.167.144.230', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWWtmUTd0ZHF3UkxpN2FkMkZuajRYd0ZkeDhtV3djTXBneTRNSm11MyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tL3BhY2thZ2VzIjtzOjU6InJvdXRlIjtzOjg6InBhY2thZ2VzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789906559),
('R9M3b6Y5T0hejmpGOTpMOFkYzpMnAae1dx9idYLQ', NULL, '44.244.206.121', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQlZpdkYxQVRDUXZGemY1dlpBa0hONDlURldJWU80bm92WFlSa0JCVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vYnRzYmF6YXIuY29tLmxhdGVzdGJhbmdsYS5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790103742),
('Ra2McgbOL1B4E2UqPiHwHiUuOUGe44sG82Fctqe6', NULL, '52.167.144.156', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU3REcENPcFhMQmxrang3aUhBRzdMZU85NlVSbnRxa2hEdlBvRDhKdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1789969799),
('RkQt1edqzfIWpOmbdun3INzuTqNOczVawZyvsw7m', NULL, '52.167.144.161', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib0VOOEFKaWtTallrT0diYWhRWmk1dlMya1NqcVFuWVNPbjg0MHRwdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tL3BhY2thZ2VzIjtzOjU6InJvdXRlIjtzOjg6InBhY2thZ2VzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790079377),
('RR5xzbaj98xs9Co3b11mVGMoxbK56EKz62puhfWK', NULL, '35.86.137.15', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSGcyckx2cTdsTlJoaGwzSkdsaXV5TkVKZ1VNRXh5NnJnYkZ1RHpvZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9idHNiYXphci5jb20ubGF0ZXN0YmFuZ2xhLmNvbSI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790102201),
('sHPEr4gTiXET10GjeVgjznXqDsq3Ts2Yy7yqfqjA', NULL, '44.243.243.140', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia3lRZHJ6V0NhcWpudTBWbllwTUtLU3V4MjhkSmZGd0lYUGN1Qlo5UiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vd3d3LmJ0c2JhemFyLmNvbS5sYXRlc3RiYW5nbGEuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790102883),
('TMh6OWZAcROAj0V0PJN8C4QV55UvFWHj464TRDye', NULL, '35.187.36.114', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVFRWWkpONzhZTXZOV09sU0tSUm9UZFJUNXRpcE9VWG5HUHJ4aGx4cyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vd3d3LmJ0c2JhemFyLmNvbS5sYXRlc3RiYW5nbGEuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790094145),
('Tp75k70RZWphisuEzJdlcewxGPZJV5FGOQkfL82e', NULL, '185.242.177.69', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicDdNekhHSVlEa01RU0hBeW1GUXlEQXk4ZTNEMUpWbmlhQmFSUFFmRiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vd3d3LmJ0c2JhemFyLmNvbS5sYXRlc3RiYW5nbGEuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790094416),
('uE2rfaAjWOukqF66a7oeWMhr77TWFGiwg0B200ju', NULL, '100.24.208.151', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.92 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNm5iUnhER2FZZ2JCS2JmbW9yUmVaV3c4eHdrck05bUFuZjRuMEZXQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9tYXNpa2Jhenphci5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790012540),
('UHueCvUtrVA6bMhjzbihhEnTSzq5pHqGnYLfavyf', NULL, '114.119.136.249', 'Mozilla/5.0 (Linux; Android 7.0;) AppleWebKit/537.36 (HTML, like Gecko) Mobile Safari/537.36 (compatible; PetalBot;+https://webmaster.petalsearch.com/site/petalbot)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWGdPdklTNFVwRmpwZGFMdGpmcEtVb2dyamUzdkZjdDdJbUN0cGF4cyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tL2Fib3V0IjtzOjU6InJvdXRlIjtzOjU6ImFib3V0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1789922439),
('UoDQtMLwBumcmwXUyYBAKeseo12a5VqzA0XGWFm0', NULL, '40.77.167.241', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWUtkV3pVM1RJcHZlMU5INjR1bDl2U0RDSnpEV1huSERzbUptczlkVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790028448),
('VI8j2R6kJsNJ8itbak5ODEOUMDb8CPfmBIWNGYF1', NULL, '72.1.148.55', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/129.0.0.0 Safari/537.3', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia2RJc01KcHhQWHR5ZWF6a1ZDa21kODNKdzJURVplMFRxQmdVZzQ0TiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1789908917),
('VLyXwBRzr5ZNH2356mWWPGD6GdsYfOeCyQssbaH0', NULL, '114.119.136.249', 'Mozilla/5.0 (Linux; Android 7.0;) AppleWebKit/537.36 (HTML, like Gecko) Mobile Safari/537.36 (compatible; PetalBot;+https://webmaster.petalsearch.com/site/petalbot)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiajRxdlRrRnpmN0Z2SlJHU0hjZ1dnMWJla0dWaFVRWmttV3dJcWdiMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tL2hvdy1pdC13b3JrcyI7czo1OiJyb3V0ZSI7czoxMjoiaG93LWl0LXdvcmtzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790067683),
('VNMbdjYLjT0i3BgU61OAoPhG4Jg1hXZDzfDPq2Oi', NULL, '91.230.168.98', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:134.0) Gecko/20100101 Firefox/134.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQXJ2Wk5ncUJHMW16dVZaVjE2VUlHOHo2U2N6Z2lpUUR6OXlNQ1VpdCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vd3d3LmJ0c2JhemFyLmNvbS5sYXRlc3RiYW5nbGEuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790094837),
('wkYmSL8HhXe4avVkGVZgfr1XfqamJLCs5YPch7H5', NULL, '52.167.144.211', 'Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko; compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm) Chrome/116.0.1938.76 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT1hNRVI5UWFsODJjb1pFbkl3MTVXWnk0dzRXYVlYRGtRbkVyZHRuciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9tYXNpa2Jhenphci5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790021857),
('wQnSzQBfVVJLIcI5ozEqcjTcMVHjaRQ7rCxd5V43', NULL, '98.85.104.107', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Safari/605.1.15', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUk82NDRNUXJrY0pEcEhvd2hlQktUUkUzMFVrU2x5am1MOGJSSHV5aCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly93d3cuYnRzYmF6YXIuY29tLmxhdGVzdGJhbmdsYS5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790096842),
('WXyiz9ZlrZWS3t4TIlvVcMBbLNCkGIHLMjgYflqO', NULL, '159.223.200.92', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSVZoWmJmTVg4VWg1Z0ZIMXdqTUcybm1WbFQyeTI5UnhZYkVqWlRjUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjM6Imh0dHBzOi8vbWFzaWtiYXp6YXIuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1789924785),
('XdwO2RKInUPcTThRZZcE1CeK60TU1rmKD2XkeYJR', NULL, '136.66.85.185', 'Mozilla/5.0 (compatible; CMS-Checker/1.0; +https://example.com)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSUVFTWhCbUVWZVlPdzA1OWg4clFUWDh3Wk5jV1N5dVIxZFQyMEJzTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9tYXNpa2Jhenphci5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1789913646),
('xlBXHYGEQnoNEyq8xxGzJjsPPNNCkZtjg8aAzlCW', NULL, '161.97.127.208', 'Mozilla/5.0 (Macintosh, Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.0 Safari/605.1.15', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWFhMYjlORlJzMkE3RVFmOEpPRm5vMFNkaHVBZFdpaXpXVFVOb0tZbSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHBzOi8vd3d3LmJ0c2JhemFyLmNvbS5sYXRlc3RiYW5nbGEuY29tIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1790095820),
('xSY4CRQNwGm0eQ4K6bDyZScq3H6k2I7zgh5fJNXr', NULL, '54.164.153.189', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/537.13+ (KHTML, like Gecko) Version/5.1.7 Safari/534.57.2', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZzN2U1lraFVFRzZXbWwxV0d0VnRYbzU3MThkSHdzU2hXdVVyeGVQaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9tYXNpa2Jhenphci5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1789912648),
('zB6pjnRxweOSogRPn93cbbOy5HvyDwLTvQlSXgLq', NULL, '188.166.75.16', 'Mozilla/5.0 (compatible; ForestEngine/1.0; +https://forestengine.net/)', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMFM2OVRnWnVOVFB4clBHYm12OXJYcnpsRTVvS2REeUlBQXpPVUdzNCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly93d3cuYnRzYmF6YXIuY29tLmxhdGVzdGJhbmdsYS5jb20iO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1790094145),
('zCVUgabSziMDnAuO0j4TYq0s8bNGgFfLGBiZ0L1A', NULL, '35.86.137.15', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTk5mTHIzVlZDVTJqQ1duWm1QTHJidTBFeWtkZllZU1RuYlBUTVpTUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9idHNiYXphci5jb20ubGF0ZXN0YmFuZ2xhLmNvbSI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790102201),
('zWJ4urGfEQiXMLtNfbmvuVEKEyE6t2dimwIBSZX1', NULL, '143.244.182.223', 'Mozilla/5.0 (X11; Linux x86_64; rv:142.0) Gecko/20100101 Firefox/142.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaDhCdjluSG8wUmt1Undhenp5aGFCU3NZRTdWVE9iSFlLTWVDWm45SSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly9idHNiYXphci5jb20ubGF0ZXN0YmFuZ2xhLmNvbSI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1790094761);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `group`, `created_at`, `updated_at`) VALUES
(1, 'company_name', 'BTS Bazar', 'company', '2026-08-25 19:23:55', '2026-09-13 20:40:00'),
(2, 'tagline', 'Simple, transparent monthly membership for everyone.', 'company', '2026-08-25 19:23:55', '2026-08-25 19:23:55'),
(3, 'phone', '+880 01953059064', 'company', '2026-08-25 19:23:55', '2026-09-13 17:55:59'),
(4, 'email', 'btsmasikbazar.com@gmail.com', 'company', '2026-08-25 19:23:55', '2026-09-13 17:54:49'),
(5, 'address', 'House 12, Road 5, Dhanmondi, Dhaka-1209, Bangladesh', 'company', '2026-08-25 19:23:55', '2026-08-25 19:23:55'),
(6, 'google_map_embed', '', 'company', '2026-08-25 19:23:55', '2026-08-25 19:23:55'),
(7, 'facebook_url', 'https://www.facebook.com/masikbazzar', 'social', '2026-08-25 19:23:55', '2026-08-30 20:53:47'),
(8, 'youtube_url', 'https://youtube.com/masikbazzar', 'social', '2026-08-25 19:23:55', '2026-08-30 20:53:47'),
(9, 'whatsapp_number', '01953059064', 'social', '2026-08-25 19:23:55', '2026-09-13 20:41:38'),
(10, 'footer_text', '© BTSbazar. All rights reserved.', 'general', '2026-08-25 19:23:55', '2026-09-13 20:40:58'),
(11, 'currency_symbol', '৳', 'general', '2026-08-25 19:23:55', '2026-08-25 19:23:55'),
(12, 'timezone', 'Asia/Dhaka', 'general', '2026-08-25 19:23:55', '2026-08-25 19:23:55'),
(13, 'logo_path', 'branding/5Hq9ecyB7xwwdX1eGYI8AaLNQFFoheOghIPV6ymp.jpg', 'branding', '2026-08-25 19:23:55', '2026-08-30 20:51:51'),
(14, 'favicon_path', 'branding/epirblwQPH8rsbbYCG3uKgLL4h2SGkacHgSOaHeJ.jpg', 'branding', '2026-08-25 19:23:55', '2026-08-30 20:52:09'),
(15, 'theme_primary_color', '#22183a', 'theme', '2026-08-29 20:43:27', '2026-09-08 20:55:18'),
(16, 'theme_primary_dark_color', '#0f3d3e', 'theme', '2026-08-29 20:43:27', '2026-08-29 20:43:27'),
(17, 'theme_primary_light_color', '#1b8a6b', 'theme', '2026-08-29 20:43:27', '2026-08-29 20:43:27'),
(18, 'theme_footer_bg_color', '#0f3d3e', 'theme', '2026-08-29 20:43:27', '2026-08-29 20:43:27'),
(19, 'theme_footer_text_color', '#e6e6e6', 'theme', '2026-08-29 20:43:27', '2026-08-29 20:43:27'),
(20, 'theme_h1_size', '2.5rem', 'theme', '2026-08-29 20:43:27', '2026-08-29 20:43:27'),
(21, 'theme_h2_size', '2rem', 'theme', '2026-08-29 20:43:28', '2026-08-29 20:43:28'),
(22, 'theme_h3_size', '1.75rem', 'theme', '2026-08-29 20:43:28', '2026-08-29 20:43:28'),
(23, 'theme_h4_size', '1.5rem', 'theme', '2026-08-29 20:43:28', '2026-08-29 20:43:28'),
(24, 'theme_h5_size', '1.25rem', 'theme', '2026-08-29 20:43:28', '2026-08-29 20:43:28'),
(25, 'theme_h6_size', '1rem', 'theme', '2026-08-29 20:43:28', '2026-08-29 20:43:28'),
(26, 'theme_body_font_size', '1rem', 'theme', '2026-08-29 20:43:28', '2026-08-29 20:43:28');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `role_or_company` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `quote` text NOT NULL,
  `rating` tinyint(3) UNSIGNED DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `customer_name`, `role_or_company`, `photo`, `quote`, `rating`, `status`, `sort_order`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'তানজিম আহমেদ', NULL, NULL, '“চাল, ডাল, তেলসহ মাসের প্রয়োজনীয় বাজার একসাথে অর্ডার করতে পেরেছি। সবচেয়ে ভালো লেগেছে, বাসায় বসেই পুরো বাজার পেয়ে গেছি।”', 5, 'active', 1, 1, 1, '2026-08-30 21:42:41', '2026-08-30 21:42:41', NULL),
(2, 'সাদিয়া রহমান', NULL, NULL, '“অনলাইনে বাজার করার অভিজ্ঞতা বেশ ভালো। পণ্যগুলো সুন্দরভাবে প্যাক করা ছিল এবং সময়মতো ডেলিভারি পেয়েছি।”', 5, 'active', 2, 1, 1, '2026-08-30 21:43:14', '2026-08-30 21:43:14', NULL),
(3, 'রাকিব হাসান', NULL, NULL, '“মাসিক বাজারের জন্য এখন আর আলাদা করে মার্কেটে যেতে হয় না। ওয়েবসাইট থেকে অর্ডার করা সহজ এবং ডেলিভারি সার্ভিসও ভালো।”', 5, 'active', 3, 1, 1, '2026-08-30 21:44:01', '2026-08-30 21:44:01', NULL),
(4, 'নুসরাত জাহান', NULL, NULL, '“প্রথমবার অর্ডার করেছিলাম, অভিজ্ঞতা খুবই ভালো হয়েছে। প্রয়োজনীয় অনেক পণ্য এক জায়গায় পাওয়ায় সময় ও ঝামেলা দুটোই কমেছে।”', 5, 'active', 4, 1, 1, '2026-08-30 21:48:43', '2026-08-30 21:48:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `referral_code` varchar(20) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `phone`, `referral_code`, `email_verified_at`, `password`, `status`, `last_login_at`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'System Administrator', 'admin@monthlybazar.test', NULL, NULL, NULL, '2026-08-25 19:23:51', '$2y$12$STSKejZN52JAot.VAnuqQOoNTb6HJQeTE8zo/el0UkY4yCcM/xbsy', 'active', NULL, 'VvJvwZrp8ynwAQq2XXrGKhkC2nB1h9z8zE7SjEy4fSugWIeoJgo6QEc5kd2G', '2026-08-25 19:23:51', '2026-08-25 19:23:51', NULL),
(2, 'Md. Karim Hossain', 'karim.hossain@monthlybazar.test', NULL, '01711000001', NULL, '2026-08-25 19:23:51', '$2y$12$9wlrZ.xQtCbIx9a5r33.mOnUSWfTEgXyenxMC5VT04J1UgS/c73NK', 'active', NULL, NULL, '2026-08-25 19:23:51', '2026-08-25 19:54:09', '2026-08-25 19:54:09'),
(3, 'Fatema Begum', 'fatema.begum@monthlybazar.test', NULL, '01711000002', NULL, '2026-08-25 19:23:51', '$2y$12$ZAW19yHkt04tw1Bl0vq/4OdQep0OcA9DuLQrI3Ugo2LI1Nqmoy.EG', 'active', NULL, NULL, '2026-08-25 19:23:51', '2026-08-25 19:54:33', '2026-08-25 19:54:33'),
(4, 'Abdur Rahman', 'abdur.rahman@monthlybazar.test', NULL, '01711000003', NULL, '2026-08-25 19:23:51', '$2y$12$4lPsq0wvchpzl438Q4odeOVLhxsTXafJRXr4yhMVE4fsdp4ctfC46', 'inactive', NULL, NULL, '2026-08-25 19:23:51', '2026-08-25 19:54:37', '2026-08-25 19:54:37'),
(5, 'Nusrat Jahan', 'nusrat.jahan@monthlybazar.test', NULL, '01711000004', '0003', '2026-08-25 19:23:52', '$2y$12$tsLSFTRelLfFInZqC6jWrentMAoiIAV9q4n/46zcLJBl1X6SpBGHu', 'active', NULL, NULL, '2026-08-25 19:23:52', '2026-08-25 19:53:55', '2026-08-25 19:53:55'),
(6, 'Shariful Islam', 'shariful.islam@monthlybazar.test', NULL, '01711000005', '0007', '2026-08-25 19:23:52', '$2y$12$507gPexpSF9KWQjV3Dse5OcDM9eoawTK.9baaMARX1ZfhOZo6/XeK', 'active', NULL, NULL, '2026-08-25 19:23:52', '2026-08-25 19:53:50', '2026-08-25 19:53:50'),
(7, 'Rina Akter', 'rina.akter@monthlybazar.test', NULL, '01711000006', '0005', '2026-08-25 19:23:52', '$2y$12$gCmjDURPFRb2P1GXLvLxT.ab9Po6Wu3fx4Rr69Nhlm16udLYfLbT.', 'active', NULL, NULL, '2026-08-25 19:23:52', '2026-08-25 19:53:41', '2026-08-25 19:53:41'),
(8, 'Jahangir Alam', 'jahangir.alam@monthlybazar.test', NULL, '01711000007', '0001', '2026-08-25 19:23:52', '$2y$12$qny6SiTjLH6Ki9QC9UDefOqj1kcjYAtq09Xhx/WAtWQYikvBO3/mC', 'active', NULL, NULL, '2026-08-25 19:23:52', '2026-08-25 19:53:37', '2026-08-25 19:53:37'),
(9, 'Salma Khatun', 'salma.khatun@monthlybazar.test', NULL, '01711000008', '0006', '2026-08-25 19:23:52', '$2y$12$d0NMAGFXZIW9jBklwAfy6OLx/JgiFmzI8GLYs2ebjQxYqD/Yg9C62', 'suspended', NULL, NULL, '2026-08-25 19:23:52', '2026-08-25 19:53:45', '2026-08-25 19:53:45'),
(10, 'Rafiqul Islam', 'rafiqul.islam@monthlybazar.test', NULL, '01711000009', '0004', '2026-08-25 19:23:53', '$2y$12$N3DwNjR2zeJg99IXaj9hYe6jWIUwrTXWsIkfkFNTJn8T1okezUG5m', 'inactive', NULL, NULL, '2026-08-25 19:23:53', '2026-08-25 19:49:30', '2026-08-25 19:49:30'),
(11, 'Taslima Nasrin', 'taslima.nasrin@monthlybazar.test', NULL, '01711000010', '0008', '2026-08-25 19:23:53', '$2y$12$X5516tx/plqQGGckcnA6Ce9auAvdkunoIgWJpFcREbi75n9GGl5X.', 'active', NULL, NULL, '2026-08-25 19:23:53', '2026-08-25 19:49:35', '2026-08-25 19:49:35'),
(12, 'Mizanur Rahman', 'mizanur.rahman@monthlybazar.test', NULL, '01711000011', '0002', '2026-08-25 19:23:53', '$2y$12$tJ8LynlwNfKbShtiI0871u.UIOsfvQ8ugCAOqxYShgCLO7v6W.a/2', 'active', NULL, NULL, '2026-08-25 19:23:53', '2026-08-25 19:54:50', '2026-08-25 19:54:50'),
(13, 'Sultana Parvin', 'sultana.parvin@monthlybazar.test', NULL, '01711000012', NULL, '2026-08-25 19:23:53', '$2y$12$U8YHZjNbpPVQumwjKgc3WO8ctiViw.3UWPMpcKwZjITi9EBvAZ.BG', 'active', NULL, NULL, '2026-08-25 19:23:53', '2026-08-25 19:54:04', '2026-08-25 19:54:04'),
(14, 'Habibur Rahman', 'habibur.rahman@monthlybazar.test', NULL, '01711000013', NULL, '2026-08-25 19:23:53', '$2y$12$Kv1ooVl0kqt4FKmxnS5FV.nyhNiwdLRIgqEKFx9JmXmbOkhRrrZPS', 'active', NULL, NULL, '2026-08-25 19:23:53', '2026-08-25 19:53:59', '2026-08-25 19:53:59'),
(15, 'Ayesha Siddika', 'ayesha.siddika@monthlybazar.test', NULL, '01711000014', NULL, '2026-08-25 19:23:54', '$2y$12$rvF2ZM/U/etsVNSPFaoD5.MpgI2VbD5i9QmkPWmxDuT2MsA9TaBKm', 'inactive', NULL, NULL, '2026-08-25 19:23:54', '2026-08-25 19:49:12', '2026-08-25 19:49:12'),
(16, 'Nazrul Islam', 'nazrul.islam@monthlybazar.test', NULL, '01711000015', NULL, '2026-08-25 19:23:54', '$2y$12$9gidBJ56bhX./xRiGsRfzu0FClWukhCAE8oVOPmSvQZapW49Hxnj6', 'active', NULL, NULL, '2026-08-25 19:23:54', '2026-08-25 19:49:26', '2026-08-25 19:49:26'),
(17, 'Ruma Aktar', 'ruma.aktar@monthlybazar.test', NULL, '01711000016', NULL, '2026-08-25 19:23:54', '$2y$12$21RcvzJB46D3ub3SIOtnNupzL/WvuEq4otPd88ik0b1hFzjTbHqsa', 'active', NULL, NULL, '2026-08-25 19:23:54', '2026-08-25 19:49:19', '2026-08-25 19:49:19'),
(18, 'Shahidul Islam', 'shahidul.islam@monthlybazar.test', NULL, '01711000017', NULL, '2026-08-25 19:23:54', '$2y$12$ScR4gOf2oKOrGSjYZUp1VuVLoSV5KvwMcC55eni.CdVOM/l8pLam6', 'suspended', NULL, NULL, '2026-08-25 19:23:54', '2026-08-25 19:49:23', '2026-08-25 19:49:23'),
(19, 'Moushumi Rahman', 'moushumi.rahman@monthlybazar.test', NULL, '01711000018', NULL, '2026-08-25 19:23:54', '$2y$12$69ssxEFm9/fCW4LIoNW9EuoaUNham0qLx0YgFRrd0DF4skKebF86e', 'active', NULL, NULL, '2026-08-25 19:23:54', '2026-08-25 19:55:05', '2026-08-25 19:55:05'),
(20, 'Delwar Hossain', 'delwar.hossain@monthlybazar.test', NULL, '01711000019', NULL, '2026-08-25 19:23:55', '$2y$12$cubHiuGJboy2tn2EARo7uuJJ3ECKVggep5z717WpUD3lhZ17FkybS', 'inactive', NULL, NULL, '2026-08-25 19:23:55', '2026-08-25 19:49:08', '2026-08-25 19:49:08'),
(21, 'Farida Yasmin', 'farida.yasmin@monthlybazar.test', NULL, '01711000020', NULL, '2026-08-25 19:23:55', '$2y$12$hcrw77gnHSJK4YdcqAyh6OAbT86YfoI.qxs6VPrPHwEwVUbC9wVRi', 'active', NULL, NULL, '2026-08-25 19:23:55', '2026-08-25 19:49:16', '2026-08-25 19:49:16'),
(26, 'Newadmin', 'new@masikbazzar.com', 'newadmin', '01911111', NULL, NULL, '$2y$12$cFOkt5Y1CCskRzr47YmW9ewk0ATb7NL9a9/GbJbWEbHfch95adeoy', 'active', NULL, NULL, '2026-08-25 21:30:20', '2026-08-25 21:32:58', '2026-08-25 21:32:58'),
(27, 'Test Name', 'c1@masikbazzar.com', '01811111', '018111112222', NULL, NULL, '$2y$12$1ffu2..P1K7hKr9gcFr6f.x.bxgavBQmImqklT4eX.7KFOqOaXnFK', 'active', NULL, NULL, '2026-08-25 21:37:11', '2026-08-26 11:34:17', '2026-08-26 11:34:17'),
(32, 'hridoy', 'hridoy@gmail.com', 'hridoy', '0179999', NULL, NULL, '$2y$12$wj538p2.Q93zyWVnh.iXGuMf2ZwWRSBhx3pUdv4QvOKKMwfxbyTFO', 'active', NULL, NULL, '2026-08-26 08:32:10', '2026-08-26 20:10:22', '2026-08-26 20:10:22'),
(38, 'Hridoy Enterprise', 'khulna@gmail.com', 'hri', '0178888888', NULL, NULL, '$2y$12$WGpLirvx4Zc5VEd6EnW56eEKSMWp3c7JTEtGZDDOorm..hEm7QRvS', 'active', NULL, NULL, '2026-08-26 11:17:37', '2026-08-26 20:10:15', '2026-08-26 20:10:15'),
(40, 'Sabya Sachi Roy', 'sabya.citik@gmail.com', 'sabyaroy', '01911418642', '0009', '2026-08-26 12:42:22', '$2y$12$CN84FIz1VgEh8d/Vcu7nCentkOgzi4rOaeDB8/9tCKAfZ6.gNfHTS', 'active', NULL, NULL, '2026-08-26 12:42:22', '2026-08-26 20:10:17', '2026-08-26 20:10:17'),
(41, 'hridoy', 'hridoy1@gmail.com', 'hridoyy', '0179999999', '0010', '2026-08-26 19:51:52', '$2y$12$q1Svl7iUxPHO2x1b5Y/dZOuLIgeIZnY77a2IxSyrpC8lgP1qij9H.', 'active', NULL, NULL, '2026-08-26 19:51:52', '2026-08-26 20:10:13', '2026-08-26 20:10:13'),
(42, 'Ratul Islan', 'islan2102@gmail.com', 'islan', '123456789111', NULL, NULL, '$2y$12$B.Tp7wMsDaHhF5hizjVrb.UQtop0gdHD062hhZRt1/R.BG8VnnKJe', 'active', NULL, NULL, '2026-08-26 19:56:40', '2026-08-26 20:10:11', '2026-08-26 20:10:11'),
(43, 'hhfb hg', 'biplob301010@gmail.com', 'iugff', '01912343432', NULL, NULL, '$2y$12$zlkd8KL7wHlkZSL/72h4EO4LAzQATdfn03Bs7rdpoQFoqknsoLCmC', 'active', NULL, NULL, '2026-08-27 08:57:12', '2026-09-01 13:02:39', '2026-09-01 13:02:39'),
(44, 'Nasir Uddin', 'nasir88908890@gmail.com', 'SM02', '01825663732', NULL, NULL, '$2y$12$n3bq7xT7bOnQmexq.v79f..qdwVY1F/mVtqNqip7jKGyUp1lQ.enu', 'active', NULL, NULL, '2026-08-27 09:06:15', '2026-09-01 13:02:41', '2026-09-01 13:02:41'),
(45, 'Shamim Hossain', 'shamim@gmail.com', 'shamim', '01799999999', '0011', '2026-08-30 20:56:14', '$2y$12$kEvCFCp6tH1tSlcSpLVJ5e4/93b3ZJMXzLW/7VjkPEmj7c0ToGO8y', 'active', NULL, NULL, '2026-08-30 20:56:14', '2026-09-01 13:02:43', '2026-09-01 13:02:43'),
(46, 'hridoy', 'hridoy11@gmail.com', 'hridoy11', '017999999', NULL, NULL, '$2y$12$DMgOGyu5vMsmm4NtuAX2leWVYNM1iULtNzavtu/wiCjMqpi5T.ikG', 'active', NULL, NULL, '2026-08-30 20:58:17', '2026-09-01 13:02:45', '2026-09-01 13:02:45'),
(47, 'biplob', 'biplob@gmail.com', 'biplob', '01799999', NULL, NULL, '$2y$12$r6STWZQccimNjD33nYuymeD6/DI87fPY3hdr8vkhdNc.O3Mk/aGK6', 'active', NULL, NULL, '2026-08-30 21:08:19', '2026-09-01 13:02:48', '2026-09-01 13:02:48'),
(48, 'Admin admin', 'biplob3010100@gmail.com', 'Admin', '01717857164', NULL, NULL, '$2y$12$tvTqCy2mPf2EB41KfyNyTeVz2gEPa9Yua8SJRNEUFmxLsUC.Njr5a', 'active', NULL, NULL, '2026-09-01 09:28:25', '2026-09-01 13:02:50', '2026-09-01 13:02:50'),
(49, 'gyytrhytht', 'dfdfdered@gmail.com', 'ttttt', 'fgdgfdregfr', NULL, NULL, '$2y$12$gqVwNdgbmYrbEDgj2RsN.eDjaWYr2N616KzRNKMRf8RPyTxMGhBK2', 'active', NULL, NULL, '2026-09-05 13:10:26', '2026-09-08 20:51:15', '2026-09-08 20:51:15'),
(50, 'MASUM BIN HABIB ', 'mbhmasum22@gmail.com', 'NSM', '01754684928', NULL, NULL, '$2y$12$TD8nhUOAKNvN/gy2yhQlyexh1ErWzhtGh/gVknaqe67SA463naxBi', 'active', NULL, NULL, '2026-09-05 13:26:58', '2026-09-08 20:51:12', '2026-09-08 20:51:12'),
(51, 'hgfdd', 'hgfdss@gmail.com', 'hfdssd', '01923543456', NULL, NULL, '$2y$12$mFUizO2/esv5/TtEIyAMH.Wtv0HYAYVaEhIGMWncCTZtLvNFKe5/q', 'active', NULL, NULL, '2026-09-05 15:18:29', '2026-09-08 20:51:09', '2026-09-08 20:51:09'),
(52, 'hfhgfghf', 'fgtrde@gmail.com', 'BTS05', 'ghfhgfhg', NULL, NULL, '$2y$12$EIWgXSFvBdycjtFQUSN.COPagHqiVrPBo2TK0G6lMORbkY21NN48m', 'active', NULL, NULL, '2026-09-05 22:24:23', '2026-09-08 20:51:07', '2026-09-08 20:51:07'),
(53, 'Manirul Islam Mony ', 'manirulislam0709@gmail.com', 'Mony0709', '01611177550', NULL, NULL, '$2y$12$vPPCOtpmOfJB1NAChPP.eeWx.TKjApe5T8wyfrarTJygVYd4m8gIq', 'active', NULL, NULL, '2026-09-06 00:44:28', '2026-09-08 20:51:05', '2026-09-08 20:51:05'),
(54, 'Bangladesh ', 'nws@gmail.com', 'ejnd', '01800166228', NULL, NULL, '$2y$12$ViaVzgvVYh8Mg361Hesmd.d4JFBbtn4sywfz6h7zLP08wjxJWeG0W', 'active', NULL, NULL, '2026-09-07 08:36:36', '2026-09-08 20:51:03', '2026-09-08 20:51:03'),
(55, 'frrsterste rff', 'hghjghf@gmail.com', 'ertrtyry', '01987654543', NULL, NULL, '$2y$12$mg3Zxch6c4OIQaSm4XN/QeX2dl0wP0nJl7lvyx4SeGP..E.ExPMp2', 'active', NULL, NULL, '2026-09-07 12:51:27', '2026-09-08 20:51:01', '2026-09-08 20:51:01'),
(56, 'EDEFER', 'asddf@gmail.com', 'hghfghfhgfr', 'EFDEFREFR', NULL, NULL, '$2y$12$iqOFeQAAQShy4G/1w6RVtuCT/jiweR9ftP4P9GRjN88FCKNUjdXK2', 'active', NULL, NULL, '2026-09-08 19:05:20', '2026-09-08 20:50:59', '2026-09-08 20:50:59'),
(57, 'syed', 'syedenamshohag@gmail.com', 'syed', '01799999998', NULL, NULL, '$2y$12$TYcfONGUzu6kT9m1rXuMn.iBOgbVruNHujxiL8CI4.YztispvnV36', 'active', NULL, 'mjioEFmveqPG1Yw8pPSEbnQBBlU5cNPZr0x0jhezzaiFy3JYcxgKLdHVvfYn', '2026-09-08 20:52:07', '2026-09-08 20:52:07', NULL),
(58, 'Sayed', 'sayed@gmail.com', 'sayed', '01789654123', '0012', '2026-09-08 21:01:55', '$2y$12$o.hz36o5owRyqdsjZHmQOeYvYvGEvJdWc6Ybo4H20S70uqkXaMc3a', 'active', NULL, NULL, '2026-09-08 21:01:55', '2026-09-08 21:01:55', NULL),
(59, 'sayedasm', 'sayed1@gmail.com', 'sayedasm', '012457896655', NULL, NULL, '$2y$12$jIYP/44Jk34fl7kvkyrUru0oPpz9QzVEfFi3EJB23/plvhvg4JgYS', 'active', NULL, NULL, '2026-09-08 21:03:57', '2026-09-08 21:03:57', NULL),
(60, 'Ashik Khan', 'n@gmail.com', 'jnd01', '01866168203', NULL, NULL, '$2y$12$9cx.HSuQUifG/g9kF1NYNur7Tgy9czCNjn2j2N9OqcAZXYA0G/VU6', 'active', NULL, NULL, '2026-09-10 08:20:29', '2026-09-10 08:20:29', NULL),
(61, 'abdur rahman', 'afsdfg2@gnail.com', 'sm9', '01917656543', NULL, NULL, '$2y$12$typlmtaoMj4UdJtCfD4a3.g9OEHGtt3bEY3dfqruViU7M3EueykA.', 'active', NULL, NULL, '2026-09-13 17:25:48', '2026-09-13 17:25:48', NULL),
(62, 'rerw', 'asdada@gmail.com', 'sm7', '01654565434', NULL, NULL, '$2y$12$GzIlZFftxSh8x2J9QG1WW.Bda5.Z3ni13QbxuT8Y8e1XDzkTTLLGC', 'active', NULL, NULL, '2026-09-13 18:17:45', '2026-09-14 13:14:19', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`),
  ADD KEY `activity_logs_model_type_model_id_index` (`model_type`,`model_id`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `applications_application_number_unique` (`application_number`),
  ADD KEY `applications_customer_id_foreign` (`customer_id`),
  ADD KEY `applications_officer_id_foreign` (`officer_id`),
  ADD KEY `applications_package_id_foreign` (`package_id`),
  ADD KEY `applications_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `applications_status_index` (`status`);

--
-- Indexes for table `application_status_histories`
--
ALTER TABLE `application_status_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_status_histories_application_id_foreign` (`application_id`),
  ADD KEY `application_status_histories_changed_by_foreign` (`changed_by`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `customers_nid_number_unique` (`nid_number`),
  ADD KEY `customers_status_index` (`status`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_name_unique` (`name`),
  ADD KEY `departments_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `designations`
--
ALTER TABLE `designations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `designations_name_unique` (`name`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_category_id_foreign` (`category_id`),
  ADD KEY `expenses_created_by_foreign` (`created_by`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `expense_categories_name_unique` (`name`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `financial_transactions`
--
ALTER TABLE `financial_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `financial_transactions_created_by_foreign` (`created_by`),
  ADD KEY `financial_transactions_reference_type_reference_id_index` (`reference_type`,`reference_id`);

--
-- Indexes for table `hero_slides`
--
ALTER TABLE `hero_slides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hero_slides_created_by_foreign` (`created_by`),
  ADD KEY `hero_slides_updated_by_foreign` (`updated_by`),
  ADD KEY `hero_slides_status_index` (`status`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `officers`
--
ALTER TABLE `officers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `officers_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `officers_employee_id_unique` (`employee_id`),
  ADD KEY `officers_department_id_foreign` (`department_id`),
  ADD KEY `officers_designation_id_foreign` (`designation_id`),
  ADD KEY `officers_status_index` (`status`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `packages_slug_unique` (`slug`),
  ADD UNIQUE KEY `packages_code_unique` (`code`),
  ADD KEY `packages_created_by_foreign` (`created_by`),
  ADD KEY `packages_updated_by_foreign` (`updated_by`),
  ADD KEY `packages_status_index` (`status`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pages_slug_unique` (`slug`),
  ADD KEY `pages_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payrolls_month_unique` (`month`),
  ADD KEY `payrolls_generated_by_foreign` (`generated_by`),
  ADD KEY `payrolls_approved_by_foreign` (`approved_by`),
  ADD KEY `payrolls_status_index` (`status`);

--
-- Indexes for table `payroll_items`
--
ALTER TABLE `payroll_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payroll_items_payroll_id_officer_id_unique` (`payroll_id`,`officer_id`),
  ADD KEY `payroll_items_officer_id_foreign` (`officer_id`),
  ADD KEY `payroll_items_salary_profile_id_foreign` (`salary_profile_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_slug_unique` (`slug`),
  ADD KEY `permissions_group_index` (`group`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permission_role_permission_id_role_id_unique` (`permission_id`,`role_id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `referrals_customer_id_unique` (`customer_id`),
  ADD KEY `referrals_officer_id_foreign` (`officer_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_user_role_id_user_id_unique` (`role_id`,`user_id`),
  ADD KEY `role_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `salary_payments`
--
ALTER TABLE `salary_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salary_payments_officer_id_foreign` (`officer_id`),
  ADD KEY `salary_payments_payroll_id_foreign` (`payroll_id`),
  ADD KEY `salary_payments_paid_by_foreign` (`paid_by`);

--
-- Indexes for table `salary_profiles`
--
ALTER TABLE `salary_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salary_profiles_officer_id_foreign` (`officer_id`),
  ADD KEY `salary_profiles_status_index` (`status`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`),
  ADD KEY `settings_group_index` (`group`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `testimonials_created_by_foreign` (`created_by`),
  ADD KEY `testimonials_updated_by_foreign` (`updated_by`),
  ADD KEY `testimonials_status_index` (`status`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD UNIQUE KEY `users_referral_code_unique` (`referral_code`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_status_index` (`status`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `application_status_histories`
--
ALTER TABLE `application_status_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `designations`
--
ALTER TABLE `designations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `financial_transactions`
--
ALTER TABLE `financial_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hero_slides`
--
ALTER TABLE `hero_slides`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `officers`
--
ALTER TABLE `officers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payrolls`
--
ALTER TABLE `payrolls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payroll_items`
--
ALTER TABLE `payroll_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `permission_role`
--
ALTER TABLE `permission_role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `salary_payments`
--
ALTER TABLE `salary_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `salary_profiles`
--
ALTER TABLE `salary_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `applications_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`),
  ADD CONSTRAINT `applications_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `application_status_histories`
--
ALTER TABLE `application_status_histories`
  ADD CONSTRAINT `application_status_histories_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `application_status_histories_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `expense_categories` (`id`),
  ADD CONSTRAINT `expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `financial_transactions`
--
ALTER TABLE `financial_transactions`
  ADD CONSTRAINT `financial_transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hero_slides`
--
ALTER TABLE `hero_slides`
  ADD CONSTRAINT `hero_slides_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `hero_slides_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `officers`
--
ALTER TABLE `officers`
  ADD CONSTRAINT `officers_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `officers_designation_id_foreign` FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `officers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `packages_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `packages_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pages`
--
ALTER TABLE `pages`
  ADD CONSTRAINT `pages_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD CONSTRAINT `payrolls_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payrolls_generated_by_foreign` FOREIGN KEY (`generated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll_items`
--
ALTER TABLE `payroll_items`
  ADD CONSTRAINT `payroll_items_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payroll_items_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `payrolls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payroll_items_salary_profile_id_foreign` FOREIGN KEY (`salary_profile_id`) REFERENCES `salary_profiles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `referrals`
--
ALTER TABLE `referrals`
  ADD CONSTRAINT `referrals_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `referrals_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `salary_payments`
--
ALTER TABLE `salary_payments`
  ADD CONSTRAINT `salary_payments_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `salary_payments_paid_by_foreign` FOREIGN KEY (`paid_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `salary_payments_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `payrolls` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `salary_profiles`
--
ALTER TABLE `salary_profiles`
  ADD CONSTRAINT `salary_profiles_officer_id_foreign` FOREIGN KEY (`officer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD CONSTRAINT `testimonials_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `testimonials_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
