<?php
require_once 'Controller.php';

class Shopping_Cart_Controller extends Controller {
	private $cart;

	private function _checkRequest() {
		$customer = $this->_user;
		$good_request = false;
		if((true == $customer->exists() && true == xsrf_check())
			|| false == $customer->exists()) {

			$good_request = true;
		}
		return $good_request;
	}

	public function ajaxUpdateQuantity() {
		$CUSTOMER = $this->_user;

		if(true == $this->_checkRequest()) {
			$cart = Shopping_Cart::singleton($CUSTOMER);
			$cart_products = $cart->getProducts();
			$cart_products_sorted = array();
			foreach($cart_products as $i => $P) {
				$cart_products_sorted[$P->getProductID()] = $P;
			}
			$updated_products = post_var('product_quantity', array());
			$removed_products = post_var('remove_product', array());

			if(false == empty($removed_products)) {
				foreach($removed_products as $product_id) {
					$cart->removeProduct($product_id);
					if(true == isset($updated_products[$product_id])) {
						unset($updated_products[$product_id]);
					}
				}
			}

			if(false == empty($updated_products)) {
				foreach($updated_products as $product_id => $quantity) {
					if(intval($quantity) < 1) {
						$quantity = 1;
					}
					if(true == array_key_exists($product_id, $cart_products_sorted) && intval($quantity) > 0) {
						$cart_products_sorted[$product_id]->setQuantity($quantity);
					}
				}
			}

			$UM = new Utility_Modifier(new Utility_Mod_Finder());
			foreach($cart->getProducts() as $i => $product) {
				$UM->modify($product, session_var('ubd_zip'));
			}

			$cart->save();
			$products = array();
			foreach($cart->getProducts() as $i => $product) {
				$product_data = array();
				$product_data['product_id'] = $product->getProductID();
				$product_data['quantity'] = $product->getQuantity();
				$P = new Product($product->getProductID());
				$UM->modify($P, session_var('ubd_zip'));
				$product_data['unit_price'] = price_format($P->getUnitPrice($product_data['quantity']));
				$product_data['total_price'] = price_format($P->getUnitPrice($product_data['quantity']) * $product_data['quantity']);
				$products[] = $product_data;
			}

			$_SESSION['coupon_code'] = post_var('coupon_code');

			$misc_charge = new Misc_Charge(post_var('misc_charge_type'));
			if(true == $misc_charge->exists()) {
				$this->_addMiscCharge($misc_charge);
			}

			$removed_misc_charges = post_var('remove_misc', array());
			foreach($removed_misc_charges as $hash) {
				if(true == array_key_exists($hash, session_var('misc_charges', array()))) {
					unset($_SESSION['misc_charges'][$hash]);
				}
			}
		}

		$this->_setTemplate(new Template('ajax.php'));
		$V = new Html_Template(dirname(__FILE__) . '/../modules/shopping_cart_table.php');
		$V->bind('CUSTOMER', $CUSTOMER);
		$V->bind('PREV_PAGE', htmlentities($_SERVER['HTTP_REFERER']));
		$ZIP = post_var('zipcode');
		if(false == is_numeric($ZIP) || empty($products)) {
			$ZIP = null;
		}
		$V->bind('ZIP', $ZIP);
		$V->bind('CODE', post_var('code'));
		$V->bind('COUPON_CODE', post_var('coupon_code'));
		$V->bind('MISC_CHARGE_LIST', $this->getAvailableMiscCharges());
		$this->_setView($V);
	}

	private function _getCouponEstimate() {
		$estimate = 0;
		$coupon_code = (session_var('coupon_code') != "") ? session_var('coupon_code') : post_var('coupon_code');
		$C = new Coupon($coupon_code, 'code');
		if(true == $C->exists()) {
			$cart = Shopping_Cart::singleton($this->_user);
			$coupon_products = $C->getProducts();
			foreach($cart->getProducts() as $P) {
				if(true == in_array($P->getProductID(), $coupon_products)) {
					$discount_per_product = $C->discount_value;
					if(Coupon::DISCOUNT_PERCENT == $C->discount_type) {
						$discount_per_product = $P->getUnitPrice() * ($discount_per_product / 100);
					}
					$estimate += ($discount_per_product * $P->getQuantity());
				}
			}
		}
		return $estimate;
	}

	private function _addMiscCharge(Misc_Charge $charge) {
		global $_SESSION;
		$charge_amount = abs(floatval(post_var('misc_value', 0)));
		$hash = sha1(implode('/', array(microtime(), $charge->ID, $charge_amount)));
		$_SESSION['misc_charges'][$hash] = array(
			'charge_id' => $charge->ID,
			'desc' => $charge->description,
			'price' => $charge_amount);
	}

	public function addToCart() {
		$return_vals = array('success' => false);
		$product_list = post_var('product_list', array());
		$CART = Shopping_Cart::singleton($this->_user);
		foreach($product_list as $product_id) {
			$P = new Product($product_id);
			if(true == $P->exists()) {
				$CART->addProduct($P, 1);
				$return_vals['products'][] = $P->ID;
			}
		}
		$CART->save();
		$return_vals['success'] = true;
		echo json_encode($return_vals);
		exit;
	}

	public static function getAvailableMiscCharges() {
		$misc_charges = array(0 => '-Select Charge-');
		/*$sql = SQL::get()
			->select('misc_charge_id, description')
			->from('misc_charges')
			->where("active = '1'")
			->orderBy('description');
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$misc_charges[$rec['misc_charge_id']] = $rec['description'];
		}
		return $misc_charges;
		*/
	}
}
?>