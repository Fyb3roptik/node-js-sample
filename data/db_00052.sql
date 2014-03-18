ALTER TABLE `order_change_item`
DROP `description`,
ADD `stock_code` varchar(64) NOT NULL,
ADD `value` varchar(64) NOT NULL;