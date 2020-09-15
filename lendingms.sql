-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 03, 2020 at 05:51 AM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `lendingms`
--

-- --------------------------------------------------------

--
-- Table structure for table `r_employee`
--

CREATE TABLE `r_employee` (
  `EmployeeId` int(11) NOT NULL,
  `EmployeeNumber` varchar(50) DEFAULT NULL,
  `FirstName` varchar(50) DEFAULT NULL,
  `MiddleName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `ExtName` varchar(50) DEFAULT NULL,
  `StatusId` int(11) DEFAULT NULL,
  `CreatedBy` varchar(50) DEFAULT NULL,
  `DateCreated` datetime DEFAULT CURRENT_TIMESTAMP,
  `UpdatedBy` varchar(50) DEFAULT NULL,
  `DateUpdated` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `r_employee`
--

INSERT INTO `r_employee` (`EmployeeId`, `EmployeeNumber`, `FirstName`, `MiddleName`, `LastName`, `ExtName`, `StatusId`, `CreatedBy`, `DateCreated`, `UpdatedBy`, `DateUpdated`) VALUES
(1, '96232-5', 'Amity Faith', '', 'Arcega', '', 1, '96232-5', '2020-07-01 20:48:02', '96232-5', '2020-07-01 20:48:02'),
(2, '96232-6', 'John', 'Arcega', 'Gaden', '', 1, '96232-5', '2020-07-01 20:48:02', '96232-5', '2020-07-01 20:48:02'),
(3, '96232-7', 'Leandro', '', 'Avena', 'IV', 1, '96232-5', '2020-07-01 20:48:02', '96232-5', '2020-07-01 20:48:02'),
(4, '96232-8', 'Glenn', '', 'Lisanin', '', 1, '96232-5', '2020-07-01 20:48:02', '96232-5', '2020-07-01 20:48:02');

-- --------------------------------------------------------

--
-- Table structure for table `r_loanrequirements`
--

CREATE TABLE `r_loanrequirements` (
  `LoanReqId` int(11) NOT NULL,
  `LoanId` int(11) DEFAULT NULL,
  `RequirementId` int(11) DEFAULT NULL,
  `Remarks` text,
  `StatusId` int(11) DEFAULT NULL,
  `CreatedBy` varchar(50) DEFAULT NULL,
  `DateCreated` datetime DEFAULT NULL,
  `UpdatedBy` varchar(50) DEFAULT NULL,
  `DateUpdated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `r_loans`
--

CREATE TABLE `r_loans` (
  `LoanId` int(11) NOT NULL,
  `Title` varchar(1000) NOT NULL,
  `Description` varchar(1000) NOT NULL,
  `Amount` decimal(15,2) NOT NULL,
  `TermType` int(11) NOT NULL,
  `Term` int(11) NOT NULL,
  `InterestCharge` decimal(15,2) NOT NULL,
  `MonthlyInstallment` decimal(15,2) NOT NULL,
  `IssueDate` datetime NOT NULL,
  `StatusId` int(11) NOT NULL DEFAULT '1',
  `CreatedBy` varchar(50) NOT NULL,
  `DateCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdatedBy` varchar(50) NOT NULL,
  `DateUpdated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `r_logs`
--

CREATE TABLE `r_logs` (
  `LogId` int(11) NOT NULL,
  `Description` text,
  `Remarks` text,
  `CreatedBy` varchar(50) DEFAULT NULL,
  `DateCreated` datetime DEFAULT CURRENT_TIMESTAMP,
  `UpdatedBy` varchar(50) DEFAULT NULL,
  `DateUpdated` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `r_logs`
--

INSERT INTO `r_logs` (`LogId`, `Description`, `Remarks`, `CreatedBy`, `DateCreated`, `UpdatedBy`, `DateUpdated`) VALUES
(1, 'Re-activated Cashier role for John Arcega Gaden', NULL, '96232-5', '2020-07-01 19:24:22', NULL, '2020-07-02 01:24:22'),
(2, 'Deactivated Cashier role for John Arcega Gaden', NULL, '96232-5', '2020-07-01 19:25:07', NULL, '2020-07-02 01:25:07'),
(3, 'Deactivated Cashier role for Amity Faith  Arcega', NULL, '96232-5', '2020-07-01 19:25:13', NULL, '2020-07-02 01:25:13'),
(4, 'Deactivated admin role for Glenn  Lisanin', NULL, '96232-5', '2020-07-01 19:26:47', NULL, '2020-07-02 01:26:47'),
(5, 'Re-activated admin role for Glenn  Lisanin', NULL, '96232-5', '2020-07-01 19:26:50', NULL, '2020-07-02 01:26:50'),
(6, 'Deactivated admin role for Glenn  Lisanin', NULL, '96232-5', '2020-07-01 19:27:11', NULL, '2020-07-02 01:27:11'),
(7, 'Re-activated admin role for Glenn  Lisanin', NULL, '96232-5', '2020-07-01 19:27:14', NULL, '2020-07-02 01:27:14'),
(8, 'Re-activated cashier role for Amity Faith  Arcega', NULL, '96232-5', '2020-07-01 19:27:16', NULL, '2020-07-02 01:27:16'),
(9, 'Logged in.', NULL, '96232-5', '2020-07-01 19:31:33', NULL, '2020-07-02 01:31:33'),
(10, 'Logged Out.', NULL, '96232-5', NULL, NULL, '2020-07-02 01:32:51'),
(11, 'Logged in.', NULL, '96232-5', '2020-07-01 19:32:55', NULL, '2020-07-02 01:32:55'),
(12, 'Logged Out.', NULL, '96232-5', NULL, NULL, '2020-07-02 01:33:26'),
(13, 'Logged in.', NULL, '96232-5', '2020-07-01 19:33:38', NULL, '2020-07-02 01:33:38'),
(14, 'Re-activated cashier role for John Arcega Gaden', NULL, '96232-5', '2020-07-01 19:33:47', NULL, '2020-07-02 01:33:47'),
(15, 'Logged in.', NULL, '96232-5', '2020-07-02 03:28:50', NULL, '2020-07-02 09:28:50'),
(16, 'Logged Out.', NULL, '96232-5', NULL, NULL, '2020-07-02 09:29:58'),
(17, 'Logged in.', NULL, '96232-7', '2020-07-02 03:30:02', NULL, '2020-07-02 09:30:02'),
(18, 'Logged Out.', NULL, '96232-7', NULL, NULL, '2020-07-02 11:02:41'),
(19, 'Logged in.', NULL, '96232-5', '2020-07-02 05:02:49', NULL, '2020-07-02 11:02:49'),
(20, 'Re-activated employee role for Leandro  Avena, IV', NULL, '96232-5', '2020-07-02 05:35:23', NULL, '2020-07-02 11:35:23'),
(21, 'Deactivated cashier role for Amity Faith  Arcega', NULL, '96232-5', '2020-07-02 06:22:31', NULL, '2020-07-02 12:22:31'),
(22, 'Logged Out.', NULL, '96232-5', NULL, NULL, '2020-07-02 13:11:14'),
(23, 'Logged in.', NULL, '96232-5', '2020-07-02 07:11:18', NULL, '2020-07-02 13:11:18'),
(24, 'Logged Out.', NULL, '96232-5', NULL, NULL, '2020-07-02 13:11:27'),
(25, 'Logged in.', NULL, '96232-7', '2020-07-02 07:11:36', NULL, '2020-07-02 13:11:36'),
(26, 'Logged Out.', NULL, '96232-7', NULL, NULL, '2020-07-02 13:12:37'),
(27, 'Logged in.', NULL, '96232-5', '2020-07-02 07:12:40', NULL, '2020-07-02 13:12:40'),
(28, 'Logged Out.', NULL, '96232-5', NULL, NULL, '2020-07-02 13:13:31'),
(29, 'Logged in.', NULL, '96232-5', '2020-07-02 07:13:36', NULL, '2020-07-02 13:13:36'),
(30, 'Logged Out.', NULL, '96232-5', NULL, NULL, '2020-07-02 13:13:43'),
(31, 'Logged in.', NULL, '96232-7', '2020-07-02 07:13:56', NULL, '2020-07-02 13:13:56'),
(32, 'Logged Out.', NULL, '96232-7', NULL, NULL, '2020-07-02 13:14:24'),
(33, 'Logged in.', NULL, '96232-5', '2020-07-02 07:14:51', NULL, '2020-07-02 13:14:51'),
(34, 'Logged Out.', NULL, '96232-5', NULL, NULL, '2020-07-02 13:23:33'),
(35, 'Logged in.', NULL, '96232-5', '2020-07-02 07:25:03', NULL, '2020-07-02 13:25:03'),
(36, 'Logged Out.', NULL, '96232-5', NULL, NULL, '2020-07-02 13:25:25'),
(37, 'Logged in.', NULL, '96232-7', '2020-07-02 07:25:30', NULL, '2020-07-02 13:25:30'),
(38, 'Logged Out.', NULL, '96232-7', NULL, NULL, '2020-07-02 13:25:45'),
(39, 'Logged in.', NULL, '96232-5', '2020-07-02 07:25:52', NULL, '2020-07-02 13:25:52'),
(40, 'Logged Out.', NULL, '96232-5', NULL, NULL, '2020-07-02 13:27:24'),
(41, 'Logged in.', NULL, '96232-7', '2020-07-02 07:27:29', NULL, '2020-07-02 13:27:29'),
(42, 'Logged Out.', NULL, '96232-7', NULL, NULL, '2020-07-02 13:29:17'),
(43, 'Logged in.', NULL, '96232-5', '2020-07-02 07:29:25', NULL, '2020-07-02 13:29:25'),
(44, 'Deactivated employee role for Leandro  Avena, IV', NULL, '96232-5', '2020-07-02 07:32:31', NULL, '2020-07-02 13:32:31'),
(45, 'Re-activated cashier role for Amity Faith  Arcega', NULL, '96232-5', '2020-07-02 07:32:46', NULL, '2020-07-02 13:32:46'),
(46, 'Deactivated admin role for Amity Faith  Arcega', NULL, '96232-5', '2020-07-02 07:32:52', NULL, '2020-07-02 13:32:52'),
(47, 'Logged Out.', NULL, '96232-5', NULL, NULL, '2020-07-02 13:33:01'),
(48, 'Logged in.', NULL, '96232-8', '2020-07-02 07:33:09', NULL, '2020-07-02 13:33:09'),
(49, 'Re-activated admin role for Amity Faith  Arcega', NULL, '96232-8', '2020-07-02 07:33:13', NULL, '2020-07-02 13:33:13'),
(50, 'Deactivated cashier role for Amity Faith  Arcega', NULL, '96232-8', '2020-07-02 07:55:00', NULL, '2020-07-02 13:55:00'),
(51, 'Logged Out.', NULL, '96232-8', NULL, NULL, '2020-07-02 13:55:07'),
(52, 'Logged in.', NULL, '96232-5', '2020-07-02 07:55:12', NULL, '2020-07-02 13:55:12'),
(53, 'Deactivated admin role for Amity Faith  Arcega', NULL, '96232-5', '2020-07-02 09:42:05', NULL, '2020-07-02 15:42:05'),
(54, 'Re-activated admin role for Amity Faith  Arcega', NULL, '96232-5', '2020-07-02 09:42:10', NULL, '2020-07-02 15:42:10'),
(55, 'Logged in.', NULL, '96232-5', '2020-07-02 13:43:15', NULL, '2020-07-02 19:43:15'),
(56, 'Logged in.', NULL, '96232-5', '2020-07-02 15:51:44', NULL, '2020-07-02 21:51:44'),
(57, 'Deactivated cashier role for John Arcega Gaden', NULL, '96232-5', '2020-07-02 15:59:57', NULL, '2020-07-02 21:59:57'),
(58, 'Logged Out.', NULL, '96232-5', '2020-07-02 16:02:41', NULL, '2020-07-02 22:02:41'),
(59, 'Logged in.', NULL, '96232-8', '2020-07-02 16:02:52', NULL, '2020-07-02 22:02:52'),
(60, 'Re-activated cashier role for Amity Faith  Arcega', NULL, '96232-8', '2020-07-02 16:02:57', NULL, '2020-07-02 22:02:57'),
(61, 'Re-activated cashier role for John Arcega Gaden', NULL, '96232-8', '2020-07-02 16:02:59', NULL, '2020-07-02 22:02:59'),
(62, 'Re-activated employee role for Leandro  Avena, IV', NULL, '96232-8', '2020-07-02 16:03:01', NULL, '2020-07-02 22:03:01'),
(63, 'Deactivated employee role for Leandro  Avena, IV', NULL, '96232-8', '2020-07-02 16:04:36', NULL, '2020-07-02 22:04:36'),
(64, 'Re-activated employee role for Leandro  Avena, IV', NULL, '96232-8', '2020-07-02 16:05:26', NULL, '2020-07-02 22:05:26'),
(65, 'Logged Out.', NULL, '96232-8', '2020-07-02 18:15:55', NULL, '2020-07-03 00:15:55'),
(66, 'Logged in.', NULL, '96232-8', '2020-07-02 18:16:06', NULL, '2020-07-03 00:16:06'),
(67, 'Logged Out.', NULL, '96232-8', '2020-07-02 18:17:29', NULL, '2020-07-03 00:17:29'),
(68, 'Logged in.', NULL, '96232-5', '2020-07-02 18:17:35', NULL, '2020-07-03 00:17:35'),
(69, 'Added Glenn  Lisanin for admin role.', NULL, '96232-5', '2020-07-02 18:52:53', NULL, '2020-07-03 00:52:53'),
(70, 'Deactivated admin role for Amity Faith  Arcega', NULL, '96232-5', '2020-07-02 18:59:11', NULL, '2020-07-03 00:59:11'),
(71, 'Re-activated admin role for Amity Faith  Arcega', NULL, '96232-5', '2020-07-02 18:59:23', NULL, '2020-07-03 00:59:23'),
(72, 'Deactivated admin role for Glenn  Lisanin', NULL, '96232-5', '2020-07-02 18:59:49', NULL, '2020-07-03 00:59:49'),
(73, 'Deactivated cashier role for Glenn  Lisanin', NULL, '96232-5', '2020-07-02 18:59:53', NULL, '2020-07-03 00:59:53'),
(74, 'Logged Out.', NULL, '96232-5', '2020-07-02 18:59:57', NULL, '2020-07-03 00:59:57'),
(75, 'Logged in.', NULL, '96232-8', '2020-07-02 19:00:01', NULL, '2020-07-03 01:00:01'),
(76, 'Logged Out.', NULL, '96232-8', '2020-07-02 19:00:03', NULL, '2020-07-03 01:00:03'),
(77, 'Logged in.', NULL, '96232-5', '2020-07-02 19:00:38', NULL, '2020-07-03 01:00:38'),
(78, 'Re-activated admin role for Glenn  Lisanin', NULL, '96232-5', '2020-07-02 19:00:45', NULL, '2020-07-03 01:00:45'),
(79, 'Deactivated employee role for Leandro  Avena, IV', NULL, '96232-5', '2020-07-02 19:02:00', NULL, '2020-07-03 01:02:00'),
(80, 'Re-activated cashier role for Glenn  Lisanin', NULL, '96232-5', '2020-07-02 19:02:05', NULL, '2020-07-03 01:02:05'),
(81, 'Re-activated employee role for Leandro  Avena, IV', NULL, '96232-5', '2020-07-02 19:02:08', NULL, '2020-07-03 01:02:08'),
(82, 'Deactivated cashier role for John Arcega Gaden', NULL, '96232-5', '2020-07-02 19:02:11', NULL, '2020-07-03 01:02:11'),
(83, 'Deactivated cashier role for Glenn  Lisanin', NULL, '96232-5', '2020-07-02 19:02:17', NULL, '2020-07-03 01:02:17'),
(84, 'Deactivated admin role for Glenn  Lisanin', NULL, '96232-5', '2020-07-02 19:02:22', NULL, '2020-07-03 01:02:22'),
(85, 'Logged Out.', NULL, '96232-5', '2020-07-02 19:02:26', NULL, '2020-07-03 01:02:26'),
(86, 'Logged in.', NULL, '96232-5', '2020-07-02 19:02:35', NULL, '2020-07-03 01:02:35'),
(87, 'Deactivated top management role for Leandro  Avena, IV', NULL, '96232-5', '2020-07-02 19:02:53', NULL, '2020-07-03 01:02:53'),
(88, 'Re-activated cashier role for Glenn  Lisanin', NULL, '96232-5', '2020-07-02 19:03:32', NULL, '2020-07-03 01:03:32'),
(89, 'Deactivated top management role for Amity Faith  Arcega', NULL, '96232-5', '2020-07-02 19:04:51', NULL, '2020-07-03 01:04:51'),
(90, 'Logged Out.', NULL, '96232-5', '2020-07-02 19:05:04', NULL, '2020-07-03 01:05:04'),
(91, 'Logged in.', NULL, '96232-5', '2020-07-02 19:05:10', NULL, '2020-07-03 01:05:10'),
(92, 'Logged Out.', NULL, '96232-5', '2020-07-02 19:05:17', NULL, '2020-07-03 01:05:17'),
(93, 'Logged in.', NULL, '96232-5', '2020-07-02 19:05:22', NULL, '2020-07-03 01:05:22'),
(94, 'Deactivated admin role for Amity Faith  Arcega', NULL, '96232-5', '2020-07-02 19:07:47', NULL, '2020-07-03 01:07:47'),
(95, 'Deactivated employee role for Amity Faith  Arcega', NULL, '96232-5', '2020-07-02 19:12:29', NULL, '2020-07-03 01:12:29'),
(96, 'Re-activated admin role for Amity Faith  Arcega', NULL, '96232-5', '2020-07-02 19:17:02', NULL, '2020-07-03 01:17:02'),
(97, 'Logged in.', NULL, '96232-5', '2020-07-03 05:28:54', NULL, '2020-07-03 11:28:54');

-- --------------------------------------------------------

--
-- Table structure for table `r_requirements`
--

CREATE TABLE `r_requirements` (
  `RequirementId` int(11) NOT NULL,
  `Description` text NOT NULL,
  `StatusId` int(11) NOT NULL,
  `CreatedBy` varchar(50) NOT NULL,
  `DateCreated` datetime NOT NULL,
  `UpdatedBy` varchar(50) NOT NULL,
  `DateUpdated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `r_role`
--

CREATE TABLE `r_role` (
  `RoleId` int(11) NOT NULL,
  `Description` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `r_role`
--

INSERT INTO `r_role` (`RoleId`, `Description`) VALUES
(1, 'Admin'),
(2, 'Cashier'),
(3, 'Employee'),
(4, 'Top Management');

-- --------------------------------------------------------

--
-- Table structure for table `r_status`
--

CREATE TABLE `r_status` (
  `StatusId` int(11) NOT NULL,
  `Description` varchar(100) NOT NULL,
  `CreatedBy` varchar(50) NOT NULL,
  `DateCreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdatedBy` varchar(50) NOT NULL,
  `DateUpdated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `r_userrole`
--

CREATE TABLE `r_userrole` (
  `UserRoleId` int(11) NOT NULL,
  `RoleId` int(11) DEFAULT NULL,
  `EmployeeNumber` varchar(50) DEFAULT NULL,
  `Password` blob NOT NULL,
  `StatusId` int(11) DEFAULT '1',
  `CreatedBy` varchar(50) DEFAULT NULL,
  `DateCreated` datetime DEFAULT CURRENT_TIMESTAMP,
  `UpdatedBy` varchar(50) DEFAULT NULL,
  `DateUpdated` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `r_userrole`
--

INSERT INTO `r_userrole` (`UserRoleId`, `RoleId`, `EmployeeNumber`, `Password`, `StatusId`, `CreatedBy`, `DateCreated`, `UpdatedBy`, `DateUpdated`) VALUES
(1, 1, '96232-5', 0xa24c7759814913add3, 1, '96232-5', '2020-07-01 18:21:18', '96232-5', '2020-07-02 19:17:02'),
(2, 2, '96232-5', 0xa24c7759814913add3, 1, '96232-5', '2020-07-01 18:21:18', '2020-07-02 16:02:57', '0000-00-00 00:00:00'),
(3, 2, '96232-6', 0xa24c7759814913add3, 2, '96232-5', '2020-07-01 18:21:18', '2020-07-02 19:02:11', '2020-07-02 19:02:11'),
(4, 3, '96232-7', 0xa24c7759814913add3, 1, '96232-5', '2020-07-01 18:21:18', '2020-07-02 19:02:08', '2020-07-02 19:02:08'),
(5, 1, '96232-8', 0xa24c7759814913add3, 2, '96232-5', '2020-07-01 18:21:18', '2020-07-02 19:02:22', '2020-07-02 19:02:22'),
(6, 3, '96232-5', 0x454e434f44452870617373776f7264312c20277365637265742729, 2, '96232-8', '2020-07-02 23:39:58', '96232-5', '2020-07-02 19:12:29'),
(7, 4, '96232-5', 0x454e434f44452870617373776f7264312c20277365637265742729, 2, '96232-8', '2020-07-02 23:53:21', '96232-5', '2020-07-02 19:04:51'),
(10, 2, '96232-8', 0x454e434f44452870617373776f7264312c20277365637265742729, 1, '96232-8', '2020-07-03 00:13:39', '96232-5', '2020-07-02 19:03:32'),
(11, 4, '96232-7', 0x454e434f44452870617373776f7264312c20277365637265742729, 2, '96232-5', '2020-07-03 00:52:53', '2020-07-02 19:02:53', '2020-07-02 19:02:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `r_employee`
--
ALTER TABLE `r_employee`
  ADD PRIMARY KEY (`EmployeeId`) USING BTREE;

--
-- Indexes for table `r_loanrequirements`
--
ALTER TABLE `r_loanrequirements`
  ADD PRIMARY KEY (`LoanReqId`) USING BTREE;

--
-- Indexes for table `r_loans`
--
ALTER TABLE `r_loans`
  ADD PRIMARY KEY (`LoanId`) USING BTREE;

--
-- Indexes for table `r_logs`
--
ALTER TABLE `r_logs`
  ADD PRIMARY KEY (`LogId`) USING BTREE;

--
-- Indexes for table `r_requirements`
--
ALTER TABLE `r_requirements`
  ADD PRIMARY KEY (`RequirementId`) USING BTREE,
  ADD KEY `R_ReqStatusId` (`StatusId`);

--
-- Indexes for table `r_role`
--
ALTER TABLE `r_role`
  ADD PRIMARY KEY (`RoleId`) USING BTREE;

--
-- Indexes for table `r_status`
--
ALTER TABLE `r_status`
  ADD PRIMARY KEY (`StatusId`) USING BTREE;

--
-- Indexes for table `r_userrole`
--
ALTER TABLE `r_userrole`
  ADD PRIMARY KEY (`UserRoleId`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `r_employee`
--
ALTER TABLE `r_employee`
  MODIFY `EmployeeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `r_loanrequirements`
--
ALTER TABLE `r_loanrequirements`
  MODIFY `LoanReqId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `r_loans`
--
ALTER TABLE `r_loans`
  MODIFY `LoanId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `r_logs`
--
ALTER TABLE `r_logs`
  MODIFY `LogId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `r_requirements`
--
ALTER TABLE `r_requirements`
  MODIFY `RequirementId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `r_role`
--
ALTER TABLE `r_role`
  MODIFY `RoleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `r_status`
--
ALTER TABLE `r_status`
  MODIFY `StatusId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `r_userrole`
--
ALTER TABLE `r_userrole`
  MODIFY `UserRoleId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
