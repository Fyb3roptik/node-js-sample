<?php
require_once 'Controller.php';

class Api_Controller extends Controller {
    
    /**
     * Get message for HTTP status code
     * @param  int         $status
     * @return string|null
     */
    public static function getMessageForCode($status)
    {
        if (isset(self::$messages[$status])) {
            return self::$messages[$status];
        } else {
            return null;
        }
    }
    
    public function login() {
		$email = $_REQUEST['email'];
		
		$c = new Customer();
		$password = trim($_REQUEST['password']);
		
		if(intval($c->login($email, $password)) > 0) {
			$token = $c->newToken();
			$c->write();
			write_user_session_cookie($c);
			
			$status_code = 200;
			$response['error'] = false;
			$response['message'] = "";
			
			$response['name'] = $c->name;
			$response['email'] = $c->email;
			$response['api_key'] = $c->api_key;
			
			$this->_echoResponse($status_code, $response);
			exit;
			
		} else {
			//login failed.
			$status_code = 400;
			$response['error'] = true;
			$response['message'] = "Login Credentials Incorrect";
			$this->_echoResponse($status_code, $response);
			exit;
		}
	}

    public function register() {
		//new customer, process them	
		
		$name = $_REQUEST['name'];
		$email = $_REQUEST['email'];
		$password = $_REQUEST['password'];
		$confirm_password = $_REQUEST['confirm_password'];
		
		if($name == "" || $email == "" || $password == "" || $confirm_password == "") {
    		$status_code = 200;
			$response['error'] = true;
			$response['message'] = "Missing required fields";
			$this->_echoResponse($status_code, $response);
			exit;
		}
		
		
		$c = new Customer($email, 'email');
		
		$registration_errors = false;
		
		if(true == $c->exists()) {
			
			$status_code = 200;
			$response['error'] = true;
			$response['message'] = self::REGISTER_FAIL_MESSAGE;
			$this->_echoResponse($status_code, $response);
			exit;
			
			//$MS->add('register', sprintf(self::REGISTER_FAIL_MESSAGE, LOC_RECOVER_PASSWORD), MS_WARNING);
			//$registration_errors = true;
		}

		if(strlen($password) < MIN_PASSWORD_LENGTH && false == $registration_errors) {
			
			$status_code = 200;
			$response['error'] = true;
			$response['message'] = "The password you entered is too short. It must be at least " . MIN_PASSWORD_LENGTH . " characters.";
			$this->_echoResponse($status_code, $response);
			exit;
			
			//$MS->add('login', "The password you entered is too short. It must be at least " . MIN_PASSWORD_LENGTH . " characters.", MS_WARNING);
			//$registration_errors = true;
		}

		if("" == $name) {
			
			$status_code = 200;
			$response['error'] = true;
			$response['message'] = "You must enter a name to register";
			$this->_echoResponse($status_code, $response);
			exit;
			
			//$MS->add('login', "You must enter a name to register");
			//$registration_errors = true;
		}

		if($password === $confirm_password && strlen($password) >= MIN_PASSWORD_LENGTH) {
			//we're good to go on the password front.
			
			// Generate API Hash
			$api_hash = $this->_generateHash();
			
			$c->setName($name);
			$c->setEmail($email);
			$c->setPassword($password);
			$c->api_key = $api_hash;
			
			$c->write();
			if(true == $c->exists()) {
				//the write, took. Great. "log them in"
				$c->newToken();
				write_user_session_cookie($c);
				
				$status_code = 201;
				$response['error'] = false;
				$response['message'] = "";
				$this->_echoResponse($status_code, $response);
				exit;
				//$this->redirect($login_location);
			}
		} else {
			//TODO: add a message to the stack here.
			$status_code = 200;
			$response['error'] = true;
			$response['message'] = "The passwords you entered didn't match.";
			$this->_echoResponse($status_code, $response);
			exit;
			
			//$MS->add('login', "The passwords you entered didn't match.", MS_WARNING);
			//$registration_errors = true;
		}
	}
	
	protected function _generateHash() {
    	return md5(uniqid(rand(), true));
	}
	
	/**
     * Echoing json response to client
     * @param String $status_code Http response code
     * @param Int $response Json response
     */
    private function _echoResponse($status_code, $response) {
        
        // Http response code
        http_response_code($status_code);
        
        // setting response content type to json
        header('Content-Type:application/json');
     
        echo json_encode($response);
    } 

}
?>