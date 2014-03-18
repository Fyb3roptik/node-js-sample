<?php
require_once dirname(__FILE__) . '/Controller.php';
require_once dirname(__FILE__) . '/../classes/Order.php';
require_once dirname(__FILE__) . '/../classes/Sales_Rep.php';

class Checkout_Controller extends Controller {
	public $checkout;
	private $_authnet;

	private $_shipping_hashes = array(
		'FEDEX_GROUND' => 'f1c37023448a729d91760a6d28071199bf39b3b9',
		'FEDEX_EXPRESS_SAVER' => '27113a65933876b19f62244f3b2f24ac4c2508e5',
		'FEDEX_2DAY' => '750a1e13ac0ab489a9ab32ab62a5261c50b021dd',
		'STANDARD_OVR' => '25e62712a8a3cbdbb557b198c661fdca6af711d1',
		'PRIORITY_OVR' => 'acee1d0ea8578c882ae85233e636a43d019e361c'
		);

	public function index() {
		redirect('/checkout/billing/');
	}

	public function buyCart() {
		$this->_configureTemplate();
		$this->checkout = new Checkout();
		$this->_loadCheckoutCustomer();
		$this->_loadCart();
		$this->_applyCoupon(post_var('coupon_code'));
		global $_SESSION;
		$_SESSION['coupon_code'] = post_var('coupon_code');
		$_SESSION['selected_shipping'] = post_var('shipping_code');

		foreach(session_var('misc_charges', array()) as $hash => $misc_data) {
			$MC = new Misc_Charge($misc_data['charge_id']);
			if(true == $MC->exists()) {
				$this->checkout->addMiscCharge($MC, $misc_data['price']);
			}
		}
		
		if(true == $checkout_errors) {
			redirect(LOC_CART);
		}

		$this->_save();
		redirect('/checkout/billing/');
	}

	public function billing() {
		$this->_configureTemplate();
		$V = new View('checkout_billing.php');
		$this->_setView($V);
		$this->_bindMessageStack();
		$this->_bindAddressDumps();
		$this->_template->bind('NAV_FILE', 'modules/nav_checkout_billing.php');
		$this->_setCheckout();
		$this->_loadCheckoutCustomer();
		FB::log($this->checkout, 'Session Checkout');
		$V->bind('CUSTOMER', $this->checkout->getCustomer());
		$V->bind('INVOICE_ALLOWED', $this->_invoiceAllowed());
		$V->bind('CREDIT_LIMIT', $this->_getCreditLimit());
		$V->bind('INVOICE_OPTIONS', $this->_getInvoiceOptions());
		$V->bind('USER', $this->_user);
		$V->bind('shipping', $this->checkout->getShippingAddress()->dump());
		$V->bind('billing', $this->checkout->getBillingAddress()->dump());
		$V->bind('CHECKOUT', $this->checkout);
		$V->bind('PO_NUMBER', $this->checkout->po_number);
		$V->bind('MS', new Message_Stack());
		$V->bind('CC_OPTIONS', $this->_getCreditCards());
		$V->bind('CC_DETAILS', $this->_getCreditCardDetails());
		$V->bind('COUNTRY_LIST', $this->_getCountryList());
		$this->_save();
	}

	public function getShippingEstimate() {
		$code = post_var('code');
		$zip = post_var('zipcode');
		$subtotal = post_var('subtotal');

		$this->_setCheckout();

		$CUSTOMER = $this->checkout->getCustomer();

		$CART = Shopping_Cart::singleton($CUSTOMER);


		$FE = new Freight_Estimator(FEDEX_KEY, FEDEX_PASSWORD, FEDEX_ACCOUNT, FEDEX_METER, FEDEX_API_SOAP);
		foreach($CART->getProducts() as $checkout_product) {
			$P = new Product($checkout_product->getProductId());
			$FE->addProduct($P, $checkout_product->getQuantity(), $P->getPrice($checkout_product->getQuantity()));
		}
		$this->_product_list = $FE->getProducts();
		$BR = new Box_Recommender(new Box_Finder());
		foreach($this->_product_list as $product_data) {
			$line_total = $product_data['qty'] * $product_data['price'];
			if($line_total < $product_data['product']->freight_override_value || false == $product_data['product']->freight_override) {
				$BR->addProduct($product_data['product'], $product_data['qty']);
			}
		}
		$package_list = $BR->recommend();

		$this->_package_list = $package_list;

		$FEDEX = new Fedex_API(FEDEX_KEY, FEDEX_PASSWORD, FEDEX_ACCOUNT, FEDEX_METER, FEDEX_API_SOAP);
		$FEDEX->setShipperZipcodeOnly('75041');
		$FEDEX->setShipToZipcodeOnly($zip);

		foreach($package_list as $package) {
			$FEDEX->addPackage($package);
		}

		$options = $FEDEX->getEstimateResults();

		$return_data = array('shipping_estimate' => 0.00);
		foreach($options as $i => $opt) {
			$original_cost = $opt['cost'];
			$new_cost = $original_cost * (1 + (Config::get()->value('fudge_factor')/100));
			$options[$i]['cost'] = $FE->getMinimums($opt['code'], $new_cost);

			if($opt['code'] == $code) {
				$return_data['shipping_estimate'] = number_format(round(floatval($options[$i]['cost']), 2), 2);
				$total = $subtotal + $return_data['shipping_estimate'];
				$return_data['handling_fee'] = number_format(round(floatval($CUSTOMER->getHandlingFee($total)), 2), 2);
			}
		}

		echo json_encode($return_data);
		exit;
	}

	private function _invoiceAllowed() {
		$allowed = false;
		if(true == ($this->_user instanceof Sales_Rep)
			&& $this->checkout->getCustomer()->credit_limit > 0) {
			$allowed = "all";
		} else {
			$allowed = "limited";
		}
		return $allowed;
	}

	private function _getCreditLimit() {
		$limit = 0;
		if(true == $this->_invoiceAllowed()) {
			$limit = $this->checkout->getCustomer()->credit_limit;
		}
		return $limit;
	}

	private function _getCreditCards() {
		$cards = array(0 => 'New card');
		$user = $this->checkout->getCustomer();
		foreach($user->getCreditCards() as $CC) {
			$cards[$CC->ID] = $CC->nickname;
		}
		return $cards;
	}

	private function _getInvoiceOptions() {
		$options = array();

		$max_sort = 0;
        //THIS NEEDS TO CHANGE AFTER GO LIVE. THIS IS HARDCODED JANKNESS
		if("all" == $this->_invoiceAllowed()) {
			$subquery = SQL::get()
				->select('sort')
				->from('payment_terms')
				->where("payment_term_id = '@syspro_code'")
				->bind('syspro_code', $this->_user->max_payment_term);
        } else {
           $subquery = SQL::get()
				->select('sort')
				->from('payment_terms')
				->where("sort <= '5'")
				->orderBy("sort");
        }
		$query = db_query($subquery);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$max_sort = $rec['sort'];
		}

		$sql = SQL::get()
			->select('payment_term_id, name')
			->from('payment_terms')
			->where("sort <= @max_sort")
			->bind('max_sort', $max_sort)
			->orderBy("sort");
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$options[$rec['payment_term_id']] = $rec['name'];
		}
		return $options;
	}

	private function _getCreditCardDetails() {
		$card_list = array();
		foreach($this->checkout->getCustomer()->getCreditCards() as $CC) {
			$card_data = array();
			$card_data['cc_id'] = $CC->ID;
			$card_data['nickname'] = $CC->nickname;
			$card_data['exp_month'] = $CC->getPlainMonth();
			$card_data['exp_year'] = $CC->getPlainYear();
			$card_data['number'] = obfuscate_cc_number($CC->getPlainNumber());
			$card_data['name'] = $CC->name;
			$card_list[] = $card_data;
		}

		return $card_list;
	}

	private function _getCountryList() {
		$country_list = $this->_getCustomerCountryList();
		if(true == ($this->_user instanceof Sales_Rep)) {
			$country_list = $this->_getSalesCountryList();
		}
		return $country_list;
	}

	private function _getCustomerCountryList() {
		return array('USA' => 'United States');
	}

	private function _getSalesCountryList() {
		$country_list = array();
		$sql = SQL::get()
			->select('syspro_code, name')
			->from('countries')
			->orderBy('name');
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$country_list[$rec['syspro_code']] = $rec['name'];
		}
		return $country_list;
	}

	public function shipping() {
		$this->_configureTemplate();
		$this->_template->bind('NAV_FILE', 'modules/nav_checkout_shipping.php');
		$V = new View('checkout_shipping.php');
		$this->_setView($V);

		try {
			$FE = new Freight_Estimator(FEDEX_KEY, FEDEX_PASSWORD, FEDEX_ACCOUNT, FEDEX_METER, FEDEX_API_SOAP);
			$FE->setShipTo($this->checkout->getShippingAddress());
			$FE->setCustomer($this->checkout->getCustomer());

            foreach($this->checkout->getProducts() as $checkout_product) {
				$FE->addProduct($checkout_product['product'], $checkout_product['quantity'], $checkout_product['price']);
			}

			$this->checkout->loadShippingOptions($FE->getOptions());

			FB::log($FE->getPackageList(), "RECOMMENDED BOXES");
			$OPTION_LIST = $this->checkout->getShippingOptions();
			$V->bind('SHIPPING_HASH', $this->_getShippingHash());
			$V->bind('OPTION_LIST', $OPTION_LIST);
			$V->bind('USER', $this->_user);
			$V->bind('CUSTOMER', $this->checkout->getCustomer());
			$V->bind('HANDLING_FEE', $this->_getHandlingFee());
			$V->bind('SHIPPING_DISCOUNT', $this->_getShippingDiscount());
			$V->bind('SHIP_VIA_LIST', $this->_getShipViaList());

			$this->_save();
		} catch(Exception $e) {
			$MS = new Message_Stack();
			$MS->add('checkout_billing', 'There was a problem connection to the shipping server, please try again.', MS_WARNING);
            
			redirect('/checkout/billing/');
		}
	}

	protected function _getShipViaList() {
		$ship_via_list = array();
		$sql = SQL::get()->select('ship_via_option_id, option_name')
			->from('ship_via_options')
			->orderBy('option_name');
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$ship_via_list[$rec['ship_via_option_id']] = $rec['option_name'];
		}
		return $ship_via_list;
	}

	protected function _getShippingHash() {
		$selected_option = session_var('selected_shipping', null);
		return exists($selected_option, $this->_shipping_hashes, null);
	}

	private function _getHandlingFee() {
		$O = $this->checkout->createOrder();
		return Custom_Fee_Finder::findFee($O->getTotal());
	}

	private function _getShippingDiscount() {
		$this->checkout->createOrder();
		$address = $this->checkout->getShippingAddress()->dump();
		return Custom_Discount_Finder::findDiscount($address['zip_code']);
	}

	public function selectShipping() {
		$this->_configureTemplate();
		$shipping_option = post_var('shipping_option', 'f1c37023448a729d91760a6d28071199bf39b3b9');
        if(false == ($this->_user instanceof Sales_Rep)) {
			$FE = new Freight_Estimator(FEDEX_KEY, FEDEX_PASSWORD, FEDEX_ACCOUNT, FEDEX_METER, FEDEX_API_SOAP);
			$FE->setShipTo($this->checkout->getShippingAddress());
			$FE->setCustomer($this->checkout->getCustomer());

            foreach($this->checkout->getProducts() as $checkout_product) {
				$FE->addProduct($checkout_product['product'], $checkout_product['quantity'], $checkout_product['price']);
			}

			$this->checkout->loadShippingOptions($FE->getOptions());
        }
		list($ship_via_id) = sscanf($shipping_option, 'sv_%d');
		if(intval($ship_via_id) > 0) {
			$SVO = new Ship_Via_Option($ship_via_id);
			if(true == $SVO->exists()) {
				$svo_cost = post_var('ship_via_cost_' . $ship_via_id, $this->_getHandlingFee());
				$SVO->cost = $svo_cost;
				$this->checkout->setShipVia($SVO);
			} else {
				redirect('/checkout/shipping/');
			}
		} elseif('dropship' === $shipping_option && true == ($this->_user instanceof Sales_Rep)) {
			$dropship_price = post_var('dropship_price');
			if(floatval($dropship_price) > 0) {
				$this->checkout->setDropship($dropship_price);
			} else {
				redirect('/checkout/shipping/');
			}
		} else {
			try {
				$this->checkout->setShippingSelection($shipping_option);
			} catch(Exception $e) {
				$CSO = new Customer_Shipping_Option($shipping_option);
				if(true == $CSO->exists() && $CSO->customer_id == $this->checkout->getCustomer()->ID) {
					$this->checkout->setCustomShipping($CSO, $this->_getHandlingFee());
				} else {
					redirect('/checkout/shipping/');
				}
			}
		}
		$this->_save();
		redirect('/checkout/review/');
	}

	public function review() {
		$this->_configureTemplate();
		$this->_template->bind('NAV_FILE', 'nav_checkout_review.php');
		$V = new View('checkout_review.php');
		$this->_setView($V);
		$this->_setCheckout();
		$this->_bindMessageStack();
		$V->bind('CC', $this);
		$V->bind('cc', $this->checkout->getCardInfo());
		$V->bind('USER', $this->_user);
	}

	public function processBilling() {
		FB::group('action::processBilling');
		$this->_configureTemplate();
		$V = new View('checkout_review.php');
		$this->_setView($V);
		$this->_setCheckout();
		$CHECKOUT = $this->checkout;
		$MS = new Message_Stack();
		$shipping_is_billing = abs(intval(exists('billing_is_shipping', $_POST, 0)));
		$billing_address = new Address(post_var('billing', array()));

		$_SESSION['billing_is_shipping'] = $shipping_is_billing;

		if(1 == $shipping_is_billing) {
			$shipping_address = new Address(post_var('billing', array()));
		} else {
			$shipping_address = new Address(post_var('shipping', array()));
		}
		global $_SESSION;
		$_SESSION['ubd_zip'] = $shipping_address->zip_code;
		$CHECKOUT->setShippingAddress($shipping_address);
		$CHECKOUT->setBillingAddress($billing_address);
		$CHECKOUT->po_number = post_var('billing_po_number', null);
		$CHECKOUT->note = post_var('order_notes', null);
		$CHECKOUT->sales_note = post_var('sales_notes', null);
		$CHECKOUT->ship_complete = post_var('ship_complete', $this->_getShipComplete());
		$CHECKOUT->scheduleOrder(post_var('ship_date', null));
		$CHECKOUT->shipping_email = post_var('shipping_email', null);

		$billing_arr = post_var('billing', array());
		$shipping_arr = post_var('shipping', array());

		if($billing_arr['saved_billing_address'] == "new" && $billing_arr['nickname'] != "") {
			$new_address = new Customer_Address();
			$new_address->load($billing_arr);
			$new_address->customer_id = $this->checkout->getCustomer()->ID;
			$new_address->write();
		}

		if($shipping_arr['saved_shipping_address'] == "new" && $shipping_arr['nickname'] != "" && 0 == $shipping_is_billing) {
			$new_address = new Customer_Address();
			$new_address->load($shipping_arr);
			$new_address->customer_id = $this->checkout->getCustomer()->ID;
			$new_address->write();
		}

		$errors = false;
		$avs_errors = false;

		if(false == $CHECKOUT->getShippingAddress()->validate()) {
			$errors = true;
			$MS->add('checkout_billing', 'Error: Invalid shipping address.', MS_ERROR);
		} else {
			$fedex_avs = new Fedex_AVS(FEDEX_KEY, FEDEX_PASSWORD, FEDEX_ACCOUNT, FEDEX_METER, FEDEX_AVS_SOAP);
			$address_validator = new Address_Validator($fedex_avs);
			$validate = $address_validator->validate($CHECKOUT->getShippingAddress());
            
			if($validate[0]['valid'] == false || $validate[0]['correction'] == true) {
				$avs_errors = true;
				FB::log($avs_errors, 'AVS Errors?');
			}
		}

		if(false == $CHECKOUT->getBillingAddress()->validate()) {
			$MS->add('checkout_billing', 'Error: Invalid billing address.', MS_ERROR);
			$errors = true;
		}

		if(0 == count($CHECKOUT->getProducts())) {
			$MS->add('checkout_billing', "Error: You don't have any products in this order...", MS_ERROR);
			$errors = true;
		}
		$this->_save();
		$this->_processPaymentInfo();
		$this->_save();
		$this->_bindMessageStack();

		FB::log($errors, 'Checkout Errors?');
		if(false == $errors && false == $avs_errors) {
			if(true == ($this->_user instanceof Sales_Rep)) {
				redirect('/checkout/shipping/');
			} else {
				FB::log('Redirecting Customer to Review');
				redirect('/checkout/selectShipping/');
			}
			exit;
		} else {
			if(false == $avs_errors) {
				redirect('/checkout/billing/');
				exit;
			} else {
				if(true == ($this->_user instanceof Sales_Rep)) {
					FB::log('Redirectig to /checkout/avs/');
					redirect('/checkout/avs/');
				} else {
					FB::log('Redirecting Customer to Review');
					redirect('/checkout/selectShipping/');
				}
				exit;
			}
		}
		exit;
		FB::groupEnd();
	}

	protected function _getShipComplete() {
		$ship_complete = 1;
		if(true == ($this->_user instanceof Sales_Rep)) {
			$ship_complete = post_var('ship_complete', 0);
		}
		return $ship_complete;
	}

	private function _processPaymentInfo() {
		$payment_term = post_var('payment_type', 'cc');
		if('cc' == $payment_term) {
			$this->_processCreditCard();
		} else {
			if(false == ($this->_user instanceof Sales_Rep)) {
				redirect('/checkout/billing/');
			}
			$this->checkout->payment_term = post_var('invoice_term', null);
		}
	}

	private function _processCreditCard() {
		$selected_cc = abs(intval(post_var('selected_cc', 0)));
		$save_cc = abs(intval(post_var('save_cc', 0)));
		$CC = new Credit_Card($selected_cc);
		$cc_data = post_var('cc', array());

		if(true == $CC->exists()) {
			if($CC->customer_id != $this->checkout->getCustomer()->ID) {
				redirect('/checkout/billing/');
				exit;
			}
            if($CC->name == $cc_data['name'] || $cc_data['name'] == "") {
				$cc_data['name'] = $CC->name;
			}
            if($CC->getPlainNumber() == $cc_data['number'] || $cc_data['number'] == "") {
				$cc_data['number'] = $CC->getPlainNumber();
			}
            if($CC->getPlainMonth() == $cc_data['exp_month']) {
				$cc_data['exp_month'] = $CC->getPlainMonth();
			}
			if($CC->getPlainYear() == $cc_data['exp_year']) {
				$cc_data['exp_year'] = $CC->getPlainYear();
			}
		}

		$this->checkout->setCardInfo($cc_data);
		$cc_data['number'] = trim($cc_data['number']);
		if(true == empty($cc_data['number'])) {
			$MS = new Message_Stack();
			$MS->add('checkout_billing', 'Credit card number required.', MS_WARNING);
			redirect('/checkout/billing/');
		}
		$this->checkout->payment_term = 'cc';

		if(false == $CC->exists() && 1 == $save_cc) {
			$this->_saveCreditCard($cc_data);
		}
	}

	private function _saveCreditCard($cc_data) {
		$CC = new Credit_Card();
		$CC->load($cc_data);
		$CC->customer_id = $this->checkout->getCustomer()->ID;
		$CC->expires_month = $cc_data['exp_month'];
		$CC->expires_year = $cc_data['exp_year'];
		$CC->write();
	}

	public function complete($order_id) {
		$O = new Order($order_id);
		if(false == $this->_validateOrderPermissions($O)) {
			redirect('/');
			exit;
		}
        //This is for the clickserv tracking tag
		$n = 1;
		foreach($O->getProducts() as $i => $P) {
			$product = new Product($P->getProductID());
			$catalog_codes .= "Product ".$n." ".$product->catalog_code."^";
			$product_names .= "Product ".$n." ".$product->name."^";
			$product_quantities .= "Product ".$n." ".$P->getQuantity()."^";
			$product_final_prices .= "Product ".$n." ".$P->getFinalUnitPrice()."^";
			$n++;
		}
		if(session_var('coupon_code') != "") {
			$COUP = new Coupon(session_var('coupon_code'), 'code');
			$O->applyCoupon($COUP);
		}
		$this->_setTemplate(new Template('wide.php'));
		$this->_template->bind('CUSTOMER', $this->_user);
		$this->_template->bind('NAV_FILE', 'nav_checkout_complete.php');
		$V = new View('checkout_complete.php');
		$V->bind('SALES_REP', new Sales_Rep($O->sales_rep_id));
		$V->bind('ORDER_ID', $O->ID);
		$V->bind('BILLING_ADDRESS', $O->billing_address);
		$V->bind('SHIPPING_ADDRESS', $O->shipping_address);
		$V->bind('PRODUCT_LIST', $O->getProducts());
		$V->bind('ORDER_TOTALS', $O->getTotals());
		$V->bind('PAYMENT_INFO', $this->_getPaymentInfo($O));
		$V->bind('USER', $this->_user);
		$V->bind('O', $O);
		$V->bind('catalog_codes', $catalog_codes);
		$V->bind('product_names', $product_names);
		$V->bind('product_quantities', $product_quantities);
		$V->bind('product_final_prices', $product_final_prices);
		$this->_setView($V);

		//START OF MAILER
		/* Setting variables for confirm_order.php */
		$SALES_REP = new Sales_Rep($O->sales_rep_id);
		$ORDER_ID = $O->ID;
		$BILLING_ADDRESS = $O->billing_address;
		$SHIPPING_ADDRESS = $O->shipping_address;
		$PRODUCT_LIST = $O->getProducts();
		$ORDER_TOTALS = $O->getTotals();
		$PAYMENT_INFO = $this->_getPaymentInfo($O);
		$CUSTOMER = new Customer($O->customer_id);

		$M = new Mailer();
		$M->addTo($CUSTOMER->email, $CUSTOMER->name);
		$M->setSubject("KaraokeVibe.com Order Confirmation");
        ob_start();
        include dirname(__FILE__) . '/../views/email/confirm_order.php';
        $body = ob_get_clean();
        $M->setBody($body);
		try {
			$M->send();
		} catch(Exception $e) {
			//Do nothing
		}
        //END Mailer

		global $_SESSION;
		if(true == ($this->_user instanceof Sales_Rep)) {
			unset($_SESSION['sales_customer']);
		}

		if(true == array_key_exists('coupon_code', $_SESSION)) {
			unset($_SESSION['coupon_code']);
		}

		if(true == array_key_exists('saved_checkout', $_SESSION)) {
			$SC = new Saved_Checkout($_SESSION['saved_checkout']);
			if(true == $SC->exists()) {
				$SC->delete();
			}
			unset($_SESSION['saved_checkout']);
		}
	}

	protected function _validateOrderPermissions(Order $order) {
		$allow = true;
		if(false == $order->exists()) {
			$allow = false;
		}

		if(false == ($this->_user instanceof Sales_Rep)) {
			if($order->customer_id != $this->_user->ID) {
				$allow = false;
			}
		}

		return $allow;
	}

	private function _getPaymentInfo(Order $O) {
		$info = null;
		if(strlen($O->cc_name) > 0) {
			$last_four = substr(decrypt($O->cc_number), -4);
			$info[] = $O->cc_name;
			$info[] = "Credit Card Ending: " . $last_four;
			$info[] = decrypt($O->cc_expires_month) . ' / ' . decrypt($O->cc_expires_year);
			$info = nl2br(implode("\n", $info));
		} else {
			$term = new Payment_Term($O->syspro_invoice_code, 'syspro_code');
			$info = 'Invoice: ' . $term->name;
		}
		return $info;
	}

	public function avs() {
		FB::group('action::avs');
		$this->_configureTemplate();
		$this->_setCheckout();

		$fedex_avs = new Fedex_AVS(FEDEX_KEY, FEDEX_PASSWORD, FEDEX_ACCOUNT, FEDEX_METER, FEDEX_AVS_SOAP);

		$address_validator = new Address_Validator($fedex_avs);

		$validate = $address_validator->validate($this->checkout->getShippingAddress());

        if(false == $validate[0]['valid'] || $validate[0]['correction'] == true) {
			FB::log('Show the goram AVS form.');
			$V = new View('checkout_avs_form.php');
			$this->_template->bind('NAV_FILE', 'nav_checkout_shipping.php');
			$V->bind('BAD_ADDRESS', $this->checkout->getShippingAddress());
			$V->bind('OPTION_LIST', $address_validator->getMatches());
			$this->_setView($V);
		} else {
			//forget it.

			redirect('/checkout/shipping/');
		}
		FB::groupEnd();
	}

	public function processAvs() {
		$this->_setCheckout();
		$new_address = new Address(post_var('shipping', array()));

		$validator = new Address_Validator(new Fedex_AVS(FEDEX_KEY, FEDEX_PASSWORD, FEDEX_ACCOUNT, FEDEX_METER, FEDEX_AVS_SOAP));
        $validate = $validator->validate($new_address);
      
		if(true == $validate[0]['valid']) {
			$this->checkout->setShippingAddress($new_address);
			$shipping_is_billing = abs(intval(session_var('shipping_is_billing', 0)));
			if($shipping_is_billing > 0) {
				$this->checkout->setBillingAddress($new_address);
			}
			$this->_save();
			redirect('/checkout/avs/');
		} else {
			redirect('/checkout/billing/');
		}
	}

	public function processOrder() {
		FB::group('action::process_order');
		$MS = new Message_Stack();
		$validation_errors = false;
		$this->_configureTemplate();
		$this->_setCheckout();
		$O = $this->checkout->createOrder();

		$CUSTOMER = $this->_user;

		if(false == ($O instanceof Order) || (intval($O->customer_id) != intval($CUSTOMER->ID) && false == ($CUSTOMER instanceof Sales_Rep))) {
			$this->redirect('/checkout/billing/');
		}

		$O->setDatePurchased(date('Y-m-d H:i:s'));
		$O->setStatus(ORDER_STATUS_PENDING);

		$sales_rep = $CUSTOMER->getSalesRep();

		if(false == $O->shipping_address->validate()) {
			$validation_errors = true;
			$MS->add('checkout_review', 'Error: Invalid shipping address.', MS_ERROR);
			FB::log($O->shipping_address, 'Bad shipping_address');
		}

		if(false == $O->billing_address->validate()) {
			$validation_errors = true;
			$MS->add('checkout_review', 'Error: Invalid billing address.', MS_ERROR);
			FB::log($O->billing_address, 'Bad billing_address');
		}

		if(0 == count($O->getProducts())) {
			$MS->add('checkout_review', "Error: You don't have any products in this order...", MS_ERROR);
			$validation_errors = true;
			FB::log('No products', 'Error');
		}

		if('cc' == $this->checkout->payment_term) {
			$A = $this->_getAuthNet();
			$cc_info = $this->checkout->getCardInfo();
			FB::log($cc_info, 'cc_info');

			$A->setCardNumber($cc_info['number']);
			$A->setExpirationDate($cc_info['exp_month'], $cc_info['exp_year']);
			$A->setCustomerID($O->customer_id);
			$A->setCustomerEmail($this->checkout->getCustomer()->email);
			$A->setCustomerName($cc_info['name']);
			$A->setBillingAddress($O->billing_address);
			$A->setAmount($O->getTotal());

			FB::log($A->getPostValues(), 'Authnet Post Values');
			try {
				$O->sent_to_syspro = 1;
				$O->write();
				$A->setOrderID($O->ID);
				$A->transact();
				$O->sent_to_syspro = 0;
				$O->cc_number = encrypt($cc_info['number']);
				$O->cc_expires_month = encrypt($cc_info['exp_month']);
				$O->cc_expires_year = encrypt($cc_info['exp_year']);
				$O->cc_ccv = encrypt($cc_info['ccv']);
				$O->cc_name = $cc_info['name'];
				$O->cc_trans_id = $A->transaction_id;
				$O->cc_auth_code = $A->authorization_code;
			} catch(Exception $e) {
				$O->delete();
				$MS->add('checkout_review', "Error AUTH: " . $e->getMessage(), MS_ERROR);
				$validation_errors = true;
				FB::log('Authnet Failure: ' . $e->getMessage(), 'Error');
			}
			$O->syspro_invoice_code = '01'; //this shouldn't be hardcoded...
		} else {
			$O->payment_term_id = $this->checkout->payment_term;
			$PT = new Payment_Term($this->checkout->payment_term);
			$O->syspro_invoice_code = $PT->syspro_code;
		}

		FB::log($validation_errors, 'Validation Errors');

		if(false == $validation_errors) {
			//everything checks out so far.
			$O->write();
			$success_loc = '/checkout/complete/' . $O->ID;

			if(true == $O->exists()) {
				global $CART;
				$CART->emptyCart();
			}
			global $_SESSION;
			unset($_SESSION['misc_charges']);
			unset($_SESSION['checkout']);
			redirect($success_loc);
		} else {
			redirect('/checkout/review/');
		}
		FB::groupEnd();
	}

	public function setAuthNet($auth) {
		$this->_authnet = $auth;
	}

	protected function _getAuthNet() {
		if(false == isset($this->_authnet)) {
			$this->_authnet = new Authnet(AUTHNET_API_LOGIN, AUTHNET_TRANS_KEY, AUTHNET_TEST_MODE);
		}
		return $this->_authnet;
	}

	public function purchaseQuote() {
		$ORDER = new Order(post_var('sales_quote_id', 0));
		if(true == ($this->_user instanceof Sales_Rep)) {
			global $_SESSION;
			$_SESSION['sales_customer'] = $ORDER->customer_id;
		}

		if(strtotime($ORDER->quote_expires) < time()) {
			redirect('/myquotes/');
		}

		$this->_configureTemplate();
		$this->checkout = new Checkout();
		$this->_loadCheckoutCustomer();
		$CHECKOUT = $this->checkout;
		$CUSTOMER = $this->checkout->getCustomer();

		if(false == $ORDER->exists() || $ORDER->customer_id != $CUSTOMER->ID || Order::TYPE_QUOTE !== $ORDER->type) {
			redirect(LOC_HOME);
		}

		$CHECKOUT->setCustomer(new Customer($ORDER->customer_id));

		$product_data = post_var('product', array());
		$order_products = $ORDER->getProducts();
		foreach($order_products as $i => $OP) {
			if(true == array_key_exists($OP->getProductID(), $product_data)) {
				$quantity = abs(intval($product_data[$OP->getProductID()]['quantity']));
				$OP->quantity = $quantity;
				$OP->write();
			}
			$CHECKOUT->addProduct(new Product($OP->getProductID()), $OP->quantity, $OP->final_price);
		}
		$this->_save();
		redirect('/checkout/billing/');
		exit;
	}

	private function _applyCoupon($coupon_code) {
		$C = new Coupon($coupon_code, 'code');
		$this->checkout->applyCoupon($C);
	}

	private function _save() {
		if(true == isset($this->checkout)) {
			global $_SESSION;
			$_SESSION['checkout'] = serialize($this->checkout);
		}
	}

	private function _configureTemplate() {
		if(false == $this->_user->exists()) {
			require_login('/checkout/buyCart/');	
		}
		$this->_setTemplate(new Template('wide.php'));
		$this->_template->bind('CUSTOMER', $this->_user);
		$this->_setCheckout();
	}

	private function _loadCart() {
		$cart = Shopping_Cart::singleton($this->_user);
		global $_SESSION;
		$products = $cart->getProducts();
		$UM = new Utility_Modifier(new Utility_Mod_Finder());
		foreach($products as $i => $product) {
			$P = Object_Factory::OF()->newObject('Product', $product->getProductID());
			$UM->modify($P, session_var('ubd_zip'));
			$this->checkout->addProduct($P, $product->getQuantity(), $P->getPrice($product->getQuantity()));
		}	
	}

	private function _setCheckout() {
		if(false == isset($this->checkout)) {
			global $_SESSION;
			$checkout = new Checkout();
			if(true == array_key_exists('checkout', $_SESSION)) {
				$session_checkout = unserialize($_SESSION['checkout']);
				if(true == ($session_checkout instanceof Checkout)) {
					$checkout = $session_checkout;
				}
			}
			$this->checkout = $checkout;
			$this->_loadCheckoutCustomer();
			$this->_save();
		}
	}

	private function _loadCheckoutCustomer() {
		$MS = new Message_Stack();
		if(true == isset($this->checkout)) {
			$checkout_customer = $this->_user;
			if(true == ($this->_user instanceof Sales_Rep)) {
				$this->checkout->sales_rep_id = $this->_user->ID;
				$sales_customer_id = abs(intval(session_var('sales_customer', 0)));
				if(0 == $sales_customer_id) {
					$MS->add('sales', 'Please choose a customer to login as and try again.', MS_WARNING);
					redirect(LOC_SALES . "?action=search_customer");
					exit;
				} else {
					$checkout_customer = new Customer($sales_customer_id);
				}
			}
			$this->checkout->setCustomer($checkout_customer);
			$this->_save();
		}
	}

	private function _bindAddressDumps() {
		$this->_view->bind('ADDRESS_OPTIONS', address_book_options(new Customer($this->checkout->getCustomer()->ID)));
		$this->_view->bind('ADDRESS_DUMP', address_book_dump(new Customer($this->checkout->getCustomer()->ID)));
	}
	
	/**
	 * Helper function to bind the message stack to the current view.
	 */
	private function _bindMessageStack() {
		$this->_view->bind('MS', new Message_Stack());
	}

	public function pickle() {
		$this->_requireSalesRep();
		$cart = Shopping_Cart::singleton($this->_user);
		$SC = new Saved_Checkout();
		$SC->sales_rep_id = $this->_user->ID;
		$SC->customer_id = $_SESSION['sales_customer'];
		$SC->timestamp = date('Y-m-d H:i:s');
		$SC->checkout = serialize($cart);
		$SC->write();
		$cart->emptyCart();
		$cart->save();
		redirect(LOC_SALES);
	}

	public function unpickle($checkout_id = 0) {
		$this->_requireSalesRep();
		$SC = new Saved_Checkout($checkout_id);
		if(false == $SC->exists()) {
			$this->redirect(LOC_SALES);
		}
		$saved_cart = unserialize($SC->checkout);
		$cart = Shopping_Cart::singleton($this->_user);
		$cart->emptyCart();
		$cart->mergeOtherCart($saved_cart);
		global $_SESSION;
		$_SESSION['sales_customer'] = $SC->customer_id;
		$_SESSION['saved_checkout'] = $SC->ID;
		$this->redirect(LOC_CART);
	}

	/**
	 * @ajax
	 */
	public function deletePickle() {
		$this->_requireSalesRep();
		$return = array('success' => false);
		$SC = new Saved_Checkout(post_var('cart_id'));
		if(true == $SC->exists() && $SC->sales_rep_id == $this->_user->ID) {
			$SC->delete();
			$return['success'] = true;
		}
		echo json_encode($return);
		exit;
	}
}
?>