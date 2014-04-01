<?php

/**
 * Active Record for Teams Lineup information.
 */
class TeamsLineup extends Object {
	protected $_table = 'teams_lineup';
	protected $_table_id = 'teams_lineup_id';

    public static function getSelectedPlayers($team_id, $enable_pitchers = true, $players_only = true) {
        
        $players = array();
        $pitchers = "";
        
        if($enable_pitchers == false) {
            $pitchers = "AND `order` > 0";
        }
        
        $sql = "SELECT teams_lineup_id, player_id FROM teams_lineup WHERE team_id = '{$team_id}' {$pitchers} ORDER BY `order` ASC";
        $results = db_arr($sql);
        
        foreach($results as $tl) {
            $players[] = array("player_id" => $tl['player_id'], "teams_lineup_id" => $tl['teams_lineup_id']);
        }
        
        if($players_only == true) {
            $players = array();
            foreach($results as $tl) {
                $players[] = $tl['player_id'];
            }
        }

        return $players;
        
    }
}
?>