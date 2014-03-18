<?php
/***********************************************************
 * WARNING: This file appears to be a horrid, horrid mess. *
 ***********************************************************/
require_once 'inc/global.php';

if(false == xsrf_check()) {
	exit;
}

/**
 * Returns the JSON encoded array of Foreign_Key_Widgets when given a Foreign_Key_Widget child class and a foreign key.
 */
function get_foreign_key_widgets($widget_class, $foreign_key) {
	$W = new $widget_class();

	if(true == is_a($W, 'Foreign_Key_Widget')) {
		$widget_list = $W->find($W->getForeignKey(), $foreign_key);
		return get_foreign_key_json($widget_list);
	}
}

/**
 * Returns a JSON encoded array when given an array of Foreign_Key_Widgets
 */
function get_foreign_key_json($widget_list) {
	$return_vals = array('widgets' => array());
	foreach($widget_list as $i => $WL) {
		if(true == is_a($WL, 'Foreign_Key_Widget')) {
			$widget_data = $WL->dump();
			$widget_data['ID'] = $WL->ID;
			$WB = new Widget_Builder($WL->widget_id);
			$widget_data['nickname'] = $WB->nickname;
			$widget_data['widget_class'] = $WB->widget_class;
			$return_vals['widgets'][] = $widget_data;
		}
	}
	return json_encode($return_vals);
}

/**
 * Add a new Foreign_Key_Widget of $fk_class with $foreign_key and $widget_id
 */
function add_foreign_key_widget($fk_class, $foreign_key, $widget_id) {
	$return_vals = array('widget_id' => 0);
	$WB = new Widget_Builder($widget_id);
	$foreign_key = abs(intval($foreign_key));

	if(true == $WB->exists() && $foreign_key > 0) {
		$W = new $fk_class();
		if(true == is_a($W, 'Foreign_Key_Widget')) {
			$foreign_key_field = $W->getForeignKey();
			$W->$foreign_key_field = $foreign_key;
			$W->widget_id = $WB->ID;
			$W->sort_order = 10000;
			$W->write();
			$return_vals['widget_id'] = $W->ID;
		}
	}
	echo json_encode($return_vals);
}

$action = post_var('action');
switch($action) {
	case 'delete_widget': {
		$WB = new Widget_Builder(post_var('widget_id'));
		$widget_id = 0;
		if(true == $WB->exists()) {
			$widget_id = $WB->ID;
			$WB->delete();
		}
		echo json_encode(array('widget_id' => $widget_id));
		break;
	}

	case 'drop_fk_widget': {
		$widget_type = post_var('widget_type', null);
		$return_vals = array('widget_id' => 0);
		if(false == is_null($widget_type)) {
			$widget = new $widget_type(post_var('widget_id', 0));
			if(true == is_a($widget, 'Foreign_Key_Widget') && true == $widget->exists()) {
				$widget_id = $widget->ID;
				$widget->delete();
				if(false == $widget->exists()) {
					$return_vals['widget_id'] = intval($widget_id);
				}
			}
		}
		echo json_encode($return_vals);
		break;
	}

	case 'save_sort_order': {
		$widget_type = post_var('widget_type', null);
		$test_widget = new $widget_type();
		if(false == is_null($widget_type) && true == is_a($test_widget, 'Foreign_Key_Widget')) {
			$widget_id_list = post_var('widgets', array());
			$sort_counter = 0;
			$return_vals = array('dump' => print_r($widget_id_list,true));
			foreach($widget_id_list as $i => $widget_id) {
				$PW = new $widget_type($widget_id);
				if(true == $PW->exists()) {
					$PW->sort_order = $sort_counter;
					$sort_counter += 1000;
					$PW->write();
				}
			}
		}
		echo json_encode($return_vals);
		break;
	}

	case 'get_global_widgets': {
		$return_vals = array('widgets' => array());
		$sql = "SELECT *
			  FROM `global_widgets`
			  ORDER BY sort_order";
		$query = db_query($sql);
		$gw_list = array();
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$GW = new Global_Widget($rec['global_widget_id']);
			$gw_list[] = $GW;
		}

		echo get_foreign_key_json($gw_list);
		break;
	}

	case 'get_account_widgets': {
		$return_vals = array('widgets' => array());
		$sql = "SELECT *
			  FROM `account_widgets`
			  ORDER BY sort_order";
		$query = db_query($sql);
		$gw_list = array();
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$GW = new Account_Widget($rec['account_widget_id']);
			$gw_list[] = $GW;
		}

		echo get_foreign_key_json($gw_list);
		break;
	}

	case 'add_account_widget': {
		echo add_foreign_key_widget('Account_Widget', 1, post_var('widget_id'));
		break;
	}

	case 'add_global_widget': {
		echo add_foreign_key_widget('Global_Widget', 1, post_var('widget_id'));
		break;
	}

	case 'get_category_widgets': {
		$return_vals = array('widgets' => array());
		echo get_foreign_key_widgets('Category_Widget', post_var('foreign_key'));
		break;
	}

	case 'add_category_widget': {
		echo add_foreign_key_widget('Category_Widget', post_var('category_id'), post_var('widget_id'));
		break;
	}

	case 'get_product_widgets': {
		echo get_foreign_key_widgets('Product_Page_Widget', post_var('foreign_key'));
		break;
	}

	case 'add_product_widget': {
		echo add_foreign_key_widget('Product_Page_Widget', post_var('product_id'), post_var('widget_id'));
		break;
	}
}
?>