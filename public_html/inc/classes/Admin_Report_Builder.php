<?php
require_once dirname(__FILE__) . '/Report_Builder.php';

class Admin_Report_Builder extends Report_Builder {
	public $order_url = '/admin/ralph/view/%d/';

	public function __construct() {
		$this->_loadAdminFields();
		$this->_loadAdminFilters();
	}

	/**
	 * Load extra, admin-only fields here. :]
	 */
	private function _loadAdminFields() {
		$this->_available_fields[] = 'coupon_code';
		$this->_available_fields[] = 'sales_rep_id';
		$this->_available_fields[] = 'sales_rep';
		$this->_available_fields[] = 'customer_id';
		$this->_available_fields[] = 'landed_cost';
		$this->_available_fields[] = 'gross_profit';
		$this->_available_fields[] = 'margin';
	}

	private function _loadAdminFilters() {
		$this->_filter_fields[] = 'sales_rep';
		$this->_filter_fields[] = 'coupon_code';
		$this->_filter_fields[] = 'ubd_code';
		$this->_filter_fileds[] = 'order_id';
	}
}
?>
