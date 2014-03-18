ALTER TABLE `pages` ADD `url` VARCHAR( 256 ) NOT NULL AFTER `title` ,
ADD INDEX ( url ) ;
