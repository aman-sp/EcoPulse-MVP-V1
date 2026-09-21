-- phpMyAdmin SQL Dump
-- EcoPulse MVP V1 — Database Schema
-- Server version: 10.4.32-MariaDB / MySQL 8.0+

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecopulse_db`
--
CREATE DATABASE IF NOT EXISTS `ecopulse_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `ecopulse_db`;

-- --------------------------------------------------------

--
-- Drop existing tables in reverse dependency order
--
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `activity_logs`;
DROP TABLE IF EXISTS `generated_reports`;
DROP TABLE IF EXISTS `recommendations`;
DROP TABLE IF EXISTS `carbon_results`;
DROP TABLE IF EXISTS `renewable_energy_logs`;
DROP TABLE IF EXISTS `transportation_logs`;
DROP TABLE IF EXISTS `medical_gases`;
DROP TABLE IF EXISTS `biomedical_waste_logs`;
DROP TABLE IF EXISTS `diesel_logs`;
DROP TABLE IF EXISTS `water_logs`;
DROP TABLE IF EXISTS `electricity_logs`;
DROP TABLE IF EXISTS `monthly_submissions`;
DROP TABLE IF EXISTS `hospital_profile`;
DROP TABLE IF EXISTS `hospital_users`;
DROP TABLE IF EXISTS `emission_factors`;
DROP TABLE IF EXISTS `hospitals`;
DROP TABLE IF EXISTS `admins`;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `avatar` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hospitals`
--

CREATE TABLE `hospitals` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(200) NOT NULL,
  `registration_number` VARCHAR(100) NOT NULL UNIQUE,
  `hospital_type` ENUM('Government','Private','Trust','Corporate','Military') NOT NULL,
  `ownership` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `address` TEXT NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `district` VARCHAR(100) NOT NULL,
  `city` VARCHAR(100) NOT NULL,
  `pin` VARCHAR(10) NOT NULL,
  `beds` INT DEFAULT 0,
  `buildings` INT DEFAULT 1,
  `floors` INT DEFAULT 1,
  `departments` INT DEFAULT 1,
  `solar_installed` TINYINT(1) DEFAULT 0,
  `stp_installed` TINYINT(1) DEFAULT 0,
  `dg_sets` INT DEFAULT 0,
  `nabh_status` ENUM('Accredited','Not Accredited','In Process') DEFAULT 'Not Accredited',
  `status` ENUM('active','suspended','inactive') DEFAULT 'active',
  `logo` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hospital_users`
--

CREATE TABLE `hospital_users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `hospital_id` INT NOT NULL,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `email` VARCHAR(150) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(20) NULL,
  `designation` VARCHAR(100) NULL,
  `status` ENUM('active','inactive') DEFAULT 'active',
  `last_login` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`hospital_id`) REFERENCES `hospitals`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hospital_profile`
--

CREATE TABLE `hospital_profile` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `hospital_id` INT NOT NULL UNIQUE,
  `ot_count` INT DEFAULT 0,
  `icu_count` INT DEFAULT 0,
  `lab_count` INT DEFAULT 0,
  `rainwater_harvesting` TINYINT(1) DEFAULT 0,
  `solar_capacity_kw` DECIMAL(10,2) DEFAULT 0,
  `stp_capacity_kld` DECIMAL(10,2) DEFAULT 0,
  `dg_capacity_kva` DECIMAL(10,2) DEFAULT 0,
  `total_staff` INT DEFAULT 0,
  `avg_daily_patients` INT DEFAULT 0,
  `avg_daily_opd` INT DEFAULT 0,
  `avg_daily_ipd` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`hospital_id`) REFERENCES `hospitals`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monthly_submissions`
--

CREATE TABLE `monthly_submissions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `hospital_id` INT NOT NULL,
  `month` DATE NOT NULL,
  `status` ENUM('draft','submitted','approved','rejected') DEFAULT 'draft',
  `submitted_at` TIMESTAMP NULL,
  `current_step` INT DEFAULT 1,
  `notes` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `unique_hospital_month` (`hospital_id`, `month`),
  FOREIGN KEY (`hospital_id`) REFERENCES `hospitals`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `electricity_logs`
--

CREATE TABLE `electricity_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `submission_id` INT NOT NULL,
  `units_consumed` DECIMAL(12,2) DEFAULT 0,
  `bill_amount` DECIMAL(12,2) DEFAULT 0,
  `grid_percentage` DECIMAL(5,2) DEFAULT 100,
  `renewable_percentage` DECIMAL(5,2) DEFAULT 0,
  `bill_file_path` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`submission_id`) REFERENCES `monthly_submissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `water_logs`
--

CREATE TABLE `water_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `submission_id` INT NOT NULL,
  `municipal_kl` DECIMAL(10,2) DEFAULT 0,
  `borewell_kl` DECIMAL(10,2) DEFAULT 0,
  `tanker_kl` DECIMAL(10,2) DEFAULT 0,
  `recycled_kl` DECIMAL(10,2) DEFAULT 0,
  `bill_upload` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`submission_id`) REFERENCES `monthly_submissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `diesel_logs`
--

CREATE TABLE `diesel_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `submission_id` INT NOT NULL,
  `generator_hours` DECIMAL(10,2) DEFAULT 0,
  `diesel_purchased` DECIMAL(10,2) DEFAULT 0,
  `diesel_used` DECIMAL(10,2) DEFAULT 0,
  `invoice_file_path` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`submission_id`) REFERENCES `monthly_submissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `biomedical_waste_logs`
--

CREATE TABLE `biomedical_waste_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `submission_id` INT NOT NULL,
  `yellow_kg` DECIMAL(10,2) DEFAULT 0,
  `red_kg` DECIMAL(10,2) DEFAULT 0,
  `white_kg` DECIMAL(10,2) DEFAULT 0,
  `blue_kg` DECIMAL(10,2) DEFAULT 0,
  `general_kg` DECIMAL(10,2) DEFAULT 0,
  `recycled_kg` DECIMAL(10,2) DEFAULT 0,
  `vendor_name` VARCHAR(200) NULL,
  `manifest_file_path` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`submission_id`) REFERENCES `monthly_submissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_gases`
--

CREATE TABLE `medical_gases` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `submission_id` INT NOT NULL,
  `oxygen_cylinders` INT DEFAULT 0,
  `oxygen_volume_m3` DECIMAL(10,2) DEFAULT 0,
  `nitrous_oxide_cylinders` INT DEFAULT 0,
  `nitrous_oxide_volume_m3` DECIMAL(10,2) DEFAULT 0,
  `anaesthetic_gas_kg` DECIMAL(10,2) DEFAULT 0,
  `supplier_name` VARCHAR(200) NULL,
  `invoice_file_path` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`submission_id`) REFERENCES `monthly_submissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transportation_logs`
--

CREATE TABLE `transportation_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `submission_id` INT NOT NULL,
  `ambulance_count` INT DEFAULT 0,
  `diesel_vehicles` INT DEFAULT 0,
  `petrol_vehicles` INT DEFAULT 0,
  `total_distance_km` DECIMAL(10,2) DEFAULT 0,
  `electric_vehicles` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`submission_id`) REFERENCES `monthly_submissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `renewable_energy_logs`
--

CREATE TABLE `renewable_energy_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `submission_id` INT NOT NULL,
  `solar_generated_kwh` DECIMAL(12,2) DEFAULT 0,
  `solar_used_kwh` DECIMAL(12,2) DEFAULT 0,
  `battery_storage_kwh` DECIMAL(12,2) DEFAULT 0,
  `grid_offset_kwh` DECIMAL(12,2) DEFAULT 0,
  `renewable_percentage` DECIMAL(5,2) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`submission_id`) REFERENCES `monthly_submissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emission_factors`
--

CREATE TABLE `emission_factors` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category` VARCHAR(100) NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `unit` VARCHAR(50) NOT NULL,
  `factor` DECIMAL(15,6) NOT NULL,
  `version` VARCHAR(20) DEFAULT '1.0',
  `source` VARCHAR(255) NULL,
  `effective_date` DATE NOT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_category_active` (`category`, `is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carbon_results`
--

CREATE TABLE `carbon_results` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `submission_id` INT NOT NULL,
  `scope1_diesel` DECIMAL(12,4) DEFAULT 0,
  `scope1_medical_gas` DECIMAL(12,4) DEFAULT 0,
  `scope1_refrigerant` DECIMAL(12,4) DEFAULT 0,
  `scope1_transport` DECIMAL(12,4) DEFAULT 0,
  `scope1_total` DECIMAL(12,4) DEFAULT 0,
  `scope2_electricity` DECIMAL(12,4) DEFAULT 0,
  `scope2_total` DECIMAL(12,4) DEFAULT 0,
  `total_co2e` DECIMAL(12,4) DEFAULT 0,
  `co2e_per_bed` DECIMAL(12,4) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`submission_id`) REFERENCES `monthly_submissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recommendations`
--

CREATE TABLE `recommendations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `submission_id` INT NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `recommendation` TEXT NOT NULL,
  `severity` ENUM('low','medium','high') NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`submission_id`) REFERENCES `monthly_submissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `generated_reports`
--

CREATE TABLE `generated_reports` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `hospital_id` INT NOT NULL,
  `submission_id` INT NOT NULL,
  `report_type` VARCHAR(50) DEFAULT 'sustainability',
  `file_path` VARCHAR(255) NOT NULL,
  `file_name` VARCHAR(255) NOT NULL,
  `generated_by` VARCHAR(50) DEFAULT 'system',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`hospital_id`) REFERENCES `hospitals`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`submission_id`) REFERENCES `monthly_submissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_type` ENUM('admin','hospital') NOT NULL,
  `user_id` INT NOT NULL,
  `action` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `ip_address` VARCHAR(45) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_user_type_id` (`user_type`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_type` ENUM('admin','hospital') NOT NULL,
  `user_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_user_read` (`user_type`, `user_id`, `is_read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) NOT NULL UNIQUE,
  `setting_value` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`) VALUES
(1, 'Super Admin', 'admin@ecopulse.in', '$2y$10$E9kARS.91EomRE.IXNgX4e8ET.dPOX/loyOTcrLyU7YfR6ZYbPgKy');

-- --------------------------------------------------------

--
-- Dumping data for table `hospitals`
--

INSERT INTO `hospitals` (`id`, `name`, `registration_number`, `hospital_type`, `ownership`, `email`, `phone`, `address`, `state`, `district`, `city`, `pin`, `beds`, `buildings`, `floors`, `departments`, `solar_installed`, `stp_installed`, `dg_sets`, `nabh_status`, `status`) VALUES
(1, 'City General Hospital', 'HOS-MH-2024-001', 'Government', 'State Government', 'info@citygeneralhospital.in', '022-12345678', '123 Health Road, Andheri East', 'Maharashtra', 'Mumbai Suburban', 'Mumbai', '400069', 250, 3, 5, 12, 1, 1, 2, 'Accredited', 'active');

-- --------------------------------------------------------

--
-- Dumping data for table `hospital_users`
--

INSERT INTO `hospital_users` (`id`, `hospital_id`, `username`, `email`, `password`, `name`, `phone`, `designation`, `status`) VALUES
(1, 1, 'citygenhospital', 'demo@hospital.in', '$2y$10$tIPzdfeG7MXfjskVm0QESOhrxRDiuAZfIH/Wwk8Shhbcf3SdiTnAS', 'Dr. Priya Sharma', '9876543210', 'Sustainability Officer', 'active');

-- --------------------------------------------------------

--
-- Dumping data for table `hospital_profile`
--

INSERT INTO `hospital_profile` (`id`, `hospital_id`, `ot_count`, `icu_count`, `lab_count`, `rainwater_harvesting`, `solar_capacity_kw`, `stp_capacity_kld`, `dg_capacity_kva`, `total_staff`, `avg_daily_patients`, `avg_daily_opd`, `avg_daily_ipd`) VALUES
(1, 1, 8, 4, 3, 1, 50.00, 100.00, 500.00, 450, 800, 600, 200);

-- --------------------------------------------------------

--
-- Dumping data for table `emission_factors`
--

INSERT INTO `emission_factors` (`category`, `name`, `unit`, `factor`, `version`, `source`, `effective_date`) VALUES
('electricity', 'Grid Electricity (India)', 'kgCO2/kWh', 0.820000, '2024', 'CEA CO2 Baseline Database, Ministry of Power, India', '2024-01-01'),
('diesel', 'Diesel Combustion', 'kgCO2/liter', 2.680000, '2024', 'Indian fuel combustion defaults / IPCC reference methodology', '2024-01-01'),
('petrol', 'Petrol Combustion', 'kgCO2/liter', 2.310000, '2024', 'Indian fuel combustion defaults / IPCC reference methodology', '2024-01-01'),
('water', 'Municipal Water Supply', 'kgCO2/kL', 0.344000, '2024', 'Water Supply Carbon Factors, India', '2024-01-01'),
('biomedical_waste', 'Biomedical Waste Incineration', 'kgCO2/kg', 0.500000, '2024', 'CPCB Guidelines 2023', '2024-01-01'),
('medical_gas_oxygen', 'Medical Oxygen Production', 'kgCO2/m3', 0.520000, '2024', 'Industrial Gas Association / Indian healthcare operations standard', '2024-01-01'),
('medical_gas_n2o', 'Nitrous Oxide', 'kgCO2e/kg', 265.000000, '2024', 'IPCC AR5 GWP (India reporting reference)', '2024-01-01'),
('medical_gas_anaesthetic', 'Anaesthetic Gas (Desflurane)', 'kgCO2e/kg', 2540.000000, '2024', 'NHS / IPCC reference for inhalation anaesthetics', '2024-01-01'),
('refrigerant', 'HFC-134a Refrigerant', 'kgCO2e/kg', 1430.000000, '2024', 'IPCC AR5 GWP (India reporting reference)', '2024-01-01');

-- --------------------------------------------------------

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('app_name', 'EcoPulse'),
('app_tagline', 'Healthcare Sustainability Management'),
('app_version', '1.0.0'),
('footer_text', '© 2024 EcoPulse. All rights reserved.'),
('session_timeout', '3600'),
('max_upload_size', '10485760'),
('allowed_file_types', 'pdf,png,jpg,jpeg');

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
