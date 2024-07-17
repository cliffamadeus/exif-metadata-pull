-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2024 at 02:37 PM
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
-- Database: `dev_exif`
--

-- --------------------------------------------------------

--
-- Table structure for table `records`
--

CREATE TABLE `records` (
  `record_id` int(11) NOT NULL,
  `record_lat` varchar(255) NOT NULL,
  `record_lon` varchar(255) NOT NULL,
  `record_date` datetime DEFAULT NULL,
  `record_loc` varchar(50) NOT NULL,
  `record_temp` varchar(50) NOT NULL,
  `record_weather` varchar(50) NOT NULL,
  `record_hum` varchar(10) NOT NULL,
  `record_wind_speed` varchar(10) NOT NULL,
  `record_created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `records`
--

INSERT INTO `records` (`record_id`, `record_lat`, `record_lon`, `record_date`, `record_loc`, `record_temp`, `record_weather`, `record_hum`, `record_wind_speed`, `record_created_at`) VALUES
(1, '8.293577777777777', '125.02520277777778', '2023-06-28 07:29:00', 'La Fortuna', '21.05 °C', 'overcast clouds', '100%', '0.47 m/s', '2024-07-17 20:30:58'),
(2, '7.889169444444444', '125.04601666666666', '2023-08-19 06:57:59', 'Dologon', '21.49 °C', 'light rain', '98%', '0.14 m/s', '2024-07-17 20:35:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `records`
--
ALTER TABLE `records`
  ADD PRIMARY KEY (`record_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `records`
--
ALTER TABLE `records`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
