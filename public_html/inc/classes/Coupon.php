<?php
require_once dirname(__FILE__) . '/Object.php';
require_once dirname(__FILE__) . '/Coupon_Category.php';
require_once dirname(__FILE__) . '/Coupon_Product.php';

/**
 * This class handles all things directly related to coupons.
 */
class Coupon extends Object {
	protected $_table = 'coupons';
	protected $_table_id = 'coupon_id';

	/**
	 * List of trigger products.
	 */
	protected $_product_list = array();

	/**
	 * List of trigger categories.
	 */
	protected $_category_list = array();

	protected $_set_hooks = array('discount_type' => '_setDiscountType', 'discount_value' => '_setDiscountValue');

	/**
	 * Coupon applies discount as a percentage.
	 */
	const DISCOUNT_PERCENT = 0;

	/**
	 * Coupon applies discount as flat dollar rate.
	 */
	const DISCOUNT_DOLLAR = 1;

	protected $_default_vals = array('discount_type' => self::DISCOUNT_PERCENT, 'all_products' => 1);

	/**
	 * Override Object::__construct() to set some calculated default values.
	 */
	public function __construct($ID = 0, $key = null) {
		$this->_default_vals['start_date'] = date('Y-m-d'); //default to today.
		$this->_default_vals['end_date'] = date('Y-m-d', time() + (7 * 24 * 60 * 60)); //a week from now.
		parent::__construct($ID, $key);
	}

	/**
	 * Set hook for discount value if it's a percent, we only allow values of 0-100.
	 */
	protected function _setDiscountValue($discount_value) {
		$discount_value = floatval(number_format($discount_value, 2, '.', ''));
		switch($this->discount_type) {
			case self::DISCOUNT_PERCENT: {
				if($discount_value < 0) {
					$discount_value = 0;
				} elseif($discount_value > 100) {
					$discount_value = 100;
				}
				$this->_data['discount_value'] = $discount_value;
				break;
			}

			case self::DISCOUNT_DOLLAR: {
				$discount_value = abs($discount_value);
				$this->_data['discount_value'] = $discount_value;
				break;
			}
		}
		return $this->_data['discount_value'];
	}

	/**
	 * Set hook for discount type requires the discount type to be one of the class constants.
	 */
	protected function _setDiscountType($discount_type) {
		$accepted_types = array(self::DISCOUNT_PERCENT, self::DISCOUNT_DOLLAR);
		if(false == in_array($discount_type, $accepted_types, true)) {
			throw new Exception("Bad Coupon discount type.");
		}
		return $discount_type;
	}

	/**
	 * Add a trigger product.
	 */
	public function addProduct(Product $product) {
		if(false == $product->exists()) {
			throw new Exception("Product must exist.");
		} else {
			$this->_product_list[sha1($product->ID)] = $product;
		}
	}

	/**
	 * Add a trigger category.
	 */
	public function addCategory(Category $category) {
		if(false == $category->exists()) {
			throw new Exception("Category must exist.");
		} else {
			$this->_category_list[sha1($category->ID)] = $category;
		}
	}

	/**
	 * Return an array of unique `product_id`s of products that this coupon is applicable to.
	 */
	public function getProducts() {
		$product_ids = array();
		foreach($this->_product_list as $i => $product) {
			$product_ids[] = $product->ID;
		}

		$category_ids = array();
		foreach($this->_category_list as $i => $category) {
			$category_ids = array_merge($category_ids, $category->allIDs());
		}
		$category_ids = array_unique($category_ids);
		$category_ids = $this->_scrubCategoryList($category_ids);
		if(count($category_ids) > 0) {
			$sql = SQL::get()
				->select('product_id')
				->from('products_categories')
				->where('category_id IN (' . implode(',', $category_ids) . ")");
			$query = db_query($sql);
			while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
				$product_ids[] = $rec['product_id'];
			}
		}
		return array_unique($product_ids);
	}

	private function _scrubCategoryList($category_ids) {
		$good_list = array();
		foreach($category_ids as $ID) {
			if(abs(intval($ID)) > 0) {
				$good_list[] = $ID;
			}
		}

		return $good_list;
	}

	/**
	 * Delete trigger categories.
	 */
	private function _deleteCategories() {
		if(true == $this->exists()) {
			$CC = new Coupon_Category();
			$cc_list = $CC->find('coupon_id', $this->ID);
			foreach($cc_list as $i => $cc) {
				$cc->delete();
			}
		}
	}

	/**
	 * Delete trigger products.
	 */
	private function _deleteProducts() {
		if(true == $this->exists()) {
			$CP = new Coupon_Product();
			$cp_list = $CP->find('coupon_id', $this->ID);
			foreach($cp_list as $i => $cp) {
				$cp->delete();
			}
		}
	}

	/**
	 * Override Object::_load to load up some extra information.
	 */
	protected function _load($id, $key) {
		parent::_load($id, $key);
		if(true == $this->exists()) {
			$this->_loadCategories();
			$this->_loadProducts();
		}
	}

	/**
	 * Load trigger products.
	 */
	protected function _loadProducts() {
		if(true == $this->exists()) {
			$CP = new Coupon_Product();
			$cp_list = $CP->find('coupon_id', $this->ID);
			foreach($cp_list as $i => $cp) {
				$this->_product_list[sha1($cp->product_id)] = Object_Factory::OF()->newObject('Product', $cp->product_id);
			}
		}
	}

	/**
	 * Load trigger categories.
	 */
	protected function _loadCategories() {
		if(true == $this->exists()) {
			$CC = new Coupon_Category();
			$cc_list = $CC->find('coupon_id', $this->ID);
			foreach($cc_list as $i => $cc) {
				$this->_category_list[sha1($cc->category_id)] = Object_Factory::OF()->newObject('Category', $cc->category_id);
			}
		}
	}

	/**
	 * Override Object::write() to write our product/category relationships.
	 */
	public function write() {
		parent::write();
		if(true == $this->exists()) {
			$this->_deleteCategories();
			foreach($this->_category_list as $i => $cat) {
				$CC = new Coupon_Category();
				$CC->coupon_id = $this->ID;
				$CC->category_id = $cat->ID;
				$CC->write();
			}

			$this->_deleteProducts();
			foreach($this->_product_list as $i => $prod) {
				$CP = new Coupon_Product();
				$CP->coupon_id = $this->ID;
				$CP->product_id = $prod->ID;
				$CP->write();
			}
		}
	}

	/**
	 * Override Object::delete() to delete our associated products / categories.
	 */
	public function delete() {
		$this->_deleteCategories();
		$this->_deleteProducts();
		parent::delete();
	}

	/**
	 * Return the start date in a given format.
	 */
	public function startDate($format = 'Y-m-d') {
		return date($format, strtotime($this->start_date));
	}

	/**
	 * Return the end date in a given format.
	 */
	public function endDate($format = 'Y-m-d') {
		$end_date = date('Y-m-d', strtotime($this->end_date));
		$end_time = strtotime($end_date) + (23 * 60 * 60) + (59 * 60) + 59; //add 23:59:59 to the end time.
		return date($format, $end_time);
	}

	/**
	 * Returns true if the date is applicable, and false otherwise.
	 */
	public function dateIsGood() {
		$good_date = false;
		$time_now = time();
		$start_time = strtotime($this->startDate('Y-m-d H:i:s'));
		$end_time = strtotime($this->endDate('Y-m-d H:i:s'));

		if($time_now >= $start_time && $time_now <= $end_time) {
			$good_date = true;
		}

		return $good_date;
	}

	/**
	 * Get this total discount that applies for this product.
	 */
	public function getDiscountForProduct(Coupon_Product_Interface $product) {
		return $this->getUnitDiscount($product) * $product->getQuantity();
	}

	/**
	 * Get the unit discount that applies for a single unit of this product.
	 */
	public function getUnitDiscount(Coupon_Product_Interface $product) {
		$line_discount = 0;
		if(true == $this->_discountAppliesToProduct($product)) {
			$line_discount = $this->discount_value;
			if(self::DISCOUNT_PERCENT == $this->discount_type) {
				$line_discount = ($this->discount_value / 100) * $product->getUnitPrice();
			}
		}
		return $line_discount;
	}

	private function _discountAppliesToProduct(Coupon_Product_Interface $product) {
		$discount_applies = true;
		if(0 == $this->all_products) {
			$discount_applies = in_array($product->getProductID(), $this->getProducts());
		}
		if(false == $this->dateIsGood()) {
			$discount_applies = false;
		}
		return $discount_applies;
	}
}
?>