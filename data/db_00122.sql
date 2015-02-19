CREATE TABLE `date_settings` (
  `date_settings_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL DEFAULT '0',
  `key` varchar(100) DEFAULT '',
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`date_settings_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;