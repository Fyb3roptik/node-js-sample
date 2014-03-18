<?php
require_once dirname(__FILE__) . '/Address.php';
require_once dirname(__FILE__) . '/Customer.php';
require_once dirname(__FILE__) . '/Product.php';

class Checkout {
	private $_customer;
	private $_shipping_address;
	private $_billing_address;
	private $_cc_info;
	private $_product_list = array();
	private $_shipping_method;
	private $_coupon;
	private $_scheduled_date;
	private $_misc_charges = array();

	private $_shipping_options = array();
	private $_selected_shipping;

	private $_dropship = false;
	private $_dropship_price = 0;

	public $payment_term;

	public $po_number;
	public $note;
	public $sales_rep_id = null;
	public $sales_note;
	public $ship_complete = 0;
	public $shipping_email = null;

	private $_custom_freight = false;
	private $_custom_account_number;
	private $_handling_fee = 0;

	private $_custom_shipping;
	private $_ship_via;

	public function addMiscCharge(Misc_Charge $charge, $value = 0.00) {
		$this->_misc_charges[] = array(
			'charge' => $charge,
			'price'	=> $value
		);
	}

	public function setShipVia(Ship_Via_Option $SVO) {
		$this->_ship_via = $SVO;
		unset($this->_selected_shipping);
		unset($this->_custom_shipping);
		unset($this->_dropship);
	}

	public function setCustomShipping(Customer_Shipping_Option $CSO, $handling_fee = 0) {
		if(true == $CSO->exists()) {
			$this->_custom_shipping = $CSO;
			$this->_handling_fee = floatval($handling_fee);
		}
	}

	public function setCustomFreight($account_number, $handling_fee = 0) {
		$this->_custom_account_number = trim($account_number);
		$this->_handling_fee = abs(floatval($handling_fee));
		$this->_custom_freight = true;
	}

	public function scheduleOrder($raw_date) {
		$valid_date = false;
		$date = strtotime($raw_date);

		if($date > time()) {
			$this->_scheduled_date = date('Y-m-d', $date);
			$valid_date = true;
		}
		return $valid_date;
	}

	public function getSchedule($format = 'm/d/Y') {
		$date = date($format, (time() + 24 * 2 * 60 * 60));
		if(true == isset($this->_scheduled_date)) {
			$date = date($format, strtotime($this->_scheduled_date));
		}
		return $date;
	}

	public function clearSchedule() {
		unset($this->_scheduled_date);
	}

	public function applyCoupon(Coupon $coupon) {
		if(true == $coupon->exists()) {
			$this->_coupon = $coupon;
		}
	}

	public function setCustomer(Customer $customer) {
		if(true == $customer->exists()) {
			$this->_customer = $customer;
			$this->_setDefaultAddresses();
		} else {
			throw new Exception("Bad customer.");
		}
	}

	public function loadShippingOptions($shipping_options) {
	 
		$this->_shipping_options = array();
		foreach($shipping_options as $opt) {
			$hash = sha1($opt['type'] . '/' . $opt['code']);
			$this->_shipping_options[$hash] = $opt;
		}
	}

	public function getShippingOptions() {
		return $this->_shipping_options;
	}

	public function setShippingSelection($hash) {
		if(true == array_key_exists($hash, $this->_shipping_options)) {
			$this->_selected_shipping = $hash;
		} else {
			throw new Exception("Bad shipping option.");
		}
	}

	public function setDropship($dropship_price) {
		$this->_dropship = true;
		$this->_dropship_price = abs(floatval($dropship_price));
	}

	public function setCardInfo($cc_info) {
		$this->_cc_info = $cc_info;
		$this->payment_term = 'cc';
	}

	public function getCardInfo() {
		return $this->_cc_info;
	}

	private function _setDefaultAddresses() {
		if(true == isset($this->_customer)) {
			if(false == isset($this->_shipping_address) && intval($this->_customer->default_shipping) > 0) {
				$CA = new Customer_Address($this->_customer->default_shipping);
				if(true == $CA->exists()) {
					$address_dump = $CA->dataDump();
					$this->setShippingAddress(new Address($address_dump));
				}
			}

			if(false == isset($this->_billing_address) && intval($this->_customer->default_billing) > 0) {
				$CA = new Customer_Address($this->_customer->default_billing);
				if(true == $CA->exists()) {
					$address_dump = $CA->dataDump();
					$this->setBillingAddress(new Address($address_dump));
				}
			}
		}
	}

	public function getCustomer() {
		return $this->_customer;
	}

	public function setShippingAddress(Address $address) {
		$this->_shipping_address = $address;
		$this->_modify_products();
	}

	private function _modify_products() {
		$UM = new Utility_Modifier(new Utility_Mod_Finder());
		$zip_code = $this->_shipping_address->zip_code;
		foreach($this->_product_list as $product_data) {
			$product = $product_data['product'];
			$UM->modify($product, $zip_code);
		}
	}

	public function setBillingAddress(Address $address) {
		$this->_billing_address = $address;
	}

	public function getShippingAddress() {
		if(false == isset($this->_shipping_address)) {
			$this->_shipping_address = new Address();
		}
		return $this->_shipping_address;
	}

	public function getBillingAddress() {
		if(false == isset($this->_billing_address)) {
			$this->_billing_address = new Address();

		}
		return $this->_billing_address;
	}

	public function addProduct(Product $product, $quantity = 1, $price) {
		$quantity = abs(intval($quantity));
		$price = abs(floatval($price));
		if($product->exists() && $quantity > 0) {
			$this->_product_list[] = array('product' => $product, 'quantity' => $quantity, 'price' => $price);
		}
	}

	public function getProducts() {
		return $this->_product_list;
	}

	public function createOrder() {
		$O = new Order();
		$O->customer_id = $this->_customer->ID;
		$this->_modify_products();
		foreach($this->_product_list as $i => $product_data) {
			$price = $product_data['price'];
			$ubd_code = null;
			if(false == is_null($product_data['product']->getMod())) {
				$price = $product_data['product']->getMod()->price;
				$ubd_code = $product_data['product']->getMod()->program_id;
			}
			$O->addProduct($product_data['product']->ID, $product_data['quantity'], $price, $ubd_code);
		}
		if(true == $this->_dropship) {
			$DSO = new Dropship_Shipping_Option();
			$DSO->setCost($this->_dropship_price);
			$O->setShipping($DSO);
			$O->warehouse = 90;
		} elseif(true == isset($this->_custom_shipping)) {
			$CSO = new Custom_Shipping_Option($this->_custom_shipping->custom_shipping_option_id);
			$CSO->cost = $this->_handling_fee;
			$O->setShipping($CSO);
			$O->freight_account = $this->_custom_shipping->account_number;
		} elseif(true == isset($this->_selected_shipping)) {
			$opt = $this->_shipping_options[$this->_selected_shipping];
			$fedex_opt = new Custom_Fedex_Option($opt['code'], 'fedex_code');
			$fedex_opt->description = $opt['description'];
			$fedex_opt->cost = $opt['cost'];
			$O->setShipping($fedex_opt);
		} elseif(true == isset($this->_ship_via)) {
			$O->setShipping($this->_ship_via);
		}

		foreach($this->_misc_charges as $misc_charge_data) {
			$O->addMiscCharge($misc_charge_data['charge'], $misc_charge_data['price']);
		}

		$O->sales_rep_id = $this->sales_rep_id;
		$O->setShippingAddress($this->getShippingAddress());
		$O->setBillingAddress($this->getBillingAddress());
		$O->po_number = $this->po_number;
		$O->note = $this->note;
		$O->shipping_email = $this->shipping_email;
		if(true == isset($this->_scheduled_date)) {
			$O->ship_date = date("Y-m-d H:i:s", strtotime($this->_scheduled_date));
		}
		$O->sales_note = $this->sales_note;
		$O->ship_complete = intval($this->ship_complete);
		if(true == isset($this->_coupon)) {
			$O->applyCoupon($this->_coupon);
		}
		return $O;
	}
}
?>