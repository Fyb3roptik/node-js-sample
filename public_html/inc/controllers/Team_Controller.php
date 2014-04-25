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
    	$this->_config(true);
    	$V = new View('view_team.php');
    	
    	$TEAM = new Team($team_id);
    	
    	$MATCH = new Match($TEAM->match_id);
    	
        $CA = Player::getCA($MATCH->match_teams);
        $FB = Player::getFB($MATCH->match_teams);
        $SB = Player::getSB($MATCH->match_teams);
        $TB = Player::getTB($MATCH->match_teams);
        $SS = Player::getSS($MATCH->match_teams);
        $OF = Player::getOF($MATCH->match_teams);
        $DH = Player::getDH($MATCH->match_teams);

        $SELECTED_PLAYERS = TeamsLineup::getSelectedPlayers($team_id, false, false);
        $SELECTED_PLAYERS_LIST = TeamsLineup::getSelectedPlayers($team_id, true);
        
        $OFS = TeamsLineup::getOutfielders($team_id);
        $DHS = TeamsLineup::getDHS($team_id);
        
        $TEAM = new Team($team_id);
        $TEAM_LIST = $TEAM->getTeamLineupById(false);
        $SCORE = Team::getScore($team_id);
        $AT_BAT = $TEAM_LIST[$SCORE['at_bat']];
        
        // Update Leaderboards
        $TEAMS = Team::getAllTeams($MATCH->ID);
        
        foreach($TEAMS as $T) {
            $score = Team::getScore($T->ID);
            $T->getTotal($score);
        }
        
        $LEADERBOARD = Team::getLeaderboard($MATCH);
        
        $total = $TEAM->getTotal($SCORE);
        
        $LAYOUT_TITLE = "Beast Franchise | My Team";
        $this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE);
        
		$V->bind('TEAM_LIST', $TEAM_LIST);
		$V->bind('SCORE', $SCORE);
		$V->bind('total', $total);
		$V->bind('AT_BAT', $AT_BAT);

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
	
	private function _config($require_login = false, $user = "") {
		if(true == $require_login) {
			$this->_checkPermissions();
		}
		$this->_setTemplate(new Template('user.php'));
		$this->_template->bind('CUSTOMER', $this->_user);
		$REDIR = sanitize_string(exists('go', $_GET));
		global $LAYOUT_TITLE;
		$this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE .= ' | '.$user);
	}
	
	private function _checkPermissions() {
		if(false == $this->_user->exists()) {
			$_SESSION['login_redirect'] = $_SERVER['REDIRECT_URL'];
			$this->redirect(LOC_LOGIN);
			exit;
		}
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