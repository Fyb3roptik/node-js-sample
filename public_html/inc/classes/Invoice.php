<?php
require_once dirname(__FILE__) . '/Object.php';
require_once dirname(__FILE__) . '/Invoice_Detail.php';

/**
 * Represents a SysPro invoice.
 */
class Invoice extends Object {
	protected $_table = 'invoices';
	protected $_table_id = 'invoice_id';

	protected $_product_list;
	protected $_misc_list;
	protected $_freight_list;

	public function getProducts() {
		if(false == isset($this->_product_list)) {
			$this->_loadDetails();
		}
		return $this->_product_list;
	}

	public function getMiscCharges() {
		if(false == isset($this->_misc_list)) {
			$this->_loadDetails();
		}
		return $this->_misc_list;
	}

	public function getDetails() {
		return array_merge($this->getProducts(), $this->getMiscCharges());
	}

	private function _loadDetails() {
		$product_list = array();
		$misc_list = array();
		$freight_list = array();

		$sql = SQL::get()->select('invoice_detail_id')
			->from('invoice_detail')
			->where("invoice_id = '@invoice_id'")
			->bind('invoice_id', $this->ID);
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$detail = Object_Factory::OF()->newObject("Invoice_Detail", $rec['invoice_detail_id']);
			if(Invoice_Detail::TYPE_MISC == $detail->product_class) {
				$misc_list[] = $detail;
			} elseif(Invoice_Detail::TYPE_FREIGHT == $detail->product_class) {
				$freight_list[] = $detail;
			} else {
				$product_list[] = $detail;
			}
		}

		$this->_misc_list = $misc_list;
		$this->_product_list = $product_list;
		$this->_freight_list = $freight_list;
	}

	public function getMiscTotal() {
		$misc_total = 0;
		foreach($this->getMiscCharges() as $charge) {
			$misc_total += $charge->getUnitPrice();
		}
		return $misc_total;
	}
}
?>