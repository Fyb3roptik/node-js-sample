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
		
		$P->load(post_var('player', array()));

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