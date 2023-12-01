-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 30, 2023 at 05:50 PM
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
-- Database: `remote-med`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `Date` date NOT NULL,
  `Hour` int(24) NOT NULL,
  `d_id` int(100) NOT NULL,
  `p_id` int(100) NOT NULL,
  `appointment_id` int(100) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`Date`, `Hour`, `d_id`, `p_id`, `appointment_id`) VALUES
('2023-12-01', 12, 5, 5, 1),
('2023-12-13', 13, 0, 5, 4),
('2023-12-13', 10, 0, 5, 5),
('2023-12-13', 10, 0, 5, 6),
('2023-12-13', 9, 0, 5, 7),
('2023-11-24', 10, 4, 0, 8),
('2023-11-07', 17, 4, 5, 9),
('2023-11-16', 8, 5, 5, 10),
('2023-11-01', 13, 5, 5, 11),
('2024-02-08', 14, 5, 5, 12),
('2023-11-01', 9, 5, 5, 13),
('2023-11-02', 12, 5, 5, 14),
('2023-11-01', 17, 4, 5, 15),
('2023-11-01', 11, 4, 5, 16),
('2023-12-06', 11, 5, 5, 17),
('2023-12-06', 8, 5, 5, 18);

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `d_id` int(100) UNSIGNED NOT NULL,
  `Login` varchar(30) NOT NULL,
  `Password` varchar(32) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Surname` varchar(20) NOT NULL,
  `Gender` varchar(8) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Phone_number` varchar(12) NOT NULL,
  `Spec` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`d_id`, `Login`, `Password`, `Name`, `Surname`, `Gender`, `Email`, `Phone_number`, `Spec`) VALUES
(4, 'Alex228', '60279e147e2fd922e9e8d4084469f534', 'Oleksandr', 'Rotaienko', 'male', 'sasharotaenko1@gmail.com', '866262056', 'Cardiolog'),
(5, 'oleg18', '60279e147e2fd922e9e8d4084469f534', 'Olexii', 'Kurulyk', 'male', 'sasharotaenko1@gmail.com', '866262056', 'lor');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `p_id` int(100) UNSIGNED NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Password` varchar(32) NOT NULL,
  `Name` varchar(20) NOT NULL,
  `Surname` varchar(20) NOT NULL,
  `Date_of_birth` date NOT NULL,
  `Gender` varchar(8) NOT NULL,
  `Email` varchar(30) NOT NULL,
  `Phone_number` varchar(12) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`p_id`, `Username`, `Password`, `Name`, `Surname`, `Date_of_birth`, `Gender`, `Email`, `Phone_number`) VALUES
(13, 'sasha228', '60279e147e2fd922e9e8d4084469f534', 'Oleksandr', 'Rotaienko', '0000-00-00', 'male', 'sasharotaenko1@gmail.com', '866262056'),
(11, 'sasha', '60279e147e2fd922e9e8d4084469f534', 'Oleksandr', 'Rotaienko', '2004-02-18', 'male', 'sasharotaenko1@gmail.com', '866262056'),
(12, 'Sasha', '60279e147e2fd922e9e8d4084469f534', 'Oleksandr', 'Rotaienko', '2004-12-03', 'other', 'sasharotaenko1@gmail.com', '866262056'),
(9, 'sasha228', '60279e147e2fd922e9e8d4084469f534', 'Oleksandr', 'Rotaienko', '2004-12-03', 'male', 'sasharotaenko1@gmail.com', '866262056'),
(10, 'sasha222', '60279e147e2fd922e9e8d4084469f534', 'Oleksandr', 'Rotaienko', '2004-12-03', 'male', 'sasharotaenko1@gmail.com', '866262056');

-- --------------------------------------------------------

--
-- Table structure for table `patient_card`
--

CREATE TABLE `patient_card` (
  `p_id` int(100) NOT NULL,
  `Allergies` text NOT NULL,
  `Current_med` text NOT NULL,
  `Medical_history` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_card`
--

INSERT INTO `patient_card` (`p_id`, `Allergies`, `Current_med`, `Medical_history`) VALUES
(9, 'some ', 'some Current Medications:', 'some Medical History:'),
(11, '24іфавсчм', 'dfsdfsdf', 'sdfsdfsdsd'),
(13, ' sun', 'a lot', 'terrible history'),
(10, 'Penicillin\r\nShellfish\r\nPollen (seasonal allergy)', 'Daily multivitamin\r\nIbuprofen as needed for pain\r\nPrescription medication for hypertension', 'Previous surgery on the left knee in 2010\r\nAllergic reaction to penicillin in childhood\r\nAnnual check-ups with no significant issues');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointment_id`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD UNIQUE KEY `d_id` (`d_id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD UNIQUE KEY `id` (`p_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appointment_id` int(100) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `d_id` int(100) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `p_id` int(100) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
