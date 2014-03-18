<?php
class Order_Change_History_XML {
	private $_change;

	private $_change_items = array();
	private $_cancel_items = array();
	private $_misc_items = array();

	public function __construct(Order_Change_History $OCH) {
		if(false == $OCH->exists()) {
			throw new Exception("Bad change history.");
		}
		$this->_change = $OCH;
		$this->_processChange();
	}

	private function _processChange() {
		$cancel_items = array();
		$change_items = array();
		$misc_items = array();
		foreach($this->_change->getItems() as $OCI) {
			switch($OCI->change_type) {
				case Order_Change_Item::CANCEL: {
					$cancel_items[] = $OCI;
					break;
				}

				case Order_Change_Item::CHANGE_PRICE: {
					$change_items[$OCI->stock_code]['price'] = floatval($OCI->value);
					break;
				}

				case Order_Change_Item::CHANGE_QUANTITY: {
					$change_items[$OCI->stock_code]['quantity'] = intval($OCI->value);
					break;

				}

				case Order_Change_Item::CANCEL_MISC: {
					$misc_items[] = $OCI;
				}
			}
		}
		$this->_change_items = $change_items;
		$this->_cancel_items = $cancel_items;
		$this->_misc_items = $misc_items;
	}

	public function asXML() {
		switch($this->_change->change_type) {
			case Order_Change_History::CANCEL: {
				$xml = $this->_cancelXML();
				break;
			}

			case Order_Change_History::CHANGE: {
				$xml = $this->_changeXML();
				break;
			}

			default: {
				$xml = null;
				break;
			}
		}

		return $xml;
	}

	private function _cancelXML() {
		$order_cancel = new SimpleXMLElement('<order />');
		$order_cancel->addChild('process_type', 3);
		$order_cancel->addChild('cancel_code', $this->_change->cancel_code);
		$order_cancel->addChild('order_id', $this->_change->order_id);
		return $order_cancel->asXML();
	}

	private function _changeXML() {
		$order = new SimpleXMLElement('<order />');
		$order->addChild('process_type', 2);
		$order->addChild('order_id', $this->_change->order_id);

		$order_detail = $order->addChild('order_detail');
		$this->_addChanges($order_detail);
		$this->_addCancels($order_detail);

		$misc_detail = $order->addChild('misc_detail');
		$this->_addMiscCancels($misc_detail);

		if(strlen($this->_change->cc_trans_id) > 0) {
			$transaction = $order->addChild('transaction');
			$transaction->addChild('approval_code', $this->_change->cc_auth_code);
			$transaction->addChild('transaction_id', $this->_change->cc_trans_id);
		}

		return $order->asXML();
	}

	private function _addChanges(SimpleXMLElement $product_list) {
		if(count($this->_change_items) > 0) {
			foreach($this->_change_items as $catalog_code => $change_data) {
				$product = $product_list->addChild('line_item');
				$product->addChild('stock_code', $catalog_code);
				foreach($change_data as $field => $value) {
					$product->addChild($field, $value);
				}
			}
		}
	}

	private function _addCancels(SimpleXMLElement $product_cancel_list) {
		if(count($this->_cancel_items) > 0) {
			foreach($this->_cancel_items as $OCI) {
				$product_cancel = $product_cancel_list->addChild('line_item');
				$product_cancel->addChild('stock_code', $OCI->stock_code);
				$product_cancel->addChild('cancel_code', $OCI->value);
			}
		}
	}

	private function _addMiscCancels(SimpleXMLElement $misc_list) {
		if(count($this->_misc_items) > 0) {
			foreach($this->_misc_items as $OCI) {
				$misc_cancel = $misc_list->addChild('line_item');
				$misc_cancel->addChild('desc_code', $OCI->stock_code);
				$misc_cancel->addChild('cancel_code', $OCI->value);
			}
		}
	}
}
?>