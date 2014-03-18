<?php
require_once 'Object.php';

/**
 * Class for tracking Order Changes.
 */
class Order_Change_History extends Object {
	protected $_table = 'order_change_history';
	protected $_table_id = 'order_change_id';
	protected $_set_hooks = array('change_type' => 'setChangeType');

	/**
	 * `change_type` associated with a canceled order.
	 */
	const CANCEL = 'cancel';

	/**
	 * `change_type` associated with a partial order change.
	 */
	const CHANGE = 'change';

	/**
	 * Sets the change_type.
	 * @param change_type Must be a valid change type.
	 * @throws Exception when passed an invalid change_type.
	 */
	public function setChangeType($change_type) {
		$good_types = array(self::CANCEL, self::CHANGE);
		if(false == in_array($change_type, $good_types)) {
			throw new Exception("Bad change type for order history.");
		}
		return $change_type;
	}

	public function getItems() {
		$item_list = array();
		if(true == $this->exists()) {
			$OCI = new Order_Change_Item();
			$item_list = $OCI->find('order_change_id', $this->ID);
		}
		return $item_list;
	}
}
?>