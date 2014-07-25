<?php

/**
 * Active Record for Match information.
 */
class Match extends Object {
	protected $_table = 'matches';
	protected $_table_id = 'match_id';
	
	public static function getActiveMatches($locked = false, $start_date = true) {
    	
    	$MATCHES = array();
    	
    	$locked_check = "";
    	
    	if($locked == false) {
        	$locked_check = " AND locked = '0'";
    	}
    	
    	$start_check = "";
    	if($start_date == true) {
        	$start_check = "AND start_date >= '".time()."'";
    	}
    	
    	$sql = "SELECT match_id FROM matches WHERE active = '1' {$start_check} {$locked_check} ORDER BY match_id DESC";
    	
    	$arr = db_arr($sql);
    	
    	foreach($arr as $match) {
        	$MATCHES[] = new Match($match['match_id']);
    	}
    	
    	return $MATCHES;
    	
	}
	
	public static function getAllMatches() {
    	
    	$MATCHES = array();
    	
    	$sql = "SELECT match_id FROM matches ORDER BY match_id DESC";
    	
    	$arr = db_arr($sql);
    	
    	foreach($arr as $match) {
        	$MATCHES[] = new Match($match['match_id']);
    	}
    	
    	return $MATCHES;
    	
	}
	
	public function getTotalTeams() {
    	$count = "";
    	
    	$sql = "SELECT count(*) as total FROM teams WHERE match_id = '".$this->ID."'";
    	
    	$arr = db_arr($sql);
    	
    	$count = $arr[0]['total'];
    	
    	return $count;
	}
	
	public function teamExists($customer_id) {
    	$return['check'] = false;
    	
    	$sql = "SELECT * FROM teams WHERE customer_id = '".$customer_id."' AND match_id = '".$this->ID."'";

    	$results = db_query($sql);
    	$arr = db_arr($sql);
    	
    	if($results->num_rows > 0) {
        	$return['check'] = true;
        	$return['team_id'] = $arr[0]['team_id'];
    	}
    	
    	return $return;
	}
	
	public function getPrizePool($total_entrents = 0) {
    	$prize_pool = "";
    	
    	$prize_pool = ($total_entrents * $this->entry_fee) - floatval($this->prize_pool);
    	
    	if($prize_pool < 0) {
        	$prize_pool = 0;
    	}
    	
    	return number_format($prize_pool, 2);
	}

}
?>