CREATE TABLE  `score_settings` (
`score_settings_id` INT NOT NULL AUTO_INCREMENT ,
`key` VARCHAR( 255 ) NOT NULL ,
`value` FLOAT NOT NULL ,
PRIMARY KEY (  `score_settings_id` )
) ENGINE = INNODB;