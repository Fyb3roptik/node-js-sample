<?php
require_once 'inc/global.php';
$VIEW = 'shopping_cart.php';
$NAV_FILE = 'modules/nav_shopping_cart.php';
$action = exists('action', $_REQUEST, null);
switch(strtolower($action)) {

	case 'import_wishlist': {
		$W = new Wishlist(post_var('wishlist_id', 0));
		if(true == $W->exists()) {
			$CART == Shopping_Cart::singleton($CUSTOMER);
			$CART->mergeOtherCart($W, false);
			$CART->save();
		}
		redirect(LOC_CART);
		break;
	}

	/**
	 * Add a product to the cart.
	 */
	case 'add_product': {
		//only allow if they are logged in and the XSRF check passes OR they're a guest.
		$product_id = post_var('product_id');
		$quantity = abs(intval(post_var('quantity')));
		$P = new Product($product_id);
		if(true == $P->exists() && $quantity > 0) {
			$CART = Shopping_Cart::singleton($CUSTOMER);
			$CART->addProduct($P, $quantity);
			$CART->save();
		} else {
			error_log("Failed to add product to cart - bad product info.");
		}
		echo count(Shopping_Cart::singleton($CUSTOMER)->getProducts());
		exit();
		break;
	}

	case 'add_product_noscript': {
        $product_id = post_var('product_id');
		$quantity = abs(intval(post_var('quantity')));
		$P = new Product($product_id);
		if(true == $P->exists() && $quantity > 0) {
			$CART = Shopping_Cart::singleton($CUSTOMER);
			$CART->addProduct($P, $quantity);
			$CART->save();
		} else {
			error_log("Failed to add product to cart - bad product info.");
		}
		$redirect = post_var('redirect');
        $PREV_PAGE = post_var('redirect_url');
        $return = LOC_CART;
        redirect($return);
	}

	/**
	 * Update the quantities in the cart.
	 */
	case 'update_cart': {
		if((true == $CUSTOMER->exists() && true == $XSRF_CHECK) || false == $CUSTOMER->exists()) {
			$cart = Shopping_Cart::singleton($CUSTOMER);
			$cart_products = $cart->getProducts();
			$cart_products_sorted = array();
			foreach($cart_products as $i => $P) {
				$cart_products_sorted[$P->getProductID()] = $P;
			}

			$updated_products = exists('product_quantity', $_POST);
			foreach($updated_products as $product_id => $quantity) {
				if(true == array_key_exists($product_id, $cart_products_sorted)) {
					$cart_products_sorted[$product_id]->setQuantity($quantity);
				}
			}
			$cart->save();
		}
		break;
	}

	/**
	 * Default. :-/
	 */
	default: {
		$MISC_CHARGE_LIST = Shopping_Cart_Controller::getAvailableMiscCharges();
		$PREV_PAGE = $_SERVER['HTTP_REFERER'];
		break;
	}
}
require_once 'inc/layouts/wide.php';
?>