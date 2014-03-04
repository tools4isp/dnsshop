-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 04, 2014 at 06:47 PM
-- Server version: 5.5.31
-- PHP Version: 5.4.4-14+deb7u7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dev_cp`
--

-- --------------------------------------------------------

--
-- Table structure for table `dns_dom_results`
--

CREATE TABLE IF NOT EXISTS `dns_dom_results` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `dom_id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `ttl` varchar(25) NOT NULL,
  `info` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dom_id` (`dom_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21145 ;

-- --------------------------------------------------------

--
-- Table structure for table `dns_templates`
--

CREATE TABLE IF NOT EXISTS `dns_templates` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `account` int(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`,`account`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `dns_templates_records`
--

CREATE TABLE IF NOT EXISTS `dns_templates_records` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `template_id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(25) NOT NULL,
  `content` varchar(255) NOT NULL,
  `ttl` int(255) NOT NULL,
  `prio` int(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `template_id` (`template_id`,`name`,`type`,`content`,`ttl`,`prio`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=312 ;

-- --------------------------------------------------------

--
-- Table structure for table `pakketten`
--

CREATE TABLE IF NOT EXISTS `pakketten` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `type` varchar(25) NOT NULL,
  `user_id` int(255) NOT NULL,
  `pakket_id` int(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`,`user_id`,`pakket_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

-- --------------------------------------------------------

--
-- Table structure for table `pakketten_dns`
--

CREATE TABLE IF NOT EXISTS `pakketten_dns` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `max_domain` int(255) NOT NULL,
  `max_templates` int(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `max_domain` (`max_domain`,`max_templates`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `is_admin` int(5) NOT NULL DEFAULT '0',
  `subsuspend` int(5) NOT NULL DEFAULT '0',
  `id_master` int(255) NOT NULL DEFAULT '0',
  `suspend` int(5) NOT NULL DEFAULT '0',
  `default_lang` varchar(255) NOT NULL DEFAULT 'NL',
  `handelsnaam` varchar(255) NOT NULL,
  `home_page` varchar(255) NOT NULL,
  `layout` varchar(255) NOT NULL DEFAULT 'default',
  `email` varchar(255) NOT NULL,
  `aantal_login` int(255) NOT NULL DEFAULT '0',
  `aanmaak_datum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `aantal_wijzigingen` int(255) NOT NULL DEFAULT '0',
  `laatste_wijziging` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `username` (`username`,`pass`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_history`
--

CREATE TABLE IF NOT EXISTS `user_history` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `is_admin` varchar(5) NOT NULL,
  `subsuspend` varchar(5) NOT NULL,
  `id_master` varchar(5) NOT NULL,
  `aanmaak_datum` varchar(255) NOT NULL,
  `suspend` varchar(5) NOT NULL,
  `default_lang` varchar(255) NOT NULL,
  `handelsnaam` varchar(255) NOT NULL,
  `home_page` varchar(255) NOT NULL,
  `layout` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `laatste_wijziging` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip` varchar(255) NOT NULL,
  `aantal_login` int(255) NOT NULL,
  `aantal_wijzigingen` int(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`,`pass`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=79 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_login_history`
--

CREATE TABLE IF NOT EXISTS `user_login_history` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=900 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_right`
--

CREATE TABLE IF NOT EXISTS `user_right` (
  `id` bigint(255) NOT NULL AUTO_INCREMENT,
  `userid` int(25) NOT NULL,
  `right` varchar(255) DEFAULT NULL,
  `user` int(255) NOT NULL,
  `subuser` int(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid_2` (`userid`,`right`),
  KEY `userid` (`userid`,`right`,`user`,`subuser`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1703 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_subuser`
--

CREATE TABLE IF NOT EXISTS `user_subuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `subuserid` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`,`subuserid`,`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=146 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
