<?php
require_once dirname(__FILE__) . '/Shipping_Option_Interface.php';

class Dropship_Shipping_Option implements Shipping_Option_Interface {
	private $_cost = 0;

	public function getCode() {
		return '08'; //HARDCODED FROM SYSPRO
	}

	public function setCost($cost) {
		$this->_cost = abs(floatval($cost));
	}

	public function getCost() {
		return $this->_cost;
	}

	public function getDescription() {
		return 'Dropshipping';
	}
}
?>
