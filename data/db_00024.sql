ALTER TABLE `orders` ADD `cc_name` VARCHAR( 256 ) NOT NULL ,
ADD `cc_number` INT( 11 ) NOT NULL ,
ADD `cc_expires_month` INT( 2 ) NOT NULL ,
ADD `cc_expires_year` INT( 4 ) NOT NULL ,
ADD `cc_trans_id` VARCHAR( 4 ) NOT NULL ,
ADD `cc_auth_code` VARCHAR( 16 ) NOT NULL ,
ADD `cc_ccv` INT( 4 ) NOT NULL; 