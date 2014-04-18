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

        $MATCH_LIST = Match::getActiveMatches(true);
        
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
	
	public function find() {
    	$this->_config(true);
    	$V = new View('find_matches.php');
    	
    	$MATCHES = Match::getActiveMatches(true);
    	
    	$V->bind('MATCHES', $MATCHES);
    	
    	$this->_setView($V);
	}
	
	public function view($match_id) {
    	$this->_config(true);
    	$V = new View('view_match.php');
    	
    	$MATCH = new Match($match_id);
    	
    	$TOTAL_TEAMS = $MATCH->getTotalTeams();
    	$TEAM_EXISTS = $MATCH->teamExists($this->_user->ID);
    	
    	$V->bind('MATCH', $MATCH);
    	$V->bind('TOTAL_TEAMS', $TOTAL_TEAMS);
    	$V->bind('TEAM_EXISTS', $TEAM_EXISTS);
    	
    	$this->_setView($V);
	}
	
	public function joinMatch($match_id) {
    	$this->_config(true);
    	
    	$TEAM = new Team();
    	$TEAM->match_id = $match_id;
    	$TEAM->customer_id = $this->_user->ID;
    	
    	$today = strtotime('today');
		$TEAM->created_date = $today;
		
		$TEAM->write();
		
		$team_id = db_insert_id();
		
		redirect('/team/view/'.$team_id);
		exit;
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