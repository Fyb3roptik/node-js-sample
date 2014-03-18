<?php
require_once dirname(__FILE__) . '/Order_Line_Item.php';
require_once dirname(__FILE__) . '/Order_Product_Record.php';
require_once dirname(__FILE__) . '/Coupon_Product_Interface.php';

/**
 * Describes a Product in an Order.
 */
class Order_Product extends Order_Line_Item implements Coupon_Product_Interface {
	protected $_product_id;

	protected $OPR;
	protected $_ubd_code = null;
	protected $_catalog_code = null;

	protected $_default_vals = array('type' => Order_Line_Item::LINE_ITEM_PRODUCT);

	public function __construct($ID = 0, $field = null) {
		parent::__construct($ID, $field);
		if(true == $this->exists()) {
			$this->_loadOPR();
		}
	}

	public function setCatalogCode($catalog_code) {
		$this->_catalog_code = $catalog_code;
	}

	public function getCatalogCode() {
		return $this->_catalog_code;
	}

	public function setUtilityCode($code = null) {
		$this->_ubd_code = $code;
	}

	public function getUtilityCode() {
		return $this->_ubd_code;
	}

	public function setProductID($product_id) {
		$product_id = abs(intval($product_id));
		if(0 == $this->_product_id && $product_id > 0) {
			$this->_product_id = $product_id;
		}
		return $this->_product_id;
	}

	public function getProductID() {
		return abs(intval($this->_product_id));
	}

	public function getUnitPrice() {
		return $this->unit_price;
	}

	public function getQuantity() {
		return $this->quantity;
	}

	public function getCost() {
		$unit_price = $this->unit_price;
		$quantity = $this->quantity;
		return ($unit_price * $quantity);
	}

	protected function _loadOPR() {
		$this->OPR = new Order_Product_Record($this->ID);
		$this->_product_id = abs(intval($this->OPR->product_id));
		if(true == $this->OPR->exists()) {
			$this->_ubd_code = $this->OPR->ubd_code;
			$this->_catalog_code = $this->OPR->catalog_code;
		}
	}

	public function write() {
		parent::write();
		if(true == $this->exists()) {
			$OPR = new Order_Product_Record($this->ID);
			$OPR->product_id = $this->_product_id;
			$OPR->catalog_code = $this->getCatalogCode();
			$OPR->ubd_code = $this->_ubd_code;
			$OPR->item_id = $this->ID;
			$OPR->write();
		}
	}

	public function getDiscountedPrice() {
		return $this->getFinalUnitPrice();
	}

	public function getTotal() {
		$total = $this->getQuantity() * $this->getFinalUnitPrice();
		return floatval($total);
	}

	public function getNewMargin() {
		$final_price = $this->getFinalUnitPrice();
		$P = new Product($this->_product_id);
		$normal_base = $P->getBaseCost($this->quantity);
		$margin = $normal_base / $final_price;
		return number_format($margin, 2, '.', '');
	}
}
?>