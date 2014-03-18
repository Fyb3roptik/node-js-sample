CREATE TABLE `saved_checkouts` (
	`checkout_id` int(11) NOT NULL AUTO_INCREMENT,
	`sales_rep_id` int(11) NOT NULL,
	`customer_id` int(11) NOT NULL,
	`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`checkout` text,
	PRIMARY KEY (`checkout_id`),
	KEY `sales_rep` (`sales_rep_id`)
);
