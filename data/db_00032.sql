ALTER TABLE `categories` ADD `nav_column_id` INT( 11 ) NOT NULL ,
ADD `nav_sort_order` INT( 11 ) NOT NULL ,
ADD INDEX ( nav_column_id ) ;