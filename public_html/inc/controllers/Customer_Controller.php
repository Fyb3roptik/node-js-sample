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
		
		$customer_sql = "SELECT *
				FROM customers";
		
		$query = db_arr($customer_sql);
        
        foreach($query as $customer) {
            $CUSTOMER_LIST[] = new Customer($customer['customer_id']);
        }
        
        $LAYOUT_TITLE = "Beast Franchise | Manage Customers";
        $this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE);

		$V->bind('CUSTOMER_LIST', $CUSTOMER_LIST);
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