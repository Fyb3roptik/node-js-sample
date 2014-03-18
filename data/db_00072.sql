ALTER TABLE `orders`
	MODIFY `shipping_state` varchar(32) NOT NULL,
	MODIFY `billing_state` varchar(32) NOT NULL,
	MODIFY `billing_country` varchar(3) NOT NULL,
	MODIFY `shipping_country` varchar(3) NOT NULL;