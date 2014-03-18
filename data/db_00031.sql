CREATE TABLE `category_nav_item_columns` (
	`category_nav_item_column_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`nav_item_id` INT( 11 ) NOT NULL ,
	`sort_order` INT( 11 ) NOT NULL ,
	INDEX ( `nav_item_id` )
) ENGINE = MYISAM ;