<?php
require_once dirname(__FILE__) . '/Controller.php';
checkSecureSite($_SERVER['REQUEST_URI']);
class Login_Controller extends Controller {

	const LOGIN_SESSION_REDIR = 'login_redirect';

	/**
	 * Message for when login fails.
	 */
	const LOGIN_FAIL_MESSAGE = 'Login credentials incorrect';

	/**
	 * Message for when registration fails.
	 */
	const REGISTER_FAIL_MESSAGE = 'An account already exists with this email address. If you have an active account with Beast Franchise please click on the "<a href="%s">Forgot Password</a>" link below to reset your password';

	public function __construct() {
		$this->_setTemplate(new Template('default.php'));
	}

	public function index() {
		$this->_loginForm();
	}
	
	public function register() {
    	$this->_registerForm();
	}

	private function _loginForm($extra_bindings = array()) {
		$this->_template->bind('CUSTOMER', $this->_user);
		$REDIR = sanitize_string(exists('go', $_GET));
		global $LAYOUT_TITLE;
		$VIEW = new View('login.php');
		$VIEW->bind('REDIR', $REDIR);
		$VIEW->bind('MS', new Message_Stack());
		$this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE .= ' | Login');
		$this->_setView($VIEW);
		foreach($extra_bindings as $key => $val) {
			$VIEW->bind($key, $val);
		}
	}
	
	private function _registerForm($extra_bindings = array()) {
		$this->_template->bind('CUSTOMER', $this->_user);
		$REDIR = sanitize_string(exists('go', $_GET));
		global $LAYOUT_TITLE;
		$VIEW = new View('register.php');
		$VIEW->bind('REDIR', $REDIR);
		$VIEW->bind('MS', new Message_Stack());
		$this->_template->bind('LAYOUT_TITLE', $LAYOUT_TITLE .= ' | Registration');
		$this->_setView($VIEW);
		foreach($extra_bindings as $key => $val) {
			$VIEW->bind($key, $val);
		}
	}

	public function processLogin() {
		$username = post_var('email');
		$customer_type = post_var('customer_type', null);
		$login_errors = false;
		$MS = new Message_Stack();
		$c = new Customer();
		$password = trim(exists('password', $_POST));
		if(intval($c->login($username, $password)) > 0) {
			$token = $c->newToken();
			$c->write();
			write_user_session_cookie($c);
			$login_location = $this->_getLoginLocation($c);
			$this->redirect($login_location);
		} else {
			//login failed.
			//TODO: Add message to message stack
			$MS->add('login', sprintf(self::LOGIN_FAIL_MESSAGE, LOC_RECOVER_PASSWORD), MS_ERROR);
			$this->_loginForm(array(
				'REDIR' => post_var('go'),
				'LOGIN_EMAIL' => $email));
		}
	}

	public function processRegister() {
		//new customer, process them
		$MS = new Message_Stack();
		$name = post_var('name');
		$email = post_var('email');
		$new_password = trim(post_var('new_password'));
		$new_password_confirm = trim(post_var('new_password_confirm'));
		$username = trim(post_var('username'));
        $acknowledge = post_var('acknowledge', "unchecked");

		$c = new Customer($email, 'email');
		$login_location = $this->_getLoginLocation();
		if($login_location == "") {
			$login_location = "/";
		}
		$registration_errors = false;
		
		if($acknowledge == "unchecked") {
            $MS->add('register', "You must agree to the terms", MS_ERROR);
            $registration_errors = true;
        }
       
		if(true == $c->exists()) {
			$MS->add('register', sprintf(self::REGISTER_FAIL_MESSAGE, "/register"), MS_ERROR);
			$registration_errors = true;
		}

		if(strlen($new_password) < MIN_PASSWORD_LENGTH && false == $registration_errors) {
			$MS->add('register', "The password you entered is too short. It must be at least " . MIN_PASSWORD_LENGTH . " characters.", MS_ERROR);
			$registration_errors = true;
		}

		if("" == $name) {
			$MS->add('register', "You must enter a name to register");
			$registration_errors = true;
		}

		if(false == $registration_errors) {
			if($new_password === $new_password_confirm && strlen($new_password) >= MIN_PASSWORD_LENGTH) {
				//we're good to go on the password front.
				$c->setName($name);
				$c->stage_name = $stage_name;
				$c->funds = 150;
				$c->setEmail($email);
				$c->setPassword($new_password);
				if($username != "") {
					$c->username = $username;
				}
				$c->user_type = $user_type;
				$c->write();
				if(true == $c->exists()) {
					//the write, took. Great. "log them in"
					$c->newToken();
					$c->write();
					write_user_session_cookie($c);
					$this->redirect("/" . $c->username);
				}
			} else {
				//TODO: add a message to the stack here.
				$MS->add('register', "The passwords you entered didn't match.", MS_ERROR);
				$registration_errors = true;
			}
		}

		if(true == $registration_errors) {
			$this->_registerForm(array(
				'REDIR' => post_var('go'),
				'REGISTRATION_EMAIL' => $email
			));
		}
	}
	
	public function checkUsername() {
		$username = post_var("username");
		
		$sql = SQL::get()
				->select("username")
				->from("customers")
				->where("username = '".$username."'");
				
		$rs = db_arr($sql);
		
		$return = false;
		if(!is_null($rs)) {
			$return = true;
		}
		
		echo $return;
		exit;
	}

	protected function _getLoginLocation(Customer $CUSTOMER) {
		$login_redirect = session_var('login_redirect', null);
		$product = post_var('product', 0);
		$product_list_page = post_var('page');
		$plan_page = post_var('page');
		$redir_map = array(
			'checkout' => LOC_CHECKOUT,
			'product_list' => $product_list_page,
			'plan_page' => '/myaccount/#Plan'
		);

		$login_location = LOC_HOME;
		
		if(false == is_null($login_redirect)) {
			$login_location = $login_redirect;
		} else {
			$login_location = exists(post_var('go', null), $redir_map, $login_location);
		}
		return $login_location;
	}

	public function logout() {
		$login_location = $this->_getLoginLocation();
		$token = exists('session_id', $_COOKIE);
		$c = User_Session::tokenFactory($token);
		$c->logout();
		unset($_COOKIE['session_id']);
		$this->redirect($login_location);
		break;
	}
}
?>
