ALTER TABLE `coupons` ADD `code` VARCHAR( 64 ) NOT NULL AFTER `coupon_id` ,
ADD INDEX ( code ) ;