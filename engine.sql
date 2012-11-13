-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 04, 2012 at 11:37 AM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `engine`
--

-- --------------------------------------------------------

--
-- Table structure for table `bing`
--

CREATE TABLE IF NOT EXISTS `bing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descr` varchar(300) NOT NULL,
  `url` varchar(300) NOT NULL,
  `abstract` varchar(3000) NOT NULL,
  `rank` int(11) NOT NULL,
  `query_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2121 ;

-- --------------------------------------------------------

--
-- Table structure for table `global`
--

CREATE TABLE IF NOT EXISTS `global` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descr` varchar(300) NOT NULL,
  `url` varchar(300) NOT NULL,
  `abstract` varchar(3000) NOT NULL,
  `rank` int(11) NOT NULL,
  `score` float NOT NULL,
  `source_engine` varchar(30) NOT NULL,
  `query_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2195 ;

-- --------------------------------------------------------

--
-- Table structure for table `google`
--

CREATE TABLE IF NOT EXISTS `google` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descr` varchar(300) NOT NULL,
  `url` varchar(300) NOT NULL,
  `abstract` varchar(3000) NOT NULL,
  `rank` int(11) NOT NULL,
  `query_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2026 ;

-- --------------------------------------------------------

--
-- Table structure for table `query`
--

CREATE TABLE IF NOT EXISTS `query` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `query` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=227 ;

-- --------------------------------------------------------

--
-- Table structure for table `yahoo`
--

CREATE TABLE IF NOT EXISTS `yahoo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descr` varchar(300) NOT NULL,
  `url` varchar(300) NOT NULL,
  `abstract` varchar(3000) NOT NULL,
  `rank` int(11) NOT NULL,
  `query_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2091 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
