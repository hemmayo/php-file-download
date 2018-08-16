-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2018 at 01:12 AM
-- Server version: 5.7.23-0ubuntu0.18.04.1
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `file_upload`
--

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `size` bigint(20) NOT NULL,
  `type` varchar(15) NOT NULL,
  `location` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `name`, `size`, `type`, `location`, `created_at`) VALUES
(1, 'pic1', 2000000, 'jpg', '0018389', '2018-08-16 21:37:58');

-- --------------------------------------------------------

--
-- Table structure for table `temp_files`
--

CREATE TABLE `temp_files` (
  `id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `expire_at` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `temp_files`
--

INSERT INTO `temp_files` (`id`, `file_id`, `name`, `expire_at`, `created_at`) VALUES
(1, 1, '892y3r97ecuibsfgn4w', '2018-08-17 23:00:00', '2018-08-16 21:49:47'),
(3, 1, 'b00329eae74e6107f485278c05bf5e11', '1534458519', '2018-08-16 22:11:59'),
(4, 1, '543a91d31796b51002ae73a9d3519fe1-pic1', '1534459910', '2018-08-16 22:35:10'),
(5, 1, '050357ae5cfcc38d156708b2eac2d1ea-pic1', '1534459949', '2018-08-16 22:35:49'),
(6, 1, '5a715d650e750eb5923b820af5aa4550-pic1', '1534459963', '2018-08-16 22:36:03'),
(7, 1, '4049c8d767ef764110dd4051f7e5ae15-pic1', '1534460020', '2018-08-16 22:37:00'),
(8, 1, 'd933b8b655a668571dac96852304b412-pic1', '1534460070', '2018-08-16 22:37:50'),
(9, 1, '16b3ed9371e4cc46f527ea4711fc4f8f-pic1', '1534460084', '2018-08-16 22:38:04'),
(10, 1, '83f82517cd66b4822b3564b73c7f8b36-pic1', '1534460127', '2018-08-16 22:38:47'),
(11, 1, '8086017cf1783d19a8a8eabb79bd3506-pic1', '1534460148', '2018-08-16 22:39:08'),
(12, 1, '2ea5bb89e9a36a8c1c30298da194fa3a-pic1', '1534460158', '2018-08-16 22:39:18'),
(13, 1, '519b25f74fa2781f7f2858ad96d48451-pic1', '1534460566', '2018-08-16 22:46:06'),
(14, 1, 'b6e75a57056632b1d939608e4348a74e-pic1', '1534460571', '2018-08-16 22:46:11'),
(15, 1, '6620306c7565cfac03a4452ec07fd23e-pic1', '1534461561', '2018-08-16 23:02:41'),
(16, 1, '3c2020eda69e7f75741f0eb0e45afca5-pic1', '1534461599', '2018-08-16 23:03:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temp_files`
--
ALTER TABLE `temp_files`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `file_id` (`file_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `temp_files`
--
ALTER TABLE `temp_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `temp_files`
--
ALTER TABLE `temp_files`
  ADD CONSTRAINT `temp_file_ibfk_1` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
