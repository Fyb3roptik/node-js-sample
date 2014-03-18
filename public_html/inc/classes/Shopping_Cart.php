<?php
require_once 'Guest_Cart.php';
require_once 'Cart.php';

/**
 * This is a singleton class, sort of. It will return the correct cart (ideally).
 */
class Shopping_Cart {
	public $cart;
	private static $_instance = null;

	/**
	 * Singleton function that gets the cart for a given Customer.
	 *
	 * @param customer Customer who's cart we're looking for.
	 *
	 * @return Returns an object that implements a Cart_Interface.
	 */
	public function singleton(User $customer) {
		if(false == isset(self::$_instance)) {
			if(true == $customer->exists()) {
				self::$_instance = $customer->getCart();
			} else {
				self::$_instance = new Guest_Cart();
			}
		}
		return self::$_instance;
	}
}
?>