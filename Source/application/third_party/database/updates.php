<?php

$update[] = "ALTER TABLE `AWB` ADD `fedex_AWB_no` VARCHAR( 255 ) NULL DEFAULT NULL AFTER `AWB_no` ,
ADD UNIQUE (`fedex_AWB_no`);";

$update[] = "ALTER TABLE `AWB_status` ADD `fedex_AWB_no` VARCHAR( 255 ) NULL DEFAULT NULL AFTER `AWB_no` ,
ADD UNIQUE (`fedex_AWB_no`);";

$update[] = "ALTER TABLE `merchants` ADD `fedex_account_no` VARCHAR( 255 ) NOT NULL AFTER `account_no` ;";


$update[] = "ALTER TABLE `merchants` ADD `integra_abbreviation` VARCHAR( 255 ) NOT NULL AFTER `fedex_account_no` ;";


$update[] = "ALTER TABLE `AWB_status` CHANGE `status` `status` ENUM( 'processing', 'delivered', 'returned to shipper', 'SIP' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'processing';";


/* 15/2/2015 */
$update[] = "CREATE TABLE IF NOT EXISTS `branches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title_en` varchar(255) NOT NULL,
  `title_ar` varchar(255) NOT NULL,
  `lat` float NOT NULL,
  `lng` float NOT NULL,
  `text_en` text NOT NULL,
  `text_ar` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$update[] = "CREATE TABLE IF NOT EXISTS `services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title_en` varchar(255) NOT NULL,
  `title_ar` varchar(255) NOT NULL,
  `brief_en` varchar(255) NOT NULL,
  `brief_ar` varchar(255) NOT NULL,
  `type` enum('International','Domestic') NOT NULL,
  `color` enum('service-block-u','service-block-blue','service-block-dark','service-block-red','service-block-purple','service-block-grey','service-block-yellow','service-block-dark-blue') NOT NULL,
  `desc_en` text NOT NULL,
  `desc_ar` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

// 2015-02-23
$update[] = "ALTER TABLE `accounts` CHANGE `commercial_register` `commercial_register` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL ;";

$update[] = "
CREATE TABLE IF NOT EXISTS `notification_emails` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `notification` varchar(255) NOT NULL,
  `emails` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `notification_emails`
--

INSERT INTO `notification_emails` (`id`, `notification`, `emails`) VALUES
(1, 'new_account', 'admin@egyptexpress-eg.com,admin@egyptexpress-eg.com'),
(2, 'pickup_request', 'admin@egyptexpress-eg.com,admin@egyptexpress-eg.com');";

$update[] = "
CREATE TABLE IF NOT EXISTS `contactus_departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `department` varchar(255) NOT NULL,
  `emails` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `contactus_departments`
--

INSERT INTO `contactus_departments` (`id`, `department`, `emails`) VALUES
(1, 'support', 'admin@egyptexpress-eg.com'),
(2, 'finance', 'admin@egyptexpress-eg.com');";

/* this update is in 5/3/2015 */
$update[] = "ALTER TABLE `pickup_requests` CHANGE `pickup_address` `source_pickup_address` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `pickup_city` `source_pickup_city` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
CHANGE `governorate` `source_governorate` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;";

$update[] = "ALTER TABLE `pickup_requests` ADD `destination_pickup_address` TEXT NOT NULL AFTER `source_governorate` ,
ADD `destination_pickup_city` VARCHAR( 255 ) NOT NULL AFTER `destination_pickup_address` ,
ADD `destination_governorate` VARCHAR( 255 ) NOT NULL AFTER `destination_pickup_city` ;";


/* 8/3/2015 */
$update[] = "ALTER TABLE `slider` DROP `title_en`";
$update[] = "ALTER TABLE `slider` DROP `title_ar`";
// do not forget to edit the values of the socila media links in the translation table
// 2015-03-12
$update[] = "
CREATE TABLE IF NOT EXISTS `AWB_log` (
  `AWB_no` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `account_id` int(10) unsigned NOT NULL,
  `recipient_name` varchar(255) NOT NULL,
  `recipient_phone` varchar(255) NOT NULL,
  `recipient_city` varchar(255) NOT NULL,
  `recipient_address1` varchar(255) NOT NULL,
  `recipient_address2` varchar(255) DEFAULT NULL,
  `COD_amount` float NOT NULL DEFAULT '0',
  `no_of_pieces` smallint(5) unsigned NOT NULL,
  `weight` float NOT NULL,
  `dimensions` varchar(255) NOT NULL,
  `goods_description` text NOT NULL,
  `notes` text,
  `bill_date` datetime NOT NULL,
  `status` TINYINT NOT NULL ,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,

  PRIMARY KEY (`AWB_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";

$update[] = "ALTER TABLE `branches` CHANGE `lat` `lat` VARCHAR( 50 ) NOT NULL ,
CHANGE `lng` `lng` VARCHAR( 50 ) NOT NULL ;";


$update[]="ALTER TABLE `contact_inquiries` ADD `phone` INT NOT NULL AFTER `email`";
?>
