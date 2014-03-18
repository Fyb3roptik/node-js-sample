CREATE TABLE `category_nav_items` (
	`category_nav_item_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`category_id` INT( 11 ) NOT NULL ,
	`sort_order` INT( 11 ) NOT NULL
) ENGINE = MYISAM ;