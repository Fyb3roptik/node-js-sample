<?php
require_once dirname(__FILE__) . '/Object.php';
require_once dirname(__FILE__) . '/Order_Line_Item.php';
require_once dirname(__FILE__) . '/State.php';
require_once dirname(__FILE__) . '/Order_Tax_Item.php';

/**
 * Handles all things Order related.
 */
class Order extends Object {
	protected $_table = 'orders';
	protected $_table_id = 'order_id';

	protected $_default_vals = array('type' => self::TYPE_ORDER, 'quote_type' => self::QUOTE_TYPE_LINE_ITEM);
	protected $_unsanitized_fields = array('cc_expires_month', 'cc_expires_year', 'cc_number', 'cc_ccv');

	/**
	 * List of products in this order.
	 */
	private $_product_list = array();

	/**
	 * List of shipping methods associated with this order.
	 */
	private $_shipping_list = array();

	private $_misc_charges = array();

	private $_shipping_description;
	private $_shipping_cost;
	private $_invoice_list;

	/**
	 * Holds the subtotal of products for the order.
	 */
	private $_subtotal = 0;

	/**
	 * Holds the tax total for the order.
	 */
	private $_tax_total = 0;

	/**
	 * Information about any discounts are stored here.
	 */
	private $_discount_item;

	/**
	 * Order total after everything is said and done.
	 */
	private $_total = 0;

	/**
	 * Tax rate for the order (based on shippin address).
	 */
	private $_tax_rate = 0;

	/**
	 * Holds the tax Order_Line_Item
	 */
	private $_tax_item;

	/**
	 * If an Order has a coupon applied to it, it's located here.
	 */
	private $_coupon;
	
	//Customer ID is located here
	private $_customer_id;

	/**
	 * Where are we shipping this order? To this Address!
	 */
	public $shipping_address;

	/**
	 * Who's going to pay for all this stuff? This Address!
	 */
	public $billing_address;

	protected $_set_hooks = array('shipping_method' => 'setShippingMethod',
						'po_number' => 'setPoNumber',
						'note' => 'setNote');
	protected $_get_hooks = array('quote_expires' => 'getExpires');

	const TYPE_ORDER = 'order';
	const TYPE_QUOTE = 'quote';

	/**
	 * Type for a package quote.
	 */
	const QUOTE_TYPE_PACKAGE = 'package';

	/**
	 * Type for a line item quote.
	 */
	const QUOTE_TYPE_LINE_ITEM = 'line_item';

	/**
	 * Status for a new quote.
	 */
	const STATUS_NEW = 'new';

	/**
	 * Status for a saved quote (visible to the customer).
	 */
	const STATUS_SAVED = 'saved';

	/**
	 * Status for a draft quote (saved, but not visible to the customer yet).
	 */
	const STATUS_DRAFT = 'draft';

	const STATUS_PENDING = 'pending';

	/**
	 * "Extra Notes" Setter.
	 *
	 * @param note The note we need to attach to this order.
	 */
	public function setNote($note) {
		return trim(substr(trim($note), 0, 90));
	}

	public function getExpires($format = 'Y-m-d') {
		$expires_time = strtotime($this->_data['quote_expires']);
		return date($format, $expires_time);
	}

	/**
	 * PO Number setter.
	 *
	 * @param po_number The PO Number we want to set for this order.
	 */
	public function setPoNumber($po_number) {
		return trim(substr(trim($po_number),0,30));
	}

	public function setShipping(Shipping_Option_Interface $option) {
		$shipping_item = new Order_Line_Item();
		$shipping_item->type = Order_Line_Item::LINE_ITEM_SHIPPING;
		$shipping_item->name = $option->getDescription();
		$shipping_item->unit_price = $option->getCost();
		$this->syspro_shipping_code = $option->getCode();
		$this->_shipping_list[] = $shipping_item;
		$this->_calculateTotals();
	}

	/**
	 * Loads associated address info into the proper Address objects.
	 */
	private function _loadAddresses() {
		$shipping_address = array();
		$billing_address = array();
		foreach($this->_data as $key => $value) {
			if('shipping_' ==substr($key, 0, strlen('shipping_'))) {
				$new_key = substr($key, strlen('shipping_'), strlen($key));
				$shipping_address[$new_key] = $value;
			} elseif('billing_' ==substr($key, 0, strlen('billing_'))) {
				$new_key = substr($key, strlen('billing_'), strlen($key));
				$billing_address[$new_key] = $value;
			}
		}
		$this->setShippingAddress(new Address($shipping_address));
		$this->setBillingAddress(new Address($billing_address));
	}

	/**
	 * Override of Object::__construct() to do some extra stuff.
	 */
	public function __construct($ID = 0, $field = null) {
		parent::__construct($ID, $field);
		$this->shipping_address = new Address();
		$this->billing_address = new Address();
		if(true == $this->exists()) {
			$this->_loadLineItems();
			$this->_loadAddresses();
		}
		if(true == is_null($this->_data['quote_expires'])) {
			//default the quote_expiration to 30 days in the future.
			$this->_data['quote_expires'] = date('Y-m-d', time() + (24 * 30 * 60 * 60));
		}
	}

	/**
	 * Returns the total discount for this order.
	 */
	public function getDiscountTotal() {
		$discount = 0;
		if(true == isset($this->_coupon)) {
			foreach($this->getProducts() as $product) {
				$line_discount = ($product->getFinalPrice() - $product->getUnitPrice());
				$discount += ($line_discount * $product->getQuantity());
			}
		}
		return $discount;
	}

	public function getOriginalSubtotal() {
		$subtotal = 0;
		if(intval($this->coupon_id) > 0 || true == isset($this->_coupon)) {
			foreach($this->getProducts() as $product) {
				$subtotal += ($product->getUnitPrice() * $product->getQuantity());
			}
		}
		return $subtotal;
	}

	/**
	 * Gets the totals for an order.
	 *
	 * @return Array of "totals" for the order.
	 */
	public function getTotals() {
		$totals = array();

		$discount_total = $this->getDiscountTotal();
		if($discount_total < 0) {
			//we need to kick out the original subtotal, and the discounted total
			$totals[] = array('name' => 'Original Subtotal',
				'value' => $this->getOriginalSubtotal());
			$totals[] = array('name' => $this->_discount_item->name,
				'value' => $discount_total);
		}

		$totals[] = array(
					'name' => 'Subtotal',
					'value' => $this->getSubTotal());

		$tax_total = $this->_tax_total;
		if($tax_total > 0) {
			$totals[] = array(
						'name' => 'Tax',
						'value' => $tax_total);
		}


		foreach($this->_shipping_list as $i => $ship) {
			$totals[] = array(
					'name' => $ship->name,
					'value' => $ship->unit_price
				);
		}

		$totals[] = array('name' => 'Total',
					'value' => $this->getTotal());
		return $totals;
	}

	public function getShippingList() {
		return $this->_shipping_list;
	}

	/**
	 * Loads associated line items for an order.
	 */
	private function _loadLineItems() {
		$this->_loadProducts();
		$this->_loadShippingList();
		$this->_loadTaxItem();
		$this->_loadDiscountItem();
		$this->_loadMiscCharges();
	}

	/**
	 * Load the tax line item.
	 */
	private function _loadTaxItem() {
		$OTI = new Order_Tax_Item($this->ID);
		$this->_tax_total = $OTI->unit_price;
	}

	private function _loadMiscCharges() {
		$this->_misc_charges = array();
		$sql = SQL::get()
			->select('item_id')
			->from('order_line_items')
			->where("order_id = '@order_id'")
			->where("type = '@misc_type'")
			->bind('order_id', $this->ID)
			->bind('misc_type', Order_Line_Item::LINE_ITEM_MISC)
			->orderBy('sort_order', 'name');
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$this->_misc_charges[] = new Order_Line_Item($rec['item_id']);
		}
	}

	/**
	 * Loads the shipping list.
	 */
	private function _loadShippingList() {
		$this->_shipping_list = array();
		$sql = "SELECT item_id
			  FROM `order_line_items`
			  WHERE order_id = '" . intval($this->ID) . "'
			  	AND type = '" . Order_Line_Item::LINE_ITEM_SHIPPING . "'
			  ORDER BY sort_order, name";
		$query = db_query($sql);
		while($query->num_rows > 0 && $ship = $query->fetch_assoc()) {
			$this->_shipping_list[] = new Order_Line_Item($ship['item_id']);
		}
		$this->_calculateTotals();
	}

	/**
	 * Load the products for an order.
	 */
	private function _loadProducts() {
		$sql = "SELECT item_id
			  FROM `order_line_items`
			  WHERE order_id = '" . intval($this->ID) . "'
			  	AND type = '" . Order_Line_Item::LINE_ITEM_PRODUCT . "'
			  ORDER BY sort_order, name";
		$query = db_query($sql);
		$this->_product_list = array();
		while($query->num_rows > 0 && $p = $query->fetch_assoc()) {
			$this->_product_list[] = new Order_Product($p['item_id']);
		}
		$this->_calculateTotals();
	}

	/**
	 * Load any discount information for the order.
	 */
	private function _loadDiscountItem() {
		$sql = "SELECT item_id
			  FROM `order_line_items`
			  WHERE order_id = '" . intval($this->ID) . "'
			  	AND type= '" . Order_Line_Item::LINE_ITEM_DISCOUNT . "'
			  ORDER BY sort_order, name";
		$query = db_query($sql);
		while($query->num_rows > 0 && $d = $query->fetch_assoc()) {
			$this->_discount_item = new Order_Line_Item($d['item_id']);
		}
		$this->_calculateTotals();
	}

	/**
	 * Sets the shipping address.
	 */
	public function setShippingAddress(Address $address) {
		$this->shipping_address = $address;
		$shipping_dump = $this->shipping_address->dump('shipping');
		foreach($shipping_dump as $key => $value) {
			$this->_data[$key] = $value;
		}

		$state = new State($shipping_dump['shipping_state'], 'abbr');
		$this->_configureTaxRate($state);
	}

	private function _configureTaxRate(State $state) {
		$tax_rate = 0;
		if(true == $state->exists()) {
			$tax_rate = floatval($state->sales_tax);
		}
		$C = new Customer($this->customer_id);
		if(true == $C->exists() && intval($C->tax_exempt) > 0) {
			$tax_rate = 0;
		}
		$this->_tax_rate = $tax_rate;

		$this->_calculateTotals();
	}

	/**
	 * Sets the billing address.
	 */
	public function setBillingAddress(Address $address) {
		$this->billing_address = $address;
		$billing_dump = $this->billing_address->dump('billing');
		foreach($billing_dump as $key => $value) {
			$this->_data[$key] = $value;
		}
	}

	/**
	 * Return the array of products.
	 *
	 * @return Array of Order_Products in the Order.
	 */
	public function getProducts() {
		return $this->_product_list;
	}

	/**
	 * Returns the subtotal.
	 *
	 * @return Floatval of the Order's subtotal.
	 */
	public function getSubTotal() {
		$subtotal = 0;
		foreach($this->_product_list as $product) {
			$subtotal += ($product->getFinalUnitPrice() * $product->getQuantity());
		}
		return $subtotal;
	}
	
	//Returns Customer Id of the quote
	public function getCustomerID() {
		return $this->customer_id;
	}
	
	//Returns the Customer Name
	public function getCustomerName($ID) {
		$Customer = new Customer($ID);
		$name = $Customer->name;
		return $name;
	}

	public function getSalesRep() {
		$Sales_Rep = new Sales_Rep($this->sales_rep_id);
		$name = $Sales_Rep->name;
		return $name;
	}

	/**
	 * Returns the total for the calculated tax.
	 *
	 * @return Floatval of the Order's tax total.
	 */
	public function getTaxTotal() {
		return floatval(number_format($this->_tax_total, 2, '.', ''));
	}

	/**
	 * Returns the total.
	 *
	 * @return Returns a float of the Order total.
	 */
	public function getTotal() {
		return floatval($this->_total);
	}

	/**
	 * Calculates the totals for the order.
	 */
	private function _calculateTotals() {
		$this->_subtotal = 0;
		$taxable_total = 0;

		$coupon_products = array();

		if(true == isset($this->_coupon) &&
			true == ($this->_coupon instanceof Coupon) &&
			true == $this->_coupon->dateIsGood()) {
			foreach($this->_product_list as $product) {
				$product->final_price = $product->getUnitPrice() - $this->_coupon->getUnitDiscount($product);
			}
		}

		foreach($this->_product_list as $i => $product) {
			$this->_subtotal += ($product->getFinalUnitPrice() * $product->getQuantity());
			if(1 == intval($product->getTaxable())) {
				$taxable_total += ($product->getFinalUnitPrice() * $product->getQuantity());
			}
		}

		$misc_total = 0;
		foreach($this->_misc_charges as $misc_charge) {
			$misc_total += $misc_charge->unit_price;
		}
		$taxable_total += $misc_total;
		$this->_subtotal += $misc_total;

		$discount_total = $this->getDiscountTotal();
		if($discount_total < 0) {
			$this->_setDiscountItem($this->_coupon->description, $discount_total);
		}

		$shipping_total = 0;
		foreach($this->_shipping_list as $i => $ship) {
			$shipping_total += $ship->unit_price;
		}
		$taxable_total += $shipping_total;

		$this->_tax_total = ($taxable_total * ($this->_tax_rate / 100));
		$this->_total = $this->_subtotal + $this->_tax_total + $shipping_total;
	}

	/**
	 * Returns the total for just the shipping options.
	 */
	public function getShippingTotal() {
		$total = 0;
		foreach($this->_shipping_list as $i => $ship) {
			$total += $ship->unit_price;
		}

		return $total;
	}

	/**
	 * Transparently handles the creation of a discount item to be associated with this order.
	 *
	 * @param description The description of the discount.
	 * @param value The value of the discount.
	 */
	private function _setDiscountItem($description, $value) {
		if(false == isset($this->_discount_item)) {
			$discount = new Order_Line_Item();
			$discount->type = Order_Line_Item::LINE_ITEM_DISCOUNT;
			$discount->quantity = 1;
			$discount->taxable = 1;
			$this->_discount_item = $discount;
		}
		$this->_discount_item->name = $description;
		$this->_discount_item->unit_price = $value;
	}

	/**
	 * Add a product to the order.
	 *
	 * @param product_id ID of the Product we want to add to this order.
	 * @param quantity The number of products we want to add. Defaults to 1.
	 *
	 * @return True if the product was successfully added to the cart, otherwise false.
	 */
	public function addProduct($product_id, $quantity = 1, $final_price = null, $ubd_code = null) {
		$P = Object_Factory::OF()->newObject('Product', abs(intval($product_id)));
		$quantity = abs(intval($quantity));
		$added_product = false;
		if(true == $P->exists() && 1 == $P->active && $quantity > 0) {
			$product_updated = false;
			foreach($this->_product_list as $i => $product) {
				if($P->getID() == $product->getProductID()) {
					$qty = $product->getQuantity() + $quantity;
					$product->setQuantity($qty);
					$product->setUtilityCode($ubd_code);
					$product_updated = true;
					$added_product = true;
				}
			}

			if(false == $product_updated) {
				$OP = new Order_Product();
				$OP->setUtilityCode($ubd_code);
				$OP->setProductID($P->getID());
				$OP->setCatalogCode($P->catalog_code);
				$OP->name = $P->name;
				$OP->quantity = $quantity;
				$OP->unit_price = $P->getUnitPrice($quantity);
				if(false == is_null($final_price)) {
					$OP->final_price = $final_price;
				} else {
					$OP->final_price = $OP->unit_price;
				}
				$OP->taxable = $P->taxable;
				$this->_product_list[] = $OP;
				$added_product = true;
			}
			$this->_calculateTotals();
		}
		return $added_product;
	}

	/**
	 * Removes a product from the order.
	 *
	 * @param product_id ID of the Product we want to remove from the Order.
	 * @return Returns true if the product was removed, false otherwise.
	 */
	public function removeProduct($product_id) {
		$product_id = abs(intval($product_id));
		$product_removed = false;
		if($product_id > 0) {
			foreach($this->_product_list as $i => $product) {
				if($product->getProductID() == $product_id) {
					if(true == $product->exists()) {
						$product->delete();
					}
					unset($product);
					unset($this->_product_list[$i]);
					$this->_calculateTotals();
					$product_removed = true;
				}
			}
		}
		return $product_removed;
	}

	/**
	 * Extends Object::write() to write the order products too!
	 */
	public function write() {
		$customer_id = intval(parent::__call('getCustomerID', null));
		if($customer_id > 0) {
			if(true == isset($this->_coupon)) {
				$this->coupon_id = $this->_coupon->ID;
			}
			if(0 == strlen($this->view_token)) {
				$this->view_token = sha1(microtime() . rand() . $this->_customer_id);
			}
			parent::write();
			if(true == $this->exists()) {
				foreach($this->_product_list as $i => $product) {
					$product->setOrderID($this->ID);
					$product->write();
				}

				foreach($this->_shipping_list as $i => $ship) {
					$ship->setOrderID($this->ID);
					$ship->write();
				}
				$this->_calculateTotals();
				$OTI = new Order_Tax_Item($this->ID);
				$OTI->unit_price = $this->_tax_total;
				$OTI->write();

				$this->_writeMiscCharges();
			}
		} else {
			throw new Exception("No customer has been assigned to this order!");
		}
	}

	/**
	 * Writes the misc charges to the database.
	 */
	private function _writeMiscCharges() {
		foreach($this->_misc_charges as $charge) {
			$charge->order_id = $this->ID;
			$charge->quantity = 1;
			$charge->write();
		}
	}

	/**
	 * Deletes all things associated with this order.
	 */
	public function delete() {
		foreach($this->_product_list as $product) {
			$product->delete();
		}

		foreach($this->_misc_charges as $charge) {
			$charge->delete();
		}

		foreach($this->_shipping_list as $ship) {
			$ship->delete();
		}

		if(true == isset($this->_discount_item)) {
			$this->_discount_item->delete();
		}

		parent::delete();
	}

	public function addMiscCharge(Misc_Charge $charge, $price = 0.00) {
		$OLI = new Order_Line_Item();
		$OLI->type = Order_Line_Item::LINE_ITEM_MISC;
		$OLI->name = $charge->comment_code;
		$OLI->unit_price = $price;
		$OLI->final_price = $price;
		$OLI->quantity = 1;
		$this->_misc_charges[] = $OLI;
		$this->_calculateTotals();
	}

	public function getMiscCharges() {
		return $this->_misc_charges;
	}

	/**
	 * Gets the date purchased.
	 *
	 * @param format Format of the date to be returned. Conforms to PHP's date() function.
	 * @return The date, formatted according to $format.
	 */
	public function getDatePurchased($format = 'Y-m-d H:i:s') {
		$date = strtotime(parent::__call('getDatePurchased'));
		return date($format, $date);
	}

	/**
	 * Applies a Coupon to the order if it exists.
	 *
	 * @param C The coupon to be applied to the Order.
	 */
	public function applyCoupon(Coupon $C) {
		if(true == $C->exists()) {
			$this->_coupon = $C;
			$this->_calculateTotals();
			$this->_coupon_id = $C->ID;
		}
	}

	public function cancel($sales_rep_id, $cancel_code, $reason) {
		if(true == $this->exists()) {
			$OCH = new Order_Change_History();
			$OCH->order_id = $this->ID;
			$OCH->sales_rep = $sales_rep_id;
			$description = "Order Canceled.\n\n" . "Reason:\n" . $reason . "\n\n";
			$description .= "Cancel Code: " . $cancel_code;
			$OCH->description = $description;
			$OCH->cancel_code = $cancel_code;
			$OCH->change_type = Order_Change_History::CANCEL;
			$OCH->write();
			$this->status = 'Cancelled';
			$this->write();
		}
	}

	/**
	 * Returns the change history of this order.
	 */
	public function getHistory() {
		$OCH = new Order_Change_History();
		return $OCH->find('order_id', $this->ID, 'timestamp');
	}

	public function getSysproKey() {
		$order_id = $this->ID;
		if(strlen($this->legacy_key) > 0) {
			$order_id = $this->legacy_key;
		}
		return $order_id;
	}

	public function getTrackingNumbers() {
		$tracking_list = array();
		$OT = new Order_Tracking();
		$order_id = $this->getSysproKey();
		$tracking_list = $OT->find('order_id', $order_id, 'order_tracking_id');
		return $tracking_list;
	}

	public function getInvoices() {
		if(false == isset($this->_invoice_list)) {
			$this->_loadInvoices();
		}
		return $this->_invoice_list;
	}

	private function _loadInvoices() {
		$invoice_list = array();
		$I = new Invoice();
		$invoice_list = $I->find('order_id', $this->getSysproKey());
		$this->_invoice_list = $invoice_list;
	}
}

function quote_url(Order $O) {
	$url = '/quote/edit/' . $O->ID;
	return $url;
}
?>