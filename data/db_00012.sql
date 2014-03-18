CREATE TABLE IF NOT EXISTS `user_sessions` (
  `user_session_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `user_type` varchar(64) NOT NULL,
  `token` varchar(40) NOT NULL,
  PRIMARY KEY  (`user_session_id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`,`user_type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;