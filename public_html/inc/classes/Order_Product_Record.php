<?php
require_once 'Object.php';

/**
 * Describes a Product in an Order.
 */
class Order_Product_Record extends Object {
	protected $_table = 'order_products';
	protected $_table_id = 'order_product_id';

	public function __construct($ID = 0, $value = null) {
		parent::__construct($ID, 'item_id');
	}
}
?>