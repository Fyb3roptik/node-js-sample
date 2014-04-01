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

        $sql = "SELECT * FROM teams ORDER BY team_id DESC LIMIT 1000";
        $query = db_arr($sql);
        
        foreach($query as $team) {
            $TEAM_LIST[] = new Team($team['team_id']);
        }
        
        $LAYOUT_TITLE = "Beast Franchise | Manage Teams";
        $this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE);
        
		$V->bind('TEAM_LIST', $TEAM_LIST);
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
        
		$T->write();
        redirect('/admin/team/');
		exit;
	}
	
	public function processTeamPlayers() {
    	$this->_configure();
        $MS = new Message_Stack();
        
        $team = post_var('team', array());

        $team_id = post_var('team_id');
        
        // Get existing team
        $sql = "SELECT teams_lineup_id FROM teams_lineup WHERE team_id = '{$team_id}'";
        $results = db_query($sql);

        if($results->num_rows == 0) {
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
        }
        
        redirect('/admin/team/edit/' . $team_id);
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