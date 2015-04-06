CREATE TABLE  `matches` (
`match_id` INT NOT NULL AUTO_INCREMENT ,
`name` VARCHAR( 255 ) NOT NULL ,
`start_date` INT NOT NULL ,
`active` TINYINT NOT NULL ,
`locked` TINYINT NOT NULL ,
`current_entrants` int(11) NOT NULL DEFAULT '0',
PRIMARY KEY (  `match_id` )
) ENGINE = INNODB;