-- MySQL dump 10.14  Distrib 10.0.8-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ns2stats
-- ------------------------------------------------------
-- Server version	10.0.14-MariaDB-1~wheezy-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `YiiSession`
--

DROP TABLE IF EXISTS `YiiSession`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `YiiSession` (
  `id` char(32) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `chat`
--

DROP TABLE IF EXISTS `chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(512) DEFAULT NULL,
  `player_round_id` int(11) NOT NULL,
  `team_number` int(11) NOT NULL,
  `to_team` int(11) NOT NULL,
  `player_name` varchar(128) NOT NULL,
  `gametime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=553442 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `death`
--

DROP TABLE IF EXISTS `death`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `death` (
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
  KEY `fk_killinmatch_weapon2` (`target_weapon_id`),
  CONSTRAINT `fk_killinmatch_lifeform1` FOREIGN KEY (`attacker_lifeform_id`) REFERENCES `lifeform` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_killinmatch_lifeform2` FOREIGN KEY (`target_lifeform_id`) REFERENCES `lifeform` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_killinmatch_playerinmatch1` FOREIGN KEY (`attacker_id`) REFERENCES `player_round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_killinmatch_playerinmatch2` FOREIGN KEY (`target_id`) REFERENCES `player_round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_killinmatch_weapon1` FOREIGN KEY (`attacker_weapon_id`) REFERENCES `weapon` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_killinmatch_weapon2` FOREIGN KEY (`target_weapon_id`) REFERENCES `weapon` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=23329275 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `donation`
--

DROP TABLE IF EXISTS `donation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `donation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `last_name` varchar(255) DEFAULT NULL,
  `residence_country` varchar(50) DEFAULT NULL,
  `payer_status` varchar(50) DEFAULT NULL,
  `txn_id` varchar(50) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `item_number` varchar(50) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL,
  `mc_fee` decimal(6,2) DEFAULT NULL,
  `mc_gross` decimal(6,2) DEFAULT NULL,
  `custom` varchar(255) DEFAULT NULL,
  `ipn_track_id` varchar(50) DEFAULT NULL,
  `payer_email` varchar(255) DEFAULT NULL,
  `receiver_email` varchar(255) DEFAULT NULL,
  `mc_currency` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hit`
--

DROP TABLE IF EXISTS `hit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hit` (
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
  KEY `fk_hit_round_structure1` (`target_structure_id`),
  CONSTRAINT `fk_hit_round_structure1` FOREIGN KEY (`target_structure_id`) REFERENCES `round_structure` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_killinmatch_lifeform10` FOREIGN KEY (`attacker_lifeform_id`) REFERENCES `lifeform` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_killinmatch_lifeform20` FOREIGN KEY (`target_lifeform_id`) REFERENCES `lifeform` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_killinmatch_playerinmatch10` FOREIGN KEY (`attacker_id`) REFERENCES `player_round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_killinmatch_playerinmatch20` FOREIGN KEY (`target_id`) REFERENCES `player_round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_killinmatch_weapon10` FOREIGN KEY (`attacker_weapon_id`) REFERENCES `weapon` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_killinmatch_weapon20` FOREIGN KEY (`target_weapon_id`) REFERENCES `weapon` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `lifeform`
--

DROP TABLE IF EXISTS `lifeform`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lifeform` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `list`
--

DROP TABLE IF EXISTS `list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `description` text,
  `owner_id` int(10) unsigned NOT NULL,
  `type` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_list_player1` (`owner_id`),
  CONSTRAINT `fk_list_player1` FOREIGN KEY (`owner_id`) REFERENCES `player` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `list_admin`
--

DROP TABLE IF EXISTS `list_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `list_admin` (
  `list_id` int(10) unsigned NOT NULL,
  `player_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`list_id`,`player_id`),
  KEY `fk_list_has_player_player1` (`player_id`),
  KEY `fk_list_has_player_list1` (`list_id`),
  CONSTRAINT `fk_list_has_player_list1` FOREIGN KEY (`list_id`) REFERENCES `list` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_list_has_player_player1` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `live_player`
--

DROP TABLE IF EXISTS `live_player`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `live_player` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` int(10) unsigned NOT NULL,
  `last_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `json` text NOT NULL,
  `live_round_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `player_id` (`player_id`),
  KEY `live_round_id` (`live_round_id`),
  CONSTRAINT `live_player_ibfk_1` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`),
  CONSTRAINT `live_player_ibfk_2` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`),
  CONSTRAINT `live_player_ibfk_3` FOREIGN KEY (`live_round_id`) REFERENCES `live_round` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=80186 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `live_round`
--

DROP TABLE IF EXISTS `live_round`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `live_round` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `server_id` int(10) unsigned NOT NULL,
  `last_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `players` int(11) NOT NULL,
  `gametime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `server_id` (`server_id`),
  CONSTRAINT `live_round_ibfk_1` FOREIGN KEY (`server_id`) REFERENCES `server` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3686 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `map`
--

DROP TABLE IF EXISTS `map`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `map` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `jsonvalues` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=215 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mod`
--

DROP TABLE IF EXISTS `mod`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL,
  `workshop_id` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mod_round`
--

DROP TABLE IF EXISTS `mod_round`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mod_round` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mod_id` int(11) unsigned NOT NULL,
  `round_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_mod_round_mod` (`mod_id`),
  KEY `fk_mod_round_round1` (`round_id`),
  CONSTRAINT `fk_mod_round_mod` FOREIGN KEY (`mod_id`) REFERENCES `mod` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_mod_round_round1` FOREIGN KEY (`round_id`) REFERENCES `round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pickable`
--

DROP TABLE IF EXISTS `pickable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pickable` (
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
  KEY `fk_pickable_player_round1` (`commander_id`),
  CONSTRAINT `fk_pickable_player_round1` FOREIGN KEY (`commander_id`) REFERENCES `player_round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `player`
--

DROP TABLE IF EXISTS `player`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player` (
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
  `donator` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `steamid` (`steam_id`),
  KEY `last_server_id` (`last_server_id`),
  CONSTRAINT `player_ibfk_1` FOREIGN KEY (`last_server_id`) REFERENCES `server` (`id`),
  CONSTRAINT `player_ibfk_2` FOREIGN KEY (`last_server_id`) REFERENCES `server` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=239052 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `player_image`
--

DROP TABLE IF EXISTS `player_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_image` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `image` mediumblob NOT NULL,
  `player_id` int(10) unsigned NOT NULL,
  `data` varchar(512) DEFAULT NULL,
  `background_image` mediumblob,
  `default` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `player_id_foreign` (`player_id`),
  CONSTRAINT `player_id key` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `player_lifeform`
--

DROP TABLE IF EXISTS `player_lifeform`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_lifeform` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `player_round_id` int(10) unsigned NOT NULL,
  `lifeform_id` int(10) unsigned NOT NULL,
  `start` int(10) unsigned NOT NULL,
  `end` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_player_in_lifeform_player_in_round1` (`player_round_id`),
  KEY `fk_player_in_lifeform_lifeform1` (`lifeform_id`),
  CONSTRAINT `fk_player_in_lifeform_lifeform1` FOREIGN KEY (`lifeform_id`) REFERENCES `lifeform` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_player_in_lifeform_player_in_round1` FOREIGN KEY (`player_round_id`) REFERENCES `player_round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=312682318 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `player_round`
--

DROP TABLE IF EXISTS `player_round`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_round` (
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
  KEY `fk_player_round_round1` (`round_id`),
  CONSTRAINT `fk_player_round_player1` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_player_round_round1` FOREIGN KEY (`round_id`) REFERENCES `round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3595847 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `player_team`
--

DROP TABLE IF EXISTS `player_team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_team` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` int(10) unsigned NOT NULL,
  `player_id` int(10) unsigned NOT NULL,
  `role` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_player_team_team1` (`team_id`),
  KEY `fk_player_team_player1` (`player_id`),
  CONSTRAINT `fk_player_team_player1` FOREIGN KEY (`player_id`) REFERENCES `player` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_player_team_team1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `player_weapon`
--

DROP TABLE IF EXISTS `player_weapon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `player_weapon` (
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
  KEY `fk_player_weapon_weapon1` (`weapon_id`),
  CONSTRAINT `fk_player_in_lifeform_player_in_round10` FOREIGN KEY (`player_round_id`) REFERENCES `player_round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_player_weapon_weapon1` FOREIGN KEY (`weapon_id`) REFERENCES `weapon` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=17155237 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `resources`
--

DROP TABLE IF EXISTS `resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resources` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `round_id` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `team` int(1) unsigned NOT NULL,
  `gathered` int(5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_resources_round1` (`round_id`),
  CONSTRAINT `fk_resources_round1` FOREIGN KEY (`round_id`) REFERENCES `round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=111963963 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `round`
--

DROP TABLE IF EXISTS `round`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `round` (
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
  `gamemode` varchar(64) NOT NULL DEFAULT 'ns2',
  PRIMARY KEY (`id`),
  KEY `fk_round_server1` (`server_id`),
  KEY `fk_round_map1` (`map_id`),
  KEY `fk_round_list1` (`list_id`),
  KEY `fk_round_team1` (`team_1`),
  KEY `fk_round_team2` (`team_2`),
  KEY `filter` (`end`,`server_id`,`build`(4),`private`),
  CONSTRAINT `fk_round_list1` FOREIGN KEY (`list_id`) REFERENCES `list` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_round_map1` FOREIGN KEY (`map_id`) REFERENCES `map` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_round_server1` FOREIGN KEY (`server_id`) REFERENCES `server` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `round_ibfk_1` FOREIGN KEY (`team_1`) REFERENCES `team` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `round_ibfk_2` FOREIGN KEY (`team_2`) REFERENCES `team` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=191669 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `round_structure`
--

DROP TABLE IF EXISTS `round_structure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `round_structure` (
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
  KEY `fk_round_structure_player_round3` (`commander_id`),
  CONSTRAINT `fk_round_structure_lifeform1` FOREIGN KEY (`attacker_lifeform_id`) REFERENCES `lifeform` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_round_structure_player_round1` FOREIGN KEY (`attacker_id`) REFERENCES `player_round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_round_structure_player_round2` FOREIGN KEY (`builder_id`) REFERENCES `player_round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_round_structure_player_round3` FOREIGN KEY (`commander_id`) REFERENCES `player_round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_round_structure_round` FOREIGN KEY (`round_id`) REFERENCES `round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_round_structure_structure1` FOREIGN KEY (`structure_id`) REFERENCES `structure` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_round_structure_weapon1` FOREIGN KEY (`attacker_weapon_id`) REFERENCES `weapon` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=18340699 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `round_upgrade`
--

DROP TABLE IF EXISTS `round_upgrade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `round_upgrade` (
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
  KEY `fk_round_upgrade_player_round1` (`commander_id`),
  CONSTRAINT `fk_round_upgrade_player_round1` FOREIGN KEY (`commander_id`) REFERENCES `player_round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_round_upgrade_round1` FOREIGN KEY (`round_id`) REFERENCES `round` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_round_upgrade_upgrade1` FOREIGN KEY (`upgrade_id`) REFERENCES `upgrade` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3278381 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `server`
--

DROP TABLE IF EXISTS `server`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `server` (
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
  KEY `fk_server_player1` (`admin_id`),
  CONSTRAINT `fk_server_player1` FOREIGN KEY (`admin_id`) REFERENCES `player` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9210 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` char(32) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` longblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sessions2`
--

DROP TABLE IF EXISTS `sessions2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions2` (
  `id` char(32) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` longblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `structure`
--

DROP TABLE IF EXISTS `structure`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `structure` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `team`
--

DROP TABLE IF EXISTS `team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(62) NOT NULL,
  `website` varchar(1024) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `upgrade`
--

DROP TABLE IF EXISTS `upgrade`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `upgrade` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `weapon`
--

DROP TABLE IF EXISTS `weapon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `weapon` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54231 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-04-03 18:44:49
