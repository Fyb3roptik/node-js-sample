<?php
require_once 'inc/global.php';
if(false == $ADMIN->hasPermission('edit_coupons')) {
	redirect('/admin/denied/');
}

$action = request_var('action');

switch($action) {
	case "new": {
		$C = new Coupon();
		$C->nickname = 'New Coupon - ' . date("m/d/Y");
		$VIEW = 'coupon_form.php';
		break;
	}

	case "edit": {
		$C = new Coupon(get_var('coupon'));
		if(true == $C->exists()) {
			$VIEW = 'coupon_form.php';
		} else {
			redirect(LOC_COUPON);
		}
		break;
	}

	case "process_coupon": {
		$C = new Coupon(post_var('coupon_id'));
		$coupon_data = post_var('coupon', array());
		$coupon_data['discount_type'] = intval($coupon_data['discount_type']);
		$C->load($coupon_data);

		//add trigger products
		$products = array_unique(post_var('products', array()));
		foreach($products as $i => $product_id) {
			$C->addProduct(new Product($product_id));
		}

		//add trigger categories
		$categories = array_unique(post_var('categories', array()));
		foreach($categories as $i => $category_id) {
			$C->addCategory(new Category($category_id));
		}

		$C->write();

		redirect(LOC_COUPONS);
		break;
	}

	default: {
		$COUPON_LIST = array();
		$sql = "SELECT coupon_id
			  FROM `coupons`";
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$COUPON_LIST[] = new Coupon($rec['coupon_id']);
		}

		$VIEW = 'coupon_list.php';
		break;
	}
}

require_once 'layouts/default.php';
?>