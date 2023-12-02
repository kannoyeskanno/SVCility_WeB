-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2023 at 10:29 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `svcility`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `mname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `contact_number` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `fname`, `lname`, `mname`, `email`, `password`, `contact_number`) VALUES
(1, 'admin', 'asis', 'ramores', 'jomarasisgriffin@gmail.com', 'admin', '123123123123');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL,
  `office_org` varchar(100) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(11) NOT NULL,
  `mname` varchar(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(11) NOT NULL,
  `contact_number` varchar(11) NOT NULL,
  `account_stat` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`id`, `office_org`, `fname`, `lname`, `mname`, `email`, `password`, `contact_number`, `account_stat`) VALUES
(1, '', '0', '0', '0', '0', '0', '2147483647', '0'),
(2, 'DEPED', 'Jomar', 'Asis', 'Ramores', 'jomarasisgriffin@gmail.com', 'whatadrag16', '+6399167684', 'accept'),
(3, 'aaaaaa', 'wan', 'wan', 'wan', 'wwan63326@gmail.com', '$2y$10$0VgD', '999999999', ''),
(4, 'asdasd', 'BSIT', 'Asis', '2B - Jomar ', 'kanno@kanno', '$2y$10$yS9O', '92321872849', ''),
(5, 'sdasd', 'asd', 'dsa', 'Is', 'aaaaasd@gmail.com', '$2y$10$M2Vm', '+6395192617', ''),
(8, 'DSWD', 'Irish Shean', 'Pastor', 'Coronacion', 'irishpastor007@gmail.com', 'pastor', '09054145052', ''),
(9, 'Ics', 'Jester', 'Salen', 'Avellana', 'Salenjester@gmail.com', '$2y$10$y2GC', '09914107791', ''),
(10, 'INFORMATION TECHNOLOGY SOCIETY - CAMARINES NORTE STATE COLLEGE', 'Kristine Magdalene ', 'Ramos', 'Gapoy', 'kimramos03312002@gmail.com', 'tine', '+6393092396', ''),
(11, 'ICS', 'Jester', 'Salen', 'Pogi', 'Rainwar66@gmail.com', 'Jomardaks', '09914107791', '');

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

CREATE TABLE `equipment` (
  `id` int(11) NOT NULL,
  `image_name` varchar(100) NOT NULL,
  `equipment_name` varchar(50) NOT NULL,
  `qty` int(6) NOT NULL,
  `location` varchar(50) NOT NULL,
  `supervisor_name` varchar(50) NOT NULL,
  `supervisor_contact` varchar(20) NOT NULL,
  `supervisor_email` varchar(50) NOT NULL,
  `equipment_details` varchar(100) NOT NULL,
  `equipment_condition` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`id`, `image_name`, `equipment_name`, `qty`, `location`, `supervisor_name`, `supervisor_contact`, `supervisor_email`, `equipment_details`, `equipment_condition`) VALUES
(1, 'chair', 'Mono Block Chair', 150, '', 'jomar', '09916768498', 'kannokanno914@gmail.com', 'aaaaaasd', ''),
(2, 'speaker', 'Speaker', 41, '', 'jomar', '09916768498', 'jomarasisgriffin@gmail.com', 'speaker', ''),
(3, 'tolda', 'Tolda', 50, '', 'jomar', '09916768498', 'jomarasisgriffin@gmail.com', 'tolda', ''),
(4, 'test', 'test', 41, '', 'jomar', '09916768498', 'jomarasisgriffin@gmail.com', 'asdasd', ''),
(5, 'test1', 'test', 41, '', 'jomar', '09916768498', 'jomarasisgriffin@gmail.com', 'sadasd', ''),
(6, 'table', 'table', 1231, '', 'jomar asis', '2313434', 'jomar@gmail.com', 'ass', ''),
(7, 'weq', 'table', 1231, '', 'jomar asis', '2313434', 'jomar@gmail.com', 'asd', '');

-- --------------------------------------------------------

--
-- Table structure for table `equipment_logs`
--

CREATE TABLE `equipment_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `handed` datetime NOT NULL,
  `returned` datetime DEFAULT NULL,
  `number_missing` int(11) NOT NULL,
  `bad_condition` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `equipment_logs`
--

INSERT INTO `equipment_logs` (`id`, `user_id`, `request_id`, `equipment_id`, `qty`, `handed`, `returned`, `number_missing`, `bad_condition`) VALUES
(82, 2, 118, 1, 0, '2023-11-22 21:18:07', '2023-11-22 21:19:08', 10, 0),
(83, 2, 119, 1, 0, '2023-11-22 21:24:19', '2023-11-22 21:25:27', 0, 12),
(84, 2, 120, 3, 0, '2023-11-22 21:24:20', '2023-11-22 21:26:12', 1, 1),
(85, 2, 121, 1, 0, '2023-11-22 21:39:56', '2023-11-22 22:11:47', 1, 0),
(92, 2, 123, 1, 0, '2023-11-22 22:52:29', '2023-11-22 22:52:43', 1, 1),
(120, 2, 125, 4, 5, '2023-11-23 09:28:44', '2023-11-23 09:36:28', 0, 0),
(121, 2, 125, 5, 5, '2023-11-23 09:28:44', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `facility`
--

CREATE TABLE `facility` (
  `id` int(11) NOT NULL,
  `facility_name` varchar(50) NOT NULL,
  `location` varchar(50) NOT NULL,
  `type` varchar(30) NOT NULL,
  `capacity` int(7) NOT NULL,
  `supervisor_name` varchar(50) NOT NULL,
  `contact` varchar(11) NOT NULL,
  `email` varchar(30) NOT NULL,
  `details` varchar(300) NOT NULL,
  `availability` varchar(15) NOT NULL,
  `image_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facility`
--

INSERT INTO `facility` (`id`, `facility_name`, `location`, `type`, `capacity`, `supervisor_name`, `contact`, `email`, `details`, `availability`, `image_name`) VALUES
(16, 'SV Agricultural and Sports Complex', 'vinzons', 'Type 2', 90, 'jomar', '09916768498', 'jomarasisgriffin@gmail.com', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor ', '', 'SV_Agricultural_and_Sports_Complex'),
(17, 'SV Convention Hall', 'vinzons', 'Type 1', 90, 'jomar', '09916768498', 'jomarasisgriffin@gmail.com', 'asdaaaaaa', '', 'SV_convention_hall'),
(18, 'Test2', 'vinzons', 'Type 1', 90, 'jomar', '09916768498', 'jomarasisgriffin@gmail.com', 'asdasdas', '', 'test2'),
(19, 'Test3', 'vinzons', 'Type 1', 90, 'jomar', '09916768498', 'jomarasisgriffin@gmail.com', 'asdasd', '', 'test3'),
(20, 'Test4', 'vinzons', 'Type 1', 90, 'jomar', '09916768498', 'jomarasisgriffin@gmail.com', 'weqwe', '', 'test4'),
(21, 'Test5', 'vinzons', 'Type 1', 90, 'jomar', '09916768498', 'jomarasisgriffin@gmail.com', 'asdsd', '', 'test5');

-- --------------------------------------------------------

--
-- Table structure for table `facility_schedules`
--

CREATE TABLE `facility_schedules` (
  `id` int(11) NOT NULL,
  `facility_id` varchar(30) NOT NULL,
  `scheduled_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `user_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facility_schedules`
--

INSERT INTO `facility_schedules` (`id`, `facility_id`, `scheduled_date`, `start_time`, `end_time`, `user_id`, `request_id`) VALUES
(45, '', '2023-11-22', '00:00:00', '00:00:00', 2, 108),
(46, '16,', '2023-11-22', '00:00:00', '00:00:00', 2, 109),
(47, '16', '2023-11-23', '00:00:00', '00:00:00', 2, 111),
(48, '16', '2023-11-23', '00:00:00', '00:00:00', 2, 111),
(49, '', '2023-11-23', '00:00:00', '00:00:00', 2, 112),
(50, '', '2023-11-29', '00:00:00', '00:00:00', 2, 113),
(51, '', '2023-11-23', '00:00:00', '00:00:00', 2, 115),
(52, '', '2023-11-30', '00:00:00', '00:00:00', 2, 116),
(53, '', '2023-11-30', '00:00:00', '00:00:00', 2, 117),
(54, '', '2023-11-22', '00:00:00', '00:00:00', 2, 118),
(55, '', '2023-11-30', '00:00:00', '00:00:00', 2, 120),
(56, '', '2023-11-23', '00:00:00', '00:00:00', 2, 119),
(57, '', '2023-11-23', '00:00:00', '00:00:00', 2, 121),
(58, '', '2023-11-23', '00:00:00', '00:00:00', 2, 122),
(59, '', '2023-11-23', '00:00:00', '00:00:00', 2, 123),
(60, '17,', '2023-11-23', '00:00:00', '00:00:00', 2, 124),
(61, '', '2023-11-30', '00:00:00', '00:00:00', 2, 125),
(62, '16', '2023-11-30', '00:00:00', '00:00:00', 2, 126);

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `file_name`, `file_path`, `user_id`) VALUES
(150, 'SAD-Programme.pdf', '../resources/file/SAD-Programme.pdf', 2),
(151, 'SAD-Programme.pdf', '../resources/file/SAD-Programme.pdf', 2),
(152, 'ASIS_JOMAR_R_BSIT3B_Ping_and_Traceroute.pdf', '../resources/file/ASIS_JOMAR_R_BSIT3B_Ping_and_Traceroute.pdf', 2),
(153, 'SV-Lettter2.pdf', '../resources/file/SV-Lettter2.pdf', 2),
(154, 'SAD UI Design_Team Kimchi.pdf', '../resources/file/SAD UI Design_Team Kimchi.pdf', 2),
(155, 'SAD-Programme.pdf', '../resources/file/SAD-Programme.pdf', 2),
(156, 'SAD-Programme.pdf', '../resources/file/SAD-Programme.pdf', 2),
(157, 'EVALUATION-USE-SAD.pdf', '../resources/file/EVALUATION-USE-SAD.pdf', 2),
(158, 'SAD-Programme.pdf', '../resources/file/SAD-Programme.pdf', 2),
(159, 'SAD UI Design_Team Kimchi.pdf', '../resources/file/SAD UI Design_Team Kimchi.pdf', 2),
(160, 'SAD-Programme.pdf', '../resources/file/SAD-Programme.pdf', 2),
(161, 'SAD UI Design_Team Kimchi.pdf', '../resources/file/SAD UI Design_Team Kimchi.pdf', 2),
(162, 'SAD UI Design_Team Kimchi.pdf', '../resources/file/SAD UI Design_Team Kimchi.pdf', 2),
(163, 'Gmail-Subject_-Invitation-to-Participate-in-User-Testing-for-_VICENTURE_-A-Comprehensive-Web-Platform-for-LGU-San-Vicente-Camarines-Norte_.pdf', '../resources/file/Gmail-Subject_-Invitation-to-Participate-in-User-Testing-for-_VICENTURE_-A-Comprehensive-Web-Platform-for-LGU-San-Vicente-Camarines-Norte_.pdf', 2),
(164, 'SAD UI Design_Team Kimchi.pdf', '../resources/file/SAD UI Design_Team Kimchi.pdf', 2),
(165, 'SAD UI Design_Team Kimchi.pdf', '../resources/file/SAD UI Design_Team Kimchi.pdf', 2),
(166, 'SAD UI Design_Team Kimchi.pdf', '../resources/file/SAD UI Design_Team Kimchi.pdf', 2),
(167, 'SAD UI Design_Team Kimchi.pdf', '../resources/file/SAD UI Design_Team Kimchi.pdf', 2),
(168, 'SAD UI Design_Team Kimchi.pdf', '../resources/file/SAD UI Design_Team Kimchi.pdf', 2),
(169, 'Gmail-Subject_-Invitation-to-Participate-in-User-Testing-for-_VICENTURE_-A-Comprehensive-Web-Platform-for-LGU-San-Vicente-Camarines-Norte_.pdf', '../resources/file/Gmail-Subject_-Invitation-to-Participate-in-User-Testing-for-_VICENTURE_-A-Comprehensive-Web-Platform-for-LGU-San-Vicente-Camarines-Norte_.pdf', 2);

-- --------------------------------------------------------

--
-- Table structure for table `list_request`
--

CREATE TABLE `list_request` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL,
  `qty` int(7) NOT NULL,
  `check_out` tinyint(1) NOT NULL,
  `equipment_id` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `max_qty_available` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `list_request`
--

INSERT INTO `list_request` (`id`, `user_id`, `facility_id`, `qty`, `check_out`, `equipment_id`, `max_qty_available`) VALUES
(182, 2, 0, 1, 0, '2', 0),
(183, 2, 0, 1, 0, '1', 0),
(184, 2, 0, 1, 0, '1', 0);

-- --------------------------------------------------------

--
-- Table structure for table `list_request_equipment`
--

CREATE TABLE `list_request_equipment` (
  `id` int(11) NOT NULL,
  `list_request_id` int(11) NOT NULL,
  `equipment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `id` int(10) NOT NULL,
  `facility_id` varchar(50) NOT NULL,
  `equipment_qty` varchar(100) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `purpose` varchar(30) NOT NULL,
  `user_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `file` varchar(100) NOT NULL,
  `status` varchar(20) DEFAULT NULL,
  `equipment_status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`id`, `facility_id`, `equipment_qty`, `subject`, `date`, `time`, `purpose`, `user_id`, `admin_id`, `file`, `status`, `equipment_status`) VALUES
(118, '', '1:10,', 'equipment 1', '2023-11-22', '00:00:00', 'Purpose B', 2, 1, '150', 'approved', 'returned'),
(119, '', '1:150,', 'sdasd', '2023-11-23', '00:00:00', 'Purpose A', 2, 1, '150', 'approved', 'returned'),
(120, '', '3:50,', 'test solo 23', '2023-11-30', '00:00:00', 'Purpose A', 2, 1, '152', 'approved', 'returned'),
(121, '', '1:10,2:10,', 'test solo 123', '2023-11-23', '00:00:00', 'Purpose A', 2, 1, '153', 'approved', 'returned'),
(122, '', '1:5,2:5,', 'test solo 123', '2023-11-23', '00:00:00', 'Purpose B', 2, 1, '154', 'approved', 'returned'),
(123, '', '1:1,3:1,', 'this is testing', '2023-11-23', '00:00:00', 'Purpose A', 2, 1, '150', 'approved', 'returned'),
(125, '', '4:5,5:5,', 'test', '2023-11-30', '00:00:00', 'Purpose A', 2, 1, '157', 'approved', 'returned'),
(126, '16', '', 'asdasd', '2023-11-30', '00:00:00', 'Purpose A', 2, 1, '150', 'approved', ''),
(127, '17,19,', '', 'asdasd', '2023-11-24', '00:00:00', 'Purpose B', 2, 0, '154', 'submitted', ''),
(128, '18,', '', 'asdasd', '2023-11-25', '00:00:00', 'Purpose A', 2, 0, '150', 'submitted', ''),
(129, '17,', '', 'asdasd', '2023-11-24', '00:00:00', 'Purpose B', 2, 0, '154', 'submitted', ''),
(130, '17,', '', 'asdasd', '2023-11-30', '00:00:00', 'Purpose D', 2, 0, '154', 'submitted', ''),
(131, '16,', '', 'testtt', '2023-11-24', '00:00:00', 'Purpose A', 2, 0, '163', 'submitted', ''),
(132, '16,', '', 'testtt', '2023-11-23', '00:00:00', 'Purpose C', 2, 0, '154', 'submitted', ''),
(133, '16,', '', '1231231dasdasdasd', '2023-11-14', '00:00:00', 'Purpose C', 2, 0, '154', 'submitted', ''),
(134, '16', '', 'asdasd', '2023-12-01', '00:00:00', 'Purpose A', 2, 0, '154', 'submitted', ''),
(135, '16', '', 'asdasd1', '2023-11-30', '00:00:00', 'Purpose C', 2, 0, '154', 'submitted', ''),
(136, '', '1:150,', 'asdasd1', '2023-11-30', '00:00:00', 'Purpose B', 2, 0, '154', 'submitted', ''),
(137, '18,17,21,21,16,', '1:4,1:1,2:1,', 'sdasd', '2023-11-30', '00:00:00', 'Purpose B', 2, 0, '163', 'submitted', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `equipment`
--
ALTER TABLE `equipment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `equipment_logs`
--
ALTER TABLE `equipment_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facility`
--
ALTER TABLE `facility`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facility_schedules`
--
ALTER TABLE `facility_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `list_request`
--
ALTER TABLE `list_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `list_request_equipment`
--
ALTER TABLE `list_request_equipment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `list_request_id` (`list_request_id`),
  ADD KEY `equipment_id` (`equipment_id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `equipment`
--
ALTER TABLE `equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `equipment_logs`
--
ALTER TABLE `equipment_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `facility`
--
ALTER TABLE `facility`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `facility_schedules`
--
ALTER TABLE `facility_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=170;

--
-- AUTO_INCREMENT for table `list_request`
--
ALTER TABLE `list_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT for table `list_request_equipment`
--
ALTER TABLE `list_request_equipment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `list_request_equipment`
--
ALTER TABLE `list_request_equipment`
  ADD CONSTRAINT `list_request_equipment_ibfk_1` FOREIGN KEY (`list_request_id`) REFERENCES `list_request` (`id`),
  ADD CONSTRAINT `list_request_equipment_ibfk_2` FOREIGN KEY (`equipment_id`) REFERENCES `equipment` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
