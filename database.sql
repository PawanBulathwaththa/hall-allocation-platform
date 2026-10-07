-- ==============================================================
-- NIBM Hall & Lab Allocation Platform
-- Database Schema & Initial Seed Data
-- ==============================================================

CREATE DATABASE IF NOT EXISTS `allocation_platform` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `allocation_platform`;

-- --------------------------------------------------------------
-- Table: admin
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(191) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------------
-- Table: batches
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `batches` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(191) NOT NULL UNIQUE,
    `student_count` INT NOT NULL,
    `school` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------------
-- Table: halls
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `halls` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(191) NOT NULL UNIQUE,
    `capacity` INT NOT NULL,
    `floor` VARCHAR(100) NOT NULL,
    `type` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------------
-- Table: allocations
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `allocations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `hall_id` INT NOT NULL,
    `batch_id` INT NOT NULL,
    `allocation_date` DATE NOT NULL,
    `time_slot` VARCHAR(50) NOT NULL,
    CONSTRAINT `fk_allocations_hall` FOREIGN KEY (`hall_id`) REFERENCES `halls`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_allocations_batch` FOREIGN KEY (`batch_id`) REFERENCES `batches`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------------
-- Table: lab_allocations
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lab_allocations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `lab_name` VARCHAR(191) NOT NULL,
    `batch_name` VARCHAR(191) NOT NULL,
    `date` DATE NOT NULL,
    `time_slot` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------------
-- Table: lecturer
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lecturer` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `lecturer_name` VARCHAR(191) NOT NULL,
    `email` VARCHAR(191) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------------
-- Table: student
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `student` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_name` VARCHAR(191) NOT NULL,
    `batch` VARCHAR(191) NOT NULL,
    `email` VARCHAR(191) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------------
-- Table: lecturer_req
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `lecturer_req` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `lecturer_name` VARCHAR(191) NOT NULL,
    `lecturer_email` VARCHAR(191) NOT NULL,
    `message` TEXT NOT NULL,
    `is_read` TINYINT(1) DEFAULT 0,
    `req_date` DATE NOT NULL,
    `time` TIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------------
-- Table: assign_hall (Legacy / Auxiliary Support)
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `assign_hall` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `hall_name` VARCHAR(191) NOT NULL,
    `capacity` INT DEFAULT 0,
    `floor` VARCHAR(50) DEFAULT '1',
    `batch_name` VARCHAR(191) NOT NULL,
    `count` INT DEFAULT 0,
    `date` DATE NOT NULL,
    `hours` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==============================================================
-- Initial Seed Data
-- ==============================================================

-- Default Administrator (admin@nibm.lk / admin123)
INSERT INTO `admin` (`id`, `email`, `password`) VALUES
(1, 'admin@nibm.lk', 'admin123')
ON DUPLICATE KEY UPDATE `email` = VALUES(`email`);

-- Sample Batches
INSERT INTO `batches` (`id`, `name`, `student_count`, `school`) VALUES
(1, 'DCSD-23.1F', 45, 'computing'),
(2, 'DSE-23.2F', 30, 'computing'),
(3, 'DBM-23.1F', 55, 'business'),
(4, 'DISM-23.1F', 25, 'computing')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Sample Halls & Labs
INSERT INTO `halls` (`id`, `name`, `capacity`, `floor`, `type`) VALUES
(1, 'Lecture Hall 01', 60, '1st Floor', '1'),
(2, 'Lecture Hall 02', 40, '2nd Floor', '2'),
(3, 'Auditorium', 120, 'Ground Floor', '1'),
(4, 'Computer Lab 01', 35, '3rd Floor', '3'),
(5, 'Computer Lab 02', 30, '3rd Floor', '3')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);
