<?php
require_once 'Controller.php';

/**
 * Controller for Matches and what not.
 */
class Match_Controller extends Controller {
    
    /**
	 * Main action, displays paginated view of customer table.
	 */
	public function index() {
		$this->_configure();
		$V = new View('match_index.php');
		$this->_setView($V);
		$MS = new Message_Stack();

        $sql = "SELECT * FROM matches ORDER BY start_date DESC";
        $query = db_arr($sql);
        
        foreach($query as $match) {
            $MATCH_LIST[] = new Match($match['match_id']);
        }
        
        $LAYOUT_TITLE = "Beast Franchise | Manage Matches";
        $this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE);
        
		$V->bind('MATCH_LIST', $MATCH_LIST);
		$V->bind('MS', $MS);
	}
	
	/**
	 * Edit an existing player.
	 */
	public function add() {
		$this->_configure();
		$MS = new Message_Stack();
		$P = new Match();
		$V = new View('match_form.php');
		
		$LAYOUT_TITLE = "Beast Franchise | Add Match";
        $this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE);
		
		$V->bind('TITLE', 'Add Match');
		$V->bind('P', $P);
		$this->_setView($V);
		$V->bind('MS', $MS);
	}

	/**
	 * Edit an existing player.
	 */
	public function edit($match_id) {
		$this->_configure();
		$MS = new Message_Stack();
		$M = new Match($match_id);
		$V = new View('match_form.php');
		
		$LAYOUT_TITLE = "Beast Franchise | Edit Match";
        $this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE);
		
		$V->bind('TITLE', 'Edit Match');
		$V->bind('M', $M);
		$this->_setView($V);
		$V->bind('MS', $MS);
	}
	
	public function remove($match_id) {
		$this->_configure();
		$M = new Match($match_id);
		$M->delete();
		
		redirect('/admin/match/');
		exit;
	}
	
	

	/**
	 * Process customer data and save it.
	 */
	public function process() {
		$this->_configure();
        $MS = new Message_Stack();

		$M = new Match(post_var('match_id'));
		
		$match = post_var('match', array());
		
		$match['active'] = post_var('match_active', 0);
		$match['locked'] = post_var('match_locked', 0);
		
		$start_date = post_var('start_date');
        $start_time = post_var('start_time');
        
        $match['start_date'] = strtotime($start_date . " " . $start_time);
		
		$M->load($match);
        
        
        
		$M->write();
        redirect('/admin/match/');
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