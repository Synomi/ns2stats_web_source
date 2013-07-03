-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 03, 2013 at 11:45 PM
-- Server version: 5.5.30
-- PHP Version: 5.4.4-14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ns2stats`
--

-- --------------------------------------------------------

--
-- Table structure for table `death`
--

CREATE TABLE IF NOT EXISTS `death` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attacker_id` int(10) unsigned DEFAULT NULL,
  `target_id` int(10) unsigned NOT NULL,
  `attacker_weapon_id` int(10) unsigned DEFAULT NULL,
  `target_weapon_id` int(10) unsigned NOT NULL,
  `attacker_lifeform_id` int(10) unsigned DEFAULT NULL,
  `target_lifeform_id` int(10) unsigned NOT NULL,
  `time` int(10) NOT NULL,
  `attacker_team` int(1) DEFAULT NULL,
  `target_team` int(1) DEFAULT NULL,
  `attacker_armor` int(3) DEFAULT NULL,
  `attacker_health` int(3) DEFAULT NULL,
  `attacker_x` decimal(7,4) DEFAULT NULL,
  `attacker_y` decimal(7,4) DEFAULT NULL,
  `attacker_z` decimal(7,4) DEFAULT NULL,
  `target_x` decimal(7,4) NOT NULL,
  `target_y` decimal(7,4) NOT NULL,
  `target_z` decimal(7,4) NOT NULL,
  `target_lifetime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_killinmatch_playerinmatch1` (`attacker_id`),
  KEY `fk_killinmatch_playerinmatch2` (`target_id`),
  KEY `fk_killinmatch_weapon1` (`attacker_weapon_id`),
  KEY `fk_killinmatch_lifeform1` (`attacker_lifeform_id`),
  KEY `fk_killinmatch_lifeform2` (`target_lifeform_id`),
  KEY `fk_killinmatch_weapon2` (`target_weapon_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11907412 ;

-- --------------------------------------------------------

--
-- Table structure for table `hit`
--

CREATE TABLE IF NOT EXISTS `hit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `attacker_id` int(10) unsigned NOT NULL,
  `target_id` int(10) unsigned DEFAULT NULL,
  `target_structure_id` int(10) unsigned DEFAULT NULL,
  `attacker_weapon_id` int(10) unsigned NOT NULL,
  `target_weapon_id` int(10) unsigned DEFAULT NULL,
  `attacker_lifeform_id` int(10) unsigned NOT NULL,
  `target_lifeform_id` int(10) unsigned DEFAULT NULL,
  `time` int(10) NOT NULL,
  `attacker_team` int(1) DEFAULT NULL,
  `target_team` int(1) DEFAULT NULL,
  `attacker_armor` int(3) DEFAULT NULL,
  `attacker_health` int(3) DEFAULT NULL,
  `attacker_x` decimal(7,4) DEFAULT NULL,
  `attacker_y` decimal(7,4) DEFAULT NULL,
  `attacker_z` decimal(7,4) DEFAULT NULL,
  `target_x` decimal(7,4) NOT NULL,
  `target_y` decimal(7,4) NOT NULL,
  `target_z` decimal(7,4) NOT NULL,
  `damage_type` int(1) unsigned NOT NULL,
  `damage` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_killinmatch_playerinmatch1` (`attacker_id`),
  KEY `fk_killinmatch_playerinmatch2` (`target_id`),
  KEY `fk_killinmatch_weapon1` (`attacker_weapon_id`),
  KEY `fk_killinmatch_lifeform1` (`attacker_lifeform_id`),
  KEY `fk_killinmatch_lifeform2` (`target_lifeform_id`),
  KEY `fk_killinmatch_weapon2` (`target_weapon_id`),
  KEY `fk_hit_round_structure1` (`target_structure_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=173734118 ;

-- --------------------------------------------------------

--
-- Table structure for table `lifeform`
--

CREATE TABLE IF NOT EXISTS `lifeform` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- Table structure for table `list`
--

CREATE TABLE IF NOT EXISTS `list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `description` text,
  `owner_id` int(10) unsigned NOT NULL,
  `type` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_list_player1` (`owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `list_admin`
--

CREATE TABLE IF NOT EXISTS `list_admin` (
  `list_id` int(10) unsigned NOT NULL,
  `player_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`list_id`,`player_id`),
  KEY `fk_list_has_player_player1` (`player_id`),
  KEY `fk_list_has_player_list1` (`list_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `live_player`
--

CREATE TABLE IF NOT EXISTS `live_player` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` int(10) unsigned NOT NULL,
  `last_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `json` text NOT NULL,
  `live_round_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`),
  KEY `live_round_id` (`live_round_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=55707 ;

-- --------------------------------------------------------

--
-- Table structure for table `live_round`
--

CREATE TABLE IF NOT EXISTS `live_round` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `server_id` int(10) unsigned NOT NULL,
  `last_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `players` int(11) NOT NULL,
  `gametime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `server_id` (`server_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3064 ;

-- --------------------------------------------------------

--
-- Table structure for table `map`
--

CREATE TABLE IF NOT EXISTS `map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `jsonvalues` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=87 ;

-- --------------------------------------------------------

--
-- Table structure for table `mod`
--

CREATE TABLE IF NOT EXISTS `mod` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `workshop_id` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=829 ;

-- --------------------------------------------------------

--
-- Table structure for table `mod_round`
--

CREATE TABLE IF NOT EXISTS `mod_round` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mod_id` int(11) unsigned NOT NULL,
  `round_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mod_round_mod` (`mod_id`),
  KEY `fk_mod_round_round1` (`round_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=473259 ;

-- --------------------------------------------------------

--
-- Table structure for table `pickable`
--

CREATE TABLE IF NOT EXISTS `pickable` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `commander_id` int(10) unsigned NOT NULL,
  `drop` int(10) unsigned NOT NULL,
  `pick` int(10) DEFAULT NULL,
  `destroy` int(10) DEFAULT NULL,
  `cost` int(2) unsigned NOT NULL,
  `team` int(1) unsigned NOT NULL,
  `name` varchar(32) NOT NULL,
  `x` decimal(7,4) NOT NULL,
  `y` decimal(7,4) NOT NULL,
  `z` decimal(7,4) NOT NULL,
  `instant_hit` int(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pickable_player_round1` (`commander_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1037865 ;

-- --------------------------------------------------------

--
-- Table structure for table `player`
--

CREATE TABLE IF NOT EXISTS `player` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `steam_id` varchar(100) DEFAULT NULL,
  `steam_name` varchar(45) DEFAULT NULL,
  `steam_url` varchar(256) DEFAULT NULL,
  `steam_image` varchar(256) DEFAULT NULL,
  `group` int(1) NOT NULL DEFAULT '0',
  `bio` text,
  `ip` varchar(50) DEFAULT NULL,
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  `code` smallint(6) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `ranking` int(11) DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT '1000',
  `kill_elo_rating` int(6) NOT NULL DEFAULT '1500',
  `win_elo_rating` int(6) NOT NULL DEFAULT '1500',
  `commander_elo_rating` int(6) NOT NULL DEFAULT '1500',
  `marine_win_elo` int(6) NOT NULL DEFAULT '1500',
  `alien_win_elo` int(6) NOT NULL DEFAULT '1500',
  `marine_commander_elo` int(6) NOT NULL DEFAULT '1500',
  `alien_commander_elo` int(6) NOT NULL DEFAULT '1500',
  `last_seen` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `last_server_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `steamid` (`steam_id`),
  KEY `last_server_id` (`last_server_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=141537 ;

-- --------------------------------------------------------

--
-- Table structure for table `player_lifeform`
--

CREATE TABLE IF NOT EXISTS `player_lifeform` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `player_round_id` int(10) unsigned NOT NULL,
  `lifeform_id` int(10) unsigned NOT NULL,
  `start` int(10) unsigned NOT NULL,
  `end` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_player_in_lifeform_player_in_round1` (`player_round_id`),
  KEY `fk_player_in_lifeform_lifeform1` (`lifeform_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35685656 ;

-- --------------------------------------------------------

--
-- Table structure for table `player_round`
--

CREATE TABLE IF NOT EXISTS `player_round` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` int(10) unsigned NOT NULL,
  `round_id` int(10) unsigned NOT NULL,
  `team` int(10) unsigned NOT NULL,
  `ping` int(10) unsigned DEFAULT NULL,
  `score` int(10) unsigned NOT NULL DEFAULT '0',
  `assists` int(10) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `start` int(10) NOT NULL,
  `end` int(10) DEFAULT NULL,
  `finished` int(1) NOT NULL DEFAULT '0',
  `commander` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_player_round_player1` (`player_id`),
  KEY `fk_player_round_round1` (`round_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1805430 ;

-- --------------------------------------------------------

--
-- Table structure for table `player_team`
--

CREATE TABLE IF NOT EXISTS `player_team` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` int(10) unsigned NOT NULL,
  `player_id` int(10) unsigned NOT NULL,
  `role` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_player_team_team1` (`team_id`),
  KEY `fk_player_team_player1` (`player_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=328 ;

-- --------------------------------------------------------

--
-- Table structure for table `player_weapon`
--

CREATE TABLE IF NOT EXISTS `player_weapon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `player_round_id` int(10) unsigned NOT NULL,
  `weapon_id` int(10) unsigned NOT NULL,
  `time` int(10) NOT NULL,
  `miss` int(10) NOT NULL,
  `player_hit` int(10) NOT NULL DEFAULT '0',
  `player_damage` int(10) NOT NULL DEFAULT '0',
  `structure_hit` int(10) NOT NULL DEFAULT '0',
  `structure_damage` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_player_in_lifeform_player_in_round1` (`player_round_id`),
  KEY `fk_player_weapon_weapon1` (`weapon_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8540908 ;

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE IF NOT EXISTS `resources` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `round_id` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `team` int(1) unsigned NOT NULL,
  `gathered` int(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_resources_round1` (`round_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25266320 ;

-- --------------------------------------------------------

--
-- Table structure for table `round`
--

CREATE TABLE IF NOT EXISTS `round` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `server_id` int(10) unsigned NOT NULL,
  `map_id` int(10) unsigned NOT NULL,
  `end` int(10) NOT NULL,
  `start` int(10) NOT NULL,
  `winner` int(1) NOT NULL,
  `team_1_start` varchar(128) DEFAULT NULL,
  `team_2_start` varchar(128) DEFAULT NULL,
  `build` varchar(64) DEFAULT NULL,
  `list_id` int(10) unsigned DEFAULT NULL,
  `private` int(1) NOT NULL DEFAULT '0',
  `team_1` int(10) unsigned DEFAULT NULL,
  `team_2` int(10) unsigned DEFAULT NULL,
  `parse_status` int(1) NOT NULL DEFAULT '1',
  `log_file` varchar(64) NOT NULL,
  `added` int(11) NOT NULL,
  `tags` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_round_server1` (`server_id`),
  KEY `fk_round_map1` (`map_id`),
  KEY `fk_round_list1` (`list_id`),
  KEY `fk_round_team1` (`team_1`),
  KEY `fk_round_team2` (`team_2`),
  KEY `filter` (`end`,`server_id`,`build`(4),`private`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=103231 ;

-- --------------------------------------------------------

--
-- Table structure for table `round_structure`
--

CREATE TABLE IF NOT EXISTS `round_structure` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `round_id` int(10) unsigned NOT NULL,
  `structure_id` int(10) unsigned NOT NULL,
  `drop` int(10) NOT NULL,
  `build` int(10) unsigned DEFAULT NULL,
  `destroy` int(10) unsigned DEFAULT NULL,
  `cost` int(3) unsigned NOT NULL,
  `team` int(1) unsigned NOT NULL,
  `x` decimal(9,4) NOT NULL,
  `y` decimal(9,4) NOT NULL,
  `z` decimal(9,4) NOT NULL,
  `attacker_id` int(10) unsigned DEFAULT NULL,
  `attacker_lifeform_id` int(10) unsigned DEFAULT NULL,
  `attacker_team` int(1) DEFAULT NULL,
  `attacker_weapon_id` int(10) unsigned DEFAULT NULL,
  `builder_id` int(10) unsigned DEFAULT NULL,
  `recycle_res_back` int(1) unsigned NOT NULL DEFAULT '0',
  `commander_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_round_structure_round` (`round_id`),
  KEY `fk_round_structure_structure1` (`structure_id`),
  KEY `fk_round_structure_player_round1` (`attacker_id`),
  KEY `fk_round_structure_lifeform1` (`attacker_lifeform_id`),
  KEY `fk_round_structure_weapon1` (`attacker_weapon_id`),
  KEY `fk_round_structure_player_round2` (`builder_id`),
  KEY `fk_round_structure_player_round3` (`commander_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9786955 ;

-- --------------------------------------------------------

--
-- Table structure for table `round_upgrade`
--

CREATE TABLE IF NOT EXISTS `round_upgrade` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `round_id` int(10) unsigned NOT NULL,
  `upgrade_id` int(10) unsigned NOT NULL,
  `team` int(1) NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `cost` int(10) unsigned NOT NULL,
  `commander_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_round_upgrade_round1` (`round_id`),
  KEY `fk_round_upgrade_upgrade1` (`upgrade_id`),
  KEY `fk_round_upgrade_player_round1` (`commander_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1940787 ;

-- --------------------------------------------------------

--
-- Table structure for table `server`
--

CREATE TABLE IF NOT EXISTS `server` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `port` varchar(5) DEFAULT NULL,
  `admin_id` int(10) unsigned DEFAULT NULL,
  `server_key` varchar(32) NOT NULL,
  `created` int(10) NOT NULL,
  `stats_version` varchar(5) DEFAULT NULL,
  `motd` varchar(240) DEFAULT NULL,
  `private` int(1) NOT NULL DEFAULT '0',
  `country` varchar(2) DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_state` int(11) NOT NULL,
  `last_map` int(11) DEFAULT NULL,
  `last_player_count` int(11) DEFAULT NULL,
  `gametime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_server_player1` (`admin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6506 ;

-- --------------------------------------------------------

--
-- Table structure for table `structure`
--

CREATE TABLE IF NOT EXISTS `structure` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE IF NOT EXISTS `team` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(62) NOT NULL,
  `website` varchar(1024) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=70 ;

-- --------------------------------------------------------

--
-- Table structure for table `upgrade`
--

CREATE TABLE IF NOT EXISTS `upgrade` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=73 ;

-- --------------------------------------------------------

--
-- Table structure for table `weapon`
--

CREATE TABLE IF NOT EXISTS `weapon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=54 ;

-- --------------------------------------------------------

--
-- Table structure for table `YiiSession`
--

CREATE TABLE IF NOT EXISTS `YiiSession` (
  `id` char(32) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `death`
--
ALTER TABLE `death`
  ADD CONSTRAINT `fk_killinmatch_lifeform1` FOREIGN KEY (`attacker_lifeform_id`) REFERENCES `lifeform` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_killinmatch_lifeform2` FOREIGN KEY (`target_lifeform_id`) REFERENCES `lifeform` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_killinmatch_playerinmatch1` FOREIGN KEY (`attacker_id`) REFERENCES `player_round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_killinmatch_playerinmatch2` FOREIGN KEY (`target_id`) REFERENCES `player_round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_killinmatch_weapon1` FOREIGN KEY (`attacker_weapon_id`) REFERENCES `weapon` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_killinmatch_weapon2` FOREIGN KEY (`target_weapon_id`) REFERENCES `weapon` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `hit`
--
ALTER TABLE `hit`
  ADD CONSTRAINT `fk_hit_round_structure1` FOREIGN KEY (`target_structure_id`) REFERENCES `round_structure` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_killinmatch_lifeform10` FOREIGN KEY (`attacker_lifeform_id`) REFERENCES `lifeform` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_killinmatch_lifeform20` FOREIGN KEY (`target_lifeform_id`) REFERENCES `lifeform` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_killinmatch_playerinmatch10` FOREIGN KEY (`attacker_id`) REFERENCES `player_round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_killinmatch_playerinmatch20` FOREIGN KEY (`target_id`) REFERENCES `player_round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_killinmatch_weapon10` FOREIGN KEY (`attacker_weapon_id`) REFERENCES `weapon` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_killinmatch_weapon20` FOREIGN KEY (`target_weapon_id`) REFERENCES `weapon` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `list`
--
ALTER TABLE `list`
  ADD CONSTRAINT `fk_list_player1` FOREIGN KEY (`owner_id`) REFERENCES `player` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `list_admin`
--
ALTER TABLE `list_admin`
  ADD CONSTRAINT `fk_list_has_player_list1` FOREIGN KEY (`list_id`) REFERENCES `list` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_list_has_player_player1` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `live_player`
--
ALTER TABLE `live_player`
  ADD CONSTRAINT `live_player_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`),
  ADD CONSTRAINT `live_player_ibfk_2` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`),
  ADD CONSTRAINT `live_player_ibfk_3` FOREIGN KEY (`live_round_id`) REFERENCES `live_round` (`id`);

--
-- Constraints for table `live_round`
--
ALTER TABLE `live_round`
  ADD CONSTRAINT `live_round_ibfk_1` FOREIGN KEY (`server_id`) REFERENCES `server` (`id`);

--
-- Constraints for table `mod_round`
--
ALTER TABLE `mod_round`
  ADD CONSTRAINT `fk_mod_round_mod` FOREIGN KEY (`mod_id`) REFERENCES `mod` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_mod_round_round1` FOREIGN KEY (`round_id`) REFERENCES `round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pickable`
--
ALTER TABLE `pickable`
  ADD CONSTRAINT `fk_pickable_player_round1` FOREIGN KEY (`commander_id`) REFERENCES `player_round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `player`
--
ALTER TABLE `player`
  ADD CONSTRAINT `player_ibfk_1` FOREIGN KEY (`last_server_id`) REFERENCES `server` (`id`),
  ADD CONSTRAINT `player_ibfk_2` FOREIGN KEY (`last_server_id`) REFERENCES `server` (`id`);

--
-- Constraints for table `player_lifeform`
--
ALTER TABLE `player_lifeform`
  ADD CONSTRAINT `fk_player_in_lifeform_lifeform1` FOREIGN KEY (`lifeform_id`) REFERENCES `lifeform` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_player_in_lifeform_player_in_round1` FOREIGN KEY (`player_round_id`) REFERENCES `player_round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `player_round`
--
ALTER TABLE `player_round`
  ADD CONSTRAINT `fk_player_round_player1` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_player_round_round1` FOREIGN KEY (`round_id`) REFERENCES `round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `player_team`
--
ALTER TABLE `player_team`
  ADD CONSTRAINT `fk_player_team_player1` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_player_team_team1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `player_weapon`
--
ALTER TABLE `player_weapon`
  ADD CONSTRAINT `fk_player_in_lifeform_player_in_round10` FOREIGN KEY (`player_round_id`) REFERENCES `player_round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_player_weapon_weapon1` FOREIGN KEY (`weapon_id`) REFERENCES `weapon` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `resources`
--
ALTER TABLE `resources`
  ADD CONSTRAINT `fk_resources_round1` FOREIGN KEY (`round_id`) REFERENCES `round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `round`
--
ALTER TABLE `round`
  ADD CONSTRAINT `fk_round_list1` FOREIGN KEY (`list_id`) REFERENCES `list` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_round_map1` FOREIGN KEY (`map_id`) REFERENCES `map` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_round_server1` FOREIGN KEY (`server_id`) REFERENCES `server` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `round_ibfk_1` FOREIGN KEY (`team_1`) REFERENCES `team` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `round_ibfk_2` FOREIGN KEY (`team_2`) REFERENCES `team` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `round_structure`
--
ALTER TABLE `round_structure`
  ADD CONSTRAINT `fk_round_structure_lifeform1` FOREIGN KEY (`attacker_lifeform_id`) REFERENCES `lifeform` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_round_structure_player_round1` FOREIGN KEY (`attacker_id`) REFERENCES `player_round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_round_structure_player_round2` FOREIGN KEY (`builder_id`) REFERENCES `player_round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_round_structure_player_round3` FOREIGN KEY (`commander_id`) REFERENCES `player_round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_round_structure_round` FOREIGN KEY (`round_id`) REFERENCES `round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_round_structure_structure1` FOREIGN KEY (`structure_id`) REFERENCES `structure` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_round_structure_weapon1` FOREIGN KEY (`attacker_weapon_id`) REFERENCES `weapon` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `round_upgrade`
--
ALTER TABLE `round_upgrade`
  ADD CONSTRAINT `fk_round_upgrade_player_round1` FOREIGN KEY (`commander_id`) REFERENCES `player_round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_round_upgrade_round1` FOREIGN KEY (`round_id`) REFERENCES `round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_round_upgrade_upgrade1` FOREIGN KEY (`upgrade_id`) REFERENCES `upgrade` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `server`
--
ALTER TABLE `server`
  ADD CONSTRAINT `fk_server_player1` FOREIGN KEY (`admin_id`) REFERENCES `player` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
