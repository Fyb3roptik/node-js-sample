CREATE TABLE `invoice_detail` (
	`invoice_detail_id` int(11) NOT NULL auto_increment,
	`invoice_id` int(11) NOT NULL,
	`qty_ordered` int(5),
	`qty_invoiced` int(5),
	`stock_code` varchar(30),
	`net_sales_value` float,
	`product_class` varchar(4),
	PRIMARY KEY (`invoice_detail_id`),
	KEY `invoice_id` (`invoice_id`)
);
