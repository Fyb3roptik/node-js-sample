<?php

/**
 * Active Record for Team information.
 */
class Team extends Object {
	protected $_table = 'teams';
	protected $_table_id = 'team_id';
	
	public static function getAllTeams($match_id = false) {
    	$match = "";
    	if($match_id != false) {
        	$match = "WHERE match_id = '{$match_id}'";
    	}
    	
    	$sql = "SELECT * FROM teams {$match} ORDER BY team_id DESC LIMIT 1000";
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
        
        foreach($results as $r) {
            $player = new Player($r['player_id']);
            $lineup[] = array("teams_lineup_id" => $r['teams_lineup_id'], "team_id" => $r['team_id'], "player_id" => $r['player_id'], "mlb_player_id" => $player->mlb_id, "player_team" =>  $player->player_team, "order" => $r['order'], "position" => $r['position'], "score" => $r['score']);
        }
        return $lineup;
    }
    
    public function getScore($team_id) {
        
        $memcache = new Cache();
        
        $score = $memcache->get('scores');

        if(isset($score['done_batting'][$team_id]['final_done']) && $score['done_batting'][$team_id]['final_done'] == false) {
            
            $T = new Team($team_id);
            
            $lineup = $T->getTeamLineupById(false);

            // Update the database
            foreach($lineup as $l) {
                $TL = new TeamsLineup($l['teams_lineup_id']);
                if(isset($score['scores'][$team_id][$l['mlb_player_id']]['score']) && $score['scores'][$team_id][$l['mlb_player_id']]['score'] > 0) {
                    $TL->score = $score['scores'][$team_id][$l['mlb_player_id']]['score'];
                    $TL->inning_data = http_build_query($score['scores'][$team_id][$l['mlb_player_id']]['at_bat_stat']);
                    $TL->write();
                }
                
                $final['player_score'][] = $score['scores'][$team_id][$mlb_id]['score'];
            }
            
            $final['score_total'] = 0;
            
            foreach($final['player_score'] as $k => $s) {
                if(is_null($s) || $s == null) {
                    $final['player_score'][$k] = 0;
                }
                $final['score_total'] += $s;
            }
            
            if($final['score_total'] > $T->score) {
                $T->score = $final['score_total'];
                $T->write();
            }
            
            $final['scores'] = $score['scores'][$team_id];
            $final['bases'] = $score['bases'][$team_id];
            $final['outs'] = $score['outs'][$team_id];
            $final['at_bat'] = $score['at_bat'][$team_id];
            $final['done'] = $score['done_batting'][$team_id];
        } else {
            
            $T = new Team($team_id);
            
            $lineup = $T->getTeamLineupById(false);
            
            foreach($lineup as $l) {
                $TL = new TeamsLineup($l['teams_lineup_id']);
                $final['scores'][$l['mlb_player_id']]['score'] = $TL->score;
                parse_str(htmlspecialchars_decode(htmlspecialchars_decode(urldecode($TL->inning_data))), $final['scores'][$l['mlb_player_id']]['at_bat_stat']);
            }
            
            $final['done'] = true;
            $final['outs'] = 0;
            
            
        }
        
        return $final;
        
    }
    
    public function getScoreJSON($team_id) {
        $memcache = new Cache();
        
        $score = $memcache->get('scores');

        if(isset($score['done_batting'][$team_id]['final_done']) && $score['done_batting'][$team_id]['final_done'] == false) {
            
            $T = new Team($team_id);
            
            $lineup = $T->getTeamLineupById(false);

            // Update the database
            foreach($lineup as $l) {
                $TL = new TeamsLineup($l['teams_lineup_id']);
                if(isset($score['scores'][$team_id][$l['mlb_player_id']]['score']) && $score['scores'][$team_id][$l['mlb_player_id']]['score'] > 0) {
                    $TL->score = $score['scores'][$team_id][$l['mlb_player_id']]['score'];
                    $TL->inning_data = http_build_query($score['scores'][$team_id][$l['mlb_player_id']]['at_bat_stat']);
                    $TL->write();
                }
                
                $P = new Player($l['player_id']); 
                $mlb_id = $P->mlb_id;
                $at_bat_count[] = count($score['scores'][$team_id][$mlb_id]['at_bat_stat']);
                $at_bat_count_int = count($score['scores'][$team_id][$mlb_id]['at_bat_stat']);
                
                $mlb_id = $P->mlb_id;
                
                foreach($score['scores'][$team_id][$mlb_id]['at_bat_stat'] as $k => $at_bat_stat) {
                    $final['box_score'][$k +1][] = $at_bat_stat[0];
                }
                
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
            
            $AT_BAT_P = new Player($lineup[$score['at_bat'][$team_id]]['player_id']);
            
            $bases = array();
            if(!empty($score['bases'][$team_id])) {
                foreach($score['bases'][$team_id] as $player_id => $base) {
                    $BASES_P = new Player($player_id, "mlb_id");
                    $bases[$score['bases'][$team_id][$player_id]['base']][] = $BASES_P->first_name . " " . $BASES_P->last_name;
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
            $total = floatval($total + $score['score']);
        }
        
        // Write it to database
        if($SCORE['done'] != true) {
            $this->score = $total;
            $this->write();
            
            return $total;
        }  else {
            return $this->score;
        }      
    }
    
    public function getLeaderboard(Match $M) {
        $leaders = array();
        
        $sql = "SELECT team_id FROM teams WHERE match_id = '".$M->ID."' ORDER BY score DESC";
        $results = db_arr($sql);
        
        foreach($results as $r) {
            $leaders[] = new Team($r['team_id']);
        }
        
        return $leaders;
    }
    
    public function getGames() {
        $memcache = new Cache();
        
        $games = $memcache->get('games');
        
        return $games;
    }
}
?>