CREATE TABLE `invoices` (
	`invoice_id` int(11) NOT NULL auto_increment,
	`syspro_id` varchar(6) NOT NULL,
	`order_id` varchar(6) NOT NULL,
	`date` datetime NOT NULL,
	`po_number` varchar(6),
	`terms_code` varchar(2),
	`merchandise_value` float,
	`freight_value` float,
	`tax_value` float,
	`currency_value` float,
	PRIMARY KEY (`invoice_id`),
	KEY `order_id` (`order_id`),
	KEY `syspro_id` (`syspro_id`)
);