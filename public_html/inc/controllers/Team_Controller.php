<?php
require_once 'Controller.php';

/**
 * Controller for Teams and what not.
 */
class Team_Controller extends Controller {
    
    /**
	 * Main action, displays paginated view of customer table.
	 */
	public function index() {
		$this->_configure();
		$V = new View('team_index.php');
		$this->_setView($V);
		$MS = new Message_Stack();

        $TEAM_LIST = Team::getAllTeams();
        
        $LAYOUT_TITLE = "Beast Franchise | Manage Teams";
        $this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE);
        
		$V->bind('TEAM_LIST', $TEAM_LIST);
		$V->bind('MS', $MS);
	}
	
	public function score($team_id) {
    	$this->_configure();
		$V = new View('team_score.php');
		$this->_setView($V);
		$MS = new Message_Stack();
        
        $TEAM = new Team($team_id);
        $TEAM_LIST = $TEAM->getTeamLineupById(false);
        $SCORE = Team::getScore($team_id);
        $AT_BAT = $TEAM_LIST[$SCORE['at_bat']];
        
        // Get Total
        $total = 0;
        foreach($SCORE['scores'] as $player_id => $score) {
            $total = floatval($total + $score['score']);
        }
        
        $LAYOUT_TITLE = "Beast Franchise | View Score";
        $this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE);
        
		$V->bind('TEAM_LIST', $TEAM_LIST);
		$V->bind('SCORE', $SCORE);
		$V->bind('total', $total);
		$V->bind('AT_BAT', $AT_BAT);
		$V->bind('MS', $MS);
	}
	
	/**
	 * Edit an existing player.
	 */
	public function add() {
		$this->_configure();
		$MS = new Message_Stack();
		$T = new Team();
		$V = new View('team_form.php');
		
		$MATCHES = Match::getActiveMatches();
		$CUSTOMERS = Customer::getCustomers();
		
		$LAYOUT_TITLE = "Beast Franchise | Add Team";
        $this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE);
		
		$V->bind('TITLE', 'Add Test Team');
		$V->bind('T', $T);
		$V->bind('MATCHES', $MATCHES);
		$V->bind('CUSTOMERS', $CUSTOMERS);
		$this->_setView($V);
		$V->bind('MS', $MS);
	}

	/**
	 * Edit an existing player.
	 */
	public function edit($team_id) {
		$this->_configure();
		$MS = new Message_Stack();
		$T = new Team($team_id);
		$V = new View('team_form.php');
		
		$MATCHES = Match::getActiveMatches();
		$CUSTOMERS = Customer::getCustomers();
		
		$LAYOUT_TITLE = "Beast Franchise | Edit Team";
        $this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE);
        
        $SP = AvailablePlayer::getSP();
        $RP = AvailablePlayer::getRP();
        $CA = AvailablePlayer::getCA();
        $FB = AvailablePlayer::getFB();
        $SB = AvailablePlayer::getSB();
        $TB = AvailablePlayer::getTB();
        $SS = AvailablePlayer::getSS();
        $OF = AvailablePlayer::getOF();
        $DH = AvailablePlayer::getDH();

        $SELECTED_PLAYERS = TeamsLineup::getSelectedPlayers($team_id, false, false);
        $SELECTED_PLAYERS_LIST = TeamsLineup::getSelectedPlayers($team_id, true);

		$V->bind('TITLE', 'Edit Team');
		$V->bind('T', $T);
		$V->bind('MATCHES', $MATCHES);
		$V->bind('CUSTOMERS', $CUSTOMERS);
		
		$V->bind('SP', $SP);
		$V->bind('RP', $RP);
		$V->bind('CA', $CA);
		$V->bind('FB', $FB);
		$V->bind('SB', $SB);
		$V->bind('TB', $TB);
		$V->bind('SS', $SS);
		$V->bind('OF', $OF);
		$V->bind('DH', $DH);
		
		$V->bind('SELECTED_PLAYERS', $SELECTED_PLAYERS);
		$V->bind('SELECTED_PLAYERS_LIST', $SELECTED_PLAYERS_LIST);
		
		$this->_setView($V);
		$V->bind('MS', $MS);
	}
	
	public function remove($team_id) {
		$this->_configure();
		$T = new Team($team_id);
		$T->delete();
		
		redirect('/admin/team/');
		exit;
	}
	
	

	/**
	 * Process customer data and save it.
	 */
	public function process() {
		$this->_configure();
        $MS = new Message_Stack();
        
        $team_id = post_var('team_id');
        
		$T = new Team($team_id);
		        
		$T->load(post_var('team', array()));
		
		$today = strtotime('today');
		$T->created_date = $today;
        
		$T->write();
        redirect('/admin/team/');
		exit;
	}
	
	public function processTeamPlayers() {
    	$this->_configure();
        $MS = new Message_Stack();
        
        $team = post_var('team', array());

        $team_id = post_var('team_id');
        
        // Delete existing team
        $sql = "DELETE FROM teams_lineup WHERE team_id = '{$team_id}'";
        db_query($sql);

        $i = 1;
        foreach($team as $position => $player_id) {
            
            // Filter position
            $position = strtoupper($position);
            $position = str_replace(array("OF1", "OF2", "OF3"), "OF", $position);
            
            $TL = new TeamsLineup();
            
            $TL->team_id = $team_id;
            $TL->player_id = $player_id;
            $TL->position = $position;
            
            if($position != "SP" && $position != "RP") {
                $TL->order = $i;
                $i++;                    
            }

            
            $TL->write(); 
            
        }
        
        redirect('/admin/team/edit/' . $team_id);
        exit;
	}
	
	public function processMyTeamPlayers() {
    	$this->_config(true);
        $MS = new Message_Stack();
        
        $team = post_var('team', array());

        $team_id = post_var('team_id');
        
        // Delete existing team
        $sql = "DELETE FROM teams_lineup WHERE team_id = '{$team_id}'";
        db_query($sql);

        $i = 1;
        foreach($team as $position => $player_id) {
            
            // Filter position
            $position = strtoupper($position);
            //$position = str_replace(array("OF1", "OF2", "OF3"), "OF", $position);
            
            $TL = new TeamsLineup();
            
            $TL->team_id = $team_id;
            $TL->player_id = $player_id;
            $TL->position = $position;
            
            if($position != "SP" && $position != "RP") {
                $TL->order = $i;
                $i++;                    
            }

            
            $TL->write(); 
            
        }
        
        redirect('/team/view/' . $team_id);
        exit;
	}
	
	public function saveBattingOrder() {
    	$this->_configure();
        $MS = new Message_Stack();
        
        $players = json_decode($_REQUEST['players']);
        
        $i =1;
        foreach($players as $p) {
            $TL = new TeamsLineup($p);
            $TL->order = $i;
            $TL->write();
            $i++;
        }
        
        echo "Success";
        exit;
	}
	
	public function saveMyBattingOrder() {
    	$this->_config(true);
        $MS = new Message_Stack();
        
        $players = json_decode($_REQUEST['players']);
        
        $i =1;
        foreach($players as $p) {
            $TL = new TeamsLineup($p);
            $TL->order = $i;
            $TL->write();
            $i++;
        }
        
        echo "Success";
        exit;
	}
	
	public function view($team_id) {
  	$TEAM = new Team($team_id);
  	$GAMES = $TEAM->getGames();
  	$GAMES_INFO = $TEAM->getGamesInfo();

  	$this->_config(false, true, $GAMES);
  	$V = new View('view_team.php');
  	
  	$TEAM = new Team($team_id);
  	
  	$MATCH = new Match($TEAM->match_id);
  	
  	$cache = new Cache();
  	
  	// Set Game Times with teams
  	$GAME_TIMES = $cache->get('game_times');

  	// Lock the match if it isn't locked and start time has been reached
  	if(time() > $MATCH->start_date && $MATCH->locked == 0) {
      	
      	// Check if they have opponent. If not then remove this game and refund if necessary
      	$Opponent = $MATCH->getOpponent($MATCH->ID, $this->_user->ID);
      	
      	if(!$Opponent) {
        	$this->_user->funds += ($MATCH->entry_fee * 100);
        	$this->_user->write();
          $this->_user = new Customer($this->_user->ID);
          
          $MATCH->delete();
          
          redirect("/" . $this->_user->username);
          exit;
      	}
      	
      	$MATCH->locked = 1;
      	$MATCH->write();
      	
      	// Setup Cache for teams
      	$this->_putTeams($MATCH->ID);
      	
      	$MATCH = new Match($TEAM->match_id);

  	}
    	
    

    $SELECTED_PLAYERS = TeamsLineup::getSelectedPlayers($team_id, false, false);
    $SELECTED_PLAYERS_LIST = TeamsLineup::getSelectedPlayers($team_id, true);
    
    $OFS = TeamsLineup::getOutfielders($team_id);
    $DHS = TeamsLineup::getDHS($team_id);
    
    $TEAM_LIST = $TEAM->getTeamLineupById(false);
    
    $LINEUP = $cache->get('lineups');
    
    $WEATHER = $cache->get('weather_forecast');
    
    if($MATCH->locked == 0) {
      $CA = Player::getCA($MATCH->match_teams);
      $FB = Player::getFB($MATCH->match_teams);
      $SB = Player::getSB($MATCH->match_teams);
      $TB = Player::getTB($MATCH->match_teams);
      $SS = Player::getSS($MATCH->match_teams);
      $OF = Player::getOF($MATCH->match_teams);
      $DH = Player::getDH($MATCH->match_teams);
    }
    
    if($MATCH->locked == 1) {
      $SCORE = Team::getScore($team_id);
      
      // Update Leaderboards
      $TEAMS = Team::getAllTeams($MATCH->ID);
    
      foreach($TEAMS as $T) {
          $score = Team::getScore($T->ID);
          $T->getTotal($score);
      }
      
      
      
      if(!empty($TEAM_LIST)) {
        $AT_BAT = $TEAM_LIST[$SCORE['at_bat']];

        foreach($TEAM_LIST as $key => $lineup) {
            $P = new Player($lineup['player_id']); 
            $mlb_id = $P->mlb_id;
            
            $at_bat_count[] = count($SCORE['scores'][$mlb_id]['at_bat_stat']);
        }
        rsort($at_bat_count);
        
        $BAT_COUNT = $at_bat_count[0];
        
        $V->bind('BAT_COUNT', $BAT_COUNT);
      }
      
      
      $LEADERBOARD = Team::getLeaderboard($MATCH);
      
      $total = $TEAM->getTotal($SCORE);
    }

    $LAYOUT_TITLE = "Beast Franchise | My Team";
    $this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE);
        
		$V->bind('TEAM_LIST', $TEAM_LIST);
		$V->bind('LINEUP', $LINEUP);
		$V->bind('SCORE', $SCORE);
		$V->bind('total', $total);
		$V->bind('AT_BAT', $AT_BAT);
		$V->bind('WEATHER', $WEATHER);
		
		$V->bind('GAME_TIMES', $GAME_TIMES);

    $V->bind('TEAM', $TEAM);
    $V->bind('MATCH', $MATCH);
    	
		$V->bind('CA', $CA);
		$V->bind('FB', $FB);
		$V->bind('SB', $SB);
		$V->bind('TB', $TB);
		$V->bind('SS', $SS);
		$V->bind('OF', $OF);
		$V->bind('DH', $DH);
		
		$V->bind('SELECTED_PLAYERS', $SELECTED_PLAYERS);
		$V->bind('SELECTED_PLAYERS_LIST', $SELECTED_PLAYERS_LIST);
		$V->bind('OFS', $OFS);
		$V->bind('DHS', $DHS);
		$V->bind('LEADERBOARD', $LEADERBOARD);
		$V->bind('GAMES', $GAMES);
		$V->bind('GAMES_INFO', $GAMES_INFO);
		$V->bind('team_id', $team_id);
		$V->bind('CUSTOMER', $this->_user);
    	
    $this->_setView($V);
	}
	
	public function history() {
  	
  	$this->_config(true);
    $MS = new Message_Stack();
    
    $V = new View('view_history.php');
    
    $HISTORY = $this->_user->getTeamHistory();
    
  	$LAYOUT_TITLE = "Beast Franchise | Team History";
    $this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE);
  	
  	$V->bind('HISTORY', $HISTORY);
  	$V->bind('CUSTOMER', $this->_user);
    	
    $this->_setView($V);
	}
	
	public function find() {
    	$this->_config(true);
    	$V = new View('find_team.php');
    	
    	$TEAM_LIST = Team::getMyTeams($this->_user);
    	
    	$V->bind('TEAM_LIST', $TEAM_LIST);
    	
    	$this->_setView($V);
	}
	
	public function getScores($team_id) {
    	$this->_config(false, "", false);
    	
    	$SCORE = Team::getScoreJSON($team_id);
    	
    	return $SCORE;
    	exit;
	}
	
	public function getPlayerInfo($player_id) {
  	$this->_config(true, "", false);
  	
  	$GAMES = Team::getGames();
  	
  	$player = new Player($player_id);

  	foreach($GAMES as $game => $info) {
    	
    	if(in_array($player->player_team, $info)) {
            $key = array_search($player->player_team, $info);
            $players_team = $GAMES[$game][$key . "_abbr"];
            
            if($key == "home_team") {
                $is_home = true;
                $sps_team = "away_team_abbr";
                $sps = "away_pitcher";
            } else {
                $is_home = false;
                $sps_team = "home_team_abbr";
                $sps = "home_pitcher";
            }
            
            $sp_team = $GAMES[$game][$sps_team];
            $sp = stripslashes($GAMES[$game][$sps]);
            
            $memcache = new Cache();
            
            $LINEUP = $memcache->get('lineups');
            $LIST = $LINEUP[$players_team];
            $player_name = $player->first_name . " " . $player->last_name;
        }
  	}
    
    $player_final['player_id'] = $player->ID;
    $player_final['mlb_id'] = $player->mlb_id;
  	$player_final['player_team'] = $players_team;
  	$player_final['player'] = $player->first_name . " " . $player->last_name;
  	$player_final['sp_team'] = $sp_team;
  	$player_final['sp'] = $sp;
  	$player_final['is_home'] = $is_home;
    
    if(in_array($player_name, $LIST)) {
    	$player_final['confirmed'] = true;
    } else {
    	$player_final['confirmed'] = false;
    }
    
    if(is_null($player_final['sp'])) {
      	$player_final['sp'] = "Unknown";
  	}
  	
  	return json_encode($player_final);
    	
    exit;
	}
	
	public function getAvailablePlayers($team_id) {
    	$this->_config(true, "", false);
    	
    	$position_original = request_var('position');
    	
    	if($position_original == "OF1" || $position_original == "OF2" || $position_original == "OF3") {
        	$position = "OF";
    	} else {
        	$position = $position_original;
    	}
    	
    	$TEAM = new Team($team_id);
    	
    	$MATCH = new Match($TEAM->match_id);
    	
    	$GAMES = $TEAM->getGames();
    	
    	$function = "get" . $position;
    	
    	$PLAYERS = Player::$function($MATCH->match_teams);

    	foreach($PLAYERS AS $k => $player) {
        	foreach($GAMES as $game => $info) {
            	if(in_array($player->player_team, $info)) {
                    $key = array_search($player->player_team, $info);
                    $players_team = $GAMES[$game][$key . "_abbr"];
                    
                    if($key == "home_team") {
                        $is_home = true;
                        $sps_team = "away_team_abbr";
                        $sps = "away_pitcher";
                    } else {
                        $is_home = false;
                        $sps_team = "home_team_abbr";
                        $sps = "home_pitcher";
                    }
                    
                    $sp_team = $GAMES[$game][$sps_team];
                    $sp = stripslashes($GAMES[$game][$sps]);
                    
                    $memcache = new Cache();
                    
                    $LINEUP = $memcache->get('lineups');
                    $LIST = $LINEUP[$players_team];
                    $player_name = $player->first_name . " " . $player->last_name;
                }
        	}
  
            if(in_array($player_name, $LIST)) {
            	$player_final[$k]['player_id'] = $player->ID;
            	$player_final[$k]['position'] = $position;
            	$player_final[$k]['position_original'] = $position_original;
            	$player_final[$k]['player_team'] = $players_team;
            	$player_final[$k]['player'] = $player->first_name . " " . $player->last_name;
            	$player_final[$k]['sp_team'] = $sp_team;
            	$player_final[$k]['sp'] = $sp;
            	$player_final[$k]['is_home'] = $is_home;
            	$player_final[$k]['confirmed'] = true;
            	
            	if(is_null($player_final[$k]['sp'])) {
                	$player_final[$k]['sp'] = "Unknown";
            	}
            } else {
              $player_final[$k]['player_id'] = $player->ID;
            	$player_final[$k]['position'] = $position;
            	$player_final[$k]['position_original'] = $position_original;
            	$player_final[$k]['player_team'] = $players_team;
            	$player_final[$k]['player'] = $player->first_name . " " . $player->last_name;
            	$player_final[$k]['sp_team'] = $sp_team;
            	$player_final[$k]['sp'] = $sp;
            	$player_final[$k]['is_home'] = $is_home;
            	$player_final[$k]['confirmed'] = false;
            	
            	if(is_null($player_final[$k]['sp'])) {
                	$player_final[$k]['sp'] = "Unknown";
            	}
            }
        	
    	}
    	
    	if(is_null($player_final)) {
        	$player_final = "None";
        	return json_encode($player_final);
    	}
    	
    	return json_encode(array_values($player_final));
    	
    	exit;
	}
	
	private function _putTeams($match_id) {
    	$cache = new Cache();
        $teams = array();
        
        // Fetch the existing data
        $teams = $cache->get('teams');
        
        // Fetch todays teams
        $sql = "SELECT * FROM teams WHERE match_id = '{$match_id}'";
        
        $results = db_arr($sql);
        $MATCH = new Match($match_id);
        $MP = new Match_Price($MATCH->match_price_id);
        
        foreach($results as $team) {
            $T = new Team($team['team_id']);
            
            $teams[$team['team_id']] = $T->getTeamLineupById();
            
            // Check if promotion eligible and put the txt/hsh file together and ftp it
          	if($MP->promotion_eligible == 1) {
            	$T->sendPromotionInfo();
          	}
            
        }
        
        $cache->set("teams", $teams, 0, 0);
	}
	
	private function _config($require_login = false, $set_redirect = true, $GAMES = array()) {
		if(true == $require_login) {
			$this->_checkPermissions($set_redirect);
		}
		
		if(false == $require_login && true == $set_redirect) {
    		$this->_setRedirect();
		}
		
		$this->_setTemplate(new Template('user.php'));
		$this->_template->bind('CUSTOMER', $this->_user);
		$this->_template->bind('GAMES', $GAMES);
		$REDIR = sanitize_string(exists('go', $_GET));
		global $LAYOUT_TITLE;
		$this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE .= ' | '.$user);
	}
	
	private function _checkPermissions($set_redirect = true) {
		if(false == $this->_user->exists()) {
			if($set_redirect == true) {
			    $_SESSION['login_redirect'] = current_page_url();
            }
			$this->redirect(LOC_LOGIN);
			exit;
		}
	}
	
	private function _setRedirect() {
    	$_SESSION['login_redirect'] = current_page_url();
	}
    
    /**
	 * Sets up our template / bindings.
	 */
	private function _configure() {
		$this->_requireAdmin();
		$this->_setTemplate(new Template('default.php'));
		$this->_template->bind('ADMIN', $this->_user);
	}
}
?>