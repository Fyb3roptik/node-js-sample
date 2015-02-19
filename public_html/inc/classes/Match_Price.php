<?php

/**
 * Active Record for Match Price Settings information.
 */
class Match_Price extends Object {
	protected $_table = 'match_prices';
	protected $_table_id = 'match_price_id';


    public static function getPrices() {
        $prices = array();
        
        $sql = "SELECT match_price_id FROM match_prices WHERE active = '1' ORDER BY price ASC";
        $results = db_arr($sql);
        
        foreach($results as $mp) {
            $prices[] = new Match_Price($mp['match_price_id']);
        }
        
        return $prices;
    }
}
?>