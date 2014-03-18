<?php
require_once 'Cart_Interface.php';
require_once 'Cart_Product.php';

/**
 * Manages the shopping cart for the existing customer.
 */
class Cart implements Cart_Interface {
	private $_customer_id = 0;
	private $_products = array();
	
	public function __construct($customer_id) {
		$this->_customer_id = abs(intval($customer_id));
		$C = new Cart_Product();
		if($this->_customer_id > 0) {
			$this->_products = $C->find('customer_id', $this->_customer_id);
			$this->_scrubProducts();
		}
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
		foreach($this->_products as $i => $CP) {
			$this->removeProduct($CP->getProductID());
		}
	}
	
	public function addProduct(Product $P, $quantity = 1) {
		$updated_product = false;
		foreach($this->_products as $i => $CP) {
			if($CP->getProductID() == $P->getID()) {
				$current_quantity = $CP->getQuantity();
				$new_quantity = $current_quantity + abs(intval($quantity));
				$CP->setQuantity($new_quantity);
				$updated_product = true;
			}
		}
		if(false == $updated_product) {
			$CP = new Cart_Product();
			$CP->setCustomerID($this->_customer_id);
			$CP->setProductID($P->getID());
			$CP->setQuantity(abs(intval($quantity)));
			$this->_products[] = $CP;
			if($this->_customer_id > 0) {
				$CP->write();
			}
		}
	}
	
	public function removeProduct($product_id) {
		$product_id = abs(intval($product_id));
		foreach($this->_products as $i => $P) {
			if($product_id == $P->getProductID()) {
				if(true == $P->exists()) {
					$P->delete();
				}
				unset($this->_products[$i]);
			}
		}
	}
	
	public function getProducts() {
		return $this->_products;
	}
	
	public function save() {
		$new_list = array();
		foreach($this->_products as $i => $P) {
			if(0 == $P->getQuantity()) {
				unset($this->_products[$i]);
				if(true == $P->exists()) {
					$P->delete();
				}
			} else {
				if($this->_customer_id > 0) {
					$P->write();
				}
				$new_list[] = $P;
			}
		}
		$this->_products = $new_list;
	}
	
	public function mergeOtherCart(Cart_Interface $GC, $destroy_cart = true) {
		$guest_products = $GC->getProducts();
		foreach($guest_products as $product) {
			$P = new Product($product->getProductID());
			$this->addProduct($P, $product->getQuantity());
			if(true == $destroy_cart) {
				$GC->removeProduct($product->getProductID());
			}
		}
		$GC->save();
		$this->save();
	}
}
?>
