<?php

/**
 * Active Record for Player information.
 */
class Player extends Object {
	protected $_table = 'players';
	protected $_table_id = 'player_id';
	
	public function getCA() {
    	$sql = "SELECT player_id FROM players WHERE position = 'C' ORDER BY last_name, first_name";
        $arr = db_arr($sql);
        
        foreach($arr as $ap) {
            $result[] = new Player($ap['player_id']);
        }
        
        return $result;
	}
	
	public function getFB() {
    	$sql = "SELECT player_id FROM players WHERE position = '1B' ORDER BY last_name, first_name";
        $arr = db_arr($sql);
        
        foreach($arr as $ap) {
            $result[] = new Player($ap['player_id']);
        }
        
        return $result;
	}
	
	public function getSB() {
    	$sql = "SELECT player_id FROM players WHERE position = '2B' ORDER BY last_name, first_name";
        $arr = db_arr($sql);
        
        foreach($arr as $ap) {
            $result[] = new Player($ap['player_id']);
        }
        
        return $result;
	}
	
	public function getTB() {
    	$sql = "SELECT player_id FROM players WHERE position = '3B' ORDER BY last_name, first_name";
        $arr = db_arr($sql);
        
        foreach($arr as $ap) {
            $result[] = new Player($ap['player_id']);
        }
        
        return $result;
	}
	
	public function getSS() {
    	$sql = "SELECT player_id FROM players WHERE position = 'SS' ORDER BY last_name, first_name";
        $arr = db_arr($sql);
        
        foreach($arr as $ap) {
            $result[] = new Player($ap['player_id']);
        }
        
        return $result;
	}
	
	public function getOF() {
    	$sql = "SELECT player_id FROM players WHERE position = 'OF' ORDER BY last_name, first_name";
        $arr = db_arr($sql);
        
        foreach($arr as $ap) {
            $result[] = new Player($ap['player_id']);
        }
        
        return $result;
	}
	
	public function getDH() {
    	$sql = "SELECT player_id FROM players WHERE dh = '1' ORDER BY last_name, first_name";
        $arr = db_arr($sql);
        
        foreach($arr as $ap) {
            $result[] = new Player($ap['player_id']);
        }
        
        return $result;
	}

}
?>