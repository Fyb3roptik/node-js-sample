CREATE TABLE  `available_players` (
`available_player_id` INT NOT NULL AUTO_INCREMENT ,
`player_id` INT NOT NULL ,
`date` INT NOT NULL ,
PRIMARY KEY (  `available_player_id` )
) ENGINE = INNODB;