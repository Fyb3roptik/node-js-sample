<?php

/**
 * Active Record for Match Price Settings information.
 */
class Match_Price extends Object {
	protected $_table = 'match_prices';
	protected $_table_id = 'match_price_id';


    public static function getPrices($active_only = true) {
        $prices = array();
        
        if($active_only == true) {
          $active = "WHERE active = '1'";
        } else {
          $active = "";
        }
        
        $sql = "SELECT match_price_id FROM match_prices {$active} ORDER BY price ASC";
        $results = db_arr($sql);
        
        foreach($results as $mp) {
            $prices[] = new Match_Price($mp['match_price_id']);
        }
        
        return $prices;
    }
    
    public static function getFreerollId() {
      $freeroll = array();
      
      $sql = "SELECT match_price_id FROM match_prices WHERE price = '0' LIMIT 1";
      $results = db_arr($sql);
        
      foreach($results as $mp) {
          $freeroll = new Match_Price($mp['match_price_id']);
      }
      
      return $freeroll;
      
    }

}
?>