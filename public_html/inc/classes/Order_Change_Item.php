<?php
require_once 'Object.php';

/**
 * Handles changes to line items in an order.
 */
class Order_Change_Item extends Object {
	protected $_table = 'order_change_item';
	protected $_table_id = 'order_change_item_id';
	protected $_set_hooks = array('change_type' => 'setChangeType');

	/**
	 * Type if the price changes.
	 */
	const CHANGE_PRICE = 'change_price';

	/**
	 * Type if the quantity changes.
	 */
	const CHANGE_QUANTITY = 'change_quantity';

	/**
	 * Type if the line item is canceled.
	 */
	const CANCEL = 'cancel';

	const CANCEL_MISC = 'cancel_misc';

	public function setChangeType($change_type) {
		$good_types = array(self::CHANGE_PRICE, self::CHANGE_QUANTITY, self::CANCEL, self::CANCEL_MISC);
		if(false == in_array($change_type, $good_types)) {
			throw new Exception("Bad change type.");
		}
		return $change_type;
	}
}
?>