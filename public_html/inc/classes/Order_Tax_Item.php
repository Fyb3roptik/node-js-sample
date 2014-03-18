<?php
require_once 'Order_Line_Item.php';

/**
 * Handles some Tax specific stuff.
 */
class Order_Tax_Item extends Order_Line_Item {
	protected $_default_vals = array('type' => Order_Line_Item::LINE_ITEM_TAX);

	public function __construct($order_id = 0) {
		$order_id = abs(intval($order_id));
		if($order_id > 0) {
			$sql = "SELECT item_id
				  FROM `order_line_items`
				  WHERE order_id = '" . intval($order_id) . "'
					AND type = '" . Order_Line_Item::LINE_ITEM_TAX . "'";
			$query = db_query($sql);
			while($query->num_rows > 0 && $t = $query->fetch_assoc()) {
				$this->_load($t['item_id']);
			}
			if(false == $this->exists()) {
				$this->_loadTable();
			}
			$this->_data['order_id'] = $order_id;
		} else {
			$this->_loadTable();
		}
	}

	/**
	 * Override Order_Line_Item::write() to default some values.
	 */
	public function write() {
		$this->_data['type'] = Order_Line_Item::LINE_ITEM_TAX;
		$this->_data['unit_price'] = abs(floatval($this->_data['unit_price']));
		$this->_data['quantity'] = 1;
		parent::write();
	}
}
?>