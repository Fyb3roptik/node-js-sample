<?php
require_once dirname(__FILE__) . '/Coupon_Product_Interface.php';

/**
 * Anything representing a "Product" in a "Cart" needs to conform to this
 * basic interface.
 */
interface Cart_Product_Interface extends Coupon_Product_Interface {
	/**
	 * Sets a new quantity.
	 */
	public function setQuantity($quantity);

	/**
	 * Returns the final price (unit * quantity)
	 */
	public function getFinalPrice();
}
?>