CREATE TABLE `order_change_history` (
	`order_change_id` int(11) NOT NULL AUTO_INCREMENT,
	`order_id` int(11) NOT NULL,
	`sales_rep` int(11) NOT NULL,
	`change_type` varchar(64) NOT NULL,
	`timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`description` text NULL,
	PRIMARY KEY (`order_change_id`),
	KEY `order_id` (`order_id`)
);

CREATE TABLE `order_change_item` (
	`order_change_item_id` int(11) NOT NULL AUTO_INCREMENT,
	`order_change_id` int(11) NOT NULL,
	`change_type` varchar(64) NOT NULL,
	`description` text NULL,
	PRIMARY KEY (`order_change_item_id`),
	KEY `order_change_id` (`order_change_id`)
);