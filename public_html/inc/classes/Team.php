<?php

/**
 * Active Record for Team information.
 */
class Team extends Object {
	protected $_table = 'teams';
	protected $_table_id = 'team_id';
	
	public static function getAllTeams($match_id = false, $winner = false) {
    	$match = "";
    	if($match_id != false) {
        	$match = "WHERE match_id = '{$match_id}'";
    	}
    	
    	$order_by = "team_id DESC";
    	$limit = "1000";
    	if($winner == true) {
        	$order_by = "place ASC";
        	$limit = "1";
    	}
    	
    	$sql = "SELECT * FROM teams {$match} ORDER BY {$order_by} LIMIT {$limit}";
      $query = db_arr($sql);
      
      foreach($query as $team) {
          $TEAM_LIST[] = new Team($team['team_id']);
      }
      
      return $TEAM_LIST;
	}

    public static function getMyTeams(Customer $C) {
        $sql = "SELECT * FROM teams WHERE customer_id = '" . $C->ID . "' ORDER BY team_id DESC LIMIT 1000";
        $query = db_arr($sql);

        foreach($query as $team) {
            $TEAM_LIST[] = new Team($team['team_id']);
        }
        
        return $TEAM_LIST;
    }
    
    public function getTeamLineupById($enable_pitchers = true) {
        $lineup = array();
        
        if($enable_pitchers == false) {
            $pitchers = "AND `order` > 0";
        }
        
        $sql = "SELECT * FROM teams_lineup WHERE team_id = '" . $this->ID . "' {$pitchers} ORDER BY `order` ASC";
        $results = db_arr($sql);
        
        if(!is_null($results)) {
          foreach($results as $r) {
              $player = new Player($r['player_id']);
              $lineup[] = array("teams_lineup_id" => $r['teams_lineup_id'], "team_id" => $r['team_id'], "player_id" => $r['player_id'], "mlb_player_id" => $player->mlb_id, "player_team" =>  $player->player_team, "order" => $r['order'], "position" => $r['position'], "score" => $r['score']);
          }
        }
        
        return $lineup;
    }
    
    public function getScore($team_id) {
        
        $memcache = new Cache();
        
        $score = $memcache->get('scores');

        $final_done = 0;
        if(!empty($score['done_batting'][$team_id])) {
          foreach($score['done_batting'][$team_id] as $v) {
            if($v === true) {
              $final_done++;
            }
          }
        }
        
        $T = new Team($team_id);
        $M = new Match($T->match_id);

        if($M->active == 1) {

            $T->inning_data = base64_encode(serialize($score['scores'][$team_id]));
            $T->write();
            
            $T = new Team($team_id);
            
            $lineup = $T->getTeamLineupById(false);

            // Update the database
            if(!empty($lineup)) {
              foreach($lineup as $l) {
                  $TL = new TeamsLineup($l['teams_lineup_id']);
                  if(isset($score['scores'][$team_id][$l['mlb_player_id']]['score']) && $score['scores'][$team_id][$l['mlb_player_id']]['score'] > 0) {
                      $TL->score = $score['scores'][$team_id][$l['mlb_player_id']]['score'];
                      $TL->write();
                  }
                  
                  $final['player_score'][] = $score['scores'][$team_id][$l['mlb_player_id']]['score'];
              }
            }
            
            
            
            $final['score_total'] = 0;
                        
            foreach($final['player_score'] as $k => $s) {
                if(is_null($s) || $s == null) {
                    $final['player_score'][$k] = 0;
                }
                $final['score_total'] += $s;
            }

            if($final['score_total'] != 0) {
                $T->score = $final['score_total'];
                $T->write();
                
                $T = new Team($team_id);
            }
            
            $final['scores'] = $score['scores'][$team_id];
            $final['bases'] = $score['bases'][$team_id];
            $final['outs'] = $score['outs'][$team_id];
            $final['at_bat'] = $score['at_bat'][$team_id];
            $final['done'] = $score['done_batting'][$team_id];
        } else if($M->active == 2) {

            $T = new Team($team_id);
            $score = unserialize(base64_decode($T->inning_data));

            $lineup = $T->getTeamLineupById(false);
            
            // Update the database
            if(!empty($lineup)) {
              foreach($lineup as $l) {
                  $TL = new TeamsLineup($l['teams_lineup_id']);
                  if(isset($score['scores'][$team_id][$l['mlb_player_id']]['score']) && $score['scores'][$team_id][$l['mlb_player_id']]['score'] > 0) {
                      $TL->score = $score['scores'][$team_id][$l['mlb_player_id']]['score'];
                  }
                  
                  $final['player_score'][] = $score[$mlb_id]['score'];
              }
            }
            
            
            
            $final['score_total'] = 0;
            
            foreach($final['player_score'] as $k => $s) {
                if(is_null($s) || $s == null) {
                    $final['player_score'][$k] = 0;
                }
                $final['score_total'] += $s;
            }
            
            $final['scores'] = $score;
            
            $final['bases'] = array();
            $final['at_bat'] = array();
            
            $final['done'] = true;
            $final['outs'] = 0;
            
            
        }
        
        return $final;
        
    }
    
    public function getScoreJSON($team_id) {
        $memcache = new Cache();
        
        $score = $memcache->get('scores');

        $final_done = 0;
        if(!empty($score['done_batting'][$team_id])) {
          foreach($score['done_batting'][$team_id] as $v) {
            if($v === true) {
              $final_done++;
            }
          }
        }
        
        $T = new Team($team_id);
        $M = new Match($T->match_id);

        if($M->active == 1) {
            
            $T->inning_data = base64_encode(serialize($score['scores'][$team_id]));
            $T->write();
            
            $T = new Team($team_id);
            
            $lineup = $T->getTeamLineupById(false);

            // Update the database
            foreach($lineup as $key => $l) {
                $TL = new TeamsLineup($l['teams_lineup_id']);
                if(isset($score['scores'][$team_id][$l['mlb_player_id']]['score']) && $score['scores'][$team_id][$l['mlb_player_id']]['score'] > 0) {
                    $TL->score = $score['scores'][$team_id][$l['mlb_player_id']]['score'];
                    $TL->write();
                }
                
                $P = new Player($l['player_id']); 
                $mlb_id = $P->mlb_id;
                $at_bat_count[] = count($score['scores'][$team_id][$mlb_id]['at_bat_stat']);
                $at_bat_count_int = count($score['scores'][$team_id][$mlb_id]['at_bat_stat']);
                
                $mlb_id = $P->mlb_id;
                
                $final['box_score'][$key] = $score['scores'][$team_id][$mlb_id]['at_bat_stat'];
                
                $final['player_score'][] = $score['scores'][$team_id][$mlb_id]['score'];
            }
            
            
            rsort($at_bat_count);
            
            $final['bat_count'] = $at_bat_count[0];
            $final['score_total'] = 0;
            
            foreach($final['player_score'] as $k => $s) {
                if(is_null($s) || $s == null) {
                    $final['player_score'][$k] = 0;
                }
                $final['score_total'] += $s;
            }
            
            if($final['score_total'] != 0) {
                $T->score = $final['score_total'];
                $T->write();
                
                $T = new Team($team_id);
            }
            
            $leaderboard = $T->getLeaderboard($M);
            
            $leader = array();
                        
            foreach($leaderboard as $l) {
                $C = new Customer($l->customer_id);
                $leader[] = array("user" => $C->username, "score" => $l->score, "team_id" => $l->ID, "customer_id" => $C->ID); 
            }
            
            $final['leaderboard'] = $leader;
            
            $AT_BAT_P = new Player($lineup[$score['at_bat'][$team_id]]['player_id']);
            
            $bases = array();
            if(!empty($score['bases'][$team_id])) {
                foreach($score['bases'][$team_id] as $base => $players) {
                  foreach($players as $player_id) {
                    $BASES_P = new Player($player_id, "mlb_id");
                    $bases[$base][] = $BASES_P->first_name . " " . $BASES_P->last_name;
                  }
                }
            }
            

            $final['scores'] = $score['scores'][$team_id];

            $final['bases'] = $bases;
            $final['outs'] = $score['outs'][$team_id];
            
            if($AT_BAT_P->exists()) {
                $final['at_bat'] = $AT_BAT_P->first_name . " " . $AT_BAT_P->last_name;
            } else {
                $final['at_bat'] = "";
            }

            $final['done'] = $score['done_batting'][$team_id];
            
        } else {
            
            $T = new Team($team_id);
            
            $lineup = $T->getTeamLineupById(false);
            
            foreach($lineup as $l) {
                $TL = new TeamsLineup($l['teams_lineup_id']);
                $final['scores'][$l['mlb_player_id']]['score'] = $TL->score;
                parse_str(htmlspecialchars_decode(htmlspecialchars_decode(urldecode($TL->inning_data))), $final['scores'][$l['mlb_player_id']]['at_bat_stat']);
            }
            
            $final['bases'] = array();
            $final['done'] = true;
            $final['outs'] = 0;
            
        }
        
        return json_encode($final);
    }
    
    public function getTotal($SCORE) {
        
        // Get Total
        $total = 0;
        foreach($SCORE['scores'] as $player_id => $score) {
            $total += $score['score'];
        }

        // Write it to database
        if($SCORE['done']['final_done'] != true) {
            $this->score = $total;
            
            return $total;
        }  else {
            //return $this->score;
        }      
    }
    
    public function getLeaderboard(Match $M) {
        $leaders = array();
        
        $sql = "SELECT team_id FROM teams WHERE match_id = '".$M->ID."' ORDER BY score DESC";
        $results = db_arr($sql);
        
        foreach($results as $r) {          
            $leaders[] = new Team($r['team_id']);
        }
        
        foreach($leaders as $k => $T) {
            $T->place = $k + 1;
            $T->write();
        }
        
        return $leaders;
    }
    
    public static function getGames() {
        $memcache = new Cache();
        $games = $memcache->get('games');
        return $games;
    }
    
    public static function getGamesInfo() {
        $memcache = new Cache();
        $games = $memcache->get('games_info');
        return $games;
    }
    
    public function sendPromotionInfo() {
      $content = "";
      $filename = $this->ID;
      
      $C = new Customer($this->customer_id);
      $TeamLineup = $this->getTeamLineupById($this->ID);
      
      $content .= "Date: " . date("m/d/Y", time()) . "\n";
      $content .= "Username: " . $C->username . "\n";
      $content .= "Team ID: " . $this->ID . "\n";
      
      for($i = 0; $i < count($TeamLineup); $i++) {
        $P = new Player($TeamLineup[$i]['player_id']);
        $content .= "Batter " . ($i + 1) . ": " . $P->first_name . " " . $P->last_name . "\n";
      }
      
      $write = new File_Writer();
      $write->writeFile($filename, $content, true);
      
      $ftp = new Ftp();
      $ftp->sendFile($filename);
      
    }
}
?>