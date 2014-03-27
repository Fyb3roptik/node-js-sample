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
		
		$V->bind('TITLE', 'Edit Team');
		$V->bind('T', $T);
		$V->bind('MATCHES', $MATCHES);
		$V->bind('CUSTOMERS', $CUSTOMERS);
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

		$T = new Team(post_var('team_id'));
		        
		$T->load(post_var('team', array()));
        
		$T->write();
        redirect('/admin/team/');
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