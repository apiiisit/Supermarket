-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2021 at 11:47 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `supermarket`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(10) NOT NULL,
  `OrderDetailID` int(10) NOT NULL,
  `OrderDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `ProductName` varchar(50) NOT NULL,
  `SupplierName` varchar(100) NOT NULL,
  `Unit` varchar(100) NOT NULL,
  `Price` float NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `OrderDetailID`, `OrderDate`, `ProductName`, `SupplierName`, `Unit`, `Price`, `Status`) VALUES
(1, 1, '2021-10-24 15:58:41', 'ยาสีฟัน', 'ไทยเอง', '100 หลอด', 5900, 0),
(2, 1, '2021-10-24 15:58:41', 'แปรงสีฟัน', 'ไทยเอง', '100 ด้าม', 4900, 0),
(3, 1, '2021-10-24 15:58:41', 'น้ำยาบ้วนปาก', 'ไทยไง', '250 ขวด', 27250, 0),
(4, 2, '2021-10-24 16:05:52', 'แอปเปิ้ล', 'เปิ้ลเอง', '100 กิโลกรัม', 6500, 0),
(5, 2, '2021-10-24 16:05:52', 'องุ่น', 'หงุ่นไงจะใครหล่ะ', '245 ลิโล', 26705, 0),
(6, 2, '2021-10-24 16:05:52', 'ส้ม', 'ส้มเอง', '152 กิโลกรัม', 4914.16, 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ProductID` int(10) NOT NULL,
  `ProductName` varchar(50) NOT NULL,
  `SupplierID` int(10) NOT NULL,
  `Unit` varchar(100) NOT NULL,
  `Price` float NOT NULL,
  `ProductDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductID`, `ProductName`, `SupplierID`, `Unit`, `Price`, `ProductDate`) VALUES
(1, 'CAMPUS', 1, '20 ลัง ลังละ 48 ห่อ', 9600, '2021-10-22 19:09:00'),
(2, 'คอลลาเจน', 2, '20 แพ็ค แพ็คละ 6 ขวด', 465, '2021-10-22 19:10:44'),
(3, 'ซีเรียล COPP', 3, '30 แพ็ค แพ็คละ 12 ห่อ', 1800, '2021-10-23 09:31:12');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `SupplierID` int(10) NOT NULL,
  `SupplierName` varchar(100) NOT NULL,
  `Address` varchar(300) NOT NULL,
  `PostalCode` int(10) NOT NULL,
  `Phone` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`SupplierID`, `SupplierName`, `Address`, `PostalCode`, `Phone`) VALUES
(1, 'บริษัท เบอร์ลี่ ยุคเกอร์ ฟู้ดส์ จำกัด', 'เลขที่ 225/10 หมูที่ 1 ต.บางเสาธง\r\nอ.บางเสาธง จ.สมุทรปราการ', 10570, 23132003),
(2, 'บริษัท ไทย ทริลลิตัน จำกัด', '232 ซ.สาธุประดิษฐ์ 57 ถ.สาธุประดิษฐ์ แขวงบางโพงพาง เขตยานนาวา กรุงเทพฯ', 10120, 268315625),
(3, 'บริษัท ยูโรเปี้ยน สแนค ฟู้ด จำกัด', '44/93 หมู่ 3 ถ.สุขุมวิท. ต.ท้ายบ้านใหม่ อ.เมือง จ.สมุทรปราการ', 10280, 238279817);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `ProductName` (`ProductName`),
  ADD KEY `SupplierName` (`SupplierName`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`),
  ADD KEY `SUPPLIER_ID_PD_FK` (`SupplierID`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`SupplierID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `SupplierID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `SUPPLIER_ID_PD_FK` FOREIGN KEY (`SupplierID`) REFERENCES `suppliers` (`SupplierID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
