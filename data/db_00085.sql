ALTER TABLE `orders`
	ADD `shipping_ext` varchar(64) NULL AFTER `shipping_phone`,
	ADD `billing_ext` varchar(64) NULL AFTER `billing_phone`;
