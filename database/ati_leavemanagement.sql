-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2024 at 05:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ati_leavemanagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `EmployeeID` int(11) NOT NULL,
  `FullName` varchar(100) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `RoleID` int(11) DEFAULT NULL,
  `LeaveBalance` float DEFAULT 20,
  `IsActive` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`EmployeeID`, `FullName`, `Email`, `PasswordHash`, `RoleID`, `LeaveBalance`, `IsActive`) VALUES
(10, 'jana', 'janaththananlk2@gmail.com', '$2y$10$zVP4cP/zWtkn20di0TbvkOh9pcG2ojQAyiKV2iJs8725AOdL3ZYyK', 3, 0, 1),
(13, 'Perinparajah Thesihan', 'thesi@gmail.com', '$2y$10$2kx2mVSp3kNbNMAQWEt5Gutr5kaP/avsAGI3VaX3.LEfN3wKkMZ3.', 3, 0, 1),
(14, 'Jana ', 'jana@gmail.com', '$2y$10$ZDvjXMzE5BiU4Qwn7WIGmerQluJMJ84Plfac0rdfnLtFdkewS1OoC', 1, 12, 1),
(15, 'Dev', 'dev@gmail.com', '$2y$10$RWGjdEkDe6EBAIesavo5aOEm6IqFlJWR2G2rMi/9rgNktwn2Mz6Di', 1, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `leavepolicies`
--

CREATE TABLE `leavepolicies` (
  `PolicyID` int(11) NOT NULL,
  `RoleID` int(11) DEFAULT NULL,
  `LeaveType` varchar(50) NOT NULL,
  `LeaveEntitlement` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leavepolicies`
--

INSERT INTO `leavepolicies` (`PolicyID`, `RoleID`, `LeaveType`, `LeaveEntitlement`) VALUES
(1, 3, 'Annual Leave', 20),
(2, 3, 'Medical Leave', 15),
(4, 3, 'Short Leave', 5);

-- --------------------------------------------------------

--
-- Table structure for table `leaverequests`
--

CREATE TABLE `leaverequests` (
  `LeaveRequestID` int(11) NOT NULL,
  `EmployeeID` int(11) DEFAULT NULL,
  `LeaveType` varchar(50) DEFAULT NULL,
  `LeaveStartDate` date NOT NULL,
  `LeaveEndDate` date NOT NULL,
  `LeaveReason` text DEFAULT NULL,
  `Status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `ApprovedBy` int(11) DEFAULT NULL,
  `RequestedDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `userroles`
--

CREATE TABLE `userroles` (
  `RoleID` int(11) NOT NULL,
  `RoleName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userroles`
--

INSERT INTO `userroles` (`RoleID`, `RoleName`) VALUES
(3, 'Academic Staff'),
(1, 'Admin'),
(2, 'Director'),
(4, 'Non-Academic Staff');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`EmployeeID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD KEY `RoleID` (`RoleID`);

--
-- Indexes for table `leavepolicies`
--
ALTER TABLE `leavepolicies`
  ADD PRIMARY KEY (`PolicyID`),
  ADD KEY `RoleID` (`RoleID`);

--
-- Indexes for table `leaverequests`
--
ALTER TABLE `leaverequests`
  ADD PRIMARY KEY (`LeaveRequestID`),
  ADD KEY `EmployeeID` (`EmployeeID`),
  ADD KEY `ApprovedBy` (`ApprovedBy`);

--
-- Indexes for table `userroles`
--
ALTER TABLE `userroles`
  ADD PRIMARY KEY (`RoleID`),
  ADD UNIQUE KEY `RoleName` (`RoleName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `EmployeeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `leavepolicies`
--
ALTER TABLE `leavepolicies`
  MODIFY `PolicyID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `leaverequests`
--
ALTER TABLE `leaverequests`
  MODIFY `LeaveRequestID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `userroles`
--
ALTER TABLE `userroles`
  MODIFY `RoleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`RoleID`) REFERENCES `userroles` (`RoleID`);

--
-- Constraints for table `leavepolicies`
--
ALTER TABLE `leavepolicies`
  ADD CONSTRAINT `leavepolicies_ibfk_1` FOREIGN KEY (`RoleID`) REFERENCES `userroles` (`RoleID`);

--
-- Constraints for table `leaverequests`
--
ALTER TABLE `leaverequests`
  ADD CONSTRAINT `leaverequests_ibfk_1` FOREIGN KEY (`EmployeeID`) REFERENCES `employees` (`EmployeeID`),
  ADD CONSTRAINT `leaverequests_ibfk_2` FOREIGN KEY (`ApprovedBy`) REFERENCES `employees` (`EmployeeID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
