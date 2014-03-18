<?php
require_once 'Object.php';
require_once 'Attribute_Value.php';

/**
 * Class for handling standalone attributes.
 */
class Attribute extends Object {
	protected $_table = 'attributes';
	protected $_table_id = 'attribute_id';

	protected $_unsanitized_fields = array('name');

	protected $_set_hooks = array('display' => 'setDisplay');

	const NORMAL = 0;
	const VAL_ONLY = 1;

	public function getValues() {
		$values = array();
		if(true == $this->exists()) {
			$AV = new Attribute_Value();
			$values = $AV->find('attribute_id', $this->ID, 'value');
		}
		return $values;
	}

	public function setDisplay($display) {
		$good_vals = array(self::NORMAL, self::VAL_ONLY);
		if(false == in_array($display, $good_vals, true)) {
			throw new Exception("Bad display type.");
		}
		return $display;
	}
}
?>