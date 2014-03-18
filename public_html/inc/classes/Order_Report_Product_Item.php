<?php
/**
 * Line item class for getting info about an individual order product.
 */
class Order_Report_Product_Item {
	private $_order_product;
	private $_product;

	public function __construct(Order_Product $OP) {
		$this->_order_product = $OP;
	}

	public function __get($field) {
		$function_name = '_get_' . trim($field);
		$value = $this->_order_product->$field;
		if(true == method_exists($this, $function_name)) {
			$value = $this->$function_name();
		}
		return $value;
	}

	protected function _get_product_id() {
		return $this->_order_product->getProductID();
	}

	protected function _get_product_image() {
		$P = $this->_getProduct();
		$img_tag = '<img src="%s" />';
		$img_src = $P->getDefaultImage(50);

		return sprintf($img_tag, $img_src);
	}

	protected function _get_product_name() {
		return $this->_order_product->name;
	}

	protected function _get_catalog_code() {
		$P = $this->_getProduct();
		return $P->catalog_code;
	}

	protected function _get_product_quantity() {
		return $this->_order_product->quantity;
	}

	protected function _get_product_price() {
		return price_format($this->_order_product->unit_price);
	}

	protected function _get_product_subtotal() {
		return price_format($this->_order_product->unit_price * $this->_order_product->quantity);
	}

	private function _get_landed_cost() {
		$P = $this->_getProduct();
		return price_format($P->getLandedCost());
	}

	private function _get_gross_profit() {
		$P = $this->_getProduct();
		$cost = $P->getLandedCost();
		$price = $this->_order_product->unit_price;
		$quantity = ($this->_order_product->quantity > 0) ? $this->_order_product->quantity : 1;
		$profit = ($price - $cost) / $quantity;
		return price_format($profit);
	}

	private function _get_margin() {
		$P = $this->_getProduct();
		$cost = $P->getLandedCost();
		$price = $this->_order_product->unit_price;
		$margin = $cost / $price;
		return number_format($margin, 2, '.', '');
	}

	private function _getProduct() {
		if(false == isset($this->_product)) {
			$this->_product = Object_Factory::OF()->newObject('Product', $this->_order_product->getProductID());
		}
		return $this->_product;
	}
}
?>
