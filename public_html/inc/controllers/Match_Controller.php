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

        $MATCH_LIST = Match::getAllMatches();
        
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
		$M = new Match();
		$V = new View('match_form.php');
		
		$LAYOUT_TITLE = "Beast Franchise | Add Match";
        $this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE);
		
		$V->bind('TITLE', 'Add Match');
		$V->bind('M', $M);
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
        
        $MATCH_TEAMS = explode(",", $M->match_teams);
		
		$V->bind('TITLE', 'Edit Match');
		$V->bind('M', $M);
		$V->bind('MATCH_TEAMS', $MATCH_TEAMS);
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
	
	public function deleteTeams() {
    	$this->_configure();
        $MS = new Message_Stack();
        
        $this->_deleteTeams();
        $MS->add('match', "Deleted Team Cache", MS_SUCCESS);
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
        
        $match['match_teams'] = implode(",", post_var('match_teams', array()));
		
		$M->load($match);
        
		$M->write();
		
		// If match is locked, then lets add the teams to memory!
		if($match['locked'] == 1) {
    		$this->_putTeams(post_var('match_id'));
		}
		
        redirect('/admin/match/');
		exit;
	}
	
	public function find() {
    	$this->_config(true);
    	$V = new View('find_matches.php');
    	$MS = new Message_Stack();
    	
    	$MATCHES = Match::getActiveMatches(true);
    	
    	$memcache = new Cache();
    	
    	$GAMES = $memcache->get('games');
    	
    	$V->bind('MATCHES', $MATCHES);
    	$V->bind('GAMES', $GAMES);
    	$V->bind('CUSTOMER', $this->_user);
    	
    	$LAYOUT_TITLE = "Beast Franchise | Find Match";
        $this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE);
    	
    	$this->_setView($V);
    	$V->bind('MS', $MS);
	}
	
	public function createMatch($match_price_idAndTimeAndType = "") {
    	$this->_config(true);
    	$MS = new Message_Stack();
    	
    	if($match_price_idAndTimeAndType == "") {
      	$match_price_id = post_var('match_price_id');
      	$opponent = post_var('opponent');
      	$friend_username = post_var('friend_username');
      	$friend_email = post_var('friend_email');
      	$match_time = post_var('match_time');
      	$match_teams = post_var('match_teams');
      } else {
        $ex = explode("-", $match_price_idAndTimeAndType);
        $match_price_id = $ex[0];
        $match_time = $ex[1];
        $match_type = $ex[2];
        $opponent = "random";
      }
    	
    	switch($opponent) {
        	case "random":
        	    
        	    $MP = new Match_Price($match_price_id);
        	    
        	    $cache = new Cache();
  	
              $GAME_TIMES = Match::getGameTimes();
              
              $teams = implode(",", $GAME_TIMES[$match_type][$match_time]);
  	
        	    $Opponent = Match::findOpponent($MP, $match_time);
        	    var_dump($Opponent);
              
              if($Opponent) {
                
                $M = new Match($Opponent['match_id']);
                $OT = new Team($Opponent['team_id']);
                
                $M->current_entrants = 1;
                $M->write();
                
                $M = new Match($Opponent['match_id']);
                $T = new Team();
                $T->customer_id = $this->_user->ID;
                $T->match_id = $M->ID;
                $T->created_date = strtotime("Today");
                $T->accepted = 1;
                
                $T->write();
                
                redirect("/team/view/".db_insert_id());
                
              } else {
                $M = new Match();
                $M->start_date = $match_time;
                $M->active = 1;
                $M->entry_fee = $MP->price;
                $M->max_entrants = 2;
                $M->current_entrants = 1;
                $M->name = "H-2-H " . money_format("$%i", $MP->price) . " to win " . money_format("$%i", $MP->prize);
                $M->match_fee = $MP->profit;
                $M->match_teams = $teams;
                $M->match_price_id = $MP->ID;
                
                $M->write();
                
                // Create team for creator
                $M = new Match(db_insert_id());
                $T = new Team();
                $T->customer_id = $this->_user->ID;
                $T->match_id = $M->ID;
                $T->created_date = strtotime("Today");
                $T->accepted = 1;
                
                $T->write();
                
                redirect("/team/view/".db_insert_id());
              }
              
        	    break;
        	    
            case "friend":
                
                if($friend_username) {
                    $Opponent = new Customer($friend_username, 'username');

                    if($Opponent->exists()) {
                        $MP = new Match_Price($match_price_id);
                        $M = new Match();
                        $M->start_date = $match_time;
                        $M->active = 1;
                        $M->entry_fee = $MP->price;
                        $M->max_entrants = 2;
                        $M->current_entrants = 2;
                        $M->name = $this->_user->username . " vs " . $friend_username;
                        $M->match_fee = $MP->profit;
                        $M->match_teams = $match_teams;
                        $M->match_price_id = $MP->ID;
                        
                        $M->write();
                        
                        // Create team for creator
                        $M = new Match(db_insert_id());
                        $T = new Team();
                        $T->customer_id = $this->_user->ID;
                        $T->match_id = $M->ID;
                        $T->created_date = strtotime("Today");
                        $T->accepted = 1;
                        
                        $T->write();
                        
                        // Create team for invited
                        $T = new Team();
                        $T->customer_id = $Opponent->ID;
                        $T->match_id = $M->ID;
                        $T->created_date = strtotime("Today");
                        $T->accepted = 0;
                        
                        $T->write();
                        
                        $Mail = new Mailer();
                    	$Mail->addTo($Opponent->email, $Opponent->name);
                    	$Mail->setSubject("Beast Franchise " . $this->_user->username . " wants to play a game");
                        $body .= "<html>";
                    	$body .= "<body>";
                    	$body .= $this->_user->username . " has challenged you to a match!<br /><br />";
                    	$body .= "Click the link below to Accept the challenge!<br /><br />";
                    	$body .= "<a href='".SITE_URL."/" . $Opponent->username . "'>Go to my dashboard!</a><br /><br />";
                    	$body .= "--------------<br />This is an automated message from Beast Franchise, do not reply.";
                        $body .= "</body>";
                    	$body .= "</html>";
                    	$Mail->setBody($body);
                    	try {
                    		$Mail->send();
                    	} catch(Exception $e) {
                    		//do nothing!
                    	}
                        
                        redirect("/");
                    }
                }
                
                break;
    	}
    	
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
    	$MS = new Message_Stack();
    	
    	$M = new Match($match_id);
    	
    	if($M->locked == 0) {
        	if($M->getTotalTeams() < $M->max_entrants || $M->max_entrants == -1) {
        	    
        	    if($this->_user->funds >= $M->entry_fee) {
        	        
        	        $this->_user->funds -= ($M->entry_fee * 100);

        	        $this->_user->write();
        	        
        	        $this->_user = new Customer($this->_user->ID);
        	        
                	$TEAM = new Team();
                	$TEAM->match_id = $match_id;
                	$TEAM->customer_id = $this->_user->ID;
                	
                	$today = strtotime('today');
            		$TEAM->created_date = $today;
            		
            		$TEAM->write();
            		
            		$team_id = db_insert_id();
            		
            		redirect('/team/view/'.$team_id);
            		
                } else {
                    
                    $MS->add('/'.$this->_user->username.'/settings', "Not Enough Funds", MS_ERROR);
                    redirect('/'.$this->_user->username.'/settings');
                    
                }
            } else {
                
                $MS->add('/match/find', "Match Full", MS_ERROR);
                redirect('/match/find');
                
            }
        }
		
		exit;
	}
	
	public function getMatchPriceInfo($match_price_id) {
    	$MP = new Match_Price($match_price_id);
    	
    	$data['id'] = $MP->ID;
    	$data['price'] = money_format("%i", $MP->price);
    	$data['profit'] = money_format("%i", $MP->profit);
    	$data['prize'] = money_format("%i", $MP->prize);
    	$data['promotion_eligible'] = $MP->promotion_eligible;
    	$data['active'] = $MP->active;
    	
    	echo json_encode($data);
    	exit;
	}
	
	public function accept() {
    	
    	$match_id = post_var('match_id');
    	$opponent_id = post_var('opponent_id');
    	
    	$sql = "SELECT team_id FROM teams WHERE match_id = '{$match_id}' AND customer_id = '".$this->_user->ID."' LIMIT 1";
    	$team_id = db_arr($sql);
    	
    	$T = new Team($team_id[0]['team_id']);
    	$T->accepted = 1;
    	$T->write();
    	
    	$Opponent = new Customer($opponent_id);
    	
    	$M = new Match($match_id);
    	$MP = new Match_Price($M->match_price_id);
    	$team_id = $M->teamExists($opponent_id);
    	$OPPONENT_TEAM = new Team($team_id['team_id']);
    	
    	// Take the money out!
      $C = new Customer($this->_user->ID);
      $C->funds -= ($MP->price * 100);
      $C->write();
      
      $C = new Customer($C->ID);
      
      $Opponent->funds -= ($MP->price * 100);
      $Opponent->write();
      
      $Opponent = new Customer($Opponent->ID);
      
    	$Mail = new Mailer();
    	$Mail->addTo($Opponent->email, $Opponent->name);
    	$Mail->setSubject("Beast Franchise " . $this->_user->username . " accepted your challenge!");
        $body .= "<html>";
    	$body .= "<body>";
    	$body .= $this->_user->username . " accepted your challenge!<br /><br />";
    	$body .= "Click the link below to Set your lineup!<br /><br />";
    	$body .= "<a href='".SITE_URL."/team/view/" . $OPPONENT_TEAM->ID . "'>Set my Lineup!</a><br /><br />";
    	$body .= "--------------<br />This is an automated message from Beast Franchise, do not reply.";
        $body .= "</body>";
    	$body .= "</html>";
    	$Mail->setBody($body);
    	try {
    		$Mail->send();
    	} catch(Exception $e) {
    		//do nothing!
    	}
    	
    	$return['status'] = "accepted";
    	$return['newPrice'] = money_format("$%i", ($C->funds / 100));
    	return json_encode($return);
    	exit;
	}
	
	private function _deleteTeams() {
    	// Clear cache for today
        $cache = new Cache();
        $cache->delete("teams");
	}
	
	private function _putTeams($match_id) {
    	$cache = new Cache();
        $teams = array();
        
        // Fetch the existing data
        $teams = $cache->get('teams');
        
        // Fetch todays teams
        $sql = "SELECT * FROM teams WHERE match_id = '{$match_id}'";
        
        $results = db_arr($sql);
        
        foreach($results as $team) {
            $T = new Team($team['team_id']);
            
            $teams[$team['team_id']] = $T->getTeamLineupById();
        }
        
        $cache->set("teams", $teams, 0, 0);
	}
	
	private function _config($require_login = false, $user = "", $set_redirect = true) {
		if(true == $require_login) {
			$this->_checkPermissions($set_redirect);
		}
		
		if(false == $require_login && true == $set_redirect) {
    		$this->_setRedirect();
		}
		
		$this->_setTemplate(new Template('user.php'));
		$this->_template->bind('CUSTOMER', $this->_user);
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