<?php
require_once 'inc/global.php';

if(false == xsrf_check()) {
	exit; //bad request
}

$action = post_var("action");

switch($action) {
	case 'get_coupon_triggers': {
		$return_vals = array('products' => array(), 'cats' => array());
		$C = new Coupon(post_var('coupon_id'));
		if(true == $C->exists()) {
			$sql = "SELECT c.category_id, c.name
				  FROM `categories` c
				  	LEFT JOIN `coupon_categories` cp
				  		ON c.category_id = cp.category_id
				  WHERE coupon_id = '" . intval($C->ID) . "'";
			$query = db_query($sql);
			while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
				$return_vals['cats'][] = array('id' => $rec['category_id'], 'name' => $rec['name']);
			}

			$sql = "SELECT p.product_id, p.name
				  FROM `products` p
				  	LEFT JOIN `coupon_products` cp
				  		ON p.product_id = cp.product_id
				  WHERE coupon_id = '" . intval($C->ID) . "'";
			$query = db_query($sql);
			while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
				$return_vals['products'][] = array('id' => $rec['product_id'], 'name' => $rec['name']);
			}
		}

		echo json_encode($return_vals);
		break;
	}

	case "drop_coupon_product": {
		$return = array('success' => false);
		$C = new Coupon(post_var('coupon_id'));
		if(true == $C->exists()) {
			$sql = "SELECT coupon_product_id
				  FROM `coupon_products`
				  WHERE coupon_id = '" . intval($C->ID) . "'
					AND product_id = '" . intval(post_var('product_id')) . "'";
			$query = db_query($sql);
			while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
				$CP = new Coupon_Product($rec['coupon_product_id']);
				if(true == $CP->exists()) {
					$CP->delete();
					$return['success'] = true;
				}
			}
		}

		echo json_encode($return);
		break;
	}

	case "drop_coupon_category": {
		$return = array('success' => false);
		$C = new Coupon(post_var('coupon_id'));
		if(true == $C->exists()) {
			$sql = "SELECT coupon_category_id
				  FROM `coupon_categories`
				  WHERE coupon_id = '" . intval($C->ID) . "'
					AND category_id = '" . intval(post_var('category_id')) . "'";
			$query = db_query($sql);
			while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
				$CP = new Coupon_Category($rec['coupon_category_id']);
				if(true == $CP->exists()) {
					$CP->delete();
					$return['success'] = true;
				}
			}
		}

		echo json_encode($return);
		break;
	}

	case "drop_coupon": {
		$return = array('success' => false);
		$C = new Coupon(post_var('coupon_id'));
		if(true == $C->exists()) {
			$C->delete();
			if(false == $C->exists()) {
				$return['success'] = true;
			}
		}
		echo json_encode($return);
		break;
	}
}
?>