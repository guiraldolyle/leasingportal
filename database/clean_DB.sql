-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 07, 2018 at 12:29 AM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `agc-pms`
--
CREATE DATABASE IF NOT EXISTS `agc-pms` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `agc-pms`;

-- --------------------------------------------------------

--
-- Table structure for table `accountability_report`
--

CREATE TABLE IF NOT EXISTS `accountability_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` varchar(45) NOT NULL,
  `description` varchar(100) NOT NULL,
  `amount` float NOT NULL,
  `tender_desc` varchar(45) NOT NULL,
  `prepared_by` int(11) NOT NULL,
  `posting_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `accredited_banks`
--

CREATE TABLE IF NOT EXISTS `accredited_banks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bank_code` varchar(45) DEFAULT NULL,
  `bank_name` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `accredited_banks`
--

INSERT INTO `accredited_banks` (`id`, `bank_code`, `bank_name`) VALUES
(1, 'B-007-LBP', 'Land Bank of the Philippines'),
(2, 'B-006-BPI', 'Banks of the Philippine Islands');

-- --------------------------------------------------------

--
-- Table structure for table `advance_deposit`
--

CREATE TABLE IF NOT EXISTS `advance_deposit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` varchar(45) DEFAULT NULL,
  `doc_no` varchar(45) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `advance_payment`
--

CREATE TABLE IF NOT EXISTS `advance_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` varchar(45) DEFAULT NULL,
  `contract_no` varchar(45) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `balance` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `advance_rent`
--

CREATE TABLE IF NOT EXISTS `advance_rent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(100) DEFAULT NULL,
  `months` varchar(20) DEFAULT NULL,
  `actual_num` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `advance_rent`
--

INSERT INTO `advance_rent` (`id`, `description`, `months`, `actual_num`) VALUES
(1, 'Basic/fixed monthly rent', '1 mo', 1);

-- --------------------------------------------------------

--
-- Table structure for table `area_classification`
--

CREATE TABLE IF NOT EXISTS `area_classification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classification` varchar(100) DEFAULT NULL,
  `description` text,
  `status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `area_classification`
--

INSERT INTO `area_classification` (`id`, `classification`, `description`, `status`) VALUES
(1, 'LOBBY', 'Lobby Description', ''),
(3, 'ROOM', 'Room Description', ''),
(4, 'ACTIVITY CENTER', 'Activity Center', '');

-- --------------------------------------------------------

--
-- Table structure for table `area_type`
--

CREATE TABLE IF NOT EXISTS `area_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) DEFAULT NULL,
  `description` text,
  `status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `area_type`
--

INSERT INTO `area_type` (`id`, `type`, `description`, `status`) VALUES
(1, 'PREMIUM', 'Accessible Area', ''),
(2, 'STANDARD', 'Standard Type', ''),
(3, 'DELUXE', 'Deluxe Description', '');

-- --------------------------------------------------------

--
-- Table structure for table `billing_date`
--

CREATE TABLE IF NOT EXISTS `billing_date` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` varchar(45) DEFAULT NULL,
  `inclusive_date` varchar(45) DEFAULT NULL,
  `balance` float DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `brochures`
--

CREATE TABLE IF NOT EXISTS `brochures` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prospect_id` int(11) DEFAULT NULL,
  `file_name` varchar(100) DEFAULT NULL,
  `flag` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `brochures`
--

INSERT INTO `brochures` (`id`, `prospect_id`, `file_name`, `flag`) VALUES
(1, 1, '1513293312fabian-grohs-423591.jpg', 'Long Term'),
(2, 6, '1513462756pan-xiaozhen-254933.jpg', 'Long Term'),
(3, 1, '1513808959luca-bravo-207676.jpg', 'Long Term'),
(4, 2, '1513897181Pictures Brochures of Products.png', 'Long Term'),
(5, 5, '1515015376Pictures Brochures of Products.png', 'Long Term');

-- --------------------------------------------------------

--
-- Table structure for table `cash_bank`
--

CREATE TABLE IF NOT EXISTS `cash_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `category_one`
--

CREATE TABLE IF NOT EXISTS `category_one` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(64) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `category_one`
--

INSERT INTO `category_one` (`id`, `category_name`, `description`) VALUES
(1, 'FOOD CATEGORY', 'Food Category'),
(2, 'APPAREL', 'Apparel'),
(3, 'GADGETS', 'Gadgets'),
(4, 'CAR EXHIBIT', 'Car Exhibit'),
(5, 'PROGRAMS and ACTIVITIES', 'Programs and Activities');

-- --------------------------------------------------------

--
-- Table structure for table `category_three`
--

CREATE TABLE IF NOT EXISTS `category_three` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(64) DEFAULT NULL,
  `description` text,
  `categoryTwo_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `category_three`
--

INSERT INTO `category_three` (`id`, `category_name`, `description`, `categoryTwo_id`) VALUES
(1, 'Cellphones and Accesories', 'Cellphones and Accesories', 8),
(2, 'Laptops Repair', 'Laptops Repair', 9);

-- --------------------------------------------------------

--
-- Table structure for table `category_two`
--

CREATE TABLE IF NOT EXISTS `category_two` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(64) DEFAULT NULL,
  `description` text,
  `categoryOne_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `category_two`
--

INSERT INTO `category_two` (`id`, `category_name`, `description`, `categoryOne_id`) VALUES
(1, 'Fastfoods', 'Fastfoods', 1),
(2, 'Cakes and Pastries', 'Cakes and Pastries', 1),
(3, 'Korean Cuisine', 'Korean Cuisine', 1),
(4, 'Seafoods Restaurant', 'Seafoods Restaurant', 1),
(5, 'Delicacies', 'Delicacies', 1),
(6, 'Sports Apparel', 'Sports Apparel', 2),
(7, 'Fashion Apparel', 'Fashion Apparel', 2),
(8, 'Cellphones', 'Cellphones', 3),
(9, 'Laptops', 'Laptops', 3),
(10, 'CARS and MOTORS', 'Cars and Motors', 4),
(11, 'DRINKS', 'Drinks', 1);

-- --------------------------------------------------------

--
-- Table structure for table `charges_setup`
--

CREATE TABLE IF NOT EXISTS `charges_setup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `charges_type` varchar(45) DEFAULT NULL,
  `charges_code` varchar(45) DEFAULT NULL,
  `description` text,
  `uom` varchar(45) DEFAULT NULL,
  `unit_price` float DEFAULT NULL,
  `with_penalty` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `charges_setup`
--

INSERT INTO `charges_setup` (`id`, `charges_type`, `charges_code`, `description`, `uom`, `unit_price`, `with_penalty`) VALUES
(1, 'Monthly Charges', 'PC000001', 'Aircon', 'Per Square Meter', 100, 'Yes'),
(2, 'Monthly Charges', 'PC000002', 'Water', 'Per Cubic Meter', 52, 'Yes'),
(3, 'Monthly Charges', 'PC000003', 'Chilled Water', 'Per Ton', 1659.5, 'Yes'),
(4, 'Monthly Charges', 'PC000004', 'Common Usage Charges', 'Per Square Meter', 100, 'Yes'),
(5, 'Monthly Charges', 'PC000005', 'Electricity', 'Per Kilowatt Hour', 11, 'Yes'),
(6, 'Monthly Charges', 'PC000006', 'Escalator', 'Fixed Amount', 1000, 'Yes'),
(7, 'Monthly Charges', 'PC000007', 'Gas', 'Inputted', 0, 'Yes'),
(8, 'Monthly Charges', 'PC000008', 'Pest Control', 'Per Square Meter', 10, 'Yes'),
(9, 'Other Charges', 'PC000009', 'Employee ID', 'Per Piece', 35, 'Yes'),
(11, 'Other Charges', 'PC000011', 'Fax', 'Per Page', 5, 'Yes'),
(13, 'Pre Operation Charges', 'PC000012', 'Advance Rent', '1', 0, 'No'),
(14, 'Pre Operation Charges', 'PC000014', 'Security Deposit', '6', 0, 'No'),
(15, 'Pre Operation Charges', 'PC000015', 'Construction Bond', '1', 0, 'No'),
(16, 'Overtime Works Charges', 'PC000016', 'Food Tenants', 'Per Hour', 55, 'Yes'),
(17, 'Overtime Works Charges', 'PC000017', 'Non-Food Tenant', 'Per Hour', 100, 'Yes'),
(18, 'Penalty Charges', 'PC000018', 'Penalty for late Opening and Early Closing', 'Inputted', 0, 'Yes'),
(20, 'Other Charges', 'PC000020', 'Worker ID', 'Per Piece', 35, 'Yes'),
(21, 'Construction Materials', 'PC000021', 'Plywood', 'Per Feet', 400, 'Yes'),
(22, 'Construction Materials', 'PC000022', 'PVC Door and Lock Set', 'Per Linear', 1400, 'No'),
(23, 'Monthly Charges', 'PC000023', 'Bio Augmentation', 'Per Grease Trap', 2900, 'Yes'),
(24, 'Monthly Charges', 'PC000024', 'Service Request', 'Per Hour', 50, 'Yes'),
(25, 'Monthly Charges', 'PC000025', 'Overtime and Overnight', 'Inputted', 0, 'Yes'),
(26, 'Other Charges', 'PC000026', 'Fixed Asset Rental', 'Inputted', 100, 'Yes'),
(27, 'Other Charges', 'PC000027', 'Motorcade Charges', 'Fixed Amount', 1000, 'Yes'),
(28, 'Other Charges', 'PC000028', 'Security Charges', 'Inputted', 0, 'Yes'),
(29, 'Other Charges', 'PC000029', 'Exhaust Duct Cleaning Charges', 'Inputted', 0, 'Yes'),
(30, 'Other Charges', 'PC000030', 'Neon Lights', 'Per Piece', 100, 'Yes'),
(31, 'Other Charges', 'PC000031', 'Roll Up Door', 'Fixed Amount', 1500, 'Yes'),
(32, 'Other Charges', 'PC000032', 'Training Room Charges', 'Per Hour', 100, 'Yes'),
(33, 'Other Charges', 'PC000033', 'Storage Room Charges', 'Inputted', 0, 'Yes'),
(34, 'Pre Operation Charges', 'PC000034', 'Security Deposit - Kiosk and Cart', '3', 0, 'No'),
(35, 'Other Charges', 'PC000035', 'Expanded Withholding Tax', 'Inputted', 0, 'Yes'),
(36, 'Other Charges', 'PC000036', 'Adbox Charges', 'Per Piece', 500, 'Yes'),
(37, 'Penalty Charges', 'PC000037', 'Unauthorized Closure', 'Inputted', 0, 'Yes'),
(38, 'Penalty Charges', 'PC000038', 'Houserules Violation', 'Inputted', 0, 'Yes'),
(39, 'Other Charges', 'PC000039', 'Notary Fee', 'Per Contract', 200, 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `com_prof`
--

CREATE TABLE IF NOT EXISTS `com_prof` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prospect_id` int(11) DEFAULT NULL,
  `file_name` varchar(100) DEFAULT NULL,
  `flag` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `contract_docs`
--

CREATE TABLE IF NOT EXISTS `contract_docs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) DEFAULT NULL,
  `file_name` varchar(100) DEFAULT NULL,
  `flag` varchar(20) DEFAULT NULL,
  `status` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `dti_busireg`
--

CREATE TABLE IF NOT EXISTS `dti_busireg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prospect_id` int(11) DEFAULT NULL,
  `file_name` varchar(100) DEFAULT NULL,
  `flag` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `error_log`
--

CREATE TABLE IF NOT EXISTS `error_log` (
  `id` int(11) NOT NULL,
  `action` varchar(100) DEFAULT NULL,
  `error_msg` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `exhibitor_attachements`
--

CREATE TABLE IF NOT EXISTS `exhibitor_attachements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prospect_id` int(11) DEFAULT NULL,
  `file_name` varchar(100) DEFAULT NULL,
  `flag` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `exhibit_rates`
--

CREATE TABLE IF NOT EXISTS `exhibit_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `price_floor_id` int(11) DEFAULT NULL,
  `floor_area` float DEFAULT NULL,
  `category` varchar(64) DEFAULT NULL,
  `location_code` varchar(20) DEFAULT NULL,
  `status` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `floors`
--

CREATE TABLE IF NOT EXISTS `floors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(10) DEFAULT NULL,
  `floor_name` varchar(50) DEFAULT NULL,
  `model` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `floors`
--

INSERT INTO `floors` (`id`, `store_id`, `floor_name`, `model`) VALUES
(1, 1, 'First Floor', '1508986082city.x3d'),
(2, 1, 'Second Floor', '1509007707lg.x3d'),
(3, 1, 'Third Floor', '15090083742ndFloor.x3d'),
(4, 1, 'Fourth Floor', '1509008803ug.x3d'),
(5, 2, 'LG', '1509172895lg.x3d'),
(6, 2, 'UG', ''),
(7, 2, 'Second Floor', '');

-- --------------------------------------------------------

--
-- Table structure for table `general_ledger`
--

CREATE TABLE IF NOT EXISTS `general_ledger` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `posting_date` date DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `document_type` varchar(100) DEFAULT NULL,
  `ref_no` varchar(100) DEFAULT NULL,
  `doc_no` varchar(100) DEFAULT NULL,
  `tenant_id` varchar(100) DEFAULT NULL,
  `gl_accountID` int(11) DEFAULT NULL,
  `company_code` varchar(100) DEFAULT NULL,
  `department_code` varchar(45) DEFAULT NULL,
  `debit` float DEFAULT NULL,
  `credit` float DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_code` varchar(100) DEFAULT NULL,
  `tag` varchar(45) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `prepared_by` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `gl_accounts`
--

CREATE TABLE IF NOT EXISTS `gl_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gl_code` varchar(100) DEFAULT NULL,
  `gl_account` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Dumping data for table `gl_accounts`
--

INSERT INTO `gl_accounts` (`id`, `gl_code`, `gl_account`) VALUES
(3, '10.10.01.01.02', 'Cash in Bank (Peso)'),
(4, '10.10.01.03.16', 'Rent Receivables'),
(5, '10.10.01.06.05', 'Creditable WHT Receivable'),
(6, '10.20.01.01.01.14', 'VAT Output'),
(7, '10.20.01.01.02.01', 'Unearned Rent Income'),
(8, '10.20.01.01.03.10', 'Construction Bond'),
(9, '10.20.01.01.03.12', 'Security Deposit Payable'),
(10, '10.20.01.01.01.04', 'AP Non-Trade Internal'),
(11, '20.60.01', 'Rent Income'),
(12, '20.80.01.08.01', 'MI-Penalties'),
(13, '20.80.01.08.02', 'MI-Light and Power'),
(14, '20.80.01.08.03', 'MI-Common Utilities'),
(15, '20.80.01.08.04', 'MI-Aircon Charges'),
(16, '20.80.01.08.05', 'MI-Chilled Waters'),
(17, '20.80.01.08.07', 'MI-Charges'),
(18, '20.80.01.08.08', 'MI-Water'),
(19, '20.80.01.08.09', 'MI-Others'),
(20, '20.80.01.08.10', 'MI-Supplies'),
(21, '20.80.01.08.11', 'MI-Others (non-POS)'),
(22, '10.10.01.03.03', 'A/R  Non Trade External'),
(23, '10.10.01.03.11', 'Advances to/from AGC H.O'),
(24, '10.10.01.03.11.01', 'Advances to/from ICM'),
(25, '10.10.01.03.11.02', 'Advances to/from ASC TAGB'),
(26, '10.10.01.03.07.01', 'Check Receivable - PDC'),
(27, '10.10.01.01.01', 'RR Clearing'),
(28, '10.10.01.01.03', 'AR Clearing');

-- --------------------------------------------------------

--
-- Table structure for table `gsales_img`
--

CREATE TABLE IF NOT EXISTS `gsales_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prospect_id` int(11) DEFAULT NULL,
  `file_name` varchar(100) DEFAULT NULL,
  `flag` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `history_locationcode`
--

CREATE TABLE IF NOT EXISTS `history_locationcode` (
  `location_historyID` int(11) NOT NULL AUTO_INCREMENT,
  `locationCode_id` int(11) NOT NULL,
  `tenancy_type` varchar(20) NOT NULL,
  `floor_id` int(11) NOT NULL,
  `location_code` varchar(45) NOT NULL,
  `floor_area` float NOT NULL,
  `area_classification` varchar(100) NOT NULL,
  `area_type` varchar(100) NOT NULL,
  `rent_period` varchar(45) NOT NULL,
  `payment_mode` varchar(20) NOT NULL,
  `rental_rate` float NOT NULL,
  `modified_by` int(11) NOT NULL,
  `date_modified` date NOT NULL,
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`location_historyID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `intent_letter`
--

CREATE TABLE IF NOT EXISTS `intent_letter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prospect_id` int(11) DEFAULT NULL,
  `file_name` varchar(100) DEFAULT NULL,
  `flag` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `invoicing`
--

CREATE TABLE IF NOT EXISTS `invoicing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` varchar(45) NOT NULL,
  `contract_no` varchar(45) NOT NULL,
  `trade_name` varchar(100) NOT NULL,
  `doc_no` varchar(45) NOT NULL,
  `posting_date` date NOT NULL,
  `transaction_date` date NOT NULL,
  `due_date` date NOT NULL,
  `charges_type` varchar(45) NOT NULL,
  `charges_code` varchar(15) NOT NULL,
  `description` varchar(100) NOT NULL,
  `uom` varchar(25) NOT NULL,
  `prev_reading` float NOT NULL,
  `curr_reading` float NOT NULL,
  `unit_price` float NOT NULL,
  `total_unit` float NOT NULL,
  `expected_amt` float NOT NULL,
  `actual_amt` float NOT NULL,
  `balance` float NOT NULL,
  `total_gross` float NOT NULL,
  `store_code` varchar(10) NOT NULL,
  `flag` varchar(15) NOT NULL,
  `tag` varchar(15) NOT NULL,
  `with_penalty` varchar(45) NOT NULL,
  `status` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `doc_no` (`doc_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `leasee_type`
--

CREATE TABLE IF NOT EXISTS `leasee_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `leasee_type` varchar(64) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `leasee_type`
--

INSERT INTO `leasee_type` (`id`, `leasee_type`, `description`) VALUES
(1, 'RESTAURANT', 'Food Services'),
(2, 'BOTIQUE', 'Selling Area'),
(3, 'APPLIANCE', 'Appliance Description'),
(4, 'KIOSK', 'Kiosk'),
(5, 'MOTORS', 'Motors Description'),
(6, 'CARS', 'Cars Description'),
(7, 'SHOPS', 'Shops'),
(8, 'EXHIBITOR', 'Exhibitor'),
(9, 'APPAREL', 'Apparel'),
(10, 'CELLPHONES', 'Cellphones'),
(11, 'GADGETS', 'Gadgets'),
(12, 'ENTERTAINMENT', 'Entertainment'),
(13, 'OFFICE', 'Office'),
(14, 'SCHOOL SUPPLIES', 'School Supplies');

-- --------------------------------------------------------

--
-- Table structure for table `leasing_users`
--

CREATE TABLE IF NOT EXISTS `leasing_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `user_type` varchar(50) DEFAULT NULL,
  `user_group` int(10) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `leasing_users`
--

INSERT INTO `leasing_users` (`id`, `first_name`, `middle_name`, `last_name`, `username`, `password`, `user_type`, `user_group`, `status`) VALUES
(1, 'Cyril Andrew', 'Bermoy', 'Paredes', 'Admin123', 'e64b78fc3bc91bcbc7dc232ba8ec59e0', 'Administrator', 0, 'Active'),
(7, 'Lusan', '', 'Miculob', 'Cfs123', '0f2a7e944655bedf605586eafc0ac2ab', 'CFS', NULL, 'Active'),
(14, 'Michelle Marie', 'Lacre', 'Balabag', 'Amuser1', '57924fafacd3b1e141c9c7927f9b4b20', 'Documentation Officer', 1, 'Active'),
(15, 'Victorina', 'Balog', 'Bolanio', 'Amaccounting1', '57924fafacd3b1e141c9c7927f9b4b20', 'Accounting Staff', 1, 'Active'),
(16, 'Leo', '', 'Licoy', 'Ammanager1', '57924fafacd3b1e141c9c7927f9b4b20', 'Store Manager', 1, 'Active'),
(17, 'Joy', '', 'Namalata', 'Icmuser1', '57924fafacd3b1e141c9c7927f9b4b20', 'Documentation Officer', 2, 'Active'),
(18, 'Mario', '', 'Cirujales', 'Icmmanager1', '57924fafacd3b1e141c9c7927f9b4b20', 'Store Manager', 2, 'Active'),
(19, 'Ma. Luz', '', 'Alcala', 'Icmaccounting1', '57924fafacd3b1e141c9c7927f9b4b20', 'Accounting Staff', 2, 'Active'),
(20, 'Marlito', '', 'Uy', 'Gm123', 'a56020e72069bdabf7081abd4a7612b3', 'General Manager', 1, 'Active'),
(21, 'Rhea', '', 'Antiola', 'Icm-iad', '57924fafacd3b1e141c9c7927f9b4b20', 'IAD', 2, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `ledger`
--

CREATE TABLE IF NOT EXISTS `ledger` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `posting_date` date DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `document_type` varchar(45) DEFAULT NULL,
  `ref_no` varchar(25) DEFAULT NULL,
  `doc_no` varchar(45) DEFAULT NULL,
  `tenant_id` varchar(45) DEFAULT NULL,
  `contract_no` varchar(45) DEFAULT NULL,
  `description` text,
  `debit` float DEFAULT NULL,
  `credit` float DEFAULT NULL,
  `balance` float DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `charges_type` varchar(45) DEFAULT NULL,
  `with_penalty` varchar(45) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `bank_code` varchar(100) DEFAULT NULL,
  `flag` varchar(45) DEFAULT NULL,
  `prepared_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `location_code`
--

CREATE TABLE IF NOT EXISTS `location_code` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenancy_type` varchar(20) DEFAULT NULL,
  `floor_id` int(11) DEFAULT NULL,
  `slots_id` varchar(50) NOT NULL,
  `location_code` varchar(45) DEFAULT NULL,
  `location_desc` varchar(50) NOT NULL,
  `floor_area` float DEFAULT NULL,
  `area_classification` varchar(100) DEFAULT NULL,
  `area_type` varchar(100) DEFAULT NULL,
  `rent_period` varchar(45) DEFAULT NULL,
  `payment_mode` varchar(20) DEFAULT NULL,
  `rental_rate` float DEFAULT NULL,
  `status` varchar(25) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `date_modified` date DEFAULT NULL,
  `flag` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `location_slot`
--

CREATE TABLE IF NOT EXISTS `location_slot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slot_no` varchar(20) NOT NULL,
  `tenancy_type` varchar(20) NOT NULL,
  `floor_id` int(11) NOT NULL,
  `floor_area` int(11) NOT NULL,
  `rental_rate` float NOT NULL,
  `modified_by` int(11) NOT NULL,
  `modified_date` date NOT NULL,
  `flag` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `action` text,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ltenant`
--

CREATE TABLE IF NOT EXISTS `ltenant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prospect_id` int(11) NOT NULL,
  `tenant_id` varchar(45) NOT NULL,
  `store_code` varchar(20) NOT NULL,
  `contract_no` varchar(25) NOT NULL,
  `tin` varchar(20) NOT NULL,
  `rental_type` varchar(100) NOT NULL,
  `rent_percentage` float NOT NULL,
  `lease_period` varchar(10) NOT NULL,
  `opening_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `is_vat` varchar(10) NOT NULL,
  `bir_doc` varchar(100) NOT NULL,
  `price_persq` float NOT NULL,
  `floor_area` float NOT NULL,
  `monthly_rental` float NOT NULL,
  `actual_balance` float NOT NULL,
  `flag` varchar(20) NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `monthly_penalty`
--

CREATE TABLE IF NOT EXISTS `monthly_penalty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` varchar(15) DEFAULT NULL,
  `percent` float DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `collection_date` date DEFAULT NULL,
  `doc_no` varchar(15) DEFAULT NULL,
  `soa_no` varchar(20) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `balance` float DEFAULT NULL,
  `status` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `monthly_receivable_report`
--

CREATE TABLE IF NOT EXISTS `monthly_receivable_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` varchar(45) NOT NULL,
  `posting_date` date NOT NULL,
  `description` varchar(100) NOT NULL,
  `amount` float NOT NULL,
  `flag` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` varchar(20) DEFAULT NULL,
  `contract_no` varchar(45) DEFAULT NULL,
  `tenancy_type` varchar(20) DEFAULT NULL,
  `receipt_no` varchar(20) DEFAULT NULL,
  `soa_no` varchar(20) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `remarks` text,
  `description` varchar(45) DEFAULT NULL,
  `doc_no` varchar(15) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `posting_date` date DEFAULT NULL,
  `amount_due` float DEFAULT NULL,
  `total_balance` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `payment_scheme`
--

CREATE TABLE IF NOT EXISTS `payment_scheme` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` varchar(20) DEFAULT NULL,
  `contract_no` varchar(45) DEFAULT NULL,
  `tenancy_type` varchar(20) DEFAULT NULL,
  `receipt_no` varchar(20) DEFAULT NULL,
  `billing_period` varchar(100) NOT NULL,
  `tender_typeCode` varchar(2) DEFAULT NULL,
  `tender_typeDesc` varchar(45) DEFAULT NULL,
  `soa_no` varchar(45) NOT NULL,
  `amount_due` float NOT NULL,
  `amount_paid` float DEFAULT NULL,
  `bank` varchar(100) DEFAULT NULL,
  `bank_code` varchar(100) DEFAULT NULL,
  `check_no` varchar(20) DEFAULT NULL,
  `check_date` date DEFAULT NULL,
  `payor` varchar(100) DEFAULT NULL,
  `payee` varchar(100) DEFAULT NULL,
  `supp_doc` varchar(250) DEFAULT NULL,
  `receipt_doc` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `perspective`
--

CREATE TABLE IF NOT EXISTS `perspective` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prospect_id` int(11) DEFAULT NULL,
  `file_name` varchar(100) DEFAULT NULL,
  `flag` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `posted_charges`
--

CREATE TABLE IF NOT EXISTS `posted_charges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` varchar(50) DEFAULT NULL,
  `charges_type` varchar(45) DEFAULT NULL,
  `charges_code` varchar(45) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  `uom` varchar(100) DEFAULT NULL,
  `unit_price` float DEFAULT NULL,
  `total_unit` float DEFAULT NULL,
  `actual_amount` float DEFAULT NULL,
  `flag` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pricemenu_list`
--

CREATE TABLE IF NOT EXISTS `pricemenu_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prospect_id` int(11) DEFAULT NULL,
  `file_name` varchar(100) DEFAULT NULL,
  `flag` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `prospect`
--

CREATE TABLE IF NOT EXISTS `prospect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trade_name` varchar(100) DEFAULT NULL,
  `corporate_name` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `lesseeType_id` int(11) DEFAULT NULL,
  `first_category` varchar(100) DEFAULT NULL,
  `second_category` varchar(100) DEFAULT NULL,
  `third_category` varchar(100) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `contact_number` varchar(45) DEFAULT NULL,
  `store_id` int(11) DEFAULT NULL,
  `remarks` text,
  `request_date` date DEFAULT NULL,
  `approved_date` date DEFAULT NULL,
  `flag` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `prepared_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rental_increment`
--

CREATE TABLE IF NOT EXISTS `rental_increment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `rental_increment`
--

INSERT INTO `rental_increment` (`id`, `amount`) VALUES
(1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `rent_period`
--

CREATE TABLE IF NOT EXISTS `rent_period` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenancy_type` varchar(45) DEFAULT NULL,
  `number` float DEFAULT NULL,
  `uom` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `selected_banks`
--

CREATE TABLE IF NOT EXISTS `selected_banks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_id` int(11) NOT NULL,
  `bank_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `selected_discount`
--

CREATE TABLE IF NOT EXISTS `selected_discount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` varchar(45) DEFAULT NULL,
  `discount_id` int(11) DEFAULT NULL,
  `status` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `selected_monthly_charges`
--

CREATE TABLE IF NOT EXISTS `selected_monthly_charges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` varchar(20) DEFAULT NULL,
  `monthly_chargers_id` int(11) DEFAULT NULL,
  `flag` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `soa`
--

CREATE TABLE IF NOT EXISTS `soa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` varchar(15) NOT NULL,
  `store_code` varchar(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `soa_no` varchar(20) NOT NULL,
  `contract_no` varchar(45) NOT NULL,
  `billing_period` varchar(45) NOT NULL,
  `collection_date` date NOT NULL,
  `flag` varchar(45) NOT NULL,
  `status` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `soa_file`
--

CREATE TABLE IF NOT EXISTS `soa_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` varchar(20) NOT NULL,
  `file_name` varchar(100) NOT NULL,
  `soa_no` varchar(15) NOT NULL,
  `billing_period` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE IF NOT EXISTS `stores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `store_name` varchar(50) DEFAULT NULL,
  `company_code` varchar(45) NOT NULL,
  `store_code` varchar(50) DEFAULT NULL,
  `store_address` varchar(100) DEFAULT NULL,
  `contact_person` varchar(100) NOT NULL,
  `contact_no` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `logo` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `store_name`, `company_code`, `store_code`, `store_address`, `contact_person`, `contact_no`, `email`, `logo`) VALUES
(1, 'ALTURAS MALL', '01.01', 'AM', 'Tagbilaran City', 'Ararao, Ma. Editha', '501-8648', 'alturasleasing@gmail.com', '1508986052logo-am.png'),
(2, 'Island City Mall', '01.04', 'ICM', 'Dampas Dist. Tagb. City', 'Karen Longjas', '411-265644', 'leasing_icm@yahoo.com', '1509172865logo-icm.png');

-- --------------------------------------------------------

--
-- Table structure for table `subsidiary_ledger`
--

CREATE TABLE IF NOT EXISTS `subsidiary_ledger` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `posting_date` date NOT NULL,
  `transaction_date` date NOT NULL,
  `due_date` date NOT NULL,
  `document_type` varchar(100) NOT NULL,
  `ref_no` varchar(100) NOT NULL,
  `doc_no` varchar(100) NOT NULL,
  `tenant_id` varchar(100) NOT NULL,
  `gl_accountID` int(11) NOT NULL,
  `company_code` varchar(45) NOT NULL,
  `department_code` varchar(45) NOT NULL,
  `debit` float NOT NULL,
  `credit` float NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `bank_code` varchar(45) NOT NULL,
  `tag` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  `prepared_by` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE IF NOT EXISTS `tenants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prospect_id` int(11) NOT NULL,
  `tenant_id` varchar(20) NOT NULL,
  `locationCode_id` int(11) NOT NULL,
  `store_code` varchar(20) NOT NULL,
  `tenancy_type` varchar(15) NOT NULL,
  `contract_no` varchar(45) NOT NULL,
  `tin` varchar(15) NOT NULL,
  `rental_type` varchar(45) NOT NULL,
  `rent_percentage` float NOT NULL,
  `opening_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `tenant_type` varchar(100) NOT NULL,
  `increment_percentage` int(11) NOT NULL,
  `increment_frequency` varchar(40) NOT NULL,
  `is_vat` varchar(10) NOT NULL,
  `bir_doc` varchar(100) NOT NULL,
  `basic_rental` float NOT NULL,
  `status` varchar(15) NOT NULL,
  `flag` varchar(100) NOT NULL,
  `created_at` date NOT NULL,
  `prepared_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tenanttype_supportingdocs`
--

CREATE TABLE IF NOT EXISTS `tenanttype_supportingdocs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` varchar(45) NOT NULL,
  `file_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_type`
--

CREATE TABLE IF NOT EXISTS `tenant_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_type` varchar(64) NOT NULL,
  `discount_type` varchar(50) NOT NULL,
  `discount` decimal(10,0) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tenant_type`
--

INSERT INTO `tenant_type` (`id`, `tenant_type`, `discount_type`, `discount`, `description`) VALUES
(1, 'Business Partner', 'Fixed Amount', '1500', 'Business Partner'),
(2, 'Friends Discount', 'Percentage', '5', 'Friends');

-- --------------------------------------------------------

--
-- Table structure for table `terminated_contract`
--

CREATE TABLE IF NOT EXISTS `terminated_contract` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `termination_date` date NOT NULL,
  `terminated_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_latepaymentpenalty`
--

CREATE TABLE IF NOT EXISTS `tmp_latepaymentpenalty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` varchar(45) NOT NULL,
  `doc_no` varchar(100) NOT NULL,
  `posting_date` date NOT NULL,
  `contract_no` varchar(45) NOT NULL,
  `description` text NOT NULL,
  `amount` float NOT NULL,
  `flag` varchar(45) NOT NULL,
  `soa` varchar(45) NOT NULL,
  `soa_no` varchar(45) NOT NULL,
  `invoice_no` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_preoperationcharges`
--

CREATE TABLE IF NOT EXISTS `tmp_preoperationcharges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` varchar(45) NOT NULL,
  `doc_no` varchar(45) NOT NULL,
  `description` text NOT NULL,
  `posting_date` date NOT NULL,
  `due_date` date NOT NULL,
  `amount` float NOT NULL,
  `tag` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `uft_payment`
--

CREATE TABLE IF NOT EXISTS `uft_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tenant_id` varchar(100) NOT NULL,
  `bank_code` varchar(100) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `posting_date` date NOT NULL,
  `amount_payable` float NOT NULL,
  `amount_paid` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `vat`
--

CREATE TABLE IF NOT EXISTS `vat` (
  `vat` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vat`
--

INSERT INTO `vat` (`vat`) VALUES
(12);

-- --------------------------------------------------------

--
-- Table structure for table `waived_penalties`
--

CREATE TABLE IF NOT EXISTS `waived_penalties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sl_id` int(11) NOT NULL,
  `attachment` varchar(100) NOT NULL,
  `prepared_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `withholding_tax`
--

CREATE TABLE IF NOT EXISTS `withholding_tax` (
  `withholding` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `withholding_tax`
--

INSERT INTO `withholding_tax` (`withholding`) VALUES
(5);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
