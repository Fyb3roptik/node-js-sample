<?php
require_once dirname(__FILE__) . '/Report_Builder.php';

class Customer_Report_Builder extends Report_Builder {
	private $_customer_id;

	public function __construct($customer_id) {
		$this->_customer_id = $customer_id;
	}

	public function buildReport(Order_Report $OR) {
		$OR = parent::buildReport($OR);
		$OR->setFilter('customer_id', $this->_customer_id);
		return $OR;
	}
}
?>
