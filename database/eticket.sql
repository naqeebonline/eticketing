-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 07, 2026 at 11:14 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eticket`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `log_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint UNSIGNED DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `batch_uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `causer` (`causer_type`,`causer_id`),
  KEY `activity_log_log_name_index` (`log_name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `subject_id`, `causer_type`, `causer_id`, `properties`, `event`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'default', 'updated', 'App\\Models\\User', 1, 'App\\Models\\User', 1, '{\"old\": {\"name\": \"Super Admin\", \"email\": \"admin@bssbooking.com\", \"is_active\": true}, \"attributes\": {\"name\": \"Super Admin\", \"email\": \"admin@bssbooking.com\", \"is_active\": true}}', 'updated', NULL, '2026-06-09 12:35:28', '2026-06-09 12:35:28'),
(2, 'default', 'updated', 'App\\Models\\User', 1, 'App\\Models\\User', 1, '{\"old\": {\"name\": \"Super Admin\", \"email\": \"admin@bssbooking.com\", \"is_active\": true}, \"attributes\": {\"name\": \"Super Admin\", \"email\": \"admin@bssbooking.com\", \"is_active\": true}}', 'updated', NULL, '2026-06-25 09:04:48', '2026-06-25 09:04:48'),
(3, 'default', 'updated', 'App\\Models\\User', 1, 'App\\Models\\User', 1, '{\"old\": {\"name\": \"Super Admin\", \"email\": \"admin@bssbooking.com\", \"is_active\": true}, \"attributes\": {\"name\": \"Super Admin\", \"email\": \"admin@bssbooking.com\", \"is_active\": true}}', 'updated', NULL, '2026-06-25 09:06:19', '2026-06-25 09:06:19'),
(4, 'default', 'created', 'App\\Models\\User', 4, 'App\\Models\\User', 1, '{\"attributes\": {\"name\": \"Swat Travels\", \"email\": \"swat@gmail.com\", \"is_active\": true}}', 'created', NULL, '2026-06-25 09:07:31', '2026-06-25 09:07:31'),
(5, 'default', 'updated', 'App\\Models\\User', 4, 'App\\Models\\User', 4, '{\"old\": {\"name\": \"Swat Travels\", \"email\": \"swat@gmail.com\", \"is_active\": true}, \"attributes\": {\"name\": \"Swat Travels\", \"email\": \"swat@gmail.com\", \"is_active\": true}}', 'updated', NULL, '2026-06-25 09:08:51', '2026-06-25 09:08:51'),
(6, 'default', 'created', 'App\\Models\\User', 5, 'App\\Models\\User', 4, '{\"attributes\": {\"name\": \"Niaz\", \"email\": \"niaz@gmail.com\", \"is_active\": true}}', 'created', NULL, '2026-06-25 09:09:25', '2026-06-25 09:09:25'),
(7, 'default', 'updated', 'App\\Models\\User', 5, 'App\\Models\\User', 5, '{\"old\": {\"name\": \"Niaz\", \"email\": \"niaz@gmail.com\", \"is_active\": true}, \"attributes\": {\"name\": \"Niaz\", \"email\": \"niaz@gmail.com\", \"is_active\": true}}', 'updated', NULL, '2026-06-25 09:09:39', '2026-06-25 09:09:39'),
(8, 'default', 'updated', 'App\\Models\\User', 1, 'App\\Models\\User', 1, '{\"old\": {\"name\": \"Super Admin\", \"email\": \"admin@bssbooking.com\", \"is_active\": true}, \"attributes\": {\"name\": \"Super Admin\", \"email\": \"admin@bssbooking.com\", \"is_active\": true}}', 'updated', NULL, '2026-06-25 09:36:35', '2026-06-25 09:36:35'),
(9, 'default', 'updated', 'App\\Models\\User', 1, 'App\\Models\\User', 1, '{\"old\": {\"name\": \"Super Admin\", \"email\": \"admin@bssbooking.com\", \"is_active\": true}, \"attributes\": {\"name\": \"Super Admin\", \"email\": \"admin@bssbooking.com\", \"is_active\": true}}', 'updated', NULL, '2026-06-25 11:26:26', '2026-06-25 11:26:26');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `schedule_id` bigint UNSIGNED NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `subtotal` decimal(12,2) NOT NULL,
  `discount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(12,2) NOT NULL,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `coupon_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `loyalty_points_used` int UNSIGNED NOT NULL DEFAULT '0',
  `loyalty_points_earned` int UNSIGNED NOT NULL DEFAULT '0',
  `booking_source` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'online',
  `booked_by` bigint UNSIGNED DEFAULT NULL,
  `qr_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hold_expires_at` timestamp NULL DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancellation_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_uuid_unique` (`uuid`),
  UNIQUE KEY `bookings_booking_number_unique` (`booking_number`),
  KEY `bookings_user_id_foreign` (`user_id`),
  KEY `bookings_booked_by_foreign` (`booked_by`),
  KEY `bookings_schedule_id_status_index` (`schedule_id`,`status`),
  KEY `bookings_status_index` (`status`),
  KEY `bookings_payment_status_index` (`payment_status`),
  KEY `bookings_hold_expires_at_index` (`hold_expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `uuid`, `booking_number`, `user_id`, `schedule_id`, `status`, `payment_status`, `subtotal`, `discount`, `tax`, `total_amount`, `paid_amount`, `coupon_code`, `loyalty_points_used`, `loyalty_points_earned`, `booking_source`, `booked_by`, `qr_code`, `hold_expires_at`, `confirmed_at`, `cancelled_at`, `cancellation_reason`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '661904cb-52b8-4c02-954b-a3f518da04ec', 'BSS-TK1OWQIN-260625', 5, 210, 'confirmed', 'paid', 7500.00, 0.00, 0.00, 7500.00, 7500.00, NULL, 0, 0, 'offline', 5, 'http://127.0.0.1:8000/ticket/verify/661904cb-52b8-4c02-954b-a3f518da04ec', NULL, '2026-06-25 09:55:50', NULL, NULL, '2026-06-25 09:55:50', '2026-06-25 09:55:50', NULL),
(2, '48ad24ee-c96d-4f9a-ab62-757567a6d83e', 'BSS-1MVMOBK4-260625', NULL, 210, 'held', 'pending', 4000.00, 0.00, 0.00, 4000.00, 0.00, NULL, 0, 0, 'online', NULL, NULL, '2026-06-25 10:18:33', NULL, NULL, NULL, '2026-06-25 10:08:33', '2026-06-25 10:08:33', NULL),
(3, 'd9622041-e29d-4f74-9201-cf95007e5e97', 'BSS-EZMJHMLE-260625', NULL, 210, 'confirmed', 'paid', 4000.00, 0.00, 0.00, 4000.00, 4000.00, NULL, 0, 0, 'online', NULL, 'http://127.0.0.1:8000/ticket/verify/d9622041-e29d-4f74-9201-cf95007e5e97', NULL, '2026-06-25 10:09:29', NULL, NULL, '2026-06-25 10:09:14', '2026-06-25 10:09:29', NULL),
(4, '967eaa33-3b71-4868-a851-aa32c58bfa0e', 'BSS-UMOZJTEJ-260625', NULL, 210, 'held', 'pending', 4000.00, 0.00, 0.00, 4000.00, 0.00, NULL, 0, 0, 'online', NULL, NULL, NULL, NULL, NULL, NULL, '2026-06-25 10:12:53', '2026-06-25 10:12:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `booking_passengers`
--

DROP TABLE IF EXISTS `booking_passengers`;
CREATE TABLE IF NOT EXISTS `booking_passengers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` bigint UNSIGNED NOT NULL,
  `seat_id` bigint UNSIGNED NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cnic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `passenger_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'adult',
  `fare` decimal(10,2) NOT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `cancelled_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_passengers_booking_id_foreign` (`booking_id`),
  KEY `booking_passengers_seat_id_foreign` (`seat_id`),
  KEY `booking_passengers_cancelled_by_foreign` (`cancelled_by`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_passengers`
--

INSERT INTO `booking_passengers` (`id`, `booking_id`, `seat_id`, `full_name`, `cnic`, `phone`, `email`, `gender`, `passenger_type`, `fare`, `cancelled_at`, `cancelled_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Momin', '1236512763512765', '0312376125376', NULL, 'male', 'adult', 3500.00, NULL, NULL, '2026-06-25 09:55:50', '2026-06-25 09:55:50'),
(2, 1, 5, 'Momin', '1236512763512765', '0312376125376', NULL, 'male', 'adult', 2000.00, NULL, NULL, '2026-06-25 09:55:50', '2026-06-25 09:55:50'),
(3, 1, 6, 'Momin', '1236512763512765', '0312376125376', NULL, 'female', 'adult', 2000.00, NULL, NULL, '2026-06-25 09:55:50', '2026-06-25 09:55:50'),
(4, 2, 2, 'Obiad', '382745726354', '0312354123', NULL, 'male', 'adult', 2000.00, NULL, NULL, '2026-06-25 10:08:33', '2026-06-25 10:08:33'),
(5, 2, 3, 'Obiad', '382745726354', '0312354123', NULL, 'female', 'adult', 2000.00, NULL, NULL, '2026-06-25 10:08:33', '2026-06-25 10:08:33'),
(6, 3, 4, 'Niaz', '123651276345657', '1245312765367', NULL, 'male', 'adult', 2000.00, NULL, NULL, '2026-06-25 10:09:14', '2026-06-25 10:09:14'),
(7, 3, 7, 'Niaz', '123651276345657', '1245312765367', NULL, 'male', 'adult', 2000.00, NULL, NULL, '2026-06-25 10:09:14', '2026-06-25 10:09:14'),
(8, 4, 8, 'Khan Jan', '2374572635', '03127654321', NULL, 'male', 'adult', 2000.00, NULL, NULL, '2026-06-25 10:12:53', '2026-06-25 10:12:53'),
(9, 4, 11, 'Khan Jan', '2374572635', '03127654321', NULL, 'male', 'adult', 2000.00, NULL, NULL, '2026-06-25 10:12:53', '2026-06-25 10:12:53');

-- --------------------------------------------------------

--
-- Table structure for table `booking_passenger_cancellations`
--

DROP TABLE IF EXISTS `booking_passenger_cancellations`;
CREATE TABLE IF NOT EXISTS `booking_passenger_cancellations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_passenger_id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED NOT NULL,
  `seat_id` bigint UNSIGNED DEFAULT NULL,
  `seat_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fare` decimal(10,2) NOT NULL,
  `action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cancelled_by` bigint UNSIGNED NOT NULL,
  `refund_id` bigint UNSIGNED DEFAULT NULL,
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_passenger_cancellations_booking_passenger_id_foreign` (`booking_passenger_id`),
  KEY `booking_passenger_cancellations_seat_id_foreign` (`seat_id`),
  KEY `booking_passenger_cancellations_cancelled_by_foreign` (`cancelled_by`),
  KEY `booking_passenger_cancellations_refund_id_foreign` (`refund_id`),
  KEY `booking_passenger_cancellations_booking_id_created_at_index` (`booking_id`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bus_stands`
--

DROP TABLE IF EXISTS `bus_stands`;
CREATE TABLE IF NOT EXISTS `bus_stands` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_id` bigint UNSIGNED DEFAULT NULL,
  `terminal_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `images` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `total_revenue` decimal(15,2) NOT NULL DEFAULT '0.00',
  `settings` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_stands_uuid_unique` (`uuid`),
  UNIQUE KEY `bus_stands_slug_unique` (`slug`),
  KEY `bus_stands_city_index` (`city`),
  KEY `bus_stands_is_active_index` (`is_active`),
  KEY `bus_stands_terminal_id_foreign` (`terminal_id`),
  KEY `bus_stands_owner_id_foreign` (`owner_id`),
  KEY `bus_stands_from_city_index` (`from_city`),
  KEY `bus_stands_to_city_index` (`to_city`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bus_stands`
--

INSERT INTO `bus_stands` (`id`, `uuid`, `owner_id`, `terminal_id`, `name`, `type`, `slug`, `address`, `city`, `from_city`, `to_city`, `phone`, `email`, `latitude`, `longitude`, `logo`, `images`, `is_active`, `total_revenue`, `settings`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '27168950-c8f1-4407-9fff-0d372266e680', 5, 1, 'Swat to Peshawar', 'company', 'swat-to-peshawar-t7ks', 'Swat Mingora', 'Swat', NULL, NULL, '03110312555', 'swat@gmail.com', NULL, NULL, NULL, NULL, 1, 0.00, NULL, '2026-06-25 09:08:21', '2026-06-25 09:09:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bus_stand_staff`
--

DROP TABLE IF EXISTS `bus_stand_staff`;
CREATE TABLE IF NOT EXISTS `bus_stand_staff` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `bus_stand_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `designation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_stand_staff_bus_stand_id_user_id_unique` (`bus_stand_id`,`user_id`),
  KEY `bus_stand_staff_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bus_stand_user`
--

DROP TABLE IF EXISTS `bus_stand_user`;
CREATE TABLE IF NOT EXISTS `bus_stand_user` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `bus_stand_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bus_stand_user_bus_stand_id_user_id_unique` (`bus_stand_id`,`user_id`),
  KEY `bus_stand_user_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bus_stand_user`
--

INSERT INTO `bus_stand_user` (`id`, `bus_stand_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 5, '2026-06-25 09:09:25', '2026-06-25 09:09:25');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
CREATE TABLE IF NOT EXISTS `cities` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` smallint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cities_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Karachi', 1, 1, '2026-06-08 07:05:57', '2026-06-08 07:05:57'),
(2, 'Lahore', 1, 2, '2026-06-08 07:05:57', '2026-06-08 07:05:57'),
(3, 'Islamabad', 1, 3, '2026-06-08 07:05:57', '2026-06-08 07:05:57'),
(4, 'Rawalpindi', 1, 4, '2026-06-08 07:05:57', '2026-06-08 07:05:57'),
(5, 'Multan', 1, 5, '2026-06-08 07:05:57', '2026-06-08 07:05:57'),
(6, 'Peshawar', 1, 6, '2026-06-08 07:05:57', '2026-06-08 07:05:57'),
(7, 'Faisalabad', 1, 7, '2026-06-08 07:05:57', '2026-06-08 07:05:57'),
(8, 'Quetta', 1, 8, '2026-06-08 07:05:57', '2026-06-08 07:05:57'),
(9, 'Hyderabad', 1, 9, '2026-06-08 07:05:57', '2026-06-08 07:05:57'),
(10, 'Sialkot', 1, 10, '2026-06-08 07:05:57', '2026-06-08 07:05:57'),
(11, 'Gujranwala', 1, 11, '2026-06-08 07:05:57', '2026-06-08 07:05:57'),
(12, 'Bahawalpur', 1, 12, '2026-06-08 07:05:57', '2026-06-08 07:05:57'),
(13, 'Swat', 1, 1, '2026-06-09 11:13:02', '2026-06-09 11:13:02');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

DROP TABLE IF EXISTS `complaints`;
CREATE TABLE IF NOT EXISTS `complaints` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED DEFAULT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `resolution` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `complaints_uuid_unique` (`uuid`),
  KEY `complaints_user_id_foreign` (`user_id`),
  KEY `complaints_booking_id_foreign` (`booking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conductors`
--

DROP TABLE IF EXISTS `conductors`;
CREATE TABLE IF NOT EXISTS `conductors` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `bus_stand_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cnic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conductors_uuid_unique` (`uuid`),
  KEY `conductors_bus_stand_id_foreign` (`bus_stand_id`),
  KEY `conductors_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `conductors`
--

INSERT INTO `conductors` (`id`, `uuid`, `user_id`, `bus_stand_id`, `name`, `phone`, `cnic`, `employee_id`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '321ad018-fdc3-4824-8b35-5a3b2de2de1d', NULL, 1, 'Niaz', NULL, NULL, NULL, 1, '2026-06-25 09:30:41', '2026-06-25 09:30:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `conductor_attendance`
--

DROP TABLE IF EXISTS `conductor_attendance`;
CREATE TABLE IF NOT EXISTS `conductor_attendance` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `conductor_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conductor_attendance_conductor_id_date_unique` (`conductor_id`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `min_amount` decimal(10,2) DEFAULT NULL,
  `max_uses` int UNSIGNED DEFAULT NULL,
  `used_count` int UNSIGNED NOT NULL DEFAULT '0',
  `starts_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

DROP TABLE IF EXISTS `drivers`;
CREATE TABLE IF NOT EXISTS `drivers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `bus_stand_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cnic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `license_expiry` date NOT NULL,
  `license_class` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `emergency_contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `drivers_uuid_unique` (`uuid`),
  KEY `drivers_bus_stand_id_foreign` (`bus_stand_id`),
  KEY `drivers_license_number_index` (`license_number`),
  KEY `drivers_user_id_foreign` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `uuid`, `user_id`, `bus_stand_id`, `name`, `phone`, `cnic`, `license_number`, `license_expiry`, `license_class`, `address`, `emergency_contact`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '6f922f6e-fe30-43dd-af36-9c3ec3784346', NULL, 1, 'Zain', '03987654321', NULL, 'LIC-XEODDN9D', '2031-06-25', NULL, NULL, NULL, 1, '2026-06-25 09:30:41', '2026-06-25 09:30:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"f37f0d91-2281-4015-9d5e-b0d5598b0b65\",\"displayName\":\"App\\\\Jobs\\\\ReleaseExpiredSeatHolds\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ReleaseExpiredSeatHolds\",\"command\":\"O:32:\\\"App\\\\Jobs\\\\ReleaseExpiredSeatHolds\\\":1:{s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2026-06-25 15:05:21.247529\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}}\"}}', 0, NULL, 1782399921, 1782399321),
(2, 'default', '{\"uuid\":\"fb67c05f-7e1b-4dc5-821f-78bb7567eee1\",\"displayName\":\"App\\\\Jobs\\\\SendBookingConfirmation\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBookingConfirmation\",\"command\":\"O:32:\\\"App\\\\Jobs\\\\SendBookingConfirmation\\\":1:{s:7:\\\"booking\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Booking\\\";s:2:\\\"id\\\";i:1;s:9:\\\"relations\\\";a:2:{i:0;s:10:\\\"passengers\\\";i:1;s:15:\\\"passengers.seat\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}\"}}', 0, NULL, 1782399350, 1782399350),
(3, 'default', '{\"uuid\":\"6f977018-498d-4cd1-b465-fdb910eb39d3\",\"displayName\":\"App\\\\Jobs\\\\ReleaseExpiredSeatHolds\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ReleaseExpiredSeatHolds\",\"command\":\"O:32:\\\"App\\\\Jobs\\\\ReleaseExpiredSeatHolds\\\":1:{s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2026-06-25 15:18:09.380893\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}}\"}}', 0, NULL, 1782400689, 1782400089),
(4, 'default', '{\"uuid\":\"2232a614-56be-4938-ba6b-7abfe80cc21c\",\"displayName\":\"App\\\\Jobs\\\\ReleaseExpiredSeatHolds\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ReleaseExpiredSeatHolds\",\"command\":\"O:32:\\\"App\\\\Jobs\\\\ReleaseExpiredSeatHolds\\\":1:{s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2026-06-25 15:19:00.712859\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}}\"}}', 0, NULL, 1782400740, 1782400140),
(5, 'default', '{\"uuid\":\"ac21f607-6a23-411e-be54-7e934b42fc44\",\"displayName\":\"App\\\\Jobs\\\\SendBookingConfirmation\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBookingConfirmation\",\"command\":\"O:32:\\\"App\\\\Jobs\\\\SendBookingConfirmation\\\":1:{s:7:\\\"booking\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Booking\\\";s:2:\\\"id\\\";i:3;s:9:\\\"relations\\\";a:8:{i:0;s:8:\\\"schedule\\\";i:1;s:14:\\\"schedule.route\\\";i:2;s:16:\\\"schedule.vehicle\\\";i:3;s:10:\\\"passengers\\\";i:4;s:15:\\\"passengers.seat\\\";i:5;s:26:\\\"passengers.cancelledByUser\\\";i:6;s:22:\\\"passengerCancellations\\\";i:7;s:8:\\\"payments\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}\"}}', 0, NULL, 1782400169, 1782400169),
(6, 'default', '{\"uuid\":\"3785936d-82a8-4e76-8a84-8a914670204c\",\"displayName\":\"App\\\\Jobs\\\\ReleaseExpiredSeatHolds\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ReleaseExpiredSeatHolds\",\"command\":\"O:32:\\\"App\\\\Jobs\\\\ReleaseExpiredSeatHolds\\\":1:{s:5:\\\"delay\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2026-06-25 15:22:30.364506\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}}\"}}', 0, NULL, 1782400950, 1782400350);

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_points`
--

DROP TABLE IF EXISTS `loyalty_points`;
CREATE TABLE IF NOT EXISTS `loyalty_points` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `points` int NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint UNSIGNED DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `loyalty_points_user_id_created_at_index` (`user_id`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000003_create_permission_tables', 1),
(5, '2024_01_01_000004_create_personal_access_tokens_table', 1),
(6, '2024_01_01_000010_create_bus_stands_table', 1),
(7, '2024_01_01_000011_create_drivers_conductors_table', 1),
(8, '2024_01_01_000012_create_vehicles_table', 1),
(9, '2024_01_01_000013_create_routes_table', 1),
(10, '2024_01_01_000014_create_schedules_table', 1),
(11, '2024_01_01_000015_create_bookings_table', 1),
(12, '2024_01_01_000016_create_payments_table', 1),
(13, '2024_01_01_000017_create_support_tables', 1),
(14, '2026_05_19_000001_seed_default_roles', 1),
(15, '2026_05_20_000001_add_vehicle_staff_fields', 1),
(16, '2026_05_21_000001_create_cities_table', 1),
(17, '2026_05_22_000001_create_terminals_table', 1),
(18, '2026_05_22_000002_add_terminal_id_to_bus_stands_table', 1),
(19, '2026_05_23_000001_add_owner_id_to_terminals_table', 1),
(20, '2026_05_24_000001_add_terminal_id_to_users_and_nullable_stand_owner', 1),
(21, '2026_05_25_000001_add_from_to_cities_to_bus_stands', 1),
(22, '2026_05_26_000001_create_bus_stand_user_table', 1),
(23, '2026_06_08_000001_add_passenger_cancellation_support', 2),
(24, '2026_06_08_100000_create_weekly_schedule_plans_table', 3),
(25, '2026_06_08_120000_add_fare_amount_to_seats_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(3, 'App\\Models\\User', 4),
(5, 'App\\Models\\User', 5);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otp_verifications`
--

DROP TABLE IF EXISTS `otp_verifications`;
CREATE TABLE IF NOT EXISTS `otp_verifications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `identifier` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` timestamp NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `otp_verifications_identifier_type_index` (`identifier`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_id` bigint UNSIGNED NOT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `gateway_reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway_response` json DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_uuid_unique` (`uuid`),
  UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  KEY `payments_booking_id_foreign` (`booking_id`),
  KEY `payments_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `uuid`, `booking_id`, `transaction_id`, `method`, `amount`, `status`, `gateway_reference`, `gateway_response`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, '18be2bba-0f7a-49f5-896e-2e537a9b9c1c', 1, 'TXN-BSJQYP8TYTZD', 'cash', 7500.00, 'paid', NULL, '[]', '2026-06-25 09:55:50', '2026-06-25 09:55:50', '2026-06-25 09:55:50'),
(2, '68b61ccb-6bae-4139-975b-1e62079432fd', 3, 'TXN-CPXVQ1CXKZE9', 'cash', 4000.00, 'paid', NULL, '[]', '2026-06-25 10:09:29', '2026-06-25 10:09:29', '2026-06-25 10:09:29');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'terminals.manage', 'web', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(2, 'terminals.manage', 'sanctum', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(3, 'bus-stands.manage', 'web', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(4, 'bus-stands.manage', 'sanctum', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(5, 'vehicles.manage', 'web', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(6, 'vehicles.manage', 'sanctum', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(7, 'routes.manage', 'web', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(8, 'routes.manage', 'sanctum', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(9, 'schedules.manage', 'web', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(10, 'schedules.manage', 'sanctum', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(11, 'bookings.manage', 'web', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(12, 'bookings.manage', 'sanctum', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(13, 'bookings.view', 'web', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(14, 'bookings.view', 'sanctum', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(15, 'payments.manage', 'web', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(16, 'payments.manage', 'sanctum', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(17, 'reports.view', 'web', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(18, 'reports.view', 'sanctum', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(19, 'users.manage', 'web', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(20, 'users.manage', 'sanctum', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(21, 'drivers.manage', 'web', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(22, 'drivers.manage', 'sanctum', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(23, 'conductors.manage', 'web', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(24, 'conductors.manage', 'sanctum', '2026-06-08 07:05:15', '2026-06-08 07:05:15');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

DROP TABLE IF EXISTS `referrals`;
CREATE TABLE IF NOT EXISTS `referrals` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `referrer_id` bigint UNSIGNED NOT NULL,
  `referred_id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reward_claimed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `referrals_referrer_id_foreign` (`referrer_id`),
  KEY `referrals_referred_id_foreign` (`referred_id`),
  KEY `referrals_code_index` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `refunds`
--

DROP TABLE IF EXISTS `refunds`;
CREATE TABLE IF NOT EXISTS `refunds` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `processed_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `refunds_uuid_unique` (`uuid`),
  KEY `refunds_payment_id_foreign` (`payment_id`),
  KEY `refunds_booking_id_foreign` (`booking_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED NOT NULL,
  `schedule_id` bigint UNSIGNED NOT NULL,
  `rating` tinyint UNSIGNED NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  KEY `reviews_booking_id_foreign` (`booking_id`),
  KEY `reviews_schedule_id_foreign` (`schedule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'web', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(2, 'super_admin', 'sanctum', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(3, 'terminal_admin', 'web', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(4, 'terminal_admin', 'sanctum', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(5, 'admin', 'web', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(6, 'admin', 'sanctum', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(7, 'passenger', 'web', '2026-06-08 07:05:15', '2026-06-08 07:05:15'),
(8, 'passenger', 'sanctum', '2026-06-08 07:05:15', '2026-06-08 07:05:15');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(3, 1),
(5, 1),
(7, 1),
(9, 1),
(11, 1),
(13, 1),
(15, 1),
(17, 1),
(19, 1),
(21, 1),
(23, 1),
(2, 2),
(4, 2),
(6, 2),
(8, 2),
(10, 2),
(12, 2),
(14, 2),
(16, 2),
(18, 2),
(20, 2),
(22, 2),
(24, 2),
(1, 3),
(3, 3),
(13, 3),
(17, 3),
(2, 4),
(4, 4),
(14, 4),
(18, 4),
(5, 5),
(7, 5),
(9, 5),
(11, 5),
(13, 5),
(17, 5),
(21, 5),
(23, 5),
(6, 6),
(8, 6),
(10, 6),
(12, 6),
(14, 6),
(18, 6),
(22, 6),
(24, 6);

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

DROP TABLE IF EXISTS `routes`;
CREATE TABLE IF NOT EXISTS `routes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bus_stand_id` bigint UNSIGNED NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `departure_city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination_city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `distance_km` decimal(8,2) DEFAULT NULL,
  `duration_minutes` int UNSIGNED DEFAULT NULL,
  `base_fare` decimal(10,2) NOT NULL,
  `map_polyline` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `routes_uuid_unique` (`uuid`),
  KEY `routes_bus_stand_id_foreign` (`bus_stand_id`),
  KEY `routes_departure_city_index` (`departure_city`),
  KEY `routes_destination_city_index` (`destination_city`),
  KEY `routes_is_active_index` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `uuid`, `bus_stand_id`, `code`, `departure_city`, `destination_city`, `name`, `distance_km`, `duration_minutes`, `base_fare`, `map_polyline`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'c909036e-cf62-44e8-8b99-dabf0a39b6ee', 1, NULL, 'Swat', 'Peshawar', 'Swat → Peshawar', NULL, NULL, 1000.00, NULL, 1, '2026-06-25 09:08:21', '2026-06-25 09:08:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `route_stops`
--

DROP TABLE IF EXISTS `route_stops`;
CREATE TABLE IF NOT EXISTS `route_stops` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `route_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `order` smallint UNSIGNED NOT NULL,
  `arrival_offset_minutes` int UNSIGNED NOT NULL DEFAULT '0',
  `fare_from_origin` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `route_stops_route_id_order_index` (`route_id`,`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

DROP TABLE IF EXISTS `schedules`;
CREATE TABLE IF NOT EXISTS `schedules` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_id` bigint UNSIGNED NOT NULL,
  `vehicle_id` bigint UNSIGNED NOT NULL,
  `driver_id` bigint UNSIGNED DEFAULT NULL,
  `weekly_schedule_plan_id` bigint UNSIGNED DEFAULT NULL,
  `departure_date` date NOT NULL,
  `departure_time` time NOT NULL,
  `arrival_time` time DEFAULT NULL,
  `fare` decimal(10,2) NOT NULL,
  `available_seats` smallint UNSIGNED NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'scheduled',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `schedules_uuid_unique` (`uuid`),
  KEY `schedules_driver_id_foreign` (`driver_id`),
  KEY `schedules_route_id_departure_date_departure_time_index` (`route_id`,`departure_date`,`departure_time`),
  KEY `schedules_vehicle_id_departure_date_index` (`vehicle_id`,`departure_date`),
  KEY `schedules_departure_date_index` (`departure_date`),
  KEY `schedules_weekly_schedule_plan_id_foreign` (`weekly_schedule_plan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=315 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `uuid`, `route_id`, `vehicle_id`, `driver_id`, `weekly_schedule_plan_id`, `departure_date`, `departure_time`, `arrival_time`, `fare`, `available_seats`, `status`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'e759b8db-8976-47f1-b439-aec8807cc134', 1, 1, 1, 1, '2026-06-29', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(2, '7cc263cb-2f08-467c-9c22-7fa5992f3c1b', 1, 1, 1, 1, '2026-07-06', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(3, '4ef0c2ec-f848-47c9-a79f-35e4b789bd87', 1, 1, 1, 1, '2026-07-13', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(4, 'e0264054-53a7-4281-a01d-61854f1578dc', 1, 1, 1, 1, '2026-07-20', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(5, '58a38f83-f935-4511-9622-030a5552a11b', 1, 1, 1, 1, '2026-07-27', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(6, 'd13d3a9c-58b3-4c1a-8dcc-9f4d621bb674', 1, 1, 1, 1, '2026-08-03', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(7, '3603c919-3f24-412d-9ecc-9af3a44c2613', 1, 1, 1, 1, '2026-08-10', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(8, '7f2084d6-5721-4d03-8bbc-09980a383d0e', 1, 1, 1, 1, '2026-08-17', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(9, '2f914f81-99f6-48e1-8015-b2f027dfdbe0', 1, 1, 1, 1, '2026-08-24', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(10, 'ce27ea4e-ba1b-436c-9c24-de32e7a3809c', 1, 1, 1, 1, '2026-08-31', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(11, '55325d4e-fb52-43ba-b703-8d651635aa72', 1, 1, 1, 1, '2026-09-07', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(12, '688a1239-02ca-4de4-8a8b-361e4d3c3fb6', 1, 1, 1, 1, '2026-09-14', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(13, '1e1f4753-b27c-48b8-a4c6-4bad0e457086', 1, 1, 1, 1, '2026-09-21', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(14, 'f16778c0-48b1-42fd-8af6-655b4ce582c5', 1, 1, 1, 1, '2026-09-28', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(15, 'f4aa966e-55a0-4034-9975-2ff6a9940629', 1, 1, 1, 1, '2026-10-05', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(16, '235f935a-3d8b-46dc-9fa3-ca1f218f6509', 1, 1, 1, 1, '2026-10-12', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(17, 'c1169523-092d-45e1-8a37-8006328ee948', 1, 1, 1, 1, '2026-10-19', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(18, '2f0b5d6b-d912-4ab7-8726-4b760a77a75f', 1, 1, 1, 1, '2026-10-26', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(19, '05ea5345-520b-411d-aa33-b0754917be69', 1, 1, 1, 1, '2026-11-02', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(20, '29a9bc36-8567-4432-a441-c55821796f5f', 1, 1, 1, 1, '2026-11-09', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(21, '321c9504-c9c0-465a-9755-56bb79277460', 1, 1, 1, 1, '2026-11-16', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(22, 'b2d48468-a4c5-45b2-a62d-4150248a81f9', 1, 1, 1, 1, '2026-11-23', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(23, 'd657af97-edf1-487d-8b18-d2688c1f5224', 1, 1, 1, 1, '2026-11-30', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(24, 'e3147a26-15e7-4aae-b83f-84bda4aef62a', 1, 1, 1, 1, '2026-12-07', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(25, '8db071b5-a41c-47f8-a444-32978adefca5', 1, 1, 1, 1, '2026-12-14', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(26, 'b0e8237c-916f-400b-b6a4-0e69d835af49', 1, 1, 1, 1, '2026-12-21', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(27, 'fcd12ec1-9b15-4db7-a0a3-e8fddcf84a12', 1, 1, 1, 1, '2026-12-28', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(28, 'ff10b583-a240-49df-b495-e628bc8fb7f7', 1, 1, 1, 1, '2027-01-04', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(29, '5b5f15d7-3c04-461a-99d7-4c04c240bdc4', 1, 1, 1, 1, '2027-01-11', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(30, '016c2801-2ca0-42de-bb6d-d64ebaa3571f', 1, 1, 1, 1, '2027-01-18', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(31, '592333cf-2ff7-4d6f-8f75-5c87ff1bb62f', 1, 1, 1, 1, '2027-01-25', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(32, '8a96e4e1-14b5-4c86-a6a6-accc51c5e9e9', 1, 1, 1, 1, '2027-02-01', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(33, 'a52d33d9-9299-47db-8dc8-802217ac080d', 1, 1, 1, 1, '2027-02-08', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(34, 'd1754851-3236-40af-bf65-e82fd140b7d1', 1, 1, 1, 1, '2027-02-15', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(35, '1ade29a4-14ac-434a-8c53-e96042c4e5a2', 1, 1, 1, 1, '2027-02-22', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(36, '787d0d82-7478-49aa-b45f-1b37c04de69c', 1, 1, 1, 1, '2027-03-01', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(37, '19230179-dec5-468a-ae0c-90d70c89d947', 1, 1, 1, 1, '2027-03-08', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(38, '8a9f274d-0ec7-4474-989e-63a37d0b8d17', 1, 1, 1, 1, '2027-03-15', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(39, '9fc9df86-ba49-4f53-a3d4-3960f6455b5f', 1, 1, 1, 1, '2027-03-22', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(40, '29bad0df-d15d-4aa9-8e0c-6308cbab6f24', 1, 1, 1, 1, '2027-03-29', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(41, 'de90a130-741f-4425-a76e-43e91a43a92e', 1, 1, 1, 1, '2027-04-05', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(42, 'efad891a-4990-43d8-a4f3-d5bdb747468f', 1, 1, 1, 1, '2027-04-12', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(43, 'a6ad3125-a216-4884-b502-41be9c45302d', 1, 1, 1, 1, '2027-04-19', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(44, '2f5f8c79-5b10-4bd6-83a5-3657514e8da3', 1, 1, 1, 1, '2027-04-26', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(45, '92813093-db82-4f5d-8e63-ab489bb01cf5', 1, 1, 1, 1, '2027-05-03', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(46, 'e2a610d6-e942-4729-bf2a-0a0721df9a78', 1, 1, 1, 1, '2027-05-10', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(47, 'b6cadc03-620e-4002-b710-2ea2f3f698c7', 1, 1, 1, 1, '2027-05-17', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(48, '4bd68eca-8c82-4a0e-b892-e8abd485e438', 1, 1, 1, 1, '2027-05-24', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(49, '5e55cc66-f39c-4444-9e4e-ff5f15bd5463', 1, 1, 1, 1, '2027-05-31', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(50, 'dab7ecd4-8bba-4bf1-83f3-f3b573d2386b', 1, 1, 1, 1, '2027-06-07', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(51, '189db55b-c113-484e-acfe-a1b293f6ae71', 1, 1, 1, 1, '2027-06-14', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(52, 'dd97da4a-f1ef-4a64-a682-c2fb6aa67452', 1, 1, 1, 1, '2027-06-21', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:31:30', '2026-06-25 09:40:03', '2026-06-25 09:40:03'),
(53, '290f2822-ae55-4498-bab5-5cea4711883f', 1, 1, 1, 1, '2026-06-29', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(54, '4c6a265d-3bc4-4087-82c8-c9b6f209f6d1', 1, 1, 1, 1, '2026-07-06', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(55, '21a8c4d3-3cdb-44c1-a772-f0f5d1856fc8', 1, 1, 1, 1, '2026-07-13', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(56, '63ab944a-8e71-4d60-807b-ae33288f2c5e', 1, 1, 1, 1, '2026-07-20', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(57, 'f29e0c81-684b-4ea8-a1cf-a120fe1e2554', 1, 1, 1, 1, '2026-07-27', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(58, '81692d0c-2a48-4eca-a930-ecb01641eb73', 1, 1, 1, 1, '2026-08-03', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(59, '56fbf8ca-48ad-469c-8e68-bbf2a2dafb23', 1, 1, 1, 1, '2026-08-10', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(60, '3cf756f1-3aeb-405f-a673-d1d453090464', 1, 1, 1, 1, '2026-08-17', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(61, '5a222f13-314f-4915-a895-8f4f06b6f847', 1, 1, 1, 1, '2026-08-24', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(62, '834b53c8-4751-410f-8440-a6fe0e76c65b', 1, 1, 1, 1, '2026-08-31', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(63, '9765d3cc-b2bb-46d0-893d-1caba6d17832', 1, 1, 1, 1, '2026-09-07', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(64, '2ee2f7f4-b1d7-48aa-802e-b6bd81e42621', 1, 1, 1, 1, '2026-09-14', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(65, '056a6919-fc7d-4a07-9ede-a0a55395dc3d', 1, 1, 1, 1, '2026-09-21', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(66, '7b175147-fbcc-40c7-bcbb-bc988d80bf1d', 1, 1, 1, 1, '2026-09-28', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(67, '744caf50-149a-4056-b187-d5806775b631', 1, 1, 1, 1, '2026-10-05', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(68, 'd826d6db-9ce7-442e-9a6f-e89bc10e7533', 1, 1, 1, 1, '2026-10-12', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(69, '113a5957-ec1a-4952-9fcd-f896e0094810', 1, 1, 1, 1, '2026-10-19', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(70, '10959ee8-4f2b-4669-977d-311b93321096', 1, 1, 1, 1, '2026-10-26', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(71, '15cbeaaf-6c92-4699-91fe-b80104fc822b', 1, 1, 1, 1, '2026-11-02', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(72, '27c68926-c69f-453c-870e-051451f26b59', 1, 1, 1, 1, '2026-11-09', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(73, 'ace73e1c-c074-4bbc-b193-6fa20f810532', 1, 1, 1, 1, '2026-11-16', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(74, '5af0f8a9-63f1-41c6-bde8-f15caf77fb43', 1, 1, 1, 1, '2026-11-23', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(75, '82ed1f41-f329-4479-ae0d-d37753e36f4c', 1, 1, 1, 1, '2026-11-30', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(76, '0dc2810f-c78c-4003-97fa-17ee2addf459', 1, 1, 1, 1, '2026-12-07', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(77, '84d75813-10de-47f3-9a35-9ebf80cd0520', 1, 1, 1, 1, '2026-12-14', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(78, 'e8203e72-9d99-494f-a284-8ac41e3cf55e', 1, 1, 1, 1, '2026-12-21', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(79, '568cd3bf-3cb3-4478-8d0b-a26b1f0e6452', 1, 1, 1, 1, '2026-12-28', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(80, 'ec786688-f39c-43b8-b384-039d035d38d3', 1, 1, 1, 1, '2027-01-04', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(81, 'a5e91650-1b73-40b2-a32a-2dc663eeeb51', 1, 1, 1, 1, '2027-01-11', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(82, 'accc1692-6f2e-40f2-a071-5bbe69ceaf42', 1, 1, 1, 1, '2027-01-18', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(83, '210bbb32-75f7-41ad-afb6-3888625ef728', 1, 1, 1, 1, '2027-01-25', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(84, '016c6a35-881d-41db-9921-8e3338a2f03f', 1, 1, 1, 1, '2027-02-01', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(85, '339f47e7-3281-4e08-aefe-ed44790ee398', 1, 1, 1, 1, '2027-02-08', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(86, '2b743f66-c161-4bcf-9be1-34dc61f6ca5b', 1, 1, 1, 1, '2027-02-15', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(87, '28d1b411-ced6-453c-8915-f50e30df33c5', 1, 1, 1, 1, '2027-02-22', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(88, 'ef12c4dc-2d2d-42ca-b49b-4937db905354', 1, 1, 1, 1, '2027-03-01', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(89, 'c0c51241-42d3-485f-b397-46fe688cfe79', 1, 1, 1, 1, '2027-03-08', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(90, '8e3b36e2-ac5f-4650-bcd7-58101519c587', 1, 1, 1, 1, '2027-03-15', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(91, '9e61dde1-2679-4732-97c9-23dc024de4e3', 1, 1, 1, 1, '2027-03-22', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(92, 'bafd67d0-ae53-47c3-90ce-42bfe57ec0cf', 1, 1, 1, 1, '2027-03-29', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(93, 'a3f67aec-6441-4d30-888f-03a8bfc11c7e', 1, 1, 1, 1, '2027-04-05', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(94, '6119f69b-fbea-4761-8640-2bb06d16acec', 1, 1, 1, 1, '2027-04-12', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(95, '1088a340-0468-4580-a31c-87c73ee2ee54', 1, 1, 1, 1, '2027-04-19', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(96, '1601ab24-0436-4b25-b387-350875bbe470', 1, 1, 1, 1, '2027-04-26', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(97, 'ce6801c5-62f5-4321-b6b2-774bd161d697', 1, 1, 1, 1, '2027-05-03', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(98, 'da015263-0b0d-47ce-b7f7-e374a3ddac39', 1, 1, 1, 1, '2027-05-10', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(99, '9e04d869-64e9-49c5-83f9-54b79098f4c6', 1, 1, 1, 1, '2027-05-17', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(100, '5950557f-3c00-4019-bd1a-9e2198bb3bc1', 1, 1, 1, 1, '2027-05-24', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(101, '1d194153-485b-4fa3-9646-c48b7cca0be8', 1, 1, 1, 1, '2027-05-31', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(102, '5e552edc-315d-4bd2-b82a-fc6985d1e331', 1, 1, 1, 1, '2027-06-07', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(103, '42620929-30ae-41c0-91a2-18454e05c305', 1, 1, 1, 1, '2027-06-14', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(104, 'd10e38cc-62ef-4e74-9a59-f73d3c9ca1d7', 1, 1, 1, 1, '2027-06-21', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:40:04', '2026-06-25 09:43:26', '2026-06-25 09:43:26'),
(105, 'eb9a2dbb-69e1-4598-984f-8b1662ffdda5', 1, 1, 1, 1, '2026-06-25', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(106, 'a124557f-1851-4ba8-9337-e254b0c50958', 1, 1, 1, 1, '2026-06-29', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(107, '38fbc6b4-46a1-4495-bbaf-35e54a41526e', 1, 1, 1, 1, '2026-07-02', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(108, 'dc6d1535-463e-4a23-94cf-bb6977b3f463', 1, 1, 1, 1, '2026-07-06', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(109, '33d828c4-58ef-411d-abc4-0913b2c7064d', 1, 1, 1, 1, '2026-07-09', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(110, '74f814a9-c3bc-4b54-a5f4-db727a097752', 1, 1, 1, 1, '2026-07-13', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(111, 'df5d9771-3dcf-4779-8bd7-c5ef8fc14324', 1, 1, 1, 1, '2026-07-16', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(112, '4bfd5706-26b1-4c13-9ac0-39e367ccc6d9', 1, 1, 1, 1, '2026-07-20', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(113, 'b7bca22a-8756-45b5-b2e7-66fc64ee91b5', 1, 1, 1, 1, '2026-07-23', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(114, '4141c82d-277a-4024-87b6-b869188a1671', 1, 1, 1, 1, '2026-07-27', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(115, '340f689c-6333-4034-b302-8222b850763d', 1, 1, 1, 1, '2026-07-30', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(116, '05f8186e-a9a3-4af2-8d6a-4a9daabc7c68', 1, 1, 1, 1, '2026-08-03', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(117, '85ebdee3-7e6f-43ed-9818-640e6480abc7', 1, 1, 1, 1, '2026-08-06', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(118, '279aaac9-dc4f-4919-8d6a-cc703578d8fd', 1, 1, 1, 1, '2026-08-10', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(119, '34431e2a-b66b-47ea-9131-2c0c585dd5ca', 1, 1, 1, 1, '2026-08-13', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(120, '61c9ba18-3f38-455c-939a-7c5dfb7468bd', 1, 1, 1, 1, '2026-08-17', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(121, '5c4c9c68-b05c-430d-9c01-cd31e56231cd', 1, 1, 1, 1, '2026-08-20', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(122, '4dac349d-a576-4898-94c2-e9ec2bda1a2c', 1, 1, 1, 1, '2026-08-24', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(123, 'ead848b2-9db3-42a6-9070-def33640d6ca', 1, 1, 1, 1, '2026-08-27', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(124, 'c531fc80-32d8-42e0-a9bc-fb71127c0b85', 1, 1, 1, 1, '2026-08-31', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(125, '419c6473-1d50-4d48-adec-c296b55782c4', 1, 1, 1, 1, '2026-09-03', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(126, '3c430531-a182-4990-842e-2eb58cd084c4', 1, 1, 1, 1, '2026-09-07', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(127, '6112f730-40bc-4bf3-b3a3-452ec33af8ff', 1, 1, 1, 1, '2026-09-10', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(128, 'c552a137-81f7-43c9-85af-ec2e74dd2fd1', 1, 1, 1, 1, '2026-09-14', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(129, '91e3ca7a-af51-4a9a-9755-728ce05ea3f0', 1, 1, 1, 1, '2026-09-17', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(130, 'c9c61555-326a-48dc-98c5-f05629545e67', 1, 1, 1, 1, '2026-09-21', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(131, '64ad7dd0-d8bf-495c-9045-48485eb6ba88', 1, 1, 1, 1, '2026-09-24', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(132, '483376c5-b2e5-443f-bec9-5a0461d463b6', 1, 1, 1, 1, '2026-09-28', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(133, 'a5207947-7d9d-42c6-af15-31c5e77fab09', 1, 1, 1, 1, '2026-10-01', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(134, 'fd73ec5c-bcd7-42dc-940c-bf3942eb025b', 1, 1, 1, 1, '2026-10-05', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(135, '6227a277-6fc2-47f9-9b3d-9d7d98d72adf', 1, 1, 1, 1, '2026-10-08', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(136, 'f402e820-ff89-4b9c-a2f0-3580912ac32f', 1, 1, 1, 1, '2026-10-12', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(137, '1c1a3ef1-8580-441d-8e1d-9ed31a26a97c', 1, 1, 1, 1, '2026-10-15', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(138, 'e091cc25-ff7b-4a24-a8ba-c2abbd88063e', 1, 1, 1, 1, '2026-10-19', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(139, 'ba524b42-cad0-470c-9130-bd2350b155ba', 1, 1, 1, 1, '2026-10-22', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(140, 'c9108628-dd90-488f-9f7b-ebd2ff7ce41d', 1, 1, 1, 1, '2026-10-26', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(141, '707f9703-d86e-4cde-a15f-6a2bfd6118f8', 1, 1, 1, 1, '2026-10-29', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(142, '9f1f8824-6b68-4ef6-9105-296e54b9b6fe', 1, 1, 1, 1, '2026-11-02', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(143, '1b1caa39-64df-4a66-a2b7-cf07407eaf51', 1, 1, 1, 1, '2026-11-05', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(144, '3fceef91-ad41-4905-a96c-957ab2a0bf4e', 1, 1, 1, 1, '2026-11-09', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(145, 'd13952e0-f56d-41d2-a540-504edc650dba', 1, 1, 1, 1, '2026-11-12', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(146, 'dc25e598-156a-4b8a-8b33-d41e63b293a4', 1, 1, 1, 1, '2026-11-16', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(147, '4e2050b4-cd7a-4e1b-979c-754c02804fae', 1, 1, 1, 1, '2026-11-19', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(148, '50ba18e3-2cd4-4ea6-b7fc-48ea5d1b29a5', 1, 1, 1, 1, '2026-11-23', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(149, '4244c413-2f92-410d-a76c-e971b0998416', 1, 1, 1, 1, '2026-11-26', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(150, 'a0bb5d81-bdc3-4b10-96dd-a110c4fcb361', 1, 1, 1, 1, '2026-11-30', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(151, 'fec93ce5-e6c5-416f-a85a-1770c7acb9ba', 1, 1, 1, 1, '2026-12-03', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(152, 'af527d1e-7fe8-44db-8ad9-bad9b6c38bbb', 1, 1, 1, 1, '2026-12-07', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(153, 'c426aa12-1f54-4083-9a94-07864cccb54e', 1, 1, 1, 1, '2026-12-10', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(154, '91da990a-5b78-46a9-b237-a7688c4f99bf', 1, 1, 1, 1, '2026-12-14', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(155, 'befd7e2c-14cb-4999-8821-3ad1b05728c6', 1, 1, 1, 1, '2026-12-17', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(156, '20c7db04-cdcc-461d-8b01-fdcd2da1f2fe', 1, 1, 1, 1, '2026-12-21', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(157, 'df813322-b591-41b9-940b-0453cf6141b6', 1, 1, 1, 1, '2026-12-24', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(158, '922cc0bb-f6ed-4a83-983f-a5e7131c3255', 1, 1, 1, 1, '2026-12-28', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(159, '783c8b08-0ff7-42a3-b300-ce73549469cb', 1, 1, 1, 1, '2026-12-31', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(160, '971c667b-dfc5-42ca-9e5e-46b0f01df5f8', 1, 1, 1, 1, '2027-01-04', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(161, '69028ff1-a819-434e-8cb8-2b1915f47a16', 1, 1, 1, 1, '2027-01-07', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(162, 'b911e22f-1ab1-4a4d-aa00-c94d71a42363', 1, 1, 1, 1, '2027-01-11', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(163, 'c01e8491-34f8-4b02-a6af-40d922f99278', 1, 1, 1, 1, '2027-01-14', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(164, '1acaf8ef-a88e-4a4a-a5eb-cb71d7c0b2a9', 1, 1, 1, 1, '2027-01-18', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(165, '776e4f1f-3b59-4dc7-9bed-0921111b0b92', 1, 1, 1, 1, '2027-01-21', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(166, 'bcff6570-afb2-49dd-94b3-911866bd40d7', 1, 1, 1, 1, '2027-01-25', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(167, 'e1ffd8f5-5ebe-4730-9fbc-d237db57ccc4', 1, 1, 1, 1, '2027-01-28', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(168, '09f13cc2-fd1f-4c96-8f03-eefa77860c67', 1, 1, 1, 1, '2027-02-01', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(169, 'cd1dd423-e47d-48d1-9ef3-4d520c7ed912', 1, 1, 1, 1, '2027-02-04', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(170, '86b57cf6-8e64-46ee-b15c-1f75d1c3eaf1', 1, 1, 1, 1, '2027-02-08', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(171, 'cc0fc724-90fa-4809-a822-afe7da47267a', 1, 1, 1, 1, '2027-02-11', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(172, '79b96ac4-f470-4148-8089-3cf00fc5dc55', 1, 1, 1, 1, '2027-02-15', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(173, 'd6f44343-7bbd-4800-b906-049cf673a79a', 1, 1, 1, 1, '2027-02-18', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(174, '198a034f-c9e0-4912-9ee3-4b85d1ec4d44', 1, 1, 1, 1, '2027-02-22', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(175, '7d668445-2a49-4c25-bef0-6a9bb1015bde', 1, 1, 1, 1, '2027-02-25', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(176, '90f419f0-03c1-4c40-a442-a763a623a40a', 1, 1, 1, 1, '2027-03-01', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(177, '0e2b7118-3c24-42b6-be98-92223965a3d7', 1, 1, 1, 1, '2027-03-04', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(178, '9c313218-b132-4db5-99e7-209fe62a34c7', 1, 1, 1, 1, '2027-03-08', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(179, 'c5353754-b94d-4764-859e-0c92f1219430', 1, 1, 1, 1, '2027-03-11', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(180, 'b3038fe4-2da7-4d37-9169-ebf4c818b5df', 1, 1, 1, 1, '2027-03-15', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(181, '80e9f10f-96f3-428c-9067-aa13ce8b5967', 1, 1, 1, 1, '2027-03-18', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(182, '71f918e7-ef2b-42f6-ac14-b7624d7dfa27', 1, 1, 1, 1, '2027-03-22', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(183, 'bebcd79d-5fcc-426a-8227-086b2ba41b85', 1, 1, 1, 1, '2027-03-25', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(184, '4afc7701-3b16-4904-945c-1462cad909bd', 1, 1, 1, 1, '2027-03-29', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(185, 'a98fddf8-edfc-430e-a0ce-4cc25024be22', 1, 1, 1, 1, '2027-04-01', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(186, '6c089067-2d2e-4d4e-a420-d4b5965d4648', 1, 1, 1, 1, '2027-04-05', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(187, '6edab31f-f9dd-4c93-b6d4-41635db6db67', 1, 1, 1, 1, '2027-04-08', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(188, '7162d656-5cf8-44ae-96e2-518d1936e5ed', 1, 1, 1, 1, '2027-04-12', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(189, '3ed75efb-16a8-4e5b-8a39-9318f639e999', 1, 1, 1, 1, '2027-04-15', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(190, '54eec07c-1dc7-4b63-96bd-edfe9daa4169', 1, 1, 1, 1, '2027-04-19', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(191, '763c1f1e-edeb-4034-8c6b-cc406f4be55d', 1, 1, 1, 1, '2027-04-22', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(192, '36a8a61b-50d1-4ad4-bb5a-a550a06aa40d', 1, 1, 1, 1, '2027-04-26', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(193, '2fe80bd3-bda0-4c2b-be8c-d984488ef644', 1, 1, 1, 1, '2027-04-29', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(194, 'cfa30ced-2b80-4191-aa5f-992b2b6b66f9', 1, 1, 1, 1, '2027-05-03', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(195, '084e7b8b-bb8a-4a80-a6c5-c5b6a761686a', 1, 1, 1, 1, '2027-05-06', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(196, 'f2f14497-71f5-40cb-85e2-f2b8303b2089', 1, 1, 1, 1, '2027-05-10', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(197, 'f72b7373-e000-4cbc-83c4-ba65f53cc271', 1, 1, 1, 1, '2027-05-13', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(198, 'feaf473b-3850-4d07-8efe-8f7b83a963d1', 1, 1, 1, 1, '2027-05-17', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(199, '67468f89-2b61-49bb-9ac4-0e96cc5e6bbe', 1, 1, 1, 1, '2027-05-20', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(200, '58538bc5-0ab5-4965-a599-ebbdd5f169f2', 1, 1, 1, 1, '2027-05-24', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(201, 'f548bbb1-66b6-4ec6-9236-81727a0ada57', 1, 1, 1, 1, '2027-05-27', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(202, '1cba4d12-ade8-4168-8beb-b2587194e819', 1, 1, 1, 1, '2027-05-31', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(203, 'c001619a-1344-4788-978b-f7491da09342', 1, 1, 1, 1, '2027-06-03', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(204, 'eb7ff82e-14dc-496d-80e4-c1dec75207af', 1, 1, 1, 1, '2027-06-07', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(205, '2638a3d4-7613-478b-be7b-e6e9af47b495', 1, 1, 1, 1, '2027-06-10', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(206, 'a283a15c-d6b4-40f9-b61a-f9825c1746d7', 1, 1, 1, 1, '2027-06-14', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(207, 'b3433f2b-c121-4553-94de-29df71698906', 1, 1, 1, 1, '2027-06-17', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(208, '3a5750ef-3299-4b2c-82f8-7cf17608c0d8', 1, 1, 1, 1, '2027-06-21', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(209, '4939dfaf-cedc-4b79-b54c-61670de81ab5', 1, 1, 1, 1, '2027-06-24', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:26', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(210, 'e6d2e5a4-0d22-4671-8c5b-1ccfd8a287cb', 1, 1, 1, 1, '2026-06-25', '20:00:00', '22:00:00', 1000.00, 5, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 10:12:53', NULL),
(211, '79c8c465-9a14-498e-887f-15e176434944', 1, 1, 1, 1, '2026-06-29', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(212, 'b6192251-dc67-4770-bb39-0a1dd262fa20', 1, 1, 1, 1, '2026-07-02', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(213, 'd7cb436b-dfec-4c89-a561-b49b1b4b1d99', 1, 1, 1, 1, '2026-07-06', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(214, '1822f18d-ad41-4f54-87c6-3f6c0d3cae12', 1, 1, 1, 1, '2026-07-09', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(215, '376a788b-1abc-4e12-9afa-8f624b6c9fe6', 1, 1, 1, 1, '2026-07-13', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(216, '0628ce91-934d-4004-a9c2-a36176f89e99', 1, 1, 1, 1, '2026-07-16', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(217, '878c00f2-ceaa-4522-848c-3715709ef2be', 1, 1, 1, 1, '2026-07-20', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(218, '94731aac-65b3-4f00-9ce5-e6e63c29aef7', 1, 1, 1, 1, '2026-07-23', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(219, '9f8fcfb8-95a0-4b2e-9f93-e4329f2d69d6', 1, 1, 1, 1, '2026-07-27', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(220, '280f8d88-8668-4eb7-a748-a5b88827cac1', 1, 1, 1, 1, '2026-07-30', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(221, '7593f4a8-c16f-4c51-ba6f-699a072ed002', 1, 1, 1, 1, '2026-08-03', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(222, 'a0ae4f4a-88c4-4a9f-a07f-f0c1231451ad', 1, 1, 1, 1, '2026-08-06', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(223, '19c2591e-d9bb-429e-88a4-0db000173c97', 1, 1, 1, 1, '2026-08-10', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(224, '9349e4e0-4bdb-49fb-ac55-3301ad6f953d', 1, 1, 1, 1, '2026-08-13', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(225, '26b91e63-6fe6-4e5c-81f5-19c49dc41d6d', 1, 1, 1, 1, '2026-08-17', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(226, '6712c897-73df-45d2-816d-48a5acb63a16', 1, 1, 1, 1, '2026-08-20', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(227, '6e715289-5095-4d26-8890-cf0e411a78db', 1, 1, 1, 1, '2026-08-24', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(228, 'ddd56052-db88-4357-ba9e-582c5dacff64', 1, 1, 1, 1, '2026-08-27', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(229, '7fb6ffb0-d5ed-40ad-97c9-6eb9e451861c', 1, 1, 1, 1, '2026-08-31', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(230, '4d21b578-20fa-4ad5-8925-423454acad3a', 1, 1, 1, 1, '2026-09-03', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(231, '277fdef5-33ad-4835-a809-08c6477c782a', 1, 1, 1, 1, '2026-09-07', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(232, '6c66c3e0-c5c8-4099-b4e3-0e7ef0831ea3', 1, 1, 1, 1, '2026-09-10', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(233, '34ddabc2-3ebc-4ec3-9daf-488e02cbd0d1', 1, 1, 1, 1, '2026-09-14', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(234, '87b4d028-313f-477d-8012-2c9c5c7da978', 1, 1, 1, 1, '2026-09-17', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(235, 'b1c11fcc-dea6-417b-901f-87205b4c61b6', 1, 1, 1, 1, '2026-09-21', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(236, '51f82a16-e127-4cde-b47a-a55c93e82a2c', 1, 1, 1, 1, '2026-09-24', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(237, 'fecac49e-7485-4695-a366-abb246af53e1', 1, 1, 1, 1, '2026-09-28', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(238, '5941dae9-cf65-402b-829d-7642fa6eeb82', 1, 1, 1, 1, '2026-10-01', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(239, 'af5ba878-3ece-44e0-9f15-6414034bdf9b', 1, 1, 1, 1, '2026-10-05', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(240, 'e9a46d81-266d-4bb0-9322-f2f2cf8ac1fa', 1, 1, 1, 1, '2026-10-08', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(241, 'f6a053db-0ddb-4322-876e-27a4cd78557d', 1, 1, 1, 1, '2026-10-12', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(242, 'a280d563-ce57-4525-88bc-69f47e7d0efd', 1, 1, 1, 1, '2026-10-15', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(243, 'b4baeb29-74c2-4ae7-b0b6-9fdb045ef068', 1, 1, 1, 1, '2026-10-19', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(244, 'f730dd4e-e5df-4526-95f2-8bed4dc4bc00', 1, 1, 1, 1, '2026-10-22', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(245, '14f9295f-96fe-4275-bdb8-fac09b1a5500', 1, 1, 1, 1, '2026-10-26', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(246, '4128d733-4654-4c58-9013-7de49c58a891', 1, 1, 1, 1, '2026-10-29', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(247, 'bc714ff8-808f-4ac2-af4d-f555bc32af7e', 1, 1, 1, 1, '2026-11-02', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(248, '0e82e1fc-e038-4bdc-af5c-f01fdd54b4b2', 1, 1, 1, 1, '2026-11-05', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(249, 'df261668-1287-4728-b5df-0a38107bdbb7', 1, 1, 1, 1, '2026-11-09', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(250, '3cdbad23-bbcf-411b-8ea6-0a3427924bb0', 1, 1, 1, 1, '2026-11-12', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(251, 'e6676cb0-e97f-4ca3-a952-0ce5ad7b9d59', 1, 1, 1, 1, '2026-11-16', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(252, 'cae7f44f-e61a-49d1-abbc-17fda767891f', 1, 1, 1, 1, '2026-11-19', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(253, '11cc33b0-f47a-41f0-a279-e5d954da5f17', 1, 1, 1, 1, '2026-11-23', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(254, '2012d04a-e0bf-42ee-a646-815c21a276d6', 1, 1, 1, 1, '2026-11-26', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(255, '1c08b944-a329-493b-bdab-ea2de19f5fb1', 1, 1, 1, 1, '2026-11-30', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(256, '42220af1-ec54-42cb-8420-427d30e8372f', 1, 1, 1, 1, '2026-12-03', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(257, '7db4156b-5f0f-4bf1-a788-f7fcd770737d', 1, 1, 1, 1, '2026-12-07', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(258, 'b9227ae0-faef-4405-a821-b11da19ffba3', 1, 1, 1, 1, '2026-12-10', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL);
INSERT INTO `schedules` (`id`, `uuid`, `route_id`, `vehicle_id`, `driver_id`, `weekly_schedule_plan_id`, `departure_date`, `departure_time`, `arrival_time`, `fare`, `available_seats`, `status`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(259, '94b1c300-e530-405e-b0cb-d61019001dae', 1, 1, 1, 1, '2026-12-14', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(260, 'da9f319a-6733-4958-a96b-6c2b7b94029d', 1, 1, 1, 1, '2026-12-17', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(261, 'c0a5b742-5c79-4435-afd1-3f4b393d58d9', 1, 1, 1, 1, '2026-12-21', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(262, 'a6f28dcc-0753-4926-b769-f67e4cca2955', 1, 1, 1, 1, '2026-12-24', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(263, 'ab1fb6e6-a29d-4dbf-84d3-5c919617275f', 1, 1, 1, 1, '2026-12-28', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(264, '4b154165-1ba4-422d-b4b8-68be45051d50', 1, 1, 1, 1, '2026-12-31', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(265, '88b939ff-4552-4670-b2c8-216a62162ca6', 1, 1, 1, 1, '2027-01-04', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(266, '7fcbfb57-2ca4-4f7c-b1a8-903f500ad48b', 1, 1, 1, 1, '2027-01-07', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(267, 'cee227f2-f152-4f5a-9fad-fa688336e2d4', 1, 1, 1, 1, '2027-01-11', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(268, 'd2351b2e-8218-4a65-886a-57d25d2ddb8f', 1, 1, 1, 1, '2027-01-14', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(269, '2c0926b0-7a6f-4e09-bd9e-bd591b857709', 1, 1, 1, 1, '2027-01-18', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(270, '23603d3b-a32a-4419-8d92-6cbeeec68db6', 1, 1, 1, 1, '2027-01-21', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(271, 'dcbe25f8-db5a-4809-b456-fbef7428f6db', 1, 1, 1, 1, '2027-01-25', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(272, '0a60d5cd-c0a4-493d-bd62-47ea38373c8f', 1, 1, 1, 1, '2027-01-28', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(273, '81d7cc5e-7c5a-4f1e-9c8b-5aa35389c025', 1, 1, 1, 1, '2027-02-01', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(274, '0163cedc-f982-4566-b1d6-4194bb297ff4', 1, 1, 1, 1, '2027-02-04', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(275, '82e9d8c2-5dfb-4f58-a7e3-e7eb73419caa', 1, 1, 1, 1, '2027-02-08', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(276, '05798f56-2e58-47ec-acdb-e7749a781227', 1, 1, 1, 1, '2027-02-11', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(277, '55ec4381-ae7e-4d9d-80dd-74d17c0b32cf', 1, 1, 1, 1, '2027-02-15', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(278, '24dab94e-b21b-4712-bc31-8c96a561bc9f', 1, 1, 1, 1, '2027-02-18', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(279, '70cc2c6a-020b-460d-a777-fb26fd84f05e', 1, 1, 1, 1, '2027-02-22', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(280, 'fdc34432-f34f-4670-a0a6-a5dbf5115991', 1, 1, 1, 1, '2027-02-25', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(281, '0afac4b8-b9e1-4a37-a5b1-5c035142adfa', 1, 1, 1, 1, '2027-03-01', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(282, '077ae90a-e30c-4eae-aead-8001a1cd48b6', 1, 1, 1, 1, '2027-03-04', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(283, '9b0f89ec-9cca-40a2-a1c2-de45441963c9', 1, 1, 1, 1, '2027-03-08', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(284, '1a47bb3a-51ca-4f53-b02e-86e2a0dbc2e5', 1, 1, 1, 1, '2027-03-11', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(285, '20016e83-20de-420e-9ce3-7b165d4a891e', 1, 1, 1, 1, '2027-03-15', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(286, '37c2dc41-eb44-4197-8d50-7ee3f2ca093d', 1, 1, 1, 1, '2027-03-18', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(287, '571d12f1-16e6-4f6e-b81d-1e555ef8b328', 1, 1, 1, 1, '2027-03-22', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(288, '15334a8e-744e-4a7e-89cf-db30cdd4cae0', 1, 1, 1, 1, '2027-03-25', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(289, 'e892e9a8-9990-4034-8939-bccd1db338af', 1, 1, 1, 1, '2027-03-29', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(290, '0b02131c-92bc-450f-bbee-4beab6403266', 1, 1, 1, 1, '2027-04-01', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(291, 'c2e90d3a-b61f-48b2-9a34-f2147fa028e1', 1, 1, 1, 1, '2027-04-05', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(292, '300cef26-4bc5-4bab-a0e2-84c6a0ae44fe', 1, 1, 1, 1, '2027-04-08', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(293, '7a7876c5-04dc-4690-95fe-e21d57751371', 1, 1, 1, 1, '2027-04-12', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(294, '054ebd93-4201-4723-9608-517d053dc15d', 1, 1, 1, 1, '2027-04-15', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(295, '95e32619-684a-4bf4-856d-f0eb34966911', 1, 1, 1, 1, '2027-04-19', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(296, 'b3cb3368-3114-49fb-a76c-bdd6ece31b54', 1, 1, 1, 1, '2027-04-22', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(297, 'db8d15d8-6890-4eac-923c-1425e944de96', 1, 1, 1, 1, '2027-04-26', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(298, 'e184b281-2e5d-4746-82a8-3f1f6ecba5de', 1, 1, 1, 1, '2027-04-29', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(299, '6a3d161a-9fab-4356-8526-25094e751787', 1, 1, 1, 1, '2027-05-03', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(300, '95f33625-22bd-49ad-98da-36a26d244c0e', 1, 1, 1, 1, '2027-05-06', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(301, '16403542-ba4c-4520-86ff-1d5da07b43db', 1, 1, 1, 1, '2027-05-10', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(302, '3caeca2e-fabc-46ad-9993-6ff82176bcc0', 1, 1, 1, 1, '2027-05-13', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(303, 'e2418bed-c4ba-4c43-b64b-199dc5ef4b19', 1, 1, 1, 1, '2027-05-17', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(304, '76f430c0-42d4-474b-9d22-cd1a494444c3', 1, 1, 1, 1, '2027-05-20', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(305, 'e9e09acb-d636-4a97-b961-d5b78a9d5c97', 1, 1, 1, 1, '2027-05-24', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(306, '23132636-0ff1-4050-a841-f313b171b97f', 1, 1, 1, 1, '2027-05-27', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(307, 'f190a844-9b58-4a7d-bcf3-cd601f144fc5', 1, 1, 1, 1, '2027-05-31', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(308, 'e5a2d975-2569-4524-ace3-f500012d51ae', 1, 1, 1, 1, '2027-06-03', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(309, '0846466c-75e0-4857-8629-0e4c56f1de2a', 1, 1, 1, 1, '2027-06-07', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(310, 'ee59eae5-a151-4925-9ed8-304e6e4834b6', 1, 1, 1, 1, '2027-06-10', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(311, 'a871b4d7-86e7-4a05-99a2-d7670303938e', 1, 1, 1, 1, '2027-06-14', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(312, '08ffbed6-499d-4843-9ced-5f46c51ad949', 1, 1, 1, 1, '2027-06-17', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(313, '19df856d-b924-4194-9778-31e2e29218a7', 1, 1, 1, 1, '2027-06-21', '19:30:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL),
(314, '48b95de7-7dcc-4e00-9653-f183c8ba78a5', 1, 1, 1, 1, '2027-06-24', '20:00:00', '22:00:00', 1000.00, 14, 'scheduled', NULL, '2026-06-25 09:43:56', '2026-06-25 09:43:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `schedule_conductor`
--

DROP TABLE IF EXISTS `schedule_conductor`;
CREATE TABLE IF NOT EXISTS `schedule_conductor` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `schedule_id` bigint UNSIGNED NOT NULL,
  `conductor_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `schedule_conductor_schedule_id_conductor_id_unique` (`schedule_id`,`conductor_id`),
  KEY `schedule_conductor_conductor_id_foreign` (`conductor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

DROP TABLE IF EXISTS `seats`;
CREATE TABLE IF NOT EXISTS `seats` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `seat_map_id` bigint UNSIGNED NOT NULL,
  `seat_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `row` tinyint UNSIGNED NOT NULL,
  `column` tinyint UNSIGNED NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'seater',
  `fare_amount` decimal(10,2) DEFAULT NULL,
  `fare_multiplier` decimal(4,2) NOT NULL DEFAULT '1.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seats_seat_map_id_seat_number_unique` (`seat_map_id`,`seat_number`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`id`, `seat_map_id`, `seat_number`, `row`, `column`, `type`, `fare_amount`, `fare_multiplier`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, '1', 1, 1, 'luxury', 3500.00, 1.00, 1, '2026-06-25 09:30:41', '2026-06-25 09:30:41'),
(2, 1, '2', 1, 3, 'normal', 2000.00, 1.00, 1, '2026-06-25 09:30:41', '2026-06-25 09:30:41'),
(3, 1, '3', 2, 1, 'normal', 2000.00, 1.00, 1, '2026-06-25 09:30:41', '2026-06-25 09:30:41'),
(4, 1, '4', 2, 2, 'normal', 2000.00, 1.00, 1, '2026-06-25 09:30:41', '2026-06-25 09:30:41'),
(5, 1, '5', 2, 4, 'normal', 2000.00, 1.00, 1, '2026-06-25 09:30:41', '2026-06-25 09:30:41'),
(6, 1, '6', 2, 5, 'normal', 2000.00, 1.00, 1, '2026-06-25 09:30:41', '2026-06-25 09:30:41'),
(7, 1, '7', 3, 1, 'normal', 2000.00, 1.00, 1, '2026-06-25 09:30:41', '2026-06-25 09:30:41'),
(8, 1, '8', 3, 2, 'normal', 2000.00, 1.00, 1, '2026-06-25 09:30:41', '2026-06-25 09:30:41'),
(9, 1, '9', 3, 4, 'normal', 2000.00, 1.00, 1, '2026-06-25 09:30:41', '2026-06-25 09:30:41'),
(10, 1, '10', 3, 5, 'normal', 2000.00, 1.00, 1, '2026-06-25 09:30:41', '2026-06-25 09:30:41'),
(11, 1, '11', 4, 1, 'normal', 2000.00, 1.00, 1, '2026-06-25 09:30:41', '2026-06-25 09:30:41'),
(12, 1, '12', 4, 2, 'normal', 2000.00, 1.00, 1, '2026-06-25 09:30:41', '2026-06-25 09:30:41'),
(13, 1, '13', 4, 4, 'normal', 2000.00, 1.00, 1, '2026-06-25 09:30:41', '2026-06-25 09:30:41'),
(14, 1, '14', 4, 5, 'normal', 2000.00, 1.00, 1, '2026-06-25 09:30:41', '2026-06-25 09:30:41');

-- --------------------------------------------------------

--
-- Table structure for table `seat_holds`
--

DROP TABLE IF EXISTS `seat_holds`;
CREATE TABLE IF NOT EXISTS `seat_holds` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `schedule_id` bigint UNSIGNED NOT NULL,
  `seat_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seat_holds_schedule_id_seat_id_unique` (`schedule_id`,`seat_id`),
  KEY `seat_holds_seat_id_foreign` (`seat_id`),
  KEY `seat_holds_user_id_foreign` (`user_id`),
  KEY `seat_holds_session_id_index` (`session_id`),
  KEY `seat_holds_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seat_maps`
--

DROP TABLE IF EXISTS `seat_maps`;
CREATE TABLE IF NOT EXISTS `seat_maps` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicle_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Default',
  `rows` tinyint UNSIGNED NOT NULL,
  `columns` tinyint UNSIGNED NOT NULL,
  `layout` json NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `seat_maps_vehicle_id_foreign` (`vehicle_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seat_maps`
--

INSERT INTO `seat_maps` (`id`, `vehicle_id`, `name`, `rows`, `columns`, `layout`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Default', 4, 5, '{\"rows\": [{\"row\": 1, \"left\": 1, \"right\": 1, \"left_type\": \"luxury\", \"right_type\": \"normal\"}, {\"row\": 2, \"left\": 2, \"right\": 2, \"left_type\": \"normal\", \"right_type\": \"normal\"}, {\"row\": 3, \"left\": 2, \"right\": 2, \"left_type\": \"normal\", \"right_type\": \"normal\"}, {\"row\": 4, \"left\": 2, \"right\": 2, \"left_type\": \"normal\", \"right_type\": \"normal\"}], \"type\": \"row_aisle\", \"luxury_fare\": 3500, \"normal_fare\": 2000}', 1, '2026-06-25 09:30:41', '2026-06-25 09:30:41');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7FEVFEP7txXWx4ICfTmxIwOPWGLlTBfoJvU8SKQr', 1, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicHRJdmF2YXlUYmVvUFRnZGNDaTJodjF6WUlmMEtYM1k1Nk9yUmpOWSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ib29rL2U2ZDJlNWE0LTBkMjItNDY3MS04YzViLTFjY2ZkOGEyODdjYi9zZWF0cyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1782408196),
('jtmQLhuSoO2YAXASpd6ZShDB3OZ6kM7G8lx83zy0', 1, '127.0.0.1', 'curl/8.7.1', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibmZoUTNIakM5TTI3bUVrcW5VSjBvQzREeVB3MDh2eVg0M2p4SWE5ayI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9zY2hlZHVsZS1wbGFucy8xL2VkaXQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1782398195),
('mVjF9jEI9QPq3W6r3IZ8s6QiF8lFh7oUBLKqVXCj', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoickZqRjJDNkZwRVl2bUtVcjRzdkZ1dEh0SHFCY3p5OU9VMU9SVWdVTiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo3MzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL2Jvb2tpbmdzLzY2MTkwNGNiLTUyYjgtNGMwMi05NTRiLWEzZjUxOGRhMDRlYyI7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjI3OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1782444249),
('OoQFydozYmV0lM7S9ZhE6giH6zrWElaTcVkZv52e', NULL, '127.0.0.1', 'curl/8.7.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYVRERzd0MjkxR1dTaFI3NkJXSEdvaUFqbVZkYmVXNlRMTHdxMWVZViI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1782398071),
('tuPhfjzkmIyQWorePcp9UHWsB0JHon4eS23mLEhI', NULL, '127.0.0.1', 'curl/8.7.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRWwwa214TkJrMTRwY1A3WEpvdXZlelFtQVRYU1dlanVCZDlpcGIzYSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1782398078);

-- --------------------------------------------------------

--
-- Table structure for table `terminals`
--

DROP TABLE IF EXISTS `terminals`;
CREATE TABLE IF NOT EXISTS `terminals` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `owner_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` smallint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `terminals_slug_unique` (`slug`),
  KEY `terminals_city_index` (`city`),
  KEY `terminals_owner_id_foreign` (`owner_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `terminals`
--

INSERT INTO `terminals` (`id`, `owner_id`, `name`, `slug`, `city`, `address`, `phone`, `email`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 4, 'Swat', 'zain-ul-basit', 'Swat', 'Mingora Swat', '03235205035', 'swat@gmail.com', 1, 0, '2026-06-25 09:07:31', '2026-06-25 09:08:59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `terminal_id` bigint UNSIGNED DEFAULT NULL,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cnic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `locale` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `theme` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'light',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `last_login_ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_uuid_unique` (`uuid`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_phone_index` (`phone`),
  KEY `users_cnic_index` (`cnic`),
  KEY `users_terminal_id_foreign` (`terminal_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `terminal_id`, `uuid`, `name`, `email`, `phone`, `cnic`, `email_verified_at`, `phone_verified_at`, `password`, `avatar`, `locale`, `theme`, `is_active`, `last_login_at`, `last_login_ip`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 'c4109797-bb2d-4f47-bb4b-8c5b49a6daab', 'Super Admin', 'admin@bssbooking.com', '03001234567', NULL, NULL, NULL, '$2y$10$Dl4ajnMs/vDEpsw8CSHj1.y1Sn9thQSamfA.4pPmCc1DRhPonJ412', NULL, 'en', 'light', 1, '2026-06-25 11:26:26', '127.0.0.1', NULL, '2026-06-09 11:11:12', '2026-06-25 11:26:26', NULL),
(4, NULL, '328241b8-7962-4612-8ba6-bde1f6fe5671', 'Swat Travels', 'swat@gmail.com', '03123456789', NULL, NULL, NULL, '$2y$10$Dl4ajnMs/vDEpsw8CSHj1.y1Sn9thQSamfA.4pPmCc1DRhPonJ412', NULL, 'en', 'light', 1, '2026-06-25 09:08:51', '127.0.0.1', NULL, '2026-06-25 09:07:31', '2026-06-25 09:08:51', NULL),
(5, 1, 'b6c294b1-57b9-4d74-b073-aa767b41a4f8', 'Niaz', 'niaz@gmail.com', '03987654321', NULL, NULL, NULL, '$2y$10$Dl4ajnMs/vDEpsw8CSHj1.y1Sn9thQSamfA.4pPmCc1DRhPonJ412', NULL, 'en', 'light', 1, '2026-06-25 09:09:39', '127.0.0.1', NULL, '2026-06-25 09:09:25', '2026-06-25 09:09:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

DROP TABLE IF EXISTS `vehicles`;
CREATE TABLE IF NOT EXISTS `vehicles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bus_stand_id` bigint UNSIGNED NOT NULL,
  `vehicle_category_id` bigint UNSIGNED DEFAULT NULL,
  `driver_id` bigint UNSIGNED DEFAULT NULL,
  `owner_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `owner_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bus_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `registration_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_seats` smallint UNSIGNED NOT NULL,
  `bus_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_ac` tinyint(1) NOT NULL DEFAULT '0',
  `luxury_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `amenities` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicles_uuid_unique` (`uuid`),
  UNIQUE KEY `vehicles_registration_number_unique` (`registration_number`),
  KEY `vehicles_bus_stand_id_foreign` (`bus_stand_id`),
  KEY `vehicles_vehicle_category_id_foreign` (`vehicle_category_id`),
  KEY `vehicles_driver_id_foreign` (`driver_id`),
  KEY `vehicles_bus_number_index` (`bus_number`),
  KEY `vehicles_is_active_index` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `uuid`, `bus_stand_id`, `vehicle_category_id`, `driver_id`, `owner_name`, `owner_phone`, `name`, `bus_number`, `registration_number`, `total_seats`, `bus_type`, `is_ac`, `luxury_type`, `is_active`, `amenities`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '250e65a7-9ef5-46ed-a9fd-8eeaffe5d329', 1, NULL, 1, 'Zain', '03119876543', 'Metro Express', '1234567', '1234567', 14, 'standard', 1, NULL, 1, NULL, '2026-06-25 09:30:41', '2026-06-25 09:30:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_categories`
--

DROP TABLE IF EXISTS `vehicle_categories`;
CREATE TABLE IF NOT EXISTS `vehicle_categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicle_categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_conductor`
--

DROP TABLE IF EXISTS `vehicle_conductor`;
CREATE TABLE IF NOT EXISTS `vehicle_conductor` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicle_id` bigint UNSIGNED NOT NULL,
  `conductor_id` bigint UNSIGNED NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vehicle_conductor_vehicle_id_conductor_id_unique` (`vehicle_id`,`conductor_id`),
  KEY `vehicle_conductor_conductor_id_foreign` (`conductor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicle_conductor`
--

INSERT INTO `vehicle_conductor` (`id`, `vehicle_id`, `conductor_id`, `is_primary`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2026-06-25 09:30:41', '2026-06-25 09:30:41');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_maintenance_logs`
--

DROP TABLE IF EXISTS `vehicle_maintenance_logs`;
CREATE TABLE IF NOT EXISTS `vehicle_maintenance_logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `vehicle_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `maintenance_date` date NOT NULL,
  `next_due_date` date DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vehicle_maintenance_logs_vehicle_id_foreign` (`vehicle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_tracking`
--

DROP TABLE IF EXISTS `vehicle_tracking`;
CREATE TABLE IF NOT EXISTS `vehicle_tracking` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `schedule_id` bigint UNSIGNED NOT NULL,
  `vehicle_id` bigint UNSIGNED NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `speed` decimal(6,2) DEFAULT NULL,
  `eta_minutes` int UNSIGNED DEFAULT NULL,
  `recorded_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vehicle_tracking_vehicle_id_foreign` (`vehicle_id`),
  KEY `vehicle_tracking_schedule_id_recorded_at_index` (`schedule_id`,`recorded_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `weekly_schedule_days`
--

DROP TABLE IF EXISTS `weekly_schedule_days`;
CREATE TABLE IF NOT EXISTS `weekly_schedule_days` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `weekly_schedule_plan_id` bigint UNSIGNED NOT NULL,
  `day_of_week` tinyint UNSIGNED NOT NULL,
  `departure_time` time NOT NULL,
  `arrival_time` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `weekly_schedule_days_weekly_schedule_plan_id_day_of_week_unique` (`weekly_schedule_plan_id`,`day_of_week`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `weekly_schedule_days`
--

INSERT INTO `weekly_schedule_days` (`id`, `weekly_schedule_plan_id`, `day_of_week`, `departure_time`, `arrival_time`, `created_at`, `updated_at`) VALUES
(5, 1, 1, '19:30:00', '22:00:00', '2026-06-25 09:43:56', '2026-06-25 09:43:56'),
(6, 1, 4, '20:00:00', '22:00:00', '2026-06-25 09:43:56', '2026-06-25 09:43:56');

-- --------------------------------------------------------

--
-- Table structure for table `weekly_schedule_plans`
--

DROP TABLE IF EXISTS `weekly_schedule_plans`;
CREATE TABLE IF NOT EXISTS `weekly_schedule_plans` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `route_id` bigint UNSIGNED NOT NULL,
  `vehicle_id` bigint UNSIGNED NOT NULL,
  `driver_id` bigint UNSIGNED DEFAULT NULL,
  `fare` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `weekly_schedule_plans_route_id_vehicle_id_unique` (`route_id`,`vehicle_id`),
  KEY `weekly_schedule_plans_vehicle_id_foreign` (`vehicle_id`),
  KEY `weekly_schedule_plans_driver_id_foreign` (`driver_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `weekly_schedule_plans`
--

INSERT INTO `weekly_schedule_plans` (`id`, `route_id`, `vehicle_id`, `driver_id`, `fare`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1000.00, 1, '2026-06-25 09:31:30', '2026-06-25 09:31:30');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_booked_by_foreign` FOREIGN KEY (`booked_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `booking_passengers`
--
ALTER TABLE `booking_passengers`
  ADD CONSTRAINT `booking_passengers_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_passengers_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `booking_passengers_seat_id_foreign` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_passenger_cancellations`
--
ALTER TABLE `booking_passenger_cancellations`
  ADD CONSTRAINT `booking_passenger_cancellations_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_passenger_cancellations_booking_passenger_id_foreign` FOREIGN KEY (`booking_passenger_id`) REFERENCES `booking_passengers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_passenger_cancellations_cancelled_by_foreign` FOREIGN KEY (`cancelled_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_passenger_cancellations_refund_id_foreign` FOREIGN KEY (`refund_id`) REFERENCES `refunds` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `booking_passenger_cancellations_seat_id_foreign` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `bus_stands`
--
ALTER TABLE `bus_stands`
  ADD CONSTRAINT `bus_stands_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bus_stands_terminal_id_foreign` FOREIGN KEY (`terminal_id`) REFERENCES `terminals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bus_stand_staff`
--
ALTER TABLE `bus_stand_staff`
  ADD CONSTRAINT `bus_stand_staff_bus_stand_id_foreign` FOREIGN KEY (`bus_stand_id`) REFERENCES `bus_stands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bus_stand_staff_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bus_stand_user`
--
ALTER TABLE `bus_stand_user`
  ADD CONSTRAINT `bus_stand_user_bus_stand_id_foreign` FOREIGN KEY (`bus_stand_id`) REFERENCES `bus_stands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bus_stand_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `complaints_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `conductors`
--
ALTER TABLE `conductors`
  ADD CONSTRAINT `conductors_bus_stand_id_foreign` FOREIGN KEY (`bus_stand_id`) REFERENCES `bus_stands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `conductors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `conductor_attendance`
--
ALTER TABLE `conductor_attendance`
  ADD CONSTRAINT `conductor_attendance_conductor_id_foreign` FOREIGN KEY (`conductor_id`) REFERENCES `conductors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `drivers`
--
ALTER TABLE `drivers`
  ADD CONSTRAINT `drivers_bus_stand_id_foreign` FOREIGN KEY (`bus_stand_id`) REFERENCES `bus_stands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `drivers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `loyalty_points`
--
ALTER TABLE `loyalty_points`
  ADD CONSTRAINT `loyalty_points_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `referrals`
--
ALTER TABLE `referrals`
  ADD CONSTRAINT `referrals_referred_id_foreign` FOREIGN KEY (`referred_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `referrals_referrer_id_foreign` FOREIGN KEY (`referrer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `refunds`
--
ALTER TABLE `refunds`
  ADD CONSTRAINT `refunds_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `refunds_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `routes`
--
ALTER TABLE `routes`
  ADD CONSTRAINT `routes_bus_stand_id_foreign` FOREIGN KEY (`bus_stand_id`) REFERENCES `bus_stands` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `route_stops`
--
ALTER TABLE `route_stops`
  ADD CONSTRAINT `route_stops_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `schedules_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedules_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedules_weekly_schedule_plan_id_foreign` FOREIGN KEY (`weekly_schedule_plan_id`) REFERENCES `weekly_schedule_plans` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `schedule_conductor`
--
ALTER TABLE `schedule_conductor`
  ADD CONSTRAINT `schedule_conductor_conductor_id_foreign` FOREIGN KEY (`conductor_id`) REFERENCES `conductors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedule_conductor_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `seats`
--
ALTER TABLE `seats`
  ADD CONSTRAINT `seats_seat_map_id_foreign` FOREIGN KEY (`seat_map_id`) REFERENCES `seat_maps` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `seat_holds`
--
ALTER TABLE `seat_holds`
  ADD CONSTRAINT `seat_holds_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `seat_holds_seat_id_foreign` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `seat_holds_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `seat_maps`
--
ALTER TABLE `seat_maps`
  ADD CONSTRAINT `seat_maps_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `terminals`
--
ALTER TABLE `terminals`
  ADD CONSTRAINT `terminals_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_terminal_id_foreign` FOREIGN KEY (`terminal_id`) REFERENCES `terminals` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_bus_stand_id_foreign` FOREIGN KEY (`bus_stand_id`) REFERENCES `bus_stands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vehicles_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vehicles_vehicle_category_id_foreign` FOREIGN KEY (`vehicle_category_id`) REFERENCES `vehicle_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vehicle_conductor`
--
ALTER TABLE `vehicle_conductor`
  ADD CONSTRAINT `vehicle_conductor_conductor_id_foreign` FOREIGN KEY (`conductor_id`) REFERENCES `conductors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vehicle_conductor_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicle_maintenance_logs`
--
ALTER TABLE `vehicle_maintenance_logs`
  ADD CONSTRAINT `vehicle_maintenance_logs_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vehicle_tracking`
--
ALTER TABLE `vehicle_tracking`
  ADD CONSTRAINT `vehicle_tracking_schedule_id_foreign` FOREIGN KEY (`schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vehicle_tracking_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `weekly_schedule_days`
--
ALTER TABLE `weekly_schedule_days`
  ADD CONSTRAINT `weekly_schedule_days_weekly_schedule_plan_id_foreign` FOREIGN KEY (`weekly_schedule_plan_id`) REFERENCES `weekly_schedule_plans` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `weekly_schedule_plans`
--
ALTER TABLE `weekly_schedule_plans`
  ADD CONSTRAINT `weekly_schedule_plans_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `drivers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `weekly_schedule_plans_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `weekly_schedule_plans_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
