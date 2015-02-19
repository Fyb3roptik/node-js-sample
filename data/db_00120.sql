CREATE TABLE `match_prices` (
  `match_price_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `price` float NOT NULL DEFAULT '0',
  `profit` float NOT NULL DEFAULT '0',
  `prize` float NOT NULL DEFAULT '0',
  `promotion_eligible` tinyint(4) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`match_price_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;