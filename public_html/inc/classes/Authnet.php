<?php
/**
 * Class for handling all things related to Authorize.net
 */
class Authnet {
	public $transaction_id;
	public $authorization_code;
	
	private $_loginName;
	private $_transactionKey;
	private $_test_mode;
	
	//Private Variables
	private $_name;
	private $_length;
	private $_unit;
	private $_startDate;
	private $_totalOccurrences = 9999;
	private $_amount;
	private $_cardNumber;
	private $_expirationDate;
	private $_firstName;
	private $_lastName;

	/**
	 * URL to post to in the test environment.
	 */
	const TEST_URL = "https://apitest.authorize.net/xml/v1/request.api";

	/**
	 * URL to post to in the production environment.
	 */
	const PRODUCTION_URL = "https://api.authorize.net/xml/v1/request.api";

	const AUTH_ONLY = 'AUTH_ONLY';
	const AUTH_CAPTURE = 'AUTH_CAPTURE';

	public function setSubName($name) {
		$this->_name = $name;
	}
	
	public function setLength($length) {
		$this->_length = $length;
	}
	
	public function setUnit($unit) {
		$this->_unit = $unit;
	}
	
	public function setStartDate($startDate = null) {
		$this->_startDate = $startDate;
		if(is_null($startDate)) {
			$this->_startDate = time();	
		}
	}

	public function setAmount($amount) {
		$this->_amount = $amount;
	}

	public function setFirstName($name) {
		$this->_firstName = $name;
	}

	public function setLastName($name) {
		$this->_lastName = $name;
	}
	
	public function setValues($name, $length, $unit, $startDate, $amount, $firstName, $lastName, $cardNumber, $expirationMonth, $expirationYear) {
		$this->setSubName($name);
		$this->setLength($length);
		$this->setUnit($unit);
		$this->setStartDate($startDate);
		$this->setAmount($amount);
		$this->setFirstName($firstName);
		$this->setLastName($lastName);
		$this->setCardNumber($cardNumber);
		$this->setExpirationDate($expirationMonth, $expirationYear);
	}

	public function __construct($login, $transaction_key, $test_mode) {
		$this->_loginName = trim($login);
		$this->_transactionKey = trim($transaction_key);
		$this->_test_mode = $test_mode;
	}

	public function setType($transaction_type) {
		$good_types = array(self::AUTH_ONLY, self::AUTH_CAPTURE);
		if(false == in_array($transaction_type)) {
			throw new Exception("Bad transaction type.");
		}
		$this->_post_values['x_type'] = $transaction_type;
	}

	public function setExpirationDate($month, $year) {
		$month = abs(intval($month));
		$year = abs(intval($year));
		$date_string = $year . '-' . $month . '-01';
		$time = strtotime($date_string);
		$formatted_date = date('Y-m', $time); //dates are mmyy like "1025" for 10/2025
		$this->_expirationDate = $formatted_date;
	}

	public function setCardNumber($card_number) {
		$this->_cardNumber = $card_number;
	}

	public function transact() {
		$xml = $this->_prepareXml();

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->_getPostUrl());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		// additional options may be required depending upon your server configuration
		// you can find documentation on curl options at http://www.php.net/curl_setopt
		curl_close ($request); // close curl object

		// This line takes the response and breaks it into an array using the specified delimiting character
		try {
			list ($refId, $resultCode, $code, $text, $subscriptionId) = $this->_processResponse($response);
			$return['resultCode'] = $resultCode;
			$return['code'] = $code;
			$return['text'] = $text;
			$return['subscriptionId'] = $subscriptionId;
			
			return $return;
		} catch(Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	protected function _processResponse($response) {
		$refId = $this->substring_between($response,'<refId>','</refId>');
		$resultCode = $this->substring_between($response,'<resultCode>','</resultCode>');
		$code = $this->substring_between($response,'<code>','</code>');
		$text = $this->substring_between($response,'<text>','</text>');
		$subscriptionId = $this->substring_between($response,'<subscriptionId>','</subscriptionId>');
		return array ($refId, $resultCode, $code, $text, $subscriptionId);
	}
	
	protected function _prepareXml() {
		//build xml to post
		$xml =
		        "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
		        "<ARBCreateSubscriptionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
		        "<merchantAuthentication>".
		        "<name>" . $this->_loginName . "</name>".
		        "<transactionKey>" . $this->_transactionKey . "</transactionKey>".
		        "</merchantAuthentication>".
				"<refId></refId>".
		        "<subscription>".
		        "<name>" . $this->_name . "</name>".
		        "<paymentSchedule>".
		        "<interval>".
		        "<length>". $this->_length ."</length>".
		        "<unit>". $this->_unit ."</unit>".
		        "</interval>".
		        "<startDate>" . $this->_startDate . "</startDate>".
		        "<totalOccurrences>". $this->_totalOccurrences . "</totalOccurrences>".
		        "</paymentSchedule>".
		        "<amount>". $this->_amount ."</amount>".
		        "<payment>".
		        "<creditCard>".
		        "<cardNumber>" . $this->_cardNumber . "</cardNumber>".
		        "<expirationDate>" . $this->_expirationDate . "</expirationDate>".
		        "</creditCard>".
		        "</payment>".
		        "<billTo>".
		        "<firstName>". $this->_firstName . "</firstName>".
		        "<lastName>" . $this->_lastName . "</lastName>".
		        "</billTo>".
		        "</subscription>".
		        "</ARBCreateSubscriptionRequest>";
		        
		 return $xml;
	}

	protected function _getPostUrl() {
		$post_url = self::TEST_URL;
		if(false == $this->_test_mode) {
			$post_url = self::PRODUCTION_URL;
		}
		return $post_url;
	}
	
	//helper function for parsing response
	public function substring_between($haystack,$start,$end) {
		if (strpos($haystack,$start) === false || strpos($haystack,$end) === false) 
		{
			return false;
		} 
		else 
		{
			$start_position = strpos($haystack,$start)+strlen($start);
			$end_position = strpos($haystack,$end);
			return substr($haystack,$start_position,$end_position-$start_position);
		}
	}
}
?>