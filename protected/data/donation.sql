-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Palvelin: localhost
-- Luontiaika: 15.03.2014 klo 11:10
-- Palvelimen versio: 10.0.7-MariaDB-1~raring-log
-- PHP:n versio: 5.5.3-1ubuntu2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Tietokanta: `ns2stats_dev`
--

-- --------------------------------------------------------

--
-- Rakenne taululle `donation`
--

CREATE TABLE IF NOT EXISTS `donation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_name` varchar(255) DEFAULT NULL,
  `residence_country` varchar(50) DEFAULT NULL,
  `payer_status` varchar(50) DEFAULT NULL,
  `txn_id` varchar(50) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `item_number` varchar(50) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL,
  `mc_fee` decimal(10,0) DEFAULT NULL,
  `mc_gross` decimal(10,0) DEFAULT NULL,
  `custom` varchar(255) DEFAULT NULL,
  `ipn_track_id` varchar(50) DEFAULT NULL,
  `payer_email` varchar(255) DEFAULT NULL,
  `receiver_email` varchar(255) DEFAULT NULL,
  `mc_currency` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
