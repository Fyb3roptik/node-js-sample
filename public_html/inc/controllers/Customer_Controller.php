<?php
require_once 'Controller.php';
require_once dirname(__FILE__) . '/../classes/Stripe/Stripe.php';

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
		$C = new Customer($username, "username");
		$V = new View('view_customer.php');
		
		$MATCHES = Match::getActiveMatches(true);
		
		$memcache = new Cache();
        
        $GAMES = $memcache->get('games');
    	
    	$V->bind('MATCHES', $MATCHES);
    	$V->bind('GAMES', $GAMES);
		
		$V->bind('C', $C);
		$V->bind('CUSTOMER', $this->_user);
		$this->_setView($V);
	}
	
	public function settings($username) {
		$this->_config(true, $username);
		$MS = new Message_Stack();
		$C = new Customer($username, "username");
		$V = new View('customer_settings.php');
		
		
		
		$V->bind('C', $C);
		$V->bind('MS', $MS);
		$V->bind('CUSTOMER', $this->_user);
		$this->_setView($V);
	}
	
	public function checkRouting() {
    	$this->_config(true, $username);
    	
    	$routing = get_var('rt');
    	
    	$url = "https://www.routingnumbers.info/api/data.json?rn=" . $routing;
    	
    	$get = file_get_contents($url);
    	
    	$obj = json_decode($get);
    	
    	if($routing == 110000000) {
        	return "STRIPE Test Routing";
    	} else {
        	return $obj->customer_name;
    	}
    	
    	exit;
	}
	
	public function withdrawFunds($username) {
    	$this->_config(true, $username);
    	$MS = new Message_Stack();
		$C = new Customer($username, "username");
		
		Stripe::setApiKey(STRIPE_LIVE_SECRET);
		
		$token = get_var('stripeToken');
		$amount = get_var('amount');

		// Create a Recipient
        $recipient = Stripe_Recipient::create(array(
          "name" => $C->name,
          "type" => "individual",
          "bank_account" => $token,
          "email" => $C->email)
        );
        
        $recipient_id = $recipient->id;
        
        $transfer = Stripe_Transfer::create(array(
          "amount" => $amount, // amount in cents
          "currency" => "usd",
          "recipient" => $recipient_id,
          "bank_account" => $recipient->token,
          "statement_description" => "Beast Franchise Withdraw")
        );
        
        if($transfer->status == "pending" || $transfer->status == "paid") {
            $C->funds -= $amount;
            $C->write();
            
            $C = new Customer($C->ID);
            
            $MS->add('/'.$C->username.'/settings', "$" . $amount / 100 . " has been withdrawn. Please allow 2-3 business days for the funds to reach your bank account.", MS_SUCCESS);
        }
        
        if($transfer->status == "failed") {
            $MS->add('/'.$C->username.'/settings', "Transfer Failed", MS_ERROR);
        }
        
        redirect('/'.$C->username.'/settings');
        
        exit;
	}
	
	public function addFunds($username) {
		$this->_config(true, $username);
		$C = new Customer($username, "username");
		
        Stripe::setApiKey(STRIPE_LIVE_SECRET);
        
        $token = post_var('stripeToken');
        $amount = post_var('amount');
        
        try {
            $charge = Stripe_Charge::create(array(
              "amount" => $amount,
              "currency" => "usd",
              "card" => $token,
              "description" => "Beast Franchise Adding Funds")
            );

            if($charge->paid == true) {
                $C->funds += $amount;
                $C->write();
                $C = new Customer($username, "username");
            }
        } catch(Stripe_CardError $e) {
            
        }
        
        redirect("/" . $username . "/settings");
        
        exit;
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