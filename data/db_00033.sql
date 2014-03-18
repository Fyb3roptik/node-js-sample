CREATE TABLE `category_meta_tags` (
	`category_meta_tag_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`category_id` INT( 11 ) NOT NULL ,
	`meta_tag_id` INT( 11 ) NOT NULL ,
	INDEX ( `category_id` )
) ENGINE = MYISAM ;

ALTER TABLE `category_meta_tags` ADD INDEX ( `meta_tag_id` ) ;