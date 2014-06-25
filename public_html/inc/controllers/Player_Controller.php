<?php
require_once 'Controller.php';

/**
 * Controller for MLB Players and what not.
 */
class Player_Controller extends Controller {
    
    /**
	 * Main action, displays paginated view of customer table.
	 */
	public function index() {
		$this->_configure();
		$V = new View('player_index.php');
		$this->_setView($V);
		$MS = new Message_Stack();

        $sql = "SELECT * FROM players ORDER BY last_name ASC";
        $query = db_arr($sql);
        
        foreach($query as $player) {
            $PLAYER_LIST[] = new Player($player['player_id']);
        }
        
        $LAYOUT_TITLE = "Beast Franchise | Manage Players";
        $this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE);
        
		$V->bind('PLAYER_LIST', $PLAYER_LIST);
		$V->bind('MS', $MS);
	}
	
	public function available($date) {
    	$this->_configure();
		$V = new View('player_available.php');
		$this->_setView($V);
		$MS = new Message_Stack();
        
        if(!isset($date)) {
            $date = strtotime(date('m/d/Y', time()));
        }
        
        // Available Players
        $sql = "SELECT * FROM available_players WHERE date = '{$date}' ORDER BY player_id";
        $query = db_arr($sql);
        
        foreach($query as $player) {
            $AVAILABLE_PLAYER_LIST[] = new AvailablePlayer($player['available_player_id']);
        }
                
        // Master Player List
        $sql = "SELECT * FROM players ORDER BY last_name ASC";
        $query = db_arr($sql);
        
        foreach($query as $player) {
            $PLAYER_LIST[] = new Player($player['player_id']);
        }
        
        // Make lists of id's
        foreach($AVAILABLE_PLAYER_LIST as $a) {
            $available_list[] = $a->player_id;
        }
        
        foreach($PLAYER_LIST as $p) {
            $player_list[] = $p->ID;
        }
        
        // Remove moved players from master list
        foreach($available_list as $a) {
            if(($key = array_search($a, $player_list)) !== false) {
                unset($player_list[$key]);
            }
        }
        
        // Remake Master List
        $PLAYER_LIST = array();
        foreach($player_list as $p) {
            $PLAYER_LIST[] = new Player($p);
        }
        
        $LAYOUT_TITLE = "Beast Franchise | Available Players List";
        $this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE);
        
        $V->bind('TITLE', 'Available Players');
        $V->bind('AVAILABLE_PLAYER_LIST', $AVAILABLE_PLAYER_LIST);
		$V->bind('PLAYER_LIST', $PLAYER_LIST);
		$V->bind('MS', $MS);
	}
	
	public function saveAvailable() {
    	$this->_configure();
    	
    	$players = json_decode($_REQUEST['players']);
    	
    	// Truncate table first
    	$sql = "TRUNCATE TABLE available_players";
    	db_query($sql);
    	
    	foreach($players as $p) {
        	$A = new AvailablePlayer($p, "player_id");
        	$P = new Player($p);
        	
        	if(!$A->exists()) {
            	$A->player_id = $p;
            	$A->date = strtotime(date('m/d/Y', time()));
            	$A->position = $P->position;
            	$A->write();
            	
            }
            
            if($P->dh == 1) {
            	$ADH = new AvailablePlayer();
            	
            	$ADH->player_id = $p;
                $ADH->date = strtotime(date('m/d/Y', time()));
                $ADH->position = "DH";
                
                $ADH->write();
        	}
    	}
    	
    	echo "Success";
    	
    	exit;
	}
	
	public function resetAvailable() {
    	$this->_configure();
    	
    	// Truncate table first
    	$sql = "TRUNCATE TABLE available_players";
    	db_query($sql);
    	
    	redirect('/admin/player/available/');
		exit;
	}
	
	/**
	 * Edit an existing player.
	 */
	public function add() {
		$this->_configure();
		$MS = new Message_Stack();
		$P = new Player();
		$V = new View('player_form.php');
		
		$LAYOUT_TITLE = "Beast Franchise | Add Player";
        $this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE);
		
		$V->bind('TITLE', 'Add Player');
		$V->bind('P', $P);
		$this->_setView($V);
		$V->bind('MS', $MS);
	}

	/**
	 * Edit an existing player.
	 */
	public function edit($player_id) {
		$this->_configure();
		$MS = new Message_Stack();
		$P = new Player($player_id);
		$V = new View('player_form.php');
		
		$LAYOUT_TITLE = "Beast Franchise | Edit Player";
        $this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE);
		
		$V->bind('TITLE', 'Edit ' . $P->first_name . " " . $P->last_name);
		$V->bind('P', $P);
		$this->_setView($V);
		$V->bind('MS', $MS);
	}
	
	public function remove($player_id) {
		$this->_configure();
		$P = new Player($player_id);
		$P->delete();
		
		redirect('/admin/player/');
		exit;
	}
	
	

	/**
	 * Process customer data and save it.
	 */
	public function process() {
		$this->_configure();
        $MS = new Message_Stack();
        
		$P = new Player(post_var('player_id'));
		
		$player = post_var('player', array());
		$player['dh'] = post_var('dh', 0);
		$player['active'] = post_var('active', 0);
		
		$P->load($player);

		$P->write();
        redirect('/admin/player/');
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