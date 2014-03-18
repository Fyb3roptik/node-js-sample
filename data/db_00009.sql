CREATE TABLE `coupons` (
	`coupon_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`nickname` VARCHAR( 128 ) NOT NULL ,
	`description` VARCHAR( 256 ) NOT NULL ,
	`discount_type` TINYINT( 1 ) NOT NULL ,
	`discount_value` DECIMAL( 15, 2 ) NOT NULL ,
	`start_date` DATE NOT NULL ,
	`end_date` DATE NOT NULL
) ENGINE = MYISAM ;

CREATE TABLE `coupon_products` (
	`coupon_product_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`coupon_id` INT( 11 ) NOT NULL ,
	`product_id` INT( 11 ) NOT NULL
) ENGINE = MYISAM ;

ALTER TABLE `coupon_products` ADD INDEX ( `coupon_id` ) ;

ALTER TABLE `coupon_products` ADD INDEX ( `product_id` ) ;

CREATE TABLE `coupon_categories` (
	`coupon_category_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`coupon_id` INT( 11 ) NOT NULL ,
	`category_id` INT( 11 ) NOT NULL
) ENGINE = MYISAM ;

ALTER TABLE `coupon_categories` ADD INDEX ( `coupon_id` ) ;

ALTER TABLE `coupon_categories` ADD INDEX ( `category-id` ) ;
