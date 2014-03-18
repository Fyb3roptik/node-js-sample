<?php
/**
 * This is how products interface with the Coupon class. :]
 */
interface Coupon_Product_Interface {
	/**
	 * Return the primary key for the Product.
	 */
	public function getProductID();

	/**
	 * Return the quantity for this product.
	 */
	public function getQuantity();

	/**
	 * Get the unit price damnzit.
	 */
	public function getUnitPrice();
}
?>
