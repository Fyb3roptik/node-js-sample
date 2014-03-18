ALTER TABLE `orders` CHANGE `cc_number` `cc_number` VARCHAR( 128 ) NOT NULL ,
CHANGE `cc_expires_month` `cc_expires_month` VARCHAR( 128 ) NOT NULL ,
CHANGE `cc_expires_year` `cc_expires_year` VARCHAR( 128 ) NOT NULL ,
CHANGE `cc_ccv` `cc_ccv` VARCHAR( 128 ) NOT NULL ;