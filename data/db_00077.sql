CREATE TABLE `customer_reports` (
	`customer_report_id` int(11) NOT NULL AUTO_INCREMENT,
	`customer_id` int(11) NOT NULL,
	`title` varchar(32) NOT NULL DEFAULT 'Orders',
	`max_date` timestamp NULL,
	`min_date` timestamp NULL,
	`selected_fields` text,
	PRIMARY KEY (`customer_report_id`),
	KEY `customer_id` (`customer_id`),
	UNIQUE KEY (`customer_id`, `title`)
);
