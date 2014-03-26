CREATE TABLE  `players` (
`player_id` INT NOT NULL AUTO_INCREMENT ,
`mlb_id` INT NOT NULL ,
`first_name` VARCHAR( 255 ) NOT NULL ,
`last_name` VARCHAR( 255 ) NOT NULL ,
`position` VARCHAR( 20 ) NOT NULL ,
PRIMARY KEY (  `player_id` )
) ENGINE = INNODB;