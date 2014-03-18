<?php
require_once 'Cart_Product_Interface.php';
require_once 'Utility_Modifiable_Product.php';

/**
 * Guest_Cart_Product sits in the Guest_Cart in the session.
 */
class Guest_Cart_Product implements Cart_Product_Interface, Utility_Modifiable_Product {
	private $_product_id;
	private $_quantity;
	private $_mod;

	public function __construct(Product $P, $quantity = 1) {
		if(true == $P->exists() && abs(intval($quantity)) > 0) {
			$this->_product_id = intval($P->getID());
			$this->setQuantity($quantity);
		}
	}

	public function setMod(Utility_Mod $mod) {
		$this->_mod = $mod;
	}

	public function unsetMod() {
		unset($this->_mod);
	}

	public function getID() {
		$P = new Product($this->_product_id);
		return $P->catalog_code;
	}

	public function getProductID() {
		return intval($this->_product_id);
	}

	public function getQuantity() {
		return abs(intval($this->_quantity));
	}

	public function setQuantity($quantity) {
		$this->_quantity = abs(intval($quantity));
	}

	public function getUnitPrice() {
		$unit_price = 0;
		if(false == isset($this->_mod)) {
			$P = new Product($this->getProductID());
			$unit_price = $P->getUnitPrice($this->getQuantity());
		} else {
			$unit_price = $this->_mod->price;
		}

		return $unit_price;
	}

	public function getFinalPrice() {
		return $this->getQuantity() * $this->getUnitPrice($this->getQuantity());
	}

	public function getMod() {
		$mod = null;
		if(true == isset($this->_mod)) {
			$mod = $this->_mod;
		}
		return $mod;
	}

	public function __wakeup() {
		unset($this->_mod);
	}
}
?>