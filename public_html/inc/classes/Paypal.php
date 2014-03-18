<?php
class Paypal {
	private $_environment = 'sandbox';	// or 'beta-sandbox' or 'live'
	private $_username;
	private $_password;
	private $_signature;
	private $_method;
	private $_version = '51.0';
	private $_post;
	
	
	public function __construct($username, $password, $signature) {
		$this->_username = $username;
		$this->_password = $password;
		$this->_signature = $signature;	
	}
	
	public function createRecurringPayment($method, $post) {
		$this->_method = $method;
		$this->_post = $post;
		$payment = $this->_sendPayment();
		return $payment;
	}

	private function _sendPayment() {
	
		$API_UserName = urlencode($this->_username);
		$API_Password = urlencode($this->_password);
		$API_Signature = urlencode($this->_signature);
		$API_Endpoint = "https://api-3t.paypal.com/nvp";
		if("sandbox" === $this->_environment || "beta-sandbox" === $this->_environment) {
			$API_Endpoint = "https://api-3t.".$this->_environment.".paypal.com/nvp";
		}
	
		// setting the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
	
		// turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
	
		// NVPRequest for submitting to server
		$nvpreq = "METHOD=".$this->_method."&VERSION=".$this->_version."&PWD=".$this->_password."&USER=".$this->_username."&SIGNATURE=".$this->_signature.$this->_post;

		// setting the nvpreq as POST FIELD to curl
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
	
		// getting response from server
		$httpResponse = curl_exec($ch);
	
		if(!$httpResponse) {
			exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
		}
	
		// Extract the RefundTransaction response details
		$httpResponseAr = explode("&", $httpResponse);
	
		$httpParsedResponseAr = array();
		foreach ($httpResponseAr as $i => $value) {
			$tmpAr = explode("=", $value);
			if(sizeof($tmpAr) > 1) {
				$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
			}
		}
	
		if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
			exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
		}
	
		return $httpParsedResponseAr;
	}
}
?>