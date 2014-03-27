<?php

/**
 * Active Record for Match information.
 */
class Match extends Object {
	protected $_table = 'matches';
	protected $_table_id = 'match_id';
	
	public static function getActiveMatches() {
    	
    	$MATCHES = array();
    	
    	$sql = "SELECT match_id FROM matches WHERE active = '1' ORDER BY match_id DESC";
    	
    	$arr = db_arr($sql);
    	
    	foreach($arr as $match) {
        	$MATCHES[] = new Match($match['match_id']);
    	}
    	
    	return $MATCHES;
    	
	}

}
?>