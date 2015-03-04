CREATE TABLE IF NOT EXISTS `#__lgl_leagues` (
	`league_id` char(32) NOT NULL DEFAULT '',
	`ets` int(11) unsigned NOT NULL DEFAULT '0',
	`ustatus` enum('active','stopped') DEFAULT NULL,
	`pstatus` enum('active','suspended','paused') DEFAULT NULL,
	PRIMARY KEY (`league_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
TRUNCATE TABLE `#__lgl_leagues`;