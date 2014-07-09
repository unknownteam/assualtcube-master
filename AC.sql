CREATE TABLE IF NOT EXISTS `servers` (
  `server_id` int(11) NOT NULL AUTO_INCREMENT,
  `address` varchar(1024) NOT NULL,
  `port` smallint(5) unsigned DEFAULT NULL,
  `version` varchar(32) DEFAULT NULL,
  `name` varchar(1024) DEFAULT NULL,
  `private_key` varchar(32) NOT NULL,
  `key_hash` varchar(32) NOT NULL COMMENT 'Public Key',
  `max_clients` smallint(5) unsigned DEFAULT NULL,
  `public` varchar(5) DEFAULT NULL,
  `password_protected` varchar(5) DEFAULT NULL,
  `allow_guests` varchar(5) DEFAULT NULL,
  `user_count` smallint(5) unsigned DEFAULT NULL,
  `user_list` varchar(1024) DEFAULT NULL,
  `motd` varchar(1024) DEFAULT NULL,
  `game_mode` varchar(32) DEFAULT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_heartbeat_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`server_id`),
  UNIQUE KEY `private_key` (`private_key`),
  KEY `last_heartbeat_date` (`last_heartbeat_date`),
  KEY `key_hash` (`key_hash`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password_hash` varchar(64) NOT NULL,
  `email` varchar(254) DEFAULT NULL,
  `joined_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  KEY `last_login_date` (`last_login_date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;
