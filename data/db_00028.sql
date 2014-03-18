CREATE TABLE `credit_cards` (
	`credit_card_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`customer_id` INT( 11 ) NOT NULL ,
	`nickname` VARCHAR( 256 ) NOT NULL ,
	`number` VARCHAR( 128 ) NOT NULL ,
	`expires_month` VARCHAR( 128 ) NOT NULL ,
	`expires_year` VARCHAR( 128 ) NOT NULL ,
	INDEX ( `customer_id` )
) ENGINE = MYISAM ;