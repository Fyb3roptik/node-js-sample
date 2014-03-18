<?php
require_once 'Widget.php';
require_once 'Product_Widget.php';

/**
 * Renders a random Product_Widget from the Customer's order history.
 */
class Customer_History_Widget extends Widget {
	private $_customer;

	protected function _load($ID) {
		global $CUSTOMER;
		if(true == $CUSTOMER->exists()) {
			$this->_customer = $CUSTOMER;
		}
	}

	public function render() {
		if(true == isset($this->_customer)) {
			$customer_id = intval($this->_customer->ID);
			if(intval($customer_id) > 0) {
				$sql = "SELECT op.product_id
					  FROM `order_line_items` oli
						LEFT JOIN `orders` o
							ON o.order_id = oli.order_id
						LEFT JOIN `order_products` op
							ON oli.item_id = op.item_id
					  WHERE o.customer_id = '" . $customer_id . "'
						AND oli.type = '" . Order_Line_Item::LINE_ITEM_PRODUCT . "'";
				$results = db_query_random($sql,1);
				if(1 == count($results)) {
					require DIR_ROOT . 'inc/widgets/from_history.php';
					$PW = new Product_Widget($results[0]['product_id']);
					$PW->render();
				}
			}
		}
	}
}
?>