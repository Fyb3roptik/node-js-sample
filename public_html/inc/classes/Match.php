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
    	} else {
        	$start_check = "AND start_date < '".time()."'";
    	}
    	
    	$sql = "SELECT match_id FROM matches WHERE active = '1' {$start_check} {$locked_check} ORDER BY match_id DESC";
    	
    	$arr = db_arr($sql);
    	
    	foreach($arr as $match) {
        	$MATCHES[] = new Match($match['match_id']);
    	}
    	
    	return $MATCHES;
    	
	}
	
	public static function getActiveMatchesForMe($customer_id) {
    	
    	$MATCHES = array();
    	
    	$sql = "SELECT matches.match_id FROM matches INNER JOIN teams ON matches.match_id = teams.match_id WHERE matches.active = '1' AND teams.customer_id = '{$customer_id}' ORDER BY match_id DESC";
    	
    	$arr = db_arr($sql);
    	
    	if(!empty($arr)) {
        	foreach($arr as $match) {
            	$MATCHES[] = new Match($match['match_id']);
        	}
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
    	$match_fee = "";
    	
    	$match_fee = ($total_entrents * $this->entry_fee) - floatval($this->match_fee);

    	if($match_fee < 0) {
        	$match_fee = 0;
    	}
    	
    	return number_format($match_fee, 2);
	}
	
	public function getMyStatus($customer_id) {
    	$status = "";
    	
    	$sql = "SELECT accepted FROM teams WHERE match_id = '".$this->ID."' AND customer_id = '{$customer_id}'";
    	$arr = db_arr($sql);
    	
    	return $arr[0]['accepted'];
	}
	
	public function getOpponent($match_id, $customer_id) {
        $return = array();
        
    	$sql = "SELECT customer_id FROM teams WHERE match_id = '{$match_id}' AND customer_id != '{$customer_id}'";
    	$arr = db_arr($sql);
    	
    	$return = new Customer($arr[0]['customer_id']);
    	return $return;
	}
	
	public function getStatus() {
    	$status = "";
    	$accepted = 0;
    	
    	$sql = "SELECT count(team_id) as total FROM teams WHERE match_id = '".$this->ID."'";
    	$arr_count = db_arr($sql);
    	
    	$sql = "SELECT accepted FROM teams WHERE match_id = '" . $this->ID . "'";
    	$arr = db_arr($sql);
    	
    	foreach($arr as $accept) {
        	$accepted += $accept['accepted'];
    	}
    	
    	switch($accepted) {
        	case 1:
        	    
        	    if($arr_count[0]['total'] == 1) {
          	    $status = "Accepted";
        	    }
        	    
        	    if($arr_count[0]['total'] == 2) {
        	      $status = "Pending";
              }
              
        	    break;
        	    
            case 2:
                $status = "Accepted";
                break;
                
            case 3:
                $status = "Declined";
                break;
        	    
    	}
    	
    	return $status;
	}
	
	public static function getGameTimes() {
  	
  	$cache = new Cache();
  	
  	// Set Game Times with teams
  	$GAME_TIMES = $cache->get('game_times');
  	
  	$game_times = array();
  	
  	foreach($GAME_TIMES as $time => $teams) {
    	$unixtime = strtotime(date("m/d/Y", time()) . " " . $time);
    	$cutoff_time = strtotime(date("m/d/Y", time()) . " 4:00 PM");
    	
    	if($unixtime < $cutoff_time) {
      	$times['early'][] = $unixtime;
    	} else {
      	$times['late'][] = $unixtime;
    	}
    	
    }
    
    sort($times['early']);
    sort($times['late']);
    
  	foreach($GAME_TIMES as $time => $teams) {
    	$unixtime = strtotime(date("m/d/Y", time()) . " " . $time);
    	$cutoff_time = strtotime(date("m/d/Y", time()) . " 4:00 PM");

    	foreach($teams['teams'] as $team) {
        if($unixtime < $cutoff_time) {
        	$game_times['early'][$times['early'][0]][] = $team;
      	}	else {
        	$game_times['late'][$times['late'][0]][] = $team;
      	}
      	if(isset($times['early'])) {
          $game_times['all'][$times['early'][0]][] = $team;
        } else {
          $game_times['all'][$times['late'][0]][] = $team;
        }
          
    	}
  	}
  	
  	return $game_times;
	}
	
	public static function getLobby() {
    
    $MATCH_PRICES = Match_Price::getPrices();
    $GAME_TIMES = self::getGameTimes();

    foreach($GAME_TIMES as $type => $time) {
      foreach($time as $team) {
        $teams[$type] = $team;
      }
    }
    
  	// Create array of match types
    $matches = array();
    for($i = 0; $i < 20; $i++) {
      $rand = array_rand($MATCH_PRICES);
      
      if($MATCH_PRICES[$rand]->price == 420 && array_key_exists(420, $matches)) {
        continue;
      }
      
      if(($MATCH_PRICES[$rand]->price == 420 && !array_key_exists(420, $matches)) || $MATCH_PRICES[$rand]->price != 420) {
        $all = key($GAME_TIMES['all']);
        if($all > time()) {
          $matches[] = array('match_price' => $MATCH_PRICES[$rand], "start_time" => $all, "type" => "all", "teams" => $teams['all']);
        }
      }
    }
    
    // Do Early Games
    if(isset($GAME_TIMES['early'])) {
      for($i = 0; $i < 10; $i++) {
        $rand = array_rand($MATCH_PRICES);
        
        if($MATCH_PRICES[$rand]->price == 420 && array_key_exists(420, $matches)) {
          continue;
        }
        
        if(($MATCH_PRICES[$rand]->price == 420 && !array_key_exists(420, $matches)) || $MATCH_PRICES[$rand]->price != 420) {
          $early = key($GAME_TIMES['early']);
          if($early > time()) {
            $matches[] = array('match_price' => $MATCH_PRICES[$rand], "start_time" => $early, "type" => "early", "teams" => $teams['early']);
          }
        }
      }
    }
    
    // Do Early Games
    if(isset($GAME_TIMES['late'])) {
      for($i = 0; $i < 10; $i++) {
        $rand = array_rand($MATCH_PRICES);
        
        if($MATCH_PRICES[$rand]->price == 420 && array_key_exists(420, $matches)) {
          continue;
        }
        
        if(($MATCH_PRICES[$rand]->price == 420 && !array_key_exists(420, $matches)) || $MATCH_PRICES[$rand]->price != 420) {
          $late = key($GAME_TIMES['late']);
          if($late > time()) {
            $matches[] = array('match_price' => $MATCH_PRICES[$rand], "start_time" => $late, "type" => "late", "teams" => $teams['late']);
          }
        }
      }
    }

    return $matches;
	}
	
	public static function findOpponent(Match_Price $MP, $start_time) {
  	
  	// Find a match
  	$sql = "SELECT match_id FROM matches WHERE start_date = '{$start_time}' AND match_price_id = '".$MP->ID."' AND current_entrants = '1' LIMIT 1";
  	$arr_match = db_arr($sql);
  	
  	if($arr_match) {
    	$sql = "SELECT customer_id FROM teams WHERE match_id = '".$arr_match[0]['match_id']."'";
    	$arr_team = db_arr();
    	
    	$return['match_id'] = $arr_match[0]['match_id'];
    	$return['team_id'] = $arr_team[0]['team_id'];
    	
    	return $return;
  	} else {
    	return false;
  	}
  	
	}

}
?>