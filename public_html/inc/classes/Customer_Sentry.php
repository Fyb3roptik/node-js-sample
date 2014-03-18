<?php
require_once dirname(__FILE__) . '/Sentry.php';

class Customer_Sentry implements Sentry {
	private $_customer;

	public function __construct(Customer $customer) {
		$this->_customer = $customer;
	}

	public function actionAllowed($controller, $action = null) {
		$allowed = true;
		return $allowed;
	}
}
?>