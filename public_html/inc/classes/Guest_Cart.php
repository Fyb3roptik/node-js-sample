<?php
require_once 'Cart_Interface.php';

/**
 * This is a Guest Cart. It saves an array to the $_SESSION.
 */
class Guest_Cart implements Cart_Interface {
	private $_products = array();

	/**
	 * Builds a Guest_Cart out of the entries in $_SESSION['cart'];
	 */
	public function __construct() {
		global $_SESSION;
		$products = exists('cart', $_SESSION, array());
		foreach($products as $i => $prod) {
			$P = new Product($prod['product_id']);
			if($prod['quantity'] > 0) {
				$this->_products[] = new Guest_Cart_Product($P, $prod['quantity']);
			}
		}
		$this->_scrubProducts();
	}

	private function _scrubProducts() {
		foreach($this->_products as $i => $CP) {
			$P = new Product($CP->getProductID());
			if(false == $P->exists()) {
				unset($this->_products[$i]);
				$CP->delete();
			}
		}
	}

	public function emptyCart() {
		foreach($this->_products as $i => $product) {
			$this->removeProduct($product->getProductID());
		}
	}

	/**
	 * Add a product to the Guest Cart.
	 */
	public function addProduct(Product $P, $quantity = 1) {
		$quantity = abs(intval($quantity));
		$updated_product = false;
		foreach($this->_products as $i => $GP) {
			if($GP->getProductID() == $P->getID()) {
				$new_quantity = $GP->getQuantity() + $quantity;
				$GP->setQuantity($new_quantity);
				$updated_product = true;
				break;
			}
		}
		if(false == $updated_product) {
			$this->_products[] = new Guest_Cart_Product($P, $quantity);
		}
	}

	/**
	 * Removes a product from the Guest Cart.
	 */
	public function removeProduct($product_id) {
		$product_id = abs(intval($product_id));
		foreach($this->_products as $i => $P) {
			if($P->getProductID() == $product_id) {
				unset($this->_products[$i]);
				break;
			}
		}
	}

	/**
	 * Returns the products array.
	 */
	public function getProducts() {
		return $this->_products;
	}

	/**
	 * Save the cart to the session.
	 */
	public function save() {
		global $_SESSION;

		//check for any zero quantity products left in the cart.
		$product_list = array();
		foreach($this->_products as $i => $P) {
			if(0 == $P->getQuantity()) {
				unset($this->_products[$i]);
			} else {
				$product_list[] = array(
								'product_id' => $P->getProductID(),
								'quantity' => $P->getQuantity());
			}
		}

		$_SESSION['cart'] = $product_list;
	}

	/**
	 * Guest carts should never actually get another cart merged into them,
	 * but the Interface requires it, so whatevs.
	 */
	public function mergeOtherCart(Cart_Interface $C, $destroy_cart = true) {
		//STUB
		return null;
	}
}
?>