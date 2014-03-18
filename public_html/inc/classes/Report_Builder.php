<?php
require_once dirname(__FILE__) . '/Order_Report.php';

class Report_Builder {
	public $title = 'Order Report';
	public $order_url = '/orders.php?action=view&amp;order=%d';
	private $_sort_field;
	private $_sort_direction;

	protected $_filter_fields = array(
		'min_date',
		'max_date'
	);

	protected $_set_filters = array();

	protected $_available_fields = array(
		'date_purchased',
		'billing_name',
		'billing_company',
		'billing_address_1',
		'billing_address_2',
		'billing_city',
		'billing_state',
		'billing_zip_code',
		'billing_phone',
		'shipping_company',
		'shipping_name',
		'shipping_address_1',
		'shipping_address_2',
		'shipping_city',
		'shipping_state',
		'shipping_zip_code',
		'shipping_cost',
		'subtotal',
		'tax',
		'total',
		'po_number',
		'product_id',
		'product_name',
		'product_image',
		'catalog_code',
		'product_quantity',
		'product_price',
		'product_subtotal'
	);

	private $_selected_fields = array('order_id');

	public function sortBy($field = null, $direction = 'DESC') {
		if(false == is_null($field)) {
			$this->_sort_field = $field;
			$this->_sort_direction = $direction;
		}
	}

	public function getSelectedFields() {
		return $this->_selected_fields;
	}

	public function getAvailableFields() {
		return $this->_available_fields;
	}

	public function getAvailableFilters() {
		return $this->_filter_fields;
	}

	public function setFilterList($filter_list) {
		if(false == is_null($filter_list)) {
			foreach($filter_list as $filter => $value) {
				$this->setFilter($filter, $value);
			}
		}
	}

	public function setFilter($filter, $value) {
		$filter_added = false;
		$value = trim($value);
		if(true == in_array($filter, $this->_filter_fields) && false == empty($value)) {
			$this->_set_filters[$filter] = $value;
			$filter_added = true;
		}
		return $filter_added;
	}

	public function getFilters() {
		$filter_list = array();
		foreach($this->_filter_fields as $field) {
			$value = null;
			if(true == array_key_exists($field, $this->_set_filters)) {
				$value = $this->_set_filters[$field];
			}
			$filter_list[$field] = $value;
		}
		return $filter_list;
	}

	public function addSort($sort_field) {
		if(true == $this->_invalidField($sort_field)) {
			throw new Exception("Field '" . htmlspecialchars($sort_field) . "' does not exist.");
		}
		if(false == in_array($sort_field, $this->_selected_fields)) {
			$this->_selected_fields[] = $sort_field;
			$this->_removeAvailable($sort_field);
		}
	}

	private function _invalidField($field) {
		$invalid_field = true;
		$valid_field_list = array_merge($this->getAvailableFields(), $this->getSelectedFields());
		if(true == in_array($field, $valid_field_list)) {
			$invalid_field = false;
		}
		return $invalid_field;
	}

	private function _removeAvailable($sort_field) {
		foreach($this->_available_fields as $i => $field) {
			if($field == $sort_field) {
				unset($this->_available_fields[$i]);
				break;
			}
		}
	}

	public function buildReport(Order_Report $report) {
		foreach($this->_selected_fields as $i => $field) {
			$report->addField($field);
		}

		if(false == empty($this->_sort_field)) {
			$report->sortBy($this->_sort_field, $this->_sort_direction);
		}

		if(true == isset($this->order_url)) {
			$report->order_url = $this->order_url;
		}

		$report->setFilterList($this->getFilters());

		return $report;
	}
}
?>