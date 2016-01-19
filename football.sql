-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 19, 2016 at 08:39 AM
-- Server version: 5.5.46-0ubuntu0.14.04.2
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `football`
--

-- --------------------------------------------------------

--
-- Table structure for table `2016_afl_table`
--

CREATE TABLE IF NOT EXISTS `2016_afl_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `match_year` int(4) DEFAULT NULL,
  `round_number` int(11) NOT NULL,
  `match_date` datetime NOT NULL,
  `team1` int(11) NOT NULL,
  `team2` int(11) NOT NULL,
  `location` varchar(50) NOT NULL,
  `home_flag` int(11) NOT NULL,
  `team1_score` int(11) NOT NULL,
  `team2_score` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_2016_afl_season_1_idx` (`team1`),
  KEY `fk_2016_afl_season_2_idx` (`team2`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `injury`
--

CREATE TABLE IF NOT EXISTS `injury` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player` int(11) NOT NULL,
  `injury` varchar(255) NOT NULL,
  `estimated_return` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_injury_1_idx` (`player`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `player`
--

CREATE TABLE IF NOT EXISTS `player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `player_score`
--

CREATE TABLE IF NOT EXISTS `player_score` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team` int(11) DEFAULT NULL,
  `player` int(11) DEFAULT NULL,
  `player_type` varchar(5) DEFAULT NULL,
  `player_position` varchar(5) DEFAULT NULL,
  `player_score_year` varchar(4) DEFAULT NULL,
  `player_round` int(11) DEFAULT NULL,
  `player_score_val` int(11) DEFAULT NULL,
  `player_price` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_player_score_1_idx` (`player`),
  KEY `fk_player_score_2_idx` (`team`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE IF NOT EXISTS `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `injury`
--
ALTER TABLE `injury`
  ADD CONSTRAINT `fk_injury_1` FOREIGN KEY (`player`) REFERENCES `player` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `player_score`
--
ALTER TABLE `player_score`
  ADD CONSTRAINT `fk_player_score_1` FOREIGN KEY (`player`) REFERENCES `player` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_player_score_2` FOREIGN KEY (`team`) REFERENCES `team` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
