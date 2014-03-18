<?php
/**
 * All carts / cart like things should obey this interface.
 */
interface Cart_Interface {
	/**
	 * This should add a new Product to the cart.
	 */
	function addProduct(Product $P, $quantity = 1);
	
	/**
	 * Remove a product from the cart!
	 */
	function removeProduct($product_id);

	/**
	 * Should remove all products from the cart.
	 */
	function emptyCart();
	
	/**
	 * This should return the products in the cart.
	 */
	function getProducts();
	
	/**
	 * This should save the cart somewhere.
	 */
	function save();
	
	/**
	 * Merge another cart into this one and optionally destroy it.
	 */
	function mergeOtherCart(Cart_Interface $C, $destroy_cart = true);
}
?>