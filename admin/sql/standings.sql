CREATE TABLE IF NOT EXISTS `#__lgl_standings` (
  `team` char(32) NOT NULL DEFAULT '',
  `league_id` char(32) NOT NULL DEFAULT '',
  `position` int(2) NOT NULL DEFAULT '0',
  `points` int(3) NOT NULL DEFAULT '0',
  `goals` char(255) DEFAULT NULL,
  `matches` char(255) DEFAULT NULL,
  `details` char(255) DEFAULT NULL,
  `luts` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`team`,`league_id`),
  KEY `league` (`league_id`),
  KEY `lastupdate` (`luts`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
TRUNCATE TABLE `#__lgl_standings`;
