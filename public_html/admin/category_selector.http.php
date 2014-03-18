<?php
require_once 'inc/global.php';

$action = post_var('action');

if(false == xsrf_check()) { exit; } //die!

switch($action) {
	case 'get_categories': {
		$C = new Category();
		$cat_list = $C->find('parent_id', abs(intval(post_var('parent_id', 0))), 'name');

		$return_vals = array('subcats' => array());

		foreach($cat_list as $i => $cat) {
			$return_vals['subcats'][] = array('id' => $cat->ID, 'name' => $cat->name);
		}

		$sql = "SELECT p.product_id, p.name
			  FROM `products` p
				LEFT JOIN `products_categories` pc
					ON p.product_id = pc.product_id
			  WHERE pc.category_id = '" . abs(intval(post_var('parent_id', 0))) . "'";
		$cat_products = array();
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$cat_products[] = array('id' => $rec['product_id'], 'name' => $rec['name']);
		}
		$return_vals['products'] = $cat_products;

		echo json_encode($return_vals);
		break;
	}
}
?>