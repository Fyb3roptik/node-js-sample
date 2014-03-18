CREATE TABLE `order_reports`(
	`order_report_id` int(11) NOT NULL AUTO_INCREMENT,
	`user_type` varchar(16) NOT NULL,
	`user_id` int(11) NOT NULL,
	`title` varchar(32),
	`filters` text,
	`selected_fields` text,
	PRIMARY KEY (`order_report_id`),
	KEY `user_id` (`user_id`),
	KEY `user_type` (`user_type`),
	KEY `title` (`title`)
);
