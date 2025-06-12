-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 11, 2025 at 05:20 PM
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
-- Database: `mysebenarnya`
--

-- --------------------------------------------------------

--
-- Table structure for table `agency`
--

CREATE TABLE `agency` (
  `A_ID` varchar(7) NOT NULL,
  `A_Name` varchar(50) DEFAULT NULL,
  `A_userName` varchar(10) NOT NULL,
  `A_Address` varchar(225) DEFAULT NULL,
  `A_Email` varchar(50) DEFAULT NULL,
  `A_PhoneNum` int(11) DEFAULT NULL,
  `A_Category` varchar(50) DEFAULT NULL,
  `A_ProfilePicture` varchar(50) DEFAULT NULL,
  `A_Password` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `complaint`
--

CREATE TABLE `complaint` (
  `C_ID` varchar(7) NOT NULL,
  `I_ID` varchar(7) DEFAULT NULL,
  `A_ID` varchar(7) DEFAULT NULL,
  `M_ID` varchar(7) DEFAULT NULL,
  `C_AssignedDate` date DEFAULT NULL,
  `C_Comment` text DEFAULT NULL,
  `C_History` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `inquiry`
--

CREATE TABLE `inquiry` (
  `I_ID` varchar(7) NOT NULL,
  `PU_ID` varchar(7) DEFAULT NULL,
  `I_Title` varchar(255) DEFAULT NULL,
  `I_Description` text DEFAULT NULL,
  `I_Category` varchar(50) DEFAULT NULL,
  `I_Date` date DEFAULT NULL,
  `I_Status` varchar(50) DEFAULT NULL,
  `I_Source` varchar(255) DEFAULT NULL,
  `I_filename` varchar(255) DEFAULT NULL,
  `InfoPath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `mcmc`
--

CREATE TABLE `mcmc` (
  `M_ID` varchar(7) NOT NULL,
  `M_Name` varchar(50) DEFAULT NULL,
  `M_userName` varchar(10) NOT NULL,
  `M_Address` varchar(225) DEFAULT NULL,
  `M_Email` varchar(50) DEFAULT NULL,
  `M_PhoneNum` int(11) DEFAULT NULL,
  `M_Position` varchar(50) DEFAULT NULL,
  `M_Password` varchar(10) DEFAULT NULL,
  `M_ProfilePicture` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `progress`
--

CREATE TABLE `progress` (
  `P_ID` varchar(7) NOT NULL,
  `I_ID` varchar(7) DEFAULT NULL,
  `A_ID` varchar(7) DEFAULT NULL,
  `P_Status` varchar(10) DEFAULT NULL,
  `P_Timestamp` datetime DEFAULT NULL,
  `P_Notes` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `publicuser`
--

CREATE TABLE `publicuser` (
  `PU_ID` varchar(7) NOT NULL,
  `PU_Name` varchar(255) DEFAULT NULL,
  `PU_IC` int(11) DEFAULT NULL,
  `PU_Age` int(11) DEFAULT NULL,
  `PU_Address` varchar(255) DEFAULT NULL,
  `PU_Email` varchar(50) DEFAULT NULL,
  `PU_PhoneNum` int(11) DEFAULT NULL,
  `PU_Gender` varchar(10) DEFAULT NULL,
  `PU_Password` varchar(10) DEFAULT NULL,
  `PU_ProfilePicture` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `R_ID` varchar(7) NOT NULL,
  `A_ID` varchar(7) DEFAULT NULL,
  `M_ID` varchar(7) DEFAULT NULL,
  `I_ID` varchar(7) DEFAULT NULL,
  `P_ID` varchar(7) DEFAULT NULL,
  `C_ID` varchar(7) DEFAULT NULL,
  `R_title` varchar(50) DEFAULT NULL,
  `R_date` datetime DEFAULT NULL,
  `R_timeStamp` datetime DEFAULT NULL,
  `R_agency` varchar(50) DEFAULT NULL,
  `R_category` varchar(50) DEFAULT NULL,
  `R_format` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
('pXdaiXsM1mRT7GtgphBWu6xl0R0ckFotIdvGfYHF', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiUEZmSkpjWTFja3VjN1JaOVhrT09ybUFyUEk2bVo5TURHT1ozQTI5ZCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ob21lIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjQ6ImF1dGgiO2E6MTp7czoyMToicGFzc3dvcmRfY29uZmlybWVkX2F0IjtpOjE3NDczMDIzNjU7fX0=', 1747302641);

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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Arin', 'ninjaago4@gmail.com', NULL, '$2y$12$OlafYPJfGUmbHB.o7QA7DeEJWUaxR4AGvw0PCjkqi1KMd56us7WFa', '0JGUT3u3QsnNLKGfSFrMWf8BDI4vxubGWuRIpmtlGLQg0fOL5phL2qaEUoVT', '2025-05-15 01:45:31', '2025-05-15 01:45:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agency`
--
ALTER TABLE `agency`
  ADD PRIMARY KEY (`A_ID`);

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
-- Indexes for table `complaint`
--
ALTER TABLE `complaint`
  ADD PRIMARY KEY (`C_ID`),
  ADD KEY `I_ID` (`I_ID`),
  ADD KEY `A_ID` (`A_ID`),
  ADD KEY `M_ID` (`M_ID`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `inquiry`
--
ALTER TABLE `inquiry`
  ADD PRIMARY KEY (`I_ID`),
  ADD KEY `PU_ID` (`PU_ID`);

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
-- Indexes for table `mcmc`
--
ALTER TABLE `mcmc`
  ADD PRIMARY KEY (`M_ID`);

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
-- Indexes for table `progress`
--
ALTER TABLE `progress`
  ADD PRIMARY KEY (`P_ID`),
  ADD KEY `I_ID` (`I_ID`),
  ADD KEY `A_ID` (`A_ID`);

--
-- Indexes for table `publicuser`
--
ALTER TABLE `publicuser`
  ADD PRIMARY KEY (`PU_ID`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`R_ID`),
  ADD KEY `A_ID` (`A_ID`),
  ADD KEY `M_ID` (`M_ID`),
  ADD KEY `I_ID` (`I_ID`),
  ADD KEY `P_ID` (`P_ID`),
  ADD KEY `C_ID` (`C_ID`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

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
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaint`
--
ALTER TABLE `complaint`
  ADD CONSTRAINT `complaint_ibfk_1` FOREIGN KEY (`I_ID`) REFERENCES `inquiry` (`I_ID`),
  ADD CONSTRAINT `complaint_ibfk_2` FOREIGN KEY (`A_ID`) REFERENCES `agency` (`A_ID`),
  ADD CONSTRAINT `complaint_ibfk_3` FOREIGN KEY (`M_ID`) REFERENCES `mcmc` (`M_ID`);

--
-- Constraints for table `inquiry`
--
ALTER TABLE `inquiry`
  ADD CONSTRAINT `inquiry_ibfk_1` FOREIGN KEY (`PU_ID`) REFERENCES `publicuser` (`PU_ID`);

--
-- Constraints for table `progress`
--
ALTER TABLE `progress`
  ADD CONSTRAINT `progress_ibfk_1` FOREIGN KEY (`I_ID`) REFERENCES `inquiry` (`I_ID`),
  ADD CONSTRAINT `progress_ibfk_2` FOREIGN KEY (`A_ID`) REFERENCES `agency` (`A_ID`);

--
-- Constraints for table `report`
--
ALTER TABLE `report`
  ADD CONSTRAINT `report_ibfk_1` FOREIGN KEY (`A_ID`) REFERENCES `agency` (`A_ID`),
  ADD CONSTRAINT `report_ibfk_2` FOREIGN KEY (`M_ID`) REFERENCES `mcmc` (`M_ID`),
  ADD CONSTRAINT `report_ibfk_3` FOREIGN KEY (`I_ID`) REFERENCES `inquiry` (`I_ID`),
  ADD CONSTRAINT `report_ibfk_4` FOREIGN KEY (`P_ID`) REFERENCES `progress` (`P_ID`),
  ADD CONSTRAINT `report_ibfk_5` FOREIGN KEY (`C_ID`) REFERENCES `complaint` (`C_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
