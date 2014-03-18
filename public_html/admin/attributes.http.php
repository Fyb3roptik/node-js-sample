<?php
require_once 'inc/global.php';

if(false == xsrf_check()) {
	exit;
}

$action = post_var('action');

switch($action) {
	case 'get_attributes': {
		$return = array('attributes' => array());
		$sql = "SELECT attribute_id, name
			  FROM `attributes`
			  ORDER BY name";
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$return['attributes'][$rec['attribute_id']] = $rec['name'];
		}
		echo json_encode($return);
		break;
	}

	case 'get_attribute_values': {
		$return = array('attribute_values' => array());
		$A = new Attribute(post_var('attribute_id'));
		if(true == $A->exists()) {
			$sql = "SELECT attribute_value_id, value
				  FROM `attribute_values`
				  WHERE attribute_id = '" . intval($A->ID) . "'
				  ORDER BY value";
			$query = db_query($sql);
			while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
				$return['attribute_values'][$rec['attribute_value_id']] = $rec['value'];
			}
		}
		echo json_encode($return);
		break;
	}

	case 'get_attribute_value_details': {
		$return = array('status' => false, 'attribute' => array(), 'pic' => false);
		$AV = new Attribute_Value(post_var('attribute_value_id'));
		if(true == $AV->exists()) {
			$return['status'] = true;
			$return['attribute'] = $AV->dataDump();
			if(false == empty($return['attribute_value']['image'])) {
				$return['pick'] = true;
			}
		}

		echo json_encode($return);
		break;
	}
}
?>