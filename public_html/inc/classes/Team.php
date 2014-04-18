<?php

/**
 * Active Record for Team information.
 */
class Team extends Object {
	protected $_table = 'teams';
	protected $_table_id = 'team_id';
	
	public static function getAllTeams() {
    	$sql = "SELECT * FROM teams ORDER BY team_id DESC LIMIT 1000";
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
            $lineup[] = array("teams_lineup_id" => $r['teams_lineup_id'], "team_id" => $r['team_id'], "player_id" => $r['player_id'], "mlb_player_id" => $player->mlb_id, "order" => $r['order'], "position" => $r['position'], "score" => $r['score']);
        }
        return $lineup;
    }
    
    public function getScore($team_id) {
        
        $memcache = new Cache();
        
        $score = $memcache->get('scores');

        if($score['done_batting'][$team_id]['final_done'] == false) {
            
            $T = new Team($team_id);
            
            $lineup = $T->getTeamLineupById(false);

            // Update the database
            foreach($lineup as $l) {
                $TL = new TeamsLineup($l['teams_lineup_id']);
                $TL->score = $score['scores'][$team_id][$l['mlb_player_id']]['score'];
                $TL->inning_data = implode(",", $score['scores'][$team_id][$l['mlb_player_id']]['at_bat_stat']);
                $TL->write();
            }
            
            
            $final['scores'] = $score['scores'][$team_id];
            $final['bases'] = $score['bases'][$team_id];
            $final['outs'] = $score['outs'][$team_id];
            $final['at_bat'] = $score['at_bat'][$team_id];
            $final['done'] = $score['done_batting'][$team_id];
        } else {
            
            
            
        }
        
        
        
        return $final;
        
    }
    
    public function getTotal($SCORE) {
        
        // Get Total
        $total = 0;
        foreach($SCORE['scores'] as $player_id => $score) {
            $total = floatval($total + $score['score']);
        }
        
        // Write it to database
        $this->score = $total;
        $this->write();
        
        return $total;
        
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
}
?>