-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 09, 2014 at 03:52 PM
-- Server version: 5.1.72
-- PHP Version: 5.3.5-1ubuntu7.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fedex_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `AWB`
--

DROP TABLE IF EXISTS `AWB`;
CREATE TABLE IF NOT EXISTS `AWB` (
  `AWB_no` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `merchant_id` int(10) unsigned NOT NULL,
  `shipper_name` varchar(255) NOT NULL,
  `shipper_phone` varchar(255) NOT NULL,
  `shipper_mobile` varchar(255) DEFAULT NULL,
  `shipper_city` varchar(255) NOT NULL,
  `shipper_address1` varchar(255) NOT NULL,
  `shipper_address2` varchar(255) DEFAULT NULL,
  `shipper_country` varchar(255) DEFAULT NULL,
  `shipper_fax` varchar(255) DEFAULT NULL,
  `recipient_name` varchar(255) NOT NULL,
  `recipient_phone` varchar(255) NOT NULL,
  `recipient_mobile` varchar(255) DEFAULT NULL,
  `recipient_city` varchar(255) NOT NULL,
  `recipient_address1` varchar(255) NOT NULL,
  `recipient_address2` varchar(255) DEFAULT NULL,
  `recipient_country` varchar(255) DEFAULT NULL,
  `package_ref1` varchar(255) DEFAULT NULL,
  `package_ref2` varchar(255) DEFAULT NULL,
  `pickup_location` varchar(255) DEFAULT NULL,
  `payment_method` enum('prepaid','COD') NOT NULL,
  `COD_amount` float NOT NULL DEFAULT '0',
  `no_of_pieces` smallint(5) unsigned NOT NULL,
  `weight` float NOT NULL,
  `dimensions` varchar(255) NOT NULL,
  `goods_description` text NOT NULL,
  `goods_origin_country` varchar(255) NOT NULL,
  `product_group` varchar(255) DEFAULT NULL,
  `product_type` varchar(255) DEFAULT NULL,
  `notes` text,
  `bill_date` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`AWB_no`),
  KEY `merchant_id` (`merchant_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=78 ;

-- --------------------------------------------------------

--
-- Table structure for table `AWB_status`
--

DROP TABLE IF EXISTS `AWB_status`;
CREATE TABLE IF NOT EXISTS `AWB_status` (
  `AWB_no` bigint(20) unsigned NOT NULL,
  `status` enum('processing','delivered','failed') NOT NULL DEFAULT 'processing',
  `merchant_id` int(10) unsigned NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`AWB_no`),
  KEY `merchant_id` (`merchant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
CREATE TABLE IF NOT EXISTS `cities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(4) NOT NULL,
  `name` varchar(100) NOT NULL,
  `station` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=68 ;

-- --------------------------------------------------------

--
-- Table structure for table `merchants`
--

DROP TABLE IF EXISTS `merchants`;
CREATE TABLE IF NOT EXISTS `merchants` (
  `account_no` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `private_key` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','blocked') NOT NULL DEFAULT 'active',
  `allowed_ips` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`account_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `transactions_log`
--

DROP TABLE IF EXISTS `transactions_log`;
CREATE TABLE IF NOT EXISTS `transactions_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `merchant_id` int(10) unsigned NOT NULL,
  `AWB_no` bigint(10) unsigned DEFAULT NULL,
  `type` enum('success','error','warning') NOT NULL DEFAULT 'success',
  `action` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `merchant_id` (`merchant_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=402 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `AWB`
--
ALTER TABLE `AWB`
  ADD CONSTRAINT `AWB_ibfk_1` FOREIGN KEY (`merchant_id`) REFERENCES `merchants` (`account_no`) ON UPDATE CASCADE;

--
-- Constraints for table `AWB_status`
--
ALTER TABLE `AWB_status`
  ADD CONSTRAINT `AWB_status_ibfk_1` FOREIGN KEY (`AWB_no`) REFERENCES `AWB` (`AWB_no`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `AWB_status_ibfk_2` FOREIGN KEY (`merchant_id`) REFERENCES `merchants` (`account_no`) ON UPDATE CASCADE;

--
-- Constraints for table `transactions_log`
--
ALTER TABLE `transactions_log`
  ADD CONSTRAINT `transactions_log_ibfk_1` FOREIGN KEY (`merchant_id`) REFERENCES `merchants` (`account_no`) ON UPDATE CASCADE;
