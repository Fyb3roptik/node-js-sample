ALTER TABLE `orders` ADD `coupon_id` int(11) NULL AFTER `sales_rep_id`;

ALTER TABLE `orders` ADD KEY `coupon_id` (`coupon_id`);