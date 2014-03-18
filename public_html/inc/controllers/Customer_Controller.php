<?php
require_once 'Controller.php';

/**
 * Controller for Customers and what not.
 */
class Customer_Controller extends Controller {
	
	/**
	 * Main action, displays paginated view of customer table.
	 */
	public function index() {
		$this->_configure();
		$V = new View('customer_index.php');
		$this->_setView($V);
		$MS = new Message_Stack();
        //The searching is jankness
        $customer_name = get_var('customer_name');
		$customer_id = get_var('customer_id');
        $customer_email = get_var('customer_email');
        $date_range = array("min" => urldecode(get_var("date_range_min")), "max" => urldecode(get_var("date_range_max")));
        
        $sort = "name";
        $dir = "ASC";
		$new_dir = "DESC";
        
        if($customer_email != "") {
			$where_clause[] = "email = '".$customer_email."'";
		}
		if($customer_name != "") {
			$where_clause[] = "name LIKE '%".$customer_name."%' OR stage_name LIKE '%".$customer_name."%'";
		}
		if($customer_id != "") {
			$where_clause[] = "customer_id = '".$customer_id."'";
		}
		if($date_range["min"] != "" && $date_range["max"] != "") {
			$where_clause[] = "date_registered >= '" . date("Y-m-d H:i:s", strtotime($date_range['min'])) . "' AND date_registered <= '" . date("Y-m-d H:i:s", strtotime($date_range['max'] . " 11:59:59")) . "'";
			$sort = "date_registered";
       		$dir = "DESC";
			$new_dir = "ASC";
		}
		if(false == is_null($where_clause)) {
			$where = "WHERE ".implode(" OR ", $where_clause);
		}
		
		if(get_var('sort') != "") {
			$sort = get_var('sort');
		}
		
		if(get_var('dir') != "") {
			$dir = get_var('dir');
			if(get_var('dir') == "DESC") {
				$new_dir = "ASC";
			}
		}
		
		$customer_sql = "SELECT customer_id
				FROM `customers` ".$where."
				ORDER BY ".$sort." ".$dir;
		$page = get_var('page', 1);
		$PK = new Page_Killer($customer_sql, 40, $page);
		$CUSTOMER_LIST = $PK->query();
		
		$TOTAL = $PK->getTotalRows();
		
		$dj_count_sql = "SELECT count(customer_id) as total FROM customers WHERE user_type = 'dj'";
		$dj_count = db_arr($dj_count_sql);
		
		$singer_count_sql = "SELECT count(customer_id) as total FROM customers WHERE user_type = 'user'";
		$singer_count = db_arr($singer_count_sql);
		
		$total_search_count_sql = "SELECT count(customer_id) as total FROM customers";
		$total_search_count = db_arr($total_search_count_sql);

		$pk_params = array("sort" => $sort, "dir" => $dir); //add search stuff here probably
		$PK_LINKS = $PK->getLinks($pk_params);

		$V->bind('CUSTOMER_LIST', $CUSTOMER_LIST);
		$V->bind('TOTAL', $total_search_count[0]['total']);
		$V->bind('TOTAL_SEARCH', $TOTAL);
		$V->bind('DJS', $dj_count[0]['total']);
		$V->bind('SINGERS', $singer_count[0]['total']);
		$V->bind('new_dir', $new_dir);
		$V->bind('page', $page);
		$V->bind('PK_LINKS', $PK_LINKS);
		$V->bind('MS', $MS);
	}

	/**
	 * Edit an existing customer.
	 */
	public function edit($customer_id) {
		$this->_configure();
		$MS = new Message_Stack();
		$C = new Customer($customer_id);
		$V = new View('customer_form.php');
		$V->bind('C', $C);
		$V->bind('PLANS', $this->_getPlans($C));
		$this->_setView($V);
		$V->bind('MS', $MS);
	}
	
	public function remove() {
		$this->_configure();
		$customer_id = post_var('customer_id');
		$C = new Customer($customer_id);
		$C->delete();
		
		$data['success'] = true;
		$data['customer_id'] = $customer_id;
		
		echo json_encode($data);
		exit;
	}
	
	

	/**
	 * Process customer data and save it.
	 */
	public function process() {
		$this->_configure();
        $MS = new Message_Stack();
		$C = new Customer(post_var('customer_id'));
		$C->load(post_var('customer', array()));
		
		if(post_var('username') != "") {
			$C->username = post_var('username');
		} else {
			$error = true;
			$MS->add('customer_form', "Username CANNOT be blank");
		}

		$new_password = post_var('new_password');
		$confirm_password = post_var('confirm_password');

		if($new_password != $confirm_password) {
			$error = true;
			$MS->add('customer_form', "Passwords do not match", MS_ERROR);
		}

		if($new_password != "" && $confirm_password == $new_password) {
			try {
				$C->setPassword($new_password, null, true);
				$MS->add('customer', "Password has been reset");
			} catch(Exception $e) {
				$MS->add('customer_form', "Password could not be reset", MS_ERROR);
				$error = true;
			}

		}

		$C->write();
        if(false == $error) {
			redirect('/admin/customer/');
		} else {
			redirect('/admin/customer/edit/'.$C->ID);
		}
		exit;
	}
	
	public function view($username) {
		$this->_config(false, $username);
		$DJ = new Customer($username, "username");
		$V = new View('view_customer.php');
		$V->bind('DJ', $DJ);
		$V->bind('CUSTOMER', $this->_user);
		$this->_setView($V);
	}
	
	private function _config($require_login = false, $user = "") {
		if(true == $require_login) {
			$this->_checkPermissions();
		}
		$this->_setTemplate(new Template('default.php'));
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