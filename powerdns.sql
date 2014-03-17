ALTER TABLE `domains` ADD `failed` INT( 5 ) NOT NULL;

CREATE TABLE IF NOT EXISTS `dnssec` (
  `id` bigint(255) NOT NULL auto_increment,
  `domainid` int(255) NOT NULL,
  `type` varchar(10) default NULL,
  `record` varchar(255) NOT NULL,
 PRIMARY KEY (`id`),
 KEY `domainid` (`domainid`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `domains` ADD `changed` INT( 5 ) NOT NULL;

