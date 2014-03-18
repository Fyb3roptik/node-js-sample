<?php
/**
 * Class for changing an order.
 */
class Order_Change {
	private $_order;
	private $_sales_rep;
	private $_note;
	private $_canceled_list = array();
	private $_new_quantities = array();
	private $_new_prices = array();
	private $_product_list = array();
	private $_canceled_misc = array();

	/**
	 * Constructor'd! Requires an existing Order.
	 * @param $order Order (existing) that we'll be doing our changes on.
	 */
	public function __construct(Order $order) {
		if(false == $order->exists()) {
			throw new Exception("Order must exist.");
		}
		$this->_order = $order;
		$this->_processOrder();
	}

	public function getOrder() {
		return $this->_order;
	}

	public function setNote($note) {
		$this->_note = $note;
	}

	public function getNote() {
		return $this->_note;
	}

	/**
	 * Returns the queue'd cancel list.
	 * @return Array of queued cancels.
	 */
	public function getCancelList() {
		return $this->_canceled_list;
	}

	public function getMiscList() {
		return $this->_canceled_misc;
	}

	/**
	 * Returns the list of new prices.
	 * @return Array of queued price changes.
	 */
	public function getNewPriceList() {
		return $this->_new_prices;
	}

	/**
	 * Returns the list of new quantities.
	 * @return Array of queued quantity changes.
	 */
	public function getNewQuantityList() {
		return $this->_new_quantities;
	}

	private function _processOrder() {
		$order_product_list = $this->_order->getProducts();
		foreach($order_product_list as $OP) {
			$P = new Product($OP->getProductID());
			$stock_code = $P->catalog_code;
			$this->_product_list[$stock_code] = $OP;
			$this->_new_quantities[$stock_code] = $OP->quantity;
			$this->_new_prices[$stock_code] = $OP->getFinalUnitPrice();
		}
	}

	private function _productLookup($catalog_code) {
		return array_key_exists($catalog_code, $this->_product_list);
	}

	/**
	 * Queues up a product for cancellation.
	 * @param $catalog_code Catalog (stock) code of the product to be canceled.
	 * @param $reason Reason code for cancelling this product.
	 */
	public function cancelProduct($catalog_code, $reason) {
		if(false == $this->_productLookup($catalog_code)) {
			throw new Exception("Catalog code is not part of the order.");
		}
		$this->_canceled_list[$catalog_code] = $reason;
		$this->_new_quantities[$catalog_code] = 0;
	}

	public function cancelMisc($oli_id, $reason) {
		$this->_canceled_misc[$oli_id] = $reason;
	}

	/**
	 * Uncancels a previously queued cancellation.
	 *
	 * @param $catalog_code The stock code of the product previously canceled.
	 * @returns True if successfully canceled, false otherwise.
	 */
	public function uncancelProduct($catalog_code) {
		$canceled = false;
		if(true == array_key_exists($catalog_code, $this->_canceled_list)) {
			unset($this->_canceled_list[$catalog_code]);
			$this->_new_quantities[$catalog_code] = $this->_product_list[$catalog_code]->quantity;
			$canceled = true;
		}
		return $canceled;
	}

	public function uncancelMisc($misc_id) {
		$canceled = false;
		if(true == array_key_exists($misc_id, $this->_canceled_misc)) {
			unset($this->_canceled_misc[$misc_id]);
			$canceled = true;
		}
		return $canceled;
	}

	/**
	 * Changes the quantity for a given stock code.
	 *
	 * @param $catalog_code Stock code of the product to be changed.
	 * @param $new_quantity The new quantity for this product.
	 */
	public function changeQuantity($catalog_code, $new_quantity) {
		$quantity = abs(intval($new_quantity));
		if(true == $this->_productLookup($catalog_code) && $quantity > 0) {
			$this->_new_quantities[$catalog_code] = $quantity;
		}
	}

	public function changePrice($catalog_code, $new_price) {
		$new_price = abs(floatval($new_price));
		if(true == $this->_productLookup($catalog_code)) {
			$this->_new_prices[$catalog_code] = $new_price;
		}
	}

	public function setSalesRep(Sales_Rep $rep) {
		$this->_sales_rep = $rep;
	}

	/**
	 * Processes the queued changes agains the original Order and writes the 
	 * changes to the database.
	 */
	public function process() {
		$OCH = $this->_getOrderChangeHistory();
		$order_change_item_list = $this->_getOrderChangeItemList();

		$OCH->write();
		foreach($order_change_item_list as $OCI) {
			$OCI->order_change_id = $OCH->ID;
			$OCI->write();
		}
	}

	private function _getOrderChangeHistory() {
		$OCH = new Order_Change_History();
		$OCH->order_id = $this->_order->ID;
		$OCH->change_type = Order_Change_History::CHANGE;
		$OCH->description = $this->_note;
		$OCH->timestamp = date("Y-m-d H:i:s");
		if(true == isset($this->_sales_rep)) {
			$OCH->sales_rep = $this->_sales_rep->ID;
		}
		return $OCH;
	}

	private function _getMiscCancelList() {
		$order_misc_list = array();
		foreach($this->_canceled_misc as $oli_id => $reason_code) {
			$OLI = new Order_Line_Item($oli_id);
			$OCI = new Order_Change_Item();
			$OCI->change_type = Order_Change_Item::CANCEL_MISC;
			$OCI->value = $reason_code;
			$OCI->stock_code = $OLI->name;
			$order_misc_list[] = $OCI;
			$OLI->delete();
		}
		return $order_misc_list;
	}

	private function _getOrderChangeItemList() {
		$order_change_item_list = array();
		$order_change_item_list = array_merge(
			$order_change_item_list,
			$this->_getCanceledItemList(),
			$this->_getNewQuantityList(),
			$this->_getNewPriceList(),
			$this->_getMiscCancelList()
		);
		return $order_change_item_list;
	}

	private function _getCanceledItemList() {
		$canceled_list = array();
		foreach($this->_canceled_list as $catalog_code => $reason) {
			$this->_product_list[$catalog_code]->quantity = 0;
			$this->_product_list[$catalog_code]->write();
			$OCI = new Order_Change_Item();
			$OCI->change_type = Order_Change_Item::CANCEL;
			$OCI->stock_code = $catalog_code;
			$OCI->value = $reason;
			$canceled_list[] = $OCI;
		}
		return $canceled_list;
	}

	private function _getNewQuantityList() {
		$new_qty_list = array();
		foreach($this->_new_quantities as $catalog_code => $quantity) {
			$original_quantity = $this->_product_list[$catalog_code]->quantity;
			if($original_quantity != $quantity) {
				$this->_product_list[$catalog_code]->quantity = $quantity;
				$this->_product_list[$catalog_code]->write();

				if($quantity > 0) {
					$OCI = new Order_Change_Item();
					$OCI->change_type = Order_Change_Item::CHANGE_QUANTITY;
					$OCI->stock_code = $catalog_code;
					$OCI->value = $quantity;
					$new_qty_list[] = $OCI;
				}
			}
		}
		return $new_qty_list;
	}

	private function _getNewPriceList() {
		$new_price_list = array();
		foreach($this->_new_prices as $catalog_code => $price) {
			$original_price = $this->_product_list[$catalog_code]->getFinalUnitPrice();
			if($original_price != $price) {
				$this->_product_list[$catalog_code]->final_price = $price;
				$this->_product_list[$catalog_code]->write();
				$OCI = new Order_Change_Item();
				$OCI->change_type = Order_Change_Item::CHANGE_PRICE;
				$OCI->stock_code = $catalog_code;
				$OCI->value = $price;
				$new_price_list[] = $OCI;
			}
		}
		return $new_price_list;
	}
}
?>
