DROP TABLE IF EXISTS `#__lgl_matches`;
CREATE TABLE IF NOT EXISTS `#__lgl_matches` (
  `match_id` char(32) NOT NULL DEFAULT '',
  `league_id` char(32) NOT NULL DEFAULT '',
  `hosts` char(32) NOT NULL DEFAULT '',
  `guests` char(32) NOT NULL DEFAULT '',
  `status` enum('not_started','online','finished','canceled','postponed','suspended') NOT NULL DEFAULT 'not_started',
  `current` char(255) DEFAULT NULL,
  `details` char(255) DEFAULT NULL,
  `events` text,
  `sts` int(11) unsigned NOT NULL DEFAULT '0',
  `crc` char(32) NOT NULL DEFAULT '',
  `luts` int(11) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `score` char(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`match_id`),
  KEY `league` (`league_id`),
  KEY `begintime` (`sts`),
  KEY `pub` (`published`),
  KEY `status` (`status`),
  KEY `lastupdate` (`luts`),
  KEY `host` (`hosts`),
  KEY `guest` (`guests`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
TRUNCATE TABLE `#__lgl_matches`;

