-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 05, 2020 at 05:55 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fyp_db_proto`
--

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(10) NOT NULL,
  `username` varchar(15) NOT NULL,
  `email` varchar(64) NOT NULL,
  `password` varchar(255) NOT NULL,
  `create_time` varchar(10) NOT NULL,
  `acc_level` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `email`, `password`, `create_time`, `acc_level`) VALUES
(1, 'Admin', 'webmaster@thissite.com', '$2y$10$V1qAnpp217pzaNtGOsE7sOo4vsG7DHWtO3VlXHklXj5jOkXncb1u.', '06/01/2020', 3),
(2, 'user', 'user@webmail.com', '$2y$10$ehG0jflBN1.D6Nvo5mgmfeCSzkaKkzmaoMditBARLmwYbSSDIHbnC', '06/01/2020', 1),
(3, 'iixCarbonxZz', 'iixcarbonxzz@gmail.com', '$2y$10$E./OywIdK2BcRd7UT6NGROzUbIvb5Ghde7amA22.p3FNOxbvpl2ha', '05/03/2020', 1),
(4, 'test', 'a@a.a', '$2y$10$LktSYIb9BT4bBjJHU5M2DuTQINRxyn0WvwGst6iOoJbMAtmMzkWjq', '05/03/2020', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
