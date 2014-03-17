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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

INSERT INTO `user` (`username`, `pass`, `is_admin`, `subsuspend`, `id_master`, `suspend`, `default_lang`, `handelsnaam`, `home_page`, `layout`, `email`, `aantal_login`, `aanmaak_datum`, `aantal_wijzigingen`, `laatste_wijziging`) VALUES
('admin', '2c0a054fd5968000c70cf2a0547542c6', 1, 0, 0, 0, 'nl', 'DnsShop', 'https://github.com/tools4isp/dnsshop', 'default', 'admin@dnsshop.,org', 0, '2014-03-16 16:44:16', 0, '0000-00-00 00:00:00');

INSERT INTO `user_right` (`id`, `userid`, `right`, `user`, `subuser`) VALUES
(314, 0, 'api', 1, 0),
(315, 0, 'apitoegang', 1, 340),
(63, 0, 'dns', 2, 0),
(67, 0, 'dnsdombekijken', 2, 315),
(68, 0, 'dnsdombewerken', 2, 316),
(74, 0, 'dnsdomglobbew', 2, 322),
(64, 0, 'dnsdomoverzicht', 2, 312),
(66, 0, 'dnsdomtoevoegen', 2, 314),
(65, 0, 'dnsdomverwijderen', 2, 313),
(76, 0, 'dnsdomzoeken', 2, 324),
(77, 0, 'dnsrecglobbew', 2, 325),
(309, 0, 'dnssmbewerken', 2, 337),
(313, 0, 'dnssmdomontkop', 2, 339),
(304, 0, 'dnssmoverzicht', 2, 335),
(305, 0, 'dnssmtoevoegen', 2, 336),
(310, 0, 'dnssmverwijderen', 2, 338),
(303, 0, 'dnssmzoeken', 2, 334),
(86, 0, 'dnstembekijken', 2, 333),
(70, 0, 'dnstembewerken', 2, 318),
(73, 0, 'dnstemkoppelen', 2, 321),
(72, 0, 'dnstemoverzicht', 2, 320),
(69, 0, 'dnstemtoevoegen', 2, 317),
(71, 0, 'dnstemverwijderen', 2, 319),
(75, 0, 'dnstemzoeken', 2, 323),
(14, 0, 'klantaanpassen', 5, 306),
(13, 0, 'klantbekijken', 5, 305),
(18, 0, 'klantoverzetten', 5, 310),
(12, 0, 'klantoverzicht', 5, 304),
(15, 0, 'klantrechten', 5, 307),
(16, 0, 'klanttoevoegen', 5, 308),
(62, 0, 'klantverwijderen', 5, 311),
(17, 0, 'klantzoeken', 5, 309),
(81, 0, 'pakketbekijken', 6, 328),
(80, 0, 'pakketbewerken', 6, 327),
(85, 0, 'pakketoverdragen', 6, 332),
(83, 0, 'pakketoverzicht', 6, 330),
(78, 0, 'pakketten', 6, 0),
(79, 0, 'pakkettoevoegen', 6, 326),
(82, 0, 'pakketverwijderen', 6, 329),
(84, 0, 'pakketzoeken', 6, 331),
(8, 0, 'reseller', 5, 0),
(940, 0, 'stream', 7, 0),
(942, 0, 'streambekijken', 7, 342),
(943, 0, 'streambewerken', 7, 343),
(945, 0, 'streamoverzicht', 7, 345),
(946, 0, 'streamtoevoegen', 7, 346),
(944, 0, 'streamverwijderen', 7, 344),
(941, 0, 'streamzoeken', 7, 341),
(9, 0, 'suspend', 5, 301),
(10, 0, 'unsuspend', 5, 302),
(11, 0, 'wwreset', 5, 303);

INSERT INTO `user_subuser` (`userid`, `subuserid`, `type`) VALUES (0, 1, 1);

INSERT INTO `pakketten` (`type`, `user_id`, `pakket_id`) VALUES ('dns', 1, 1);

INSERT INTO `pakketten_dns` (`id`, `max_domain`, `max_templates`) VALUES (1, 1000000, 1000000);
