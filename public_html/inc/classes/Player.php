<?php

/**
 * Active Record for Player information.
 */
class Player extends Object {
	protected $_table = 'players';
	protected $_table_id = 'player_id';
	
	public function getCA($teams = "") {        
        $team_where = "";
        
        if($teams != "") {
            $teams_arr = explode(",", $teams);
            
            $team_where = " AND (";
            foreach($teams_arr as $team) {
                
                $last = end($teams_arr);
                
                if($last == $team) {
                    $team_where .= "player_team = '{$team}')";
                } else {
                    $team_where .= "player_team = '{$team}' OR ";   
                }
            }
        }
        
        $sql = "SELECT player_id FROM players WHERE position = 'C' {$team_where} AND active = '1' ORDER BY last_name, first_name";
        $arr = db_arr($sql);
        
        foreach($arr as $ap) {
            $result[] = new Player($ap['player_id']);
        }
        
        return $result;
	}
	
	public function getFB($teams = "") {
	    $team_where = "";
        
        if($teams != "") {
            $teams_arr = explode(",", $teams);
            
            $team_where = " AND ";
            foreach($teams_arr as $team) {
                
                $last = end($teams_arr);
                
                if($last == $team) {
                    $team_where .= "player_team = '{$team}'";
                } else {
                    $team_where .= "player_team = '{$team}' OR ";   
                }
            }
        }
        
    	$sql = "SELECT player_id FROM players WHERE position = '1B' {$team_where} AND active = '1' ORDER BY last_name, first_name";
        $arr = db_arr($sql);
        
        foreach($arr as $ap) {
            $result[] = new Player($ap['player_id']);
        }
        
        return $result;
	}
	
	public function getSB($teams = "") {
	    $team_where = "";
        
        if($teams != "") {
            $teams_arr = explode(",", $teams);
            
            $team_where = " AND ";
            foreach($teams_arr as $team) {
                
                $last = end($teams_arr);
                
                if($last == $team) {
                    $team_where .= "player_team = '{$team}'";
                } else {
                    $team_where .= "player_team = '{$team}' OR ";   
                }
            }
        }
        
    	$sql = "SELECT player_id FROM players WHERE position = '2B' {$team_where} AND active = '1' ORDER BY last_name, first_name";
        $arr = db_arr($sql);
        
        foreach($arr as $ap) {
            $result[] = new Player($ap['player_id']);
        }
        
        return $result;
	}
	
	public function getTB($teams = "") {
	    $team_where = "";
        
        if($teams != "") {
            $teams_arr = explode(",", $teams);
            
            $team_where = " AND ";
            foreach($teams_arr as $team) {
                
                $last = end($teams_arr);
                
                if($last == $team) {
                    $team_where .= "player_team = '{$team}'";
                } else {
                    $team_where .= "player_team = '{$team}' OR ";   
                }
            }
        }
        
    	$sql = "SELECT player_id FROM players WHERE position = '3B' {$team_where} AND active = '1' ORDER BY last_name, first_name";
        $arr = db_arr($sql);
        
        foreach($arr as $ap) {
            $result[] = new Player($ap['player_id']);
        }
        
        return $result;
	}
	
	public function getSS($teams = "") {
	    $team_where = "";
        
        if($teams != "") {
            $teams_arr = explode(",", $teams);
            
            $team_where = " AND ";
            foreach($teams_arr as $team) {
                
                $last = end($teams_arr);
                
                if($last == $team) {
                    $team_where .= "player_team = '{$team}'";
                } else {
                    $team_where .= "player_team = '{$team}' OR ";   
                }
            }
        }
        
    	$sql = "SELECT player_id FROM players WHERE position = 'SS' {$team_where} AND active = '1' ORDER BY last_name, first_name";
        $arr = db_arr($sql);
        
        foreach($arr as $ap) {
            $result[] = new Player($ap['player_id']);
        }
        
        return $result;
	}
	
	public function getOF($teams = "") {
	    $team_where = "";
        
        if($teams != "") {
            $teams_arr = explode(",", $teams);
            
            $team_where = " AND ";
            foreach($teams_arr as $team) {
                
                $last = end($teams_arr);
                
                if($last == $team) {
                    $team_where .= "player_team = '{$team}'";
                } else {
                    $team_where .= "player_team = '{$team}' OR ";   
                }
            }
        }
        
    	$sql = "SELECT player_id FROM players WHERE position = 'OF' {$team_where} AND active = '1' ORDER BY last_name, first_name";
        $arr = db_arr($sql);
        
        foreach($arr as $ap) {
            $result[] = new Player($ap['player_id']);
        }
        
        return $result;
	}
	
	public function getDH($teams = "") {
	    $team_where = "";
        
        if($teams != "") {
            $teams_arr = explode(",", $teams);
            
            $team_where = " AND ";
            foreach($teams_arr as $team) {
                
                $last = end($teams_arr);
                
                if($last == $team) {
                    $team_where .= "player_team = '{$team}'";
                } else {
                    $team_where .= "player_team = '{$team}' OR ";   
                }
            }
        }
        
    	$sql = "SELECT player_id FROM players WHERE dh = '1' {$team_where} AND active = '1' ORDER BY last_name, first_name";
        $arr = db_arr($sql);
        
        foreach($arr as $ap) {
            $result[] = new Player($ap['player_id']);
        }
        
        return $result;
	}

}
?>