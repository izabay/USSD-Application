-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2024 at 10:12 PM
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
-- Database: `offeringsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminId` int(11) NOT NULL,
  `AdminNames` varchar(50) NOT NULL,
  `AdminTelephone` varchar(15) NOT NULL,
  `AdminEmail` varchar(100) NOT NULL,
  `AdminPassword` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminId`, `AdminNames`, `AdminTelephone`, `AdminEmail`, `AdminPassword`) VALUES
(1, 'MANISHIMWE Patrick', '0784162606', 'manishim@gmail.com', 'Manishimwe123');

-- --------------------------------------------------------

--
-- Table structure for table `balances`
--

CREATE TABLE `balances` (
  `BalanceId` int(11) NOT NULL,
  `m_id` int(11) NOT NULL,
  `MemberName` varchar(50) NOT NULL,
  `balance` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `balances`
--

INSERT INTO `balances` (`BalanceId`, `m_id`, `MemberName`, `balance`) VALUES
(1, 3, 'NIYOMUGABO Jean', 30000.00),
(2, 1, 'MANZI Josua', 42000.00),
(3, 4, 'MANIRIHO Fidele', 1985200.00),
(4, 5, 'IZABAYO Marc', 80000.00),
(5, 6, 'ISHIMWE', 28000.00);

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `ContactId` int(11) NOT NULL,
  `MemberName` varchar(50) NOT NULL,
  `ContactEmail` varchar(100) NOT NULL,
  `Subject` varchar(100) NOT NULL,
  `Message` varchar(2000) NOT NULL,
  `DATE` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`ContactId`, `MemberName`, `ContactEmail`, `Subject`, `Message`, `DATE`) VALUES
(1, 'manzi', 'man@gmail.com', 'ilegal', 'qshwhdwjkwwq', '2024-04-06 17:04:10');

-- --------------------------------------------------------

--
-- Table structure for table `loanhistory`
--

CREATE TABLE `loanhistory` (
  `l_id` int(11) NOT NULL,
  `LoanId` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loanhistory`
--

INSERT INTO `loanhistory` (`l_id`, `LoanId`, `amount`, `date`) VALUES
(1, 2, 6400.00, '2024-04-13 13:11:27'),
(2, 2, 2000.00, '2024-04-13 13:11:42'),
(3, 3, 21000.00, '2024-04-17 08:16:11');

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `LoanId` int(11) NOT NULL,
  `m_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `loan_amount` decimal(10,2) NOT NULL,
  `repayment` decimal(10,2) NOT NULL,
  `interest_rate` int(11) NOT NULL,
  `issued_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_deadline_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`LoanId`, `m_id`, `status`, `loan_amount`, `repayment`, `interest_rate`, `issued_date`, `payment_deadline_date`) VALUES
(1, 1, 'paid', 40000.00, 2000.00, 5, '2024-04-06 17:10:44', '2024-05-06'),
(2, 4, 'paid', 8000.00, 400.00, 5, '2024-04-13 11:32:31', '2024-05-13'),
(3, 1, 'approved', 20000.00, 1000.00, 5, '2024-04-17 08:03:05', '2024-05-17'),
(4, 1, 'approved', 21000.00, 1050.00, 5, '2024-04-17 08:41:16', '2024-05-17'),
(5, 6, 'approved', 10000.00, 500.00, 5, '2024-04-17 10:44:24', '2024-05-17');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `MemberId` int(11) NOT NULL,
  `names` varchar(50) NOT NULL,
  `national_identity` varchar(16) NOT NULL,
  `telephone` varchar(13) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`MemberId`, `names`, `national_identity`, `telephone`, `email`, `password`) VALUES
(1, 'MANZI Josua', '1200080186318093', '0783152501', 'manzi@gmail.com', 'Passsword@123'),
(2, 'HABIMANA Eric', '1999809783017195', '07864782291', 'habimana@gmail.com', '6446'),
(3, 'NIYOMUGABO Jean', '1200394820918092', '0784376890', 'jeann@gmail.com', 'Password123'),
(4, 'MANIRIHO Fidele', '1200380185316083', '0784386890', 'maniriho@gail.com', '5445'),
(5, 'IZABAYO Marc', '1200018938228092', '0784162606', 'marc@gmail.com', '20000'),
(6, 'ISHIMWE', '1200089388484738', '0783152505', 'ish@gmail.com', '6446'),
(7, 'patrick', '1200002939400429', '0782476818', 'pat@gmail.com', '6446'),
(8, 'patrick', '1200002939400429', '0782476818', 'pat@gmail.com', '6446'),
(9, 'manishimwe', '288394949595', '0782476817', 'm@gmail.com', '6446'),
(10, 'manishimwe', '288394949595', '0782476816', 'm@gmail.com', '6446'),
(11, 'manishimwe', '288394949595', '0782476813', 'm@gmail.com', '6446'),
(12, 'manishimwe', '288394949595', '0782476810', 'm@gmail.com', '6446'),
(13, 'manishimwe', '288394949595', '0782476811', 'm@gmail.com', '6446'),
(14, 'manishimwe', '1200001928383731', '+250783928129', 'man@gmail.com', '6446'),
(15, 'tha', '1235466789876756', '0786482278', 'm@g', '55554');

-- --------------------------------------------------------

--
-- Table structure for table `penalties`
--

CREATE TABLE `penalties` (
  `PenaltyId` int(11) NOT NULL,
  `m_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `issued_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penalties`
--

INSERT INTO `penalties` (`PenaltyId`, `m_id`, `amount`, `issued_date`) VALUES
(1, 1, 200.00, '2024-04-06 17:32:14');

-- --------------------------------------------------------

--
-- Table structure for table `saving`
--

CREATE TABLE `saving` (
  `SavingId` int(11) NOT NULL,
  `m_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `shares` int(11) NOT NULL,
  `transaction_type` varchar(20) NOT NULL,
  `SavingDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saving`
--

INSERT INTO `saving` (`SavingId`, `m_id`, `amount`, `shares`, `transaction_type`, `SavingDate`) VALUES
(1, 3, 4000000.00, 2000, 'deposit', '2024-04-09 14:14:43'),
(2, 1, 3400000.00, 1700, 'deposit', '2024-04-09 14:15:11'),
(3, 3, 2000000.00, 1000, 'deposit', '2024-04-09 14:15:45'),
(4, 1, 30000.00, 15, 'deposit', '2024-04-09 14:15:55'),
(5, 1, 400000.00, 200, 'deposit', '2024-04-09 14:25:21'),
(6, 1, 30000.00, 15, 'deposit', '2024-04-09 14:49:44'),
(7, 3, 30000.00, 15, 'deposit', '2024-04-09 15:40:44'),
(8, 1, 300000.00, 150, 'deposit', '2024-04-10 07:07:48'),
(9, 4, 2000.00, 1, 'deposit', '2024-04-13 10:43:42'),
(10, 4, 2000.00, 1, 'deposit', '2024-04-13 10:44:32'),
(11, 4, 2000.00, 1, 'deposit', '2024-04-13 10:49:36'),
(12, 4, 2000.00, 1, 'deposit', '2024-04-13 10:50:00'),
(13, 4, 2000.00, 1, 'deposit', '2024-04-13 10:51:07'),
(14, 4, 2000.00, 1, 'deposit', '2024-04-13 10:51:40'),
(15, 4, 2000000.00, 1000, 'deposit', '2024-04-13 12:42:42'),
(16, 1, 300000.00, 150, 'deposit', '2024-04-13 13:15:14'),
(17, 1, 20000.00, 10, 'deposit', '2024-04-17 08:01:10'),
(18, 1, 20000.00, 10, 'deposit', '2024-04-17 08:01:24'),
(19, 5, 20000.00, 10, 'deposit', '2024-04-17 09:40:16'),
(20, 5, 20000.00, 10, 'deposit', '2024-04-17 09:40:47'),
(21, 5, 20000.00, 10, 'deposit', '2024-04-17 09:40:56'),
(22, 5, 20000.00, 10, 'deposit', '2024-04-17 09:40:57'),
(23, 1, 2000.00, 1, 'deposit', '2024-04-17 10:07:52'),
(24, 6, 4000.00, 2, 'deposit', '2024-04-17 10:39:40'),
(25, 6, 4000.00, 2, 'deposit', '2024-04-17 10:39:45'),
(26, 6, 10000.00, 5, 'deposit', '2024-04-17 10:40:44');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `TransactionId` int(11) NOT NULL,
  `m_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`TransactionId`, `m_id`, `amount`, `transaction_type`) VALUES
(1, 2, 3000.00, 'withdrawal'),
(2, 2, 4000.00, 'withdrawal'),
(3, 1, 30000.00, 'withdrawal'),
(4, 1, 30000.00, 'withdrawal'),
(5, 1, 3860000.00, 'withdrawal'),
(6, 3, 600000.00, 'withdrawal'),
(7, 3, 500000.00, 'withdrawal'),
(8, 3, 500000.00, 'withdrawal'),
(9, 3, 500000.00, 'withdrawal'),
(10, 1, 3860000.00, 'withdrawal'),
(11, 3, 3900000.00, 'withdrawal'),
(12, 1, 200000.00, 'withdrawal'),
(13, 4, 2000.00, 'withdrawal'),
(14, 4, 2000.00, 'withdrawal'),
(15, 4, 2000.00, 'withdrawal'),
(16, 1, 300000.00, 'withdrawal');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminId`);

--
-- Indexes for table `balances`
--
ALTER TABLE `balances`
  ADD PRIMARY KEY (`BalanceId`),
  ADD KEY `MemberId` (`m_id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`ContactId`);

--
-- Indexes for table `loanhistory`
--
ALTER TABLE `loanhistory`
  ADD PRIMARY KEY (`l_id`),
  ADD KEY `LoanId` (`LoanId`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`LoanId`),
  ADD KEY `l_memberid` (`m_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`MemberId`);

--
-- Indexes for table `penalties`
--
ALTER TABLE `penalties`
  ADD PRIMARY KEY (`PenaltyId`),
  ADD KEY `p_m_id` (`m_id`);

--
-- Indexes for table `saving`
--
ALTER TABLE `saving`
  ADD PRIMARY KEY (`SavingId`),
  ADD KEY `m_id` (`m_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`TransactionId`),
  ADD KEY `t_m_id` (`m_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adminId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `balances`
--
ALTER TABLE `balances`
  MODIFY `BalanceId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `ContactId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `loanhistory`
--
ALTER TABLE `loanhistory`
  MODIFY `l_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `LoanId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `MemberId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `penalties`
--
ALTER TABLE `penalties`
  MODIFY `PenaltyId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `saving`
--
ALTER TABLE `saving`
  MODIFY `SavingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `TransactionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `balances`
--
ALTER TABLE `balances`
  ADD CONSTRAINT `MemberId` FOREIGN KEY (`m_id`) REFERENCES `members` (`MemberId`);

--
-- Constraints for table `loanhistory`
--
ALTER TABLE `loanhistory`
  ADD CONSTRAINT `LoanId` FOREIGN KEY (`LoanId`) REFERENCES `loans` (`LoanId`);

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `l_memberid` FOREIGN KEY (`m_id`) REFERENCES `members` (`MemberId`);

--
-- Constraints for table `penalties`
--
ALTER TABLE `penalties`
  ADD CONSTRAINT `p_m_id` FOREIGN KEY (`m_id`) REFERENCES `members` (`MemberId`);

--
-- Constraints for table `saving`
--
ALTER TABLE `saving`
  ADD CONSTRAINT `m_id` FOREIGN KEY (`m_id`) REFERENCES `members` (`MemberId`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `t_m_id` FOREIGN KEY (`m_id`) REFERENCES `members` (`MemberId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
