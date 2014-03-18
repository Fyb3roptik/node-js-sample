<?php
require_once 'Controller.php';

class Search_Controller extends Controller {
	public function index() {		
		$this->_config();
		$V = new View('search.php');

		$this->_setView($V);

		FB::groupEnd();
	}
	
	public function find() {		
		$this->_config();
		
		// Get parameters from URL
		$q = get_var('q');
		
		$search_results = Search::findEverything($q);
		
		$search_title = 'Search Results for "' . htmlentities($search_term) . '"';
		
		$V = new View('search.php');
		$V->bind("PAGE_TITLE", $search_title);
		$V->bind("CUSTOMER", $this->_user);
		$V->bind("search_results", $search_results);
		$this->_setView($V);
	}
	
	public function addFavorite() {
		$customer_id = post_var('customer_id');
		$dj_id = post_var('dj_id');
		
		$CD = new Customer_Dj();
		
		$CD->customer_id = $customer_id;
		$CD->dj_id = $dj_id;
		$CD->write();
		
		echo "Success";
		exit;
	}

	private function _config() {
		global $LAYOUT_TITLE;
		$this->_setTemplate(new Template('default.php'));
		$this->_template->bind('CUSTOMER', $this->_user);
		$this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE .= ' | Search');
	}
}
?>