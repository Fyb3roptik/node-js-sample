CREATE TABLE  `teams_lineup` (
`teams_lineup_id` INT NOT NULL AUTO_INCREMENT ,
`team_id` INT NOT NULL ,
`player_id` INT NOT NULL ,
`order` INT NOT NULL ,
`position` VARCHAR( 10 ) NOT NULL ,
`score` FLOAT NOT NULL ,
PRIMARY KEY (  `teams_lineup_id` )
) ENGINE = INNODB;