CREATE TABLE IF NOT EXISTS `user_password_tokens` (
  `token_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `user_type` varchar(32) NOT NULL,
  `token` varchar(16) NOT NULL,
  `expiration` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`token_id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`,`user_type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;