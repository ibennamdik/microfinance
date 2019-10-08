-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 07, 2019 at 09:26 PM
-- Server version: 5.7.24
-- PHP Version: 7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `namlcomn_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `mc_activeinvestments`
--

DROP TABLE IF EXISTS `mc_activeinvestments`;
CREATE TABLE IF NOT EXISTS `mc_activeinvestments` (
  `investment_id` int(11) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mc_activeinvestments`
--

INSERT INTO `mc_activeinvestments` (`investment_id`, `customer_id`) VALUES
(1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `mc_activeloans`
--

DROP TABLE IF EXISTS `mc_activeloans`;
CREATE TABLE IF NOT EXISTS `mc_activeloans` (
  `loan_id` int(11) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mc_activeloans`
--

INSERT INTO `mc_activeloans` (`loan_id`, `customer_id`) VALUES
(1, 2),
(2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mc_customers`
--

DROP TABLE IF EXISTS `mc_customers`;
CREATE TABLE IF NOT EXISTS `mc_customers` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(255) NOT NULL,
  `customer_status` int(3) DEFAULT NULL,
  `type_flag` int(3) DEFAULT NULL,
  `firstname` text,
  `lastname` text,
  `phone` varchar(11) NOT NULL,
  `email` varchar(64) DEFAULT NULL,
  `address` varchar(50) DEFAULT NULL,
  `customer_image` varchar(200) DEFAULT 'img/customers/customer.jpg',
  `timestamp_created` datetime DEFAULT CURRENT_TIMESTAMP,
  `staff` varchar(51) DEFAULT NULL,
  `savings_balance` double DEFAULT '0',
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mc_customers`
--

INSERT INTO `mc_customers` (`customer_id`, `password`, `customer_status`, `type_flag`, `firstname`, `lastname`, `phone`, `email`, `address`, `customer_image`, `timestamp_created`, `staff`, `savings_balance`) VALUES
(1, '467ee2dbe7c93a250754b7fbca5b9fda1ec85fd66a985264dd07481b878dfd8b2', 2, 1, 'Martha', 'Ikeh', '07061325694', 'endee09@gmail.com', 'Ebonyi', 'img/customers/customer.jpg', '2018-01-28 05:24:49', NULL, 600000),
(2, '9fa531e92f75089b9a5e77ee8ab9ce5f45569e70935265061955bf5caa6640d6d', 1, 1, 'John', 'Doe', '12345678909', 'customer@gmail.com', 'Abuja', 'img/customers/customer.jpg', '2019-10-07 20:56:05', NULL, 0),
(3, '21b6f7d76d06a2860b5186c2ef117140058f56b2d1f2d6b2cccfe6e346e5874b3', 1, 2, 'Mark', 'Hanie', '12345678908', 'customer2@gmail.com', 'Lagos', 'img/customers/customer.jpg', '2019-10-07 22:15:24', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `mc_customer_status`
--

DROP TABLE IF EXISTS `mc_customer_status`;
CREATE TABLE IF NOT EXISTS `mc_customer_status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_name` varchar(30) DEFAULT NULL,
  `status_description` varchar(50) DEFAULT NULL,
  `status_flag` int(1) DEFAULT NULL,
  `timestamp_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `staff` varchar(21) NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mc_customer_status`
--

INSERT INTO `mc_customer_status` (`status_id`, `status_name`, `status_description`, `status_flag`, `timestamp_updated`, `staff`) VALUES
(1, 'Saver', 'Owing no loans, having savings', 2, '2017-12-27 07:10:31', ''),
(2, 'Active Loan', 'Owing a Loan, Under Due date', 3, '2017-12-27 07:11:02', ''),
(3, 'Defaulter', 'Owing a Loan, Past Due date', 4, '2017-12-26 21:18:28', ''),
(4, 'Disabled', 'Inactive from system', 5, '2017-12-26 21:18:32', ''),
(5, 'New Client', 'No Savings, No loans', 1, '2017-12-27 07:10:07', '');

-- --------------------------------------------------------

--
-- Table structure for table `mc_customer_types`
--

DROP TABLE IF EXISTS `mc_customer_types`;
CREATE TABLE IF NOT EXISTS `mc_customer_types` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(30) DEFAULT NULL,
  `type_description` varchar(50) DEFAULT NULL,
  `type_flag` int(1) DEFAULT NULL,
  `timestamp_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `staff` varchar(21) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mc_customer_types`
--

INSERT INTO `mc_customer_types` (`type_id`, `type_name`, `type_description`, `type_flag`, `timestamp_updated`, `staff`) VALUES
(1, 'Regular', 'Saves, Takes Loans', 1, '2017-12-27 05:04:28', ''),
(2, 'Investor', 'Invests', 2, '2017-12-27 05:04:42', '');

-- --------------------------------------------------------

--
-- Table structure for table `mc_investments`
--

DROP TABLE IF EXISTS `mc_investments`;
CREATE TABLE IF NOT EXISTS `mc_investments` (
  `investment_id` int(11) NOT NULL AUTO_INCREMENT,
  `investment_status` int(3) DEFAULT NULL,
  `transaction_id` varchar(31) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `investment_date` datetime DEFAULT NULL,
  `investment_amount` double DEFAULT NULL,
  `liquidation_date` datetime DEFAULT NULL,
  `current_value` double DEFAULT NULL,
  `next_update_date` datetime DEFAULT NULL,
  `interest_rate` int(11) DEFAULT NULL,
  `interest_value` double DEFAULT NULL,
  `investment_staff` varchar(21) DEFAULT NULL,
  `liquidation_staff` varchar(21) DEFAULT NULL,
  PRIMARY KEY (`investment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mc_investments`
--

INSERT INTO `mc_investments` (`investment_id`, `investment_status`, `transaction_id`, `customer_id`, `investment_date`, `investment_amount`, `liquidation_date`, `current_value`, `next_update_date`, `interest_rate`, `interest_value`, `investment_staff`, `liquidation_staff`) VALUES
(1, 1, '1570482995499', 3, '2019-10-07 21:16:35', 10000000, NULL, 10000000, '2019-11-06 21:16:35', 5, 500000, 'manager', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mc_investment_status`
--

DROP TABLE IF EXISTS `mc_investment_status`;
CREATE TABLE IF NOT EXISTS `mc_investment_status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_name` varchar(30) NOT NULL,
  `status_flag` int(1) NOT NULL,
  `status_description` varchar(64) NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mc_investment_status`
--

INSERT INTO `mc_investment_status` (`status_id`, `status_name`, `status_flag`, `status_description`) VALUES
(1, 'Active', 1, 'Currently running, Not Elapsed'),
(2, 'Liquidated', 2, 'Paid off'),
(3, 'Terminated', 3, 'Currently running, Elapsed');

-- --------------------------------------------------------

--
-- Table structure for table `mc_lgas`
--

DROP TABLE IF EXISTS `mc_lgas`;
CREATE TABLE IF NOT EXISTS `mc_lgas` (
  `lga_id` int(11) NOT NULL AUTO_INCREMENT,
  `lga_name` varchar(21) NOT NULL,
  `state_id` int(11) DEFAULT NULL,
  `timestamp_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`lga_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mc_loans`
--

DROP TABLE IF EXISTS `mc_loans`;
CREATE TABLE IF NOT EXISTS `mc_loans` (
  `loan_id` int(11) NOT NULL AUTO_INCREMENT,
  `loan_status` int(3) DEFAULT NULL,
  `transaction_id` varchar(31) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `disburse_date` datetime DEFAULT NULL,
  `disburse_amount` double DEFAULT NULL,
  `loan_profit` double DEFAULT NULL,
  `repayment_date` datetime DEFAULT NULL,
  `repayment_amount` double DEFAULT NULL,
  `disburse_staff` varchar(21) DEFAULT NULL,
  `repayment_staff` varchar(21) DEFAULT NULL,
  `payed_date` datetime DEFAULT NULL,
  `payed_amount` double DEFAULT NULL,
  PRIMARY KEY (`loan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mc_loans`
--

INSERT INTO `mc_loans` (`loan_id`, `loan_status`, `transaction_id`, `customer_id`, `disburse_date`, `disburse_amount`, `loan_profit`, `repayment_date`, `repayment_amount`, `disburse_staff`, `repayment_staff`, `payed_date`, `payed_amount`) VALUES
(1, 1, '1570479426500', 2, '2019-10-07 20:17:06', 120000, 6000, '2019-11-06 20:17:06', 126000, 'manager', NULL, NULL, NULL),
(2, 1, '1570479458499', 1, '2019-10-07 20:17:38', 129000, 6450, '2019-11-06 20:17:38', 135450, 'manager', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mc_loan_status`
--

DROP TABLE IF EXISTS `mc_loan_status`;
CREATE TABLE IF NOT EXISTS `mc_loan_status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_name` varchar(30) NOT NULL,
  `status_flag` int(1) NOT NULL,
  `status_description` varchar(64) NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mc_loan_status`
--

INSERT INTO `mc_loan_status` (`status_id`, `status_name`, `status_flag`, `status_description`) VALUES
(1, 'Active Loan', 1, 'Currently running, Not Elapsed'),
(2, 'Paid', 2, 'Paid off'),
(3, 'Elapsed', 3, 'Currently running, Elapsed');

-- --------------------------------------------------------

--
-- Table structure for table `mc_logs`
--

DROP TABLE IF EXISTS `mc_logs`;
CREATE TABLE IF NOT EXISTS `mc_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `staff` varchar(64) DEFAULT NULL,
  `activity` text NOT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mc_logs`
--

INSERT INTO `mc_logs` (`log_id`, `staff`, `activity`, `timestamp_created`) VALUES
(1, 'Ixnote', 'Ixnote Registered a new customer: Ixnote Services</a>', '2018-01-28 04:24:49'),
(2, 'admin', 'admin Registered a new customer: John Doe', '2019-10-07 19:56:05'),
(3, 'admin', 'admin Registered a new customer: John Doe', '2019-10-07 19:56:05'),
(4, 'manager', 'manager Disbursed Loan of 120000 to John Doe', '2019-10-07 20:17:07'),
(5, 'manager', 'manager Disbursed Loan of 120000 to John Doe', '2019-10-07 20:17:07'),
(6, 'manager', 'manager Disbursed Loan of 129000 to Martha Ikeh', '2019-10-07 20:17:38'),
(7, 'manager', 'manager Disbursed Loan of 129000 to Martha Ikeh', '2019-10-07 20:17:38'),
(8, 'manager', 'manager Received Payment of 100000 from Martha Ikeh', '2019-10-07 20:17:56'),
(9, 'manager', 'manager Received Payment of 100000 from Martha Ikeh', '2019-10-07 20:17:56'),
(10, 'manager', 'manager Received Payment of 50000 from Martha Ikeh', '2019-10-07 20:18:09'),
(11, 'manager', 'manager Received Payment of 50000 from Martha Ikeh', '2019-10-07 20:18:09'),
(12, 'manager', 'manager Received Payment of 35000 from Martha Ikeh', '2019-10-07 20:18:20'),
(13, 'manager', 'manager Received Payment of 35000 from Martha Ikeh', '2019-10-07 20:18:20'),
(14, 'manager', 'manager Received Payment of 340000 from Martha Ikeh', '2019-10-07 21:01:38'),
(15, 'manager', 'manager Received Payment of 340000 from Martha Ikeh', '2019-10-07 21:01:38'),
(16, 'manager', 'manager Received Payment of 112000 from Martha Ikeh', '2019-10-07 21:01:50'),
(17, 'manager', 'manager Received Payment of 112000 from Martha Ikeh', '2019-10-07 21:01:50'),
(18, 'manager', 'manager Made a withdrawal of 10000 for Martha Ikeh', '2019-10-07 21:02:01'),
(19, 'manager', 'manager Made a withdrawal of 10000 for Martha Ikeh', '2019-10-07 21:02:01'),
(20, 'manager', 'manager Made a withdrawal of 27000 for Martha Ikeh', '2019-10-07 21:02:11'),
(21, 'manager', 'manager Made a withdrawal of 27000 for Martha Ikeh', '2019-10-07 21:02:11'),
(22, 'manager', 'manager Registered a new customer: Mark Hanie', '2019-10-07 21:15:24'),
(23, 'manager', 'manager Registered a new customer: Mark Hanie', '2019-10-07 21:15:24'),
(24, 'manager', 'manager Received an Investment Fund from Mark Hanie', '2019-10-07 21:16:35'),
(25, 'manager', 'manager Received an Investment Fund from Mark Hanie', '2019-10-07 21:16:35');

-- --------------------------------------------------------

--
-- Table structure for table `mc_receipts`
--

DROP TABLE IF EXISTS `mc_receipts`;
CREATE TABLE IF NOT EXISTS `mc_receipts` (
  `receipt_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `transaction_id` varchar(31) DEFAULT NULL,
  `transaction_type` varchar(30) NOT NULL,
  `amount` double NOT NULL,
  `staff` varchar(21) DEFAULT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`receipt_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mc_receipts`
--

INSERT INTO `mc_receipts` (`receipt_id`, `customer_id`, `transaction_id`, `transaction_type`, `amount`, `staff`, `timestamp_created`) VALUES
(1, 2, '1570479426500', 'Loan Disbursal', 120000, 'manager', '2019-10-07 20:17:07'),
(2, 1, '1570479458499', 'Loan Disbursal', 129000, 'manager', '2019-10-07 20:17:38'),
(3, 1, '1570479476499', 'Periodic Contributions', 100000, 'manager', '2019-10-07 20:17:56'),
(4, 1, '1570479488499', 'Periodic Contributions', 50000, 'manager', '2019-10-07 20:18:08'),
(5, 1, '1570479499500', 'Periodic Contributions', 35000, 'manager', '2019-10-07 20:18:19'),
(6, 1, '1570482098500', 'Periodic Contributions', 340000, 'manager', '2019-10-07 21:01:38'),
(7, 1, '1570482109500', 'Periodic Contributions', 112000, 'manager', '2019-10-07 21:01:49'),
(8, 1, '1570482121500', 'Savings Withdrawal', 10000, 'manager', '2019-10-07 21:02:01'),
(9, 1, '1570482131499', 'Savings Withdrawal', 27000, 'manager', '2019-10-07 21:02:11'),
(10, 3, '1570482995499', 'Investment', 10000000, 'manager', '2019-10-07 21:16:35');

-- --------------------------------------------------------

--
-- Table structure for table `mc_savings`
--

DROP TABLE IF EXISTS `mc_savings`;
CREATE TABLE IF NOT EXISTS `mc_savings` (
  `savings_id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(31) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `timestamp_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `staff` varchar(31) DEFAULT NULL,
  PRIMARY KEY (`savings_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mc_savings`
--

INSERT INTO `mc_savings` (`savings_id`, `transaction_id`, `customer_id`, `amount`, `timestamp_created`, `staff`) VALUES
(1, '1570479476499', 1, 100000, '2019-10-07 20:17:56', 'manager'),
(2, '1570479488499', 1, 50000, '2019-10-07 20:18:08', 'manager'),
(3, '1570479499500', 1, 35000, '2019-10-07 20:18:19', 'manager'),
(4, '1570482098500', 1, 340000, '2019-10-07 21:01:38', 'manager'),
(5, '1570482109500', 1, 112000, '2019-10-07 21:01:49', 'manager');

-- --------------------------------------------------------

--
-- Table structure for table `mc_settings_system`
--

DROP TABLE IF EXISTS `mc_settings_system`;
CREATE TABLE IF NOT EXISTS `mc_settings_system` (
  `company_id` int(6) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(30) NOT NULL,
  `company_address` varchar(50) NOT NULL,
  `company_phone` varchar(11) NOT NULL,
  `company_website_url` varchar(30) NOT NULL,
  `company_website_name` varchar(30) NOT NULL,
  `timestamp_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `staff` varchar(21) NOT NULL,
  PRIMARY KEY (`company_id`),
  UNIQUE KEY `company_id` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mc_settings_system`
--

INSERT INTO `mc_settings_system` (`company_id`, `company_name`, `company_address`, `company_phone`, `company_website_url`, `company_website_name`, `timestamp_updated`, `staff`) VALUES
(1, 'Crystale Medical Laboratories', 'No 4 Gomwalk Boulevard, Dogon Karfe, Jos', '08035968690', 'www.ixnoteservices.com', 'Ixnote Services', '2017-12-08 04:30:15', '0');

-- --------------------------------------------------------

--
-- Table structure for table `mc_states`
--

DROP TABLE IF EXISTS `mc_states`;
CREATE TABLE IF NOT EXISTS `mc_states` (
  `state_id` int(11) NOT NULL AUTO_INCREMENT,
  `state_name` varchar(21) NOT NULL,
  `timestamp_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`state_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mc_withdrawals`
--

DROP TABLE IF EXISTS `mc_withdrawals`;
CREATE TABLE IF NOT EXISTS `mc_withdrawals` (
  `withdrawal_id` int(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(31) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `amount` double NOT NULL,
  `timestamp_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `staff` varchar(21) NOT NULL,
  PRIMARY KEY (`withdrawal_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mc_withdrawals`
--

INSERT INTO `mc_withdrawals` (`withdrawal_id`, `transaction_id`, `customer_id`, `amount`, `timestamp_created`, `staff`) VALUES
(1, '1570482121500', 1, 10000, '2019-10-07 21:02:01', 'manager'),
(2, '1570482131499', 1, 27000, '2019-10-07 21:02:11', 'manager');

-- --------------------------------------------------------

--
-- Table structure for table `uc_configuration`
--

DROP TABLE IF EXISTS `uc_configuration`;
CREATE TABLE IF NOT EXISTS `uc_configuration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `value` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uc_configuration`
--

INSERT INTO `uc_configuration` (`id`, `name`, `value`) VALUES
(1, 'website_name', 'NAML'),
(2, 'website_url', 'naml.com.ng/portal/'),
(3, 'email', 'endee09@gmail.com'),
(4, 'activation', 'false'),
(5, 'resend_activation_threshold', '0'),
(6, 'language', 'models/languages/en.php'),
(7, 'template', 'models/site-templates/default.css');

-- --------------------------------------------------------

--
-- Table structure for table `uc_pages`
--

DROP TABLE IF EXISTS `uc_pages`;
CREATE TABLE IF NOT EXISTS `uc_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` varchar(150) NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `uc_pages`
--

INSERT INTO `uc_pages` (`id`, `page`, `private`) VALUES
(4, 'admin_page.php', 1),
(5, 'admin_pages.php', 1),
(6, 'admin_permission.php', 1),
(7, 'admin_permissions.php', 1),
(8, 'admin_user.php', 1),
(9, 'admin_users.php', 1),
(10, 'forgot-password.php', 0),
(11, 'index.php', 0),
(13, 'login.php', 0),
(16, 'resend-activation.php', 0),
(17, 'user_settings.php', 1),
(18, 'admin_register.php', 1),
(19, 'dashboard.php', 0),
(21, 'customers.php', 1),
(22, 'customer.php', 1),
(23, 'customer_records.php', 1),
(24, 'investments.php', 1),
(25, 'reports.php', 1),
(26, 'script_customeractions.php', 0),
(27, 'script_paymentactions.php', 0),
(28, 'logs.php', 1),
(29, 'script_staffactions.php', 0),
(30, 'customer_register.php', 0),
(31, 'customer_delete.php', 0);

-- --------------------------------------------------------

--
-- Table structure for table `uc_permissions`
--

DROP TABLE IF EXISTS `uc_permissions`;
CREATE TABLE IF NOT EXISTS `uc_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uc_permissions`
--

INSERT INTO `uc_permissions` (`id`, `name`) VALUES
(1, 'New Member'),
(2, 'Administrator'),
(3, 'Manager'),
(4, 'Auditor'),
(5, 'Teller');

-- --------------------------------------------------------

--
-- Table structure for table `uc_permission_page_matches`
--

DROP TABLE IF EXISTS `uc_permission_page_matches`;
CREATE TABLE IF NOT EXISTS `uc_permission_page_matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permission_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `uc_permission_page_matches`
--

INSERT INTO `uc_permission_page_matches` (`id`, `permission_id`, `page_id`) VALUES
(3, 1, 17),
(4, 2, 4),
(5, 2, 5),
(6, 2, 6),
(7, 2, 7),
(8, 2, 8),
(9, 2, 9),
(11, 2, 17),
(12, 2, 18),
(14, 2, 21),
(15, 2, 22),
(16, 2, 23),
(17, 2, 24),
(18, 2, 25),
(19, 2, 28),
(20, 3, 8),
(21, 3, 9),
(23, 3, 17),
(24, 3, 18),
(25, 3, 21),
(26, 3, 22),
(27, 3, 23),
(28, 3, 24),
(29, 3, 25),
(31, 4, 17),
(32, 4, 21),
(33, 4, 22),
(34, 4, 23),
(35, 4, 24),
(37, 5, 22),
(38, 5, 23),
(39, 5, 24);

-- --------------------------------------------------------

--
-- Table structure for table `uc_users`
--

DROP TABLE IF EXISTS `uc_users`;
CREATE TABLE IF NOT EXISTS `uc_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `display_name` varchar(50) NOT NULL,
  `password` varchar(225) NOT NULL,
  `email` varchar(150) NOT NULL,
  `activation_token` varchar(225) NOT NULL,
  `last_activation_request` int(11) NOT NULL,
  `lost_password_request` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `title` varchar(150) NOT NULL,
  `sign_up_stamp` int(11) NOT NULL,
  `last_sign_in_stamp` int(11) NOT NULL,
  `income_customers` int(11) DEFAULT '0',
  `income_profit` double DEFAULT '0',
  `income_contributions` double DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uc_users`
--

INSERT INTO `uc_users` (`id`, `user_name`, `display_name`, `password`, `email`, `activation_token`, `last_activation_request`, `lost_password_request`, `active`, `title`, `sign_up_stamp`, `last_sign_in_stamp`, `income_customers`, `income_profit`, `income_contributions`) VALUES
(1, 'admin', 'Admin', 'ca7c7d23ab40fbddcc070434a36d8c9f757059c6ae8e8fe9edcc64c46c37a930a', 'admin@gmail.com', 'fe295c8d0911bf9adbdbe06a4e59ae05', 1514283640, 0, 1, 'Administrator', 1514283640, 1517242288, 2, 0, 0),
(2, 'manager', 'Manager', 'b91aa9c49957f14ebcbaea2ba3ef507dd9637facbcbb58e3cd9a4d5a4610f12f0', 'manager@gmail.com', '6374e87190118405ee4cb2da6ffaafac', 1517041244, 0, 1, 'Manager', 1517041244, 1517241263, 1, 12450, 637000);

-- --------------------------------------------------------

--
-- Table structure for table `uc_user_permission_matches`
--

DROP TABLE IF EXISTS `uc_user_permission_matches`;
CREATE TABLE IF NOT EXISTS `uc_user_permission_matches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `uc_user_permission_matches`
--

INSERT INTO `uc_user_permission_matches` (`id`, `user_id`, `permission_id`) VALUES
(1, 1, 2),
(3, 2, 3);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
