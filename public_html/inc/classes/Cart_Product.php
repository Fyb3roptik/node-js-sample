<?php
require_once 'Object.php';
require_once 'Cart_Product_Interface.php';

/**
 * Manages a product in a cart for an existing Customer.
 */
class Cart_Product extends Object implements Cart_Product_Interface {
	protected $_table = 'customer_cart_products';
	protected $_table_id = 'cart_product_id';

	/**
	 * Returns the product ID
	 */
	public function getProductID() {
		return intval(parent::__call('getProductID'));
	}

	public function getID() {
		$P = new Product($this->getProductID());
		return $P->catalog_code;
	}

	/**
	 * Gets the quantity in the cart.
	 */
	public function getQuantity() {
		return intval(parent::__call('getQuantity'));
	}
	
	/**
	 * Sets the quantity.
	 */
	public function setQuantity($quantity) {
		return intval(parent::__call('setQuantity', abs(intval($quantity))));
	}

    public function getNote()
    {
        return parent::__call('getNotes');
    }

	public function getUnitPrice() {
		$P = new Product($this->getProductID());
		$price = $P->getUnitPrice($this->getQuantity());
		return $price;
	}

	public function getFinalPrice() {
		return $this->getQuantity() * $this->getUnitPrice($this->getQuantity());
	}
}
?>