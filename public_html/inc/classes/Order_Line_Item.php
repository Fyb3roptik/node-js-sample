<?php
require_once dirname(__FILE__) . '/Object.php';

/**
 * Describes order line items (products/tax/discounts/etc)
 */
class Order_Line_Item extends Object {
	protected $_table = 'order_line_items';
	protected $_table_id = 'item_id';

	protected $_set_hooks = array('order_id' => 'setOrderID');
	protected $_get_hooks = array('taxable' => 'getTaxable');

	const LINE_ITEM_PRODUCT = 'product';
	const LINE_ITEM_SHIPPING = 'shipping';
	const LINE_ITEM_TAX = 'tax';
	const LINE_ITEM_DISCOUNT = 'discount';
	const LINE_ITEM_MISC = 'misc';

	public function setOrderID($order_id) {
		if(0 === abs(intval($this->_data['order_id']))) {
			$this->_data['order_id'] = abs(intval($order_id));
		}
		return $this->_data['order_id'];
	}

	public function getTaxable() {
		$taxable = 0;
		$taxable_types = array(self::LINE_ITEM_PRODUCT, self::LINE_ITEM_MISC);
		if(true == in_array($this->type, $taxable_types)) {
			$taxable = 1;
		}
		return $taxable;
	}

	/**
	 * Overwrite Object::write() to require:
	 * 	- order_id (greater than 0)
	 * 	- type (not empty)
	 */
	public function write() {
		if(0 == abs(intval($this->order_id))) {
			throw new Exception('No Order ID!');
		}

		if(true == is_null($this->_data['type']) || "" == $this->_data['type'] || true == empty($this->_data['type'])) {
			throw new Exception("No line item type specified!");
		}
		parent::write();
	}

	public function getFinalUnitPrice() {
		$final_price = $this->unit_price;
		if(false == is_null($this->_data['final_price']) && $this->_data['final_price'] > 0) {
			$final_price = $this->final_price;
		}
		return $final_price;
	}
}
?>