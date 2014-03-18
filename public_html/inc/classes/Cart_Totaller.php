<?php
class Cart_Totaller {
	protected $_cart;
	protected $_total_cache = array();
	protected $_misc_charge = 0;

	public function __construct(Cart_Interface $cart) {
		$this->_cart = $cart;
	}

	public function getTotal() {
		$total = 0;
		$total += $this->_getRealSubtotal();
		$total += exists('shipping', $this->_total_cache, 0);
		$total += exists('handling_fee', $this->_total_cache, 0);
		$total -= exists('coupon_total', $this->_total_cache, 0);
		$total += $this->_misc_charge;
		return $total;
	}

	public function getSubtotal() {
		return $this->_getRealSubtotal() + $this->getMarkdownSubtotal();
	}

	public function getMiscChargeTotal($misc_charge) {
		$this->_misc_charge = $misc_charge;
		return $misc_charge;
	}

	private function _getRealSubtotal() {
		$subtotal = 0;
		foreach($this->_cart->getProducts() as $i => $CP) {
			$subtotal += $CP->getFinalPrice();
		}
		return $subtotal;
	}

	public function getMarkdownSubtotal() {
		$subtotal = 0;
		foreach($this->_cart->getProducts() as $i => $CP) {
			$mod = $CP->getMod();
			if(true == $this->_isMarkDown($mod)) {
				$P = new Product($CP->getProductID());
				$unit_difference = $P->getPrice($CP->getQuantity()) - $mod->price;
				$subtotal += ($unit_difference * $CP->getQuantity());
			}
		}
		return $subtotal;
	}

	protected function _isMarkDown($mod) {
		$markdown = false;
		if(true == is_a($mod, 'Utility_Mod') && Utility_Mod::MARKDOWN == $mod->mod_type) {
			$markdown = true;
		}
		return $markdown;
	}

	public function getShippingTotal($code, $zip, $CUSTOMER) {
		$subtotal = $this->_getRealSubtotal();
		$FE = new Freight_Estimator(FEDEX_KEY, FEDEX_PASSWORD, FEDEX_ACCOUNT, FEDEX_METER, FEDEX_API_SOAP);
		foreach($this->_cart->getProducts() as $checkout_product) {
			$P = new Product($checkout_product->getProductId());
			$FE->addProduct($P, $checkout_product->getQuantity(), $P->getPrice($checkout_product->getQuantity()));
		}

		$product_list = $FE->getProducts();
		$BR = new Box_Recommender(new Box_Finder());
		foreach($product_list as $product_data) {
			$line_total = floatval($product_data['qty'] * $product_data['price']);
			if($line_total < floatval($product_data['product']->freight_override_value) || false == $product_data['product']->freight_override) {
				$BR->addProduct($product_data['product'], $product_data['qty']);
			}
		}
        try {
			$package_list = $BR->recommend();
		} catch(Giant_Order_Exception $e) {
			$quantity_check = true;
		}

		$this->_package_list = $package_list;
		$FEDEX = new Fedex_API(FEDEX_KEY, FEDEX_PASSWORD, FEDEX_ACCOUNT, FEDEX_METER, FEDEX_API_SOAP);
		$FEDEX->setShipperZipcodeOnly('75041');
		$FEDEX->setShipToZipcodeOnly($zip);

		foreach($package_list as $package) {
			$FEDEX->addPackage($package);
		}

		if(false == $quantity_check) {
			try {
				$options = $FEDEX->getEstimateResults($code);
			} catch(Exception $e) {
	        	$options[0]['description'] = "error";
			}

			$return_data = array('shipping_estimate' => 0.00);
			foreach($options as $i => $opt) {
				$original_cost = $opt['cost'];
				$new_cost = $original_cost * (1 + (Config::get()->value('fudge_factor')/100));
				$options[$i]['cost'] = $FE->getMinimums($opt['code'], $new_cost);
				if($opt['code'] == $code) {
					$return_data['shipping_estimate'] = round(floatval($options[$i]['cost']), 2);
					$total = $subtotal + $return_data['shipping_estimate'];
					$return_data['handling_fee'] = number_format(round(floatval($CUSTOMER->getHandlingFee($total)), 2), 2);
				} elseif($opt['description'] == "error") {
					$return_data['shipping_estimate'] = "Order qualifies for freight discount!<br />Please call 1-800-624-4488 for assistance.";
				}
			}

			$this->_total_cache['shipping'] = $return_data['shipping_estimate'];
			$this->_total_cache['handling_fee'] = $return_data['handling_fee'];
		} else {
			$return_data['shipping_estimate'] = "Order qualifies for freight discount!<br />Please call 1-800-624-4488 for assistance.";
		}
		return $return_data['shipping_estimate'];
	}

	public function getHandlingFee() {
		return exists('handling_fee', $this->_total_cache, 0);
	}

	public function getCouponEstimate($coupon_code) {
		$estimate = 0;
		$C = new Coupon($coupon_code, 'code');
		if(true == $C->exists()) {
			foreach($this->_cart->getProducts() as $P) {
				$estimate += $C->getDiscountForProduct($P);
			}
		}
		$this->_total_cache['coupon_total'] = $estimate;
		return $estimate;

	}
}
?>