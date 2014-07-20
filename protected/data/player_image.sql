-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Palvelin: localhost
-- Luontiaika: 17.01.2014 klo 08:09
-- Palvelimen versio: 10.0.7-MariaDB-1~raring-log
-- PHP:n versio: 5.5.3-1ubuntu2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Tietokanta: `ns2stats`
--

-- --------------------------------------------------------

--
-- Rakenne taululle `player_image`
--

CREATE TABLE IF NOT EXISTS `player_image` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image` mediumblob NOT NULL,
  `player_id` int(10) unsigned NOT NULL,
  `data` varchar(512) DEFAULT NULL,
  `background-image` mediumblob,
  PRIMARY KEY (`id`),
  KEY `player_id_foreign` (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Rajoitteet vedostauluille
--

--
-- Rajoitteet taululle `player_image`
--
ALTER TABLE `player_image`
  ADD CONSTRAINT `player_id key` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
