CREATE TABLE `config` (
	`config_id` int(11) NOT NULL AUTO_INCREMENT,
	`config_key` varchar(256) NOT NULL,
	`config_value` varchar(256) NOT NULL,
	PRIMARY KEY (`config_id`),
	UNIQUE KEY (`config_key`)
);