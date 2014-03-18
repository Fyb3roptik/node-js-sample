<?php
require_once dirname(__FILE__) . '/Order_Report_Product_Item.php';

/**
 * Line item class for getting info about an order.
 */
class Order_Report_Item {
	private $_order;
	private $_order_url_format;

	public function __construct(Order $O) {
		$this->_order = $O;
	}

	public function getProductCount() {
		return count($this->_order->getProducts());
	}

	public function getProductLines() {
		$lines = array();
		if(count($this->_order->getProducts()) > 0) {
			foreach($this->_order->getProducts() as $OP) {
				$lines[] = new Order_Report_Product_Item($OP);
			}
		}
		return $lines;
	}

	public function setLinkFormat($format) {
		$this->_order_url_format = $format;
	}

	/**
	 * Magic getter, since not all fields equate equally with an Order
	 * member variable. :]
	 */
	public function __get($field) {
		$function_name = '_get_' . trim($field);
		$value = $this->_order->$field;
		if(true == method_exists($this, $function_name)) {
			$value = $this->$function_name();
		}
		return $value;
	}

	private function _get_total() {
		return price_format($this->_order->getTotal());
	}

	private function _get_subtotal() {
		return price_format($this->_order->getSubtotal());
	}

	private function _get_tax() {
		return price_format($this->_order->getTaxTotal());
	}

	private function _get_shipping_cost() {
		return price_format($this->_order->getShippingTotal());
	}

	private function _get_coupon_code() {
		$code = 'N/A';
		$C = new Coupon($this->_order->coupon_id);
		if(true == $C->exists()) {
			$code = $C->code;
		}
		return $code;
	}

	private function _get_sales_rep() {
		$rep = 'Internet';
		$SR = new Sales_Rep($this->_order->sales_rep_id);
		if(true == $SR->exists()) {
			$rep = $SR->name;
		}
		return $rep;
	}

	private function _get_order_id() {
		$ID = $this->_order->ID;
		$url = $this->_getUrl();
		if(false == is_null($url)) {
			$link = '<a href="%s">%d</a>';
			$ID = sprintf($link, $url, $ID);
		}
		return $ID;
	}

	protected function _getUrl() {
		$url = null;
		if(true == isset($this->_order_url_format)) {
			$url = sprintf($this->_order_url_format, $this->_order->ID);
		}
		return $url;
	}
}
?>
