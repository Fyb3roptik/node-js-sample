CREATE TABLE  `teams` (
`team_id` INT NOT NULL AUTO_INCREMENT ,
`customer_id` INT NOT NULL ,
`match_id` INT NOT NULL ,
PRIMARY KEY (  `team_id` )
) ENGINE = INNODB;