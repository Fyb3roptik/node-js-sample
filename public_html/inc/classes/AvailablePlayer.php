<?php

/**
 * Active Record for Player information.
 */
class AvailablePlayer extends Object {
	protected $_table = 'available_players';
	protected $_table_id = 'available_player_id';
	
	public function getSP() {
    	$sql = "SELECT player_id FROM available_players WHERE position = 'SP'";
        $arr = db_arr($sql);

        foreach($arr as $ap) {
            $result[] = new Player($ap['player_id']);
        }
        
        return $result;
	}
	
	public function getRP() {
    	$sql = "SELECT player_id FROM available_players WHERE position = 'RP'";
        $arr = db_arr($sql);
        
        foreach($arr as $ap) {
            $result[] = new Player($ap['player_id']);
        }
        
        return $result;
	}
	
	public function getCA() {
    	$sql = "SELECT player_id FROM available_players WHERE position = 'C'";
        $arr = db_arr($sql);
        
        foreach($arr as $ap) {
            $result[] = new Player($ap['player_id']);
        }
        
        return $result;
	}
	
	public function getFB() {
    	$sql = "SELECT player_id FROM available_players WHERE position = '1B'";
        $arr = db_arr($sql);
        
        foreach($arr as $ap) {
            $result[] = new Player($ap['player_id']);
        }
        
        return $result;
	}
	
	public function getSB() {
    	$sql = "SELECT player_id FROM available_players WHERE position = '2B'";
        $arr = db_arr($sql);
        
        foreach($arr as $ap) {
            $result[] = new Player($ap['player_id']);
        }
        
        return $result;
	}
	
	public function getTB() {
    	$sql = "SELECT player_id FROM available_players WHERE position = '3B'";
        $arr = db_arr($sql);
        
        foreach($arr as $ap) {
            $result[] = new Player($ap['player_id']);
        }
        
        return $result;
	}
	
	public function getSS() {
    	$sql = "SELECT player_id FROM available_players WHERE position = 'SS'";
        $arr = db_arr($sql);
        
        foreach($arr as $ap) {
            $result[] = new Player($ap['player_id']);
        }
        
        return $result;
	}
	
	public function getOF() {
    	$sql = "SELECT player_id FROM available_players WHERE position = 'OF'";
        $arr = db_arr($sql);
        
        foreach($arr as $ap) {
            $result[] = new Player($ap['player_id']);
        }
        
        return $result;
	}

}
?>