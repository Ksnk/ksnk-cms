SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

CREATE TABLE IF NOT EXISTS `darts_flesh` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `ival` int(11) DEFAULT NULL,
  `sval` varchar(255) NOT NULL,
  `tval` text,
  PRIMARY KEY (`id`,`name`),
  KEY `sval` (`sval`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=18 ;

CREATE TABLE IF NOT EXISTS `darts_news` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `text` text NOT NULL,
  `PLACE` int(11) DEFAULT NULL,
  `DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `category` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

CREATE TABLE IF NOT EXISTS `darts_players` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255) NOT NULL DEFAULT '',
  `NAME1` varchar(255) NOT NULL DEFAULT '',
  `NAME2` varchar(255) NOT NULL DEFAULT '',
  `PLACE` int(11) NOT NULL DEFAULT '0',
  `PHOTO` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `PLACE` (`PLACE`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=54 ;

CREATE TABLE IF NOT EXISTS `darts_tournaments` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PARENT` int(11) DEFAULT '0',
  `DATE` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `LEVEL` int(3) NOT NULL,
  `NAME` varchar(255) NOT NULL DEFAULT '',
  `AGPARAM` int(11) NOT NULL,
  `STATUS` varchar(40) DEFAULT '0',
  `RULE` varchar(10) DEFAULT NULL,
  `DESCR` text NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `PARENT` (`PARENT`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=87 ;

CREATE TABLE IF NOT EXISTS `darts_tourplayers` (
  `ID_PLAYER` int(11) NOT NULL,
  `ID_TOURNAMENT` int(11) NOT NULL,
  `NUMBER` int(11) NOT NULL,
  `DESCR` text NOT NULL,
  `RES1` int(11) DEFAULT '0',
  `RES2` int(11) DEFAULT '0',
  `RES3` int(11) DEFAULT '0',
  `RES4` int(11) DEFAULT '0',
  `RES5` int(11) DEFAULT '0',
  `RES6` int(11) DEFAULT '0',
  PRIMARY KEY (`ID_PLAYER`,`ID_TOURNAMENT`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

CREATE TABLE IF NOT EXISTS `darts_tplayers` (
  `ID_PLAYER` int(11) NOT NULL DEFAULT '0',
  `ID_TOURNAMENT` int(11) NOT NULL DEFAULT '0',
  `NUMB` int(11) NOT NULL,
  KEY `ID_PLAYER` (`ID_PLAYER`,`ID_TOURNAMENT`),
  KEY `NUMB` (`NUMB`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `darts_tree` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent` int(10) NOT NULL DEFAULT '0',
  `level` int(10) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `username` varchar(255) NOT NULL DEFAULT '',
  `flags` int(10) NOT NULL DEFAULT '0',
  `user` int(10) NOT NULL DEFAULT '0',
  `quote_id` int(10) NOT NULL DEFAULT '0',
  `text` text NOT NULL,
  `info` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  KEY `level` (`level`),
  KEY `quote_id` (`quote_id`),
  KEY `username` (`username`),
  KEY `user` (`user`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=7 ;
