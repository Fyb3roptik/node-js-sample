<?php
require_once 'Controller.php';

class Api_Controller extends Controller {
    
    private $_customer;
    
    public function login() {
		
		if (isset($_POST['email'])) {
    		$email = $email = trim($_POST['email']);
		} else {
		    $postdata = json_decode(file_get_contents("php://input"));
		    $email = $postdata->email;
    		
		}
		
		if (isset($_POST['email'])) {
    		$password = trim($_POST['password']);
		} else {
    		$postdata = json_decode(file_get_contents("php://input"));
		    $password = $postdata->password;
		}
		
        $c = new Customer();
		
		
		
		if(intval($c->login($email, $password)) > 0) {
			$token = $c->newToken();
			
			// Write new API Key
			$new_key = $this->_generateKey($c);
			
			$c->write();
			write_user_session_cookie($c);
			
			$status_code = 200;
			$response['error'] = false;
			$response['message'] = "";
			
			$response['name'] = $c->name;
			$response['email'] = $c->email;
			$response['api_key'] = $new_key;
			
			
			
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
	
	public function getUserData() {
    	
    	$this->_authenticate();
    	
    	// Write new API Key
        $new_key = $this->_generateKey($c);
        
    	$status_code = 200;
    	$response['error'] = false;
    	$response["message"] = "";
    	$response['name'] = $this->_customer->name;
    	$response['email'] = $this->_customer->email;
    	$response['api_key'] = $new_key;
    	
    	
			
    	$this->_echoResponse($status_code, $response);
    	exit;
	}
	
	protected function _authenticate() {
    	
    	$headers = apache_request_headers();
    	
    	// Verifying Authorization Header
        if (isset($headers['Authorization'])) {
            $api_key = $headers['Authorization'];
            
            $this->_customer = new Customer($api_key, "api_key");

            if(!$this->_customer->exists()) {
                // api key is not present in users table
                $response["error"] = true;
                $response["message"] = "Access Denied. Invalid Api key";
                $this->_echoResponse(400, $response);
                exit;
            }
        } else {
            // api key is missing in header
            $response["error"] = true;
            $response["message"] = "Api key is misssing";
            $this->_echoResponse(400, $response);
            exit;
        }
	}
	
	protected function _generateKey(Customer $C) {
    	$new_hash = $this->_generateHash();
    	
    	$C->api_key = $new_hash;
    	$C->write();
    	
    	return $new_hash;
	}
	
	protected function _checkAccess($api_key) {
        
        $return = false;
        
        if($C->api_key == $api_key) {
            $return = true;
        }
        
        return $return;
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