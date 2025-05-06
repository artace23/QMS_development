-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 06, 2025 at 03:44 AM
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
-- Database: `queueing`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`` PROCEDURE `DisplayPendingLogs` ()   SELECT *
        FROM manange_queue mq
        INNER JOIN window w ON w.transaction_id = mq.transaction_id
        WHERE mq.status = 2
        AND DATE(mq.timestamp) = CURDATE()$$

CREATE DEFINER=`` PROCEDURE `DisplayQueueLogs` ()   BEGIN
    SELECT *
    FROM manange_queue
    WHERE status = 1
    AND DATE(timestamp) = CURDATE();
END$$

CREATE DEFINER=`` PROCEDURE `GetLatestQueueNo` (IN `p_transaction_id` INT, OUT `p_latest_queue_no` INT, OUT `p_is_last_range` INT)   BEGIN
    -- Declare variables
    DECLARE p_is_last_range BOOLEAN;

    -- Get the latest queue number
    SELECT queue_no + 1 INTO p_latest_queue_no
    FROM manange_queue
    WHERE transaction_id = p_transaction_id
    	AND DATE(timestamp) = CURRENT_DATE
    ORDER BY queue_no DESC
    LIMIT 1;

    -- Check if it's equal to the last range of the queue
    SELECT p_latest_queue_no - 1 = q.queue_lrange INTO p_is_last_range
    FROM queue q WHERE q.transaction_id = p_transaction_id;

    -- If it's the last range, reset to the starting range
    IF p_is_last_range THEN
        -- Set p_latest_queue_no to the starting range from the queue table
        SELECT queue_srange + 1 INTO p_latest_queue_no
        FROM queue
        WHERE transaction_id = p_transaction_id
        ORDER BY queue_srange
        LIMIT 1;

    ELSEIF p_latest_queue_no IS NULL THEN
        -- Handle the case where p_latest_queue_no is NULL
        SELECT queue_srange + 1 INTO p_latest_queue_no
        FROM queue
        WHERE transaction_id = p_transaction_id
        LIMIT 1;
    END IF;

END$$

CREATE DEFINER=`` PROCEDURE `GetPendingLogs` (IN `p_userData` INT, IN `p_userId` INT)   BEGIN
    -- Retrieve data from manage_queue table
    SELECT *
    FROM manange_queue
    WHERE transaction_id = p_userData
      AND status = 2
      AND DATE(timestamp) = DATE(NOW())
      AND user_id = p_userId;
END$$

CREATE DEFINER=`` PROCEDURE `GetQueueLogs` (IN `p_userData` INT)   BEGIN
    SELECT * FROM manange_queue 
    WHERE transaction_id = p_userData
    AND status = 1
    AND DATE(timestamp) = DATE(NOW());
END$$

CREATE DEFINER=`` PROCEDURE `InsertIntoWindow` (IN `p_window_no` INT, IN `p_transaction_id` INT)   BEGIN
    INSERT INTO `window` (`window_no`, `transaction_id`)
    VALUES (p_window_no, p_transaction_id);
END$$

CREATE DEFINER=`` PROCEDURE `InsertTransaction` (IN `p_transaction_name` VARCHAR(255), IN `p_srange` INT, IN `p_lrange` INT)   BEGIN
    -- Insert a new transaction with the provided name
    INSERT INTO `transaction` (`transaction_name`) VALUES (p_transaction_name);

    -- Insert into queue table
    INSERT INTO queue (queue_srange, queue_lrange, transaction_id)
    VALUES (p_srange, p_lrange, LAST_INSERT_ID());
END$$

CREATE DEFINER=`` PROCEDURE `UpdateQueueStatus` (IN `p_queue_no` INT, IN `p_userId` INT)   BEGIN
    -- Update the status of the queue number specified
    UPDATE manange_queue
    SET status = '2',
        user_id = p_userId
    WHERE queue_no = p_queue_no AND DATE(timestamp) = DATE(CURRENT_DATE());
END$$

CREATE DEFINER=`` PROCEDURE `UpdateQueueStatusDone` (IN `p_queue_no` INT)   BEGIN
    -- Update the status of the queue number specified
    UPDATE manange_queue
    SET status = '3'
    WHERE queue_no = p_queue_no;
END$$

CREATE DEFINER=`` PROCEDURE `UpdateTransaction` (IN `p_transaction_id` INT, IN `p_transaction_name` VARCHAR(255), IN `p_srange` INT, IN `p_lrange` INT)   BEGIN
    -- Update transaction table
    UPDATE `transaction` 
    SET `transaction_name` = p_transaction_name 
    WHERE `transaction_id` = p_transaction_id;

    -- Update queue table
    UPDATE `queue` 
    SET `queue_srange` = p_srange, 
        `queue_lrange` = p_lrange
    WHERE `transaction_id` = p_transaction_id;
END$$

CREATE DEFINER=`` PROCEDURE `UpdateUser` (IN `p_id` INT, IN `p_name` VARCHAR(255), IN `p_email` VARCHAR(255), IN `p_transaction_id` VARCHAR(255))   BEGIN
    UPDATE users
    SET 
        name = p_name,
        email = p_email,
        transaction_id = p_transaction_id
    WHERE id = p_id;
END$$

CREATE DEFINER=`` PROCEDURE `UpdateWindow` (IN `p_window_id` INT, IN `p_window_no` INT, IN `p_transaction_id` INT)   UPDATE `window` 
SET 
`window_no`=p_window_no,
`transaction_id`=p_transaction_id 
WHERE 
`window_id` = p_window_id$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `manange_queue`
--

CREATE TABLE `manange_queue` (
  `manange_id` int(11) NOT NULL,
  `queue_no` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manange_queue`
--

INSERT INTO `manange_queue` (`manange_id`, `queue_no`, `transaction_id`, `timestamp`, `status`, `user_id`) VALUES
(1, 1001, 1, '2024-04-06 06:00:55', 3, 2),
(2, 1002, 1, '2024-04-06 06:03:14', 3, 2),
(3, 1003, 1, '2024-04-05 05:18:09', 3, 2),
(4, 1004, 1, '2024-04-05 05:18:40', 3, 2),
(5, 2000, 2, '2024-03-31 12:36:51', 1, NULL),
(6, 2001, 2, '2024-04-05 07:47:06', 3, 3),
(7, 1005, 1, '2024-04-05 05:25:27', 3, 2),
(8, 1001, 1, '2024-04-06 06:00:55', 3, 2),
(9, 1002, 1, '2024-04-06 06:03:14', 3, 2),
(10, 1001, 1, '2024-04-06 06:00:55', 3, 2),
(11, 1002, 1, '2024-04-06 06:03:14', 3, 2),
(12, 1003, 1, '2024-04-05 05:18:09', 3, 2),
(13, 1004, 1, '2024-04-05 05:18:40', 3, 2),
(14, 1005, 1, '2024-04-05 05:25:27', 3, 2),
(15, 1006, 1, '2024-04-05 05:25:31', 3, 2),
(16, 1007, 1, '2024-04-05 05:26:04', 3, 2),
(17, 1008, 1, '2024-04-05 05:27:22', 3, 2),
(18, 1009, 1, '2024-04-05 05:29:08', 3, 2),
(19, 1010, 1, '2024-04-05 05:29:27', 3, 2),
(20, 1011, 1, '2024-04-05 05:34:35', 3, 2),
(21, 1012, 1, '2024-04-05 05:36:20', 3, 2),
(22, 1013, 1, '2024-04-05 05:38:09', 3, 2),
(23, 1014, 1, '2024-04-05 05:40:13', 3, 2),
(24, 1015, 1, '2024-04-05 05:40:57', 3, 2),
(25, 1016, 1, '2024-04-05 05:44:14', 3, 2),
(26, 1017, 1, '2024-04-05 05:45:14', 3, 2),
(27, 1001, 1, '2024-04-06 06:00:55', 3, 2),
(28, 1002, 1, '2024-04-06 06:03:14', 3, 2),
(29, 1003, 1, '2024-04-05 05:18:09', 3, 2),
(30, 1001, 1, '2024-04-06 06:00:55', 3, 2),
(31, 1002, 1, '2024-04-06 06:03:14', 3, 2),
(32, 1003, 1, '2024-04-05 05:18:09', 3, 2),
(33, 1004, 1, '2024-04-05 05:18:40', 3, 2),
(34, 1001, 1, '2024-04-06 06:00:55', 3, 2),
(35, 1002, 1, '2024-04-06 06:03:14', 3, 2),
(36, 1001, 1, '2024-04-06 06:00:55', 3, 2),
(37, 1002, 1, '2024-04-06 06:03:14', 3, 2),
(38, 1003, 1, '2024-04-05 05:18:09', 3, 2),
(39, 1004, 1, '2024-04-05 05:18:40', 3, 2),
(40, 1005, 1, '2024-04-05 05:25:27', 3, 2),
(41, 1006, 1, '2024-04-05 05:25:31', 3, 2),
(42, 1007, 1, '2024-04-05 05:26:04', 3, 2),
(43, 1008, 1, '2024-04-05 05:27:22', 3, 2),
(44, 1009, 1, '2024-04-05 05:29:08', 3, 2),
(45, 1010, 1, '2024-04-05 05:29:27', 3, 2),
(46, 1011, 1, '2024-04-05 05:34:35', 3, 2),
(47, 1012, 1, '2024-04-05 05:36:20', 3, 2),
(48, 1013, 1, '2024-04-05 05:38:09', 3, 2),
(49, 1014, 1, '2024-04-05 05:40:13', 3, 2),
(50, 1015, 1, '2024-04-05 05:40:57', 3, 2),
(51, 1016, 1, '2024-04-05 05:44:14', 3, 2),
(52, 1017, 1, '2024-04-05 05:45:14', 3, 2),
(53, 1018, 1, '2024-04-05 05:45:48', 3, 2),
(54, 1001, 1, '2024-04-06 06:00:55', 3, 2),
(55, 1002, 1, '2024-04-06 06:03:14', 3, 2),
(56, 1003, 1, '2024-04-05 05:18:09', 3, 2),
(57, 1004, 1, '2024-04-05 05:18:40', 3, 2),
(58, 1005, 1, '2024-04-05 05:25:27', 3, 2),
(59, 1006, 1, '2024-04-05 05:25:31', 3, 2),
(60, 1007, 1, '2024-04-05 05:26:04', 3, 2),
(61, 1008, 1, '2024-04-05 05:27:22', 3, 2),
(62, 1009, 1, '2024-04-05 05:29:08', 3, 2),
(63, 1010, 1, '2024-04-05 05:29:27', 3, 2),
(64, 1011, 1, '2024-04-05 05:34:35', 3, 2),
(65, 1012, 1, '2024-04-05 05:36:20', 3, 2),
(66, 1013, 1, '2024-04-05 05:38:09', 3, 2),
(67, 1014, 1, '2024-04-05 05:40:13', 3, 2),
(68, 1015, 1, '2024-04-05 05:40:57', 3, 2),
(69, 1016, 1, '2024-04-05 05:44:14', 3, 2),
(70, 1017, 1, '2024-04-05 05:45:14', 3, 2),
(71, 1018, 1, '2024-04-05 05:45:48', 3, 2),
(72, 1019, 1, '2024-04-05 05:46:49', 3, 2),
(73, 1020, 1, '2024-04-05 05:47:05', 3, 2),
(74, 1021, 1, '2024-04-05 05:48:09', 3, 2),
(75, 1022, 1, '2024-04-05 05:49:12', 3, 2),
(76, 1023, 1, '2024-04-05 05:59:13', 3, 2),
(77, 1024, 1, '2024-04-05 05:59:16', 3, 2),
(78, 1025, 1, '2024-04-05 06:00:03', 3, 2),
(79, 1026, 1, '2024-04-05 06:15:50', 3, 2),
(80, 1027, 1, '2024-04-05 06:19:19', 3, 2),
(81, 1028, 1, '2024-04-05 06:46:49', 3, 2),
(82, 1029, 1, '2024-04-05 07:10:34', 3, 2),
(83, 1030, 1, '2024-04-05 07:11:06', 3, 2),
(84, 1031, 1, '2024-04-05 07:11:48', 3, 2),
(85, 1032, 1, '2024-04-05 07:16:06', 3, 2),
(86, 1033, 1, '2024-04-05 07:18:03', 3, 2),
(87, 1034, 1, '2024-04-05 07:18:36', 3, 2),
(88, 1035, 1, '2024-04-05 07:29:46', 3, 2),
(89, 1036, 1, '2024-04-05 07:31:50', 3, 2),
(90, 1037, 1, '2024-04-05 07:34:21', 3, 2),
(91, 1038, 1, '2024-04-05 07:36:44', 3, 2),
(92, 1039, 1, '2024-04-05 07:38:30', 3, 2),
(93, 1040, 1, '2024-04-05 07:40:40', 3, 2),
(94, 1041, 1, '2024-04-05 07:44:08', 3, 2),
(95, 1042, 1, '2024-04-05 07:44:34', 3, 2),
(96, 1043, 1, '2024-04-05 07:44:57', 3, 2),
(97, 1044, 1, '2024-04-05 07:46:40', 3, 2),
(98, 2001, 2, '2024-04-05 07:47:06', 3, 3),
(99, 1045, 1, '2024-04-05 08:06:24', 3, 2),
(100, 2002, 2, '2024-04-05 08:06:07', 3, 3),
(101, 2003, 2, '2024-04-05 08:08:16', 3, 3),
(102, 1046, 1, '2024-04-05 08:08:38', 3, 2),
(103, 2004, 2, '2024-04-05 08:10:21', 3, 3),
(104, 1047, 1, '2024-04-05 08:09:55', 3, 2),
(105, 1048, 1, '2024-04-05 08:10:37', 3, 2),
(106, 2005, 2, '2024-04-05 08:10:56', 3, 3),
(107, 1049, 1, '2024-04-05 08:11:47', 3, 2),
(108, 2006, 2, '2024-04-05 08:11:43', 3, 3),
(109, 1050, 1, '2024-04-05 08:12:38', 3, 2),
(110, 2007, 2, '2024-04-05 08:13:24', 3, 3),
(111, 2008, 2, '2024-04-05 08:13:44', 3, 3),
(112, 2009, 2, '2024-04-05 08:17:39', 3, 3),
(113, 1051, 1, '2024-04-05 08:18:42', 3, 2),
(114, 2010, 2, '2024-04-05 08:18:47', 3, 3),
(115, 1052, 1, '2024-04-05 08:24:32', 3, 2),
(116, 2011, 2, '2024-04-05 08:24:35', 3, 3),
(117, 2012, 2, '2024-04-05 08:28:32', 3, 3),
(118, 1001, 1, '2024-04-06 06:00:55', 3, 2),
(119, 1002, 1, '2024-04-06 06:03:14', 3, 2),
(120, 1003, 1, '2024-04-06 06:04:17', 3, 2),
(121, 1004, 1, '2024-04-06 14:44:03', 3, 2),
(122, 1005, 1, '2024-04-06 14:44:23', 3, 2),
(123, 1006, 1, '2024-04-06 14:48:27', 3, 2),
(124, 2001, 2, '2024-04-06 14:46:09', 3, 3),
(125, 1007, 1, '2024-04-06 14:49:24', 3, 2),
(126, 1008, 1, '2024-04-06 14:49:33', 3, 2),
(127, 1009, 1, '2024-04-06 15:59:45', 3, 5),
(128, 1010, 1, '2024-04-06 15:59:49', 3, 5),
(129, 1001, 1, '2024-04-06 16:05:00', 3, 5),
(130, 1002, 1, '2024-04-06 16:05:14', 3, 5),
(131, 1003, 1, '2024-04-06 16:07:25', 3, 2),
(132, 1004, 1, '2024-04-06 16:07:33', 3, 2),
(133, 1005, 1, '2024-04-06 16:09:02', 3, 2),
(134, 1006, 1, '2024-04-06 16:09:13', 3, 5),
(135, 1007, 1, '2024-04-06 16:12:18', 3, 5),
(136, 1008, 1, '2024-04-06 16:12:28', 3, 2),
(137, 1009, 1, '2024-04-06 16:12:32', 3, 5),
(138, 1001, 1, '2024-04-09 11:46:53', 3, 2),
(139, 1002, 1, '2024-04-09 11:47:07', 3, 2),
(140, 1003, 1, '2024-04-09 11:47:25', 3, 2),
(141, 1004, 1, '2024-04-09 11:48:28', 3, 2),
(142, 1005, 1, '2024-04-09 11:48:23', 3, 5),
(143, 1001, 1, '2024-04-12 05:04:10', 3, 2),
(144, 1002, 1, '2024-04-12 05:06:29', 3, 2),
(145, 1003, 1, '2024-04-12 05:08:38', 3, 2),
(146, 1001, 1, '2024-04-24 07:41:18', 3, 2),
(147, 1002, 1, '2024-04-24 07:42:23', 3, 2),
(148, 1001, 1, '2025-05-06 01:43:15', 1, NULL);

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1);

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
-- Table structure for table `queue`
--

CREATE TABLE `queue` (
  `queue_id` int(11) NOT NULL,
  `queue_srange` int(11) NOT NULL,
  `queue_lrange` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `queue`
--

INSERT INTO `queue` (`queue_id`, `queue_srange`, `queue_lrange`, `transaction_id`) VALUES
(1, 1000, 1999, 1),
(2, 2000, 2999, 2),
(3, 3000, 3999, 3),
(4, 4000, 4999, 4);

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
('3E4MyyVRl7bxGnSTQK1pUim5fGcFzgsYRVVAwPah', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZVBsR1pHT2oxSFhzeWF1c2FER2pSSUZsdnZrM3cwN1kyTWxadElHMiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9mZXRjaC1jdXJyZW50LXNlcnZpbmciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O30=', 1746495863);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `sfname` varchar(255) NOT NULL,
  `slname` varchar(255) NOT NULL,
  `smname` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`staff_id`, `sfname`, `slname`, `smname`) VALUES
(1, 'Admin', 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

CREATE TABLE `transaction` (
  `transaction_id` int(11) NOT NULL,
  `transaction_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`transaction_id`, `transaction_name`) VALUES
(1, 'Finance'),
(2, 'Cashier'),
(3, 'Billing'),
(4, 'Statement of Account');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `transaction_id` int(11) NOT NULL,
  `window_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `transaction_id`, `window_id`) VALUES
(1, 'admin', 'admin', NULL, '$2y$12$qi.mrt3y8b/siisbr60Yt.kyKTcaI4OpGwf5jlcNQvKf0tXUcjahS', NULL, '2024-03-25 04:26:38', '2024-03-25 04:26:38', 0, 0),
(2, 'staff', 'staff', NULL, '$2y$12$FbxvoMMHtBMYW/XbBIUup.5ea.jQBRHnyX2IsCtT8kXPGoro2JHR.', NULL, '2024-03-25 05:28:07', '2024-03-25 05:28:07', 1, 1),
(3, 'staff2', 'staff2', NULL, '$2y$12$evwYMufz/ogniNrq82L5NexbuzwFRnltewP0xDY4lPy3mrAW.2BVq', NULL, '2024-03-25 06:06:52', '2024-03-25 06:06:52', 2, 2),
(4, 'staff3', 'staff3', NULL, '$2y$12$.LhthZko4bMwC02AtXpMQOf7.gAlF1bKC3LTxHjA9EDRHzNKE3kIm', NULL, '2024-03-31 03:04:14', '2024-03-31 03:04:14', 3, 3),
(5, 'staff1', 'staff1', NULL, '$2y$12$9Xzsp4BrzB2eFNmhl4TQEOniRJn8XJWjfirMfSM7r4OXbHnHu0kse', NULL, '2024-03-31 04:15:24', '2024-03-31 04:15:24', 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `window`
--

CREATE TABLE `window` (
  `window_id` int(11) NOT NULL,
  `window_no` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `window`
--

INSERT INTO `window` (`window_id`, `window_no`, `transaction_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 4),
(5, 5, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `manange_queue`
--
ALTER TABLE `manange_queue`
  ADD PRIMARY KEY (`manange_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `queue`
--
ALTER TABLE `queue`
  ADD PRIMARY KEY (`queue_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `window`
--
ALTER TABLE `window`
  ADD PRIMARY KEY (`window_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `manange_queue`
--
ALTER TABLE `manange_queue`
  MODIFY `manange_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `queue`
--
ALTER TABLE `queue`
  MODIFY `queue_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `window`
--
ALTER TABLE `window`
  MODIFY `window_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
