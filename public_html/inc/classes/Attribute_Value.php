<?php
require_once "Object.php";

/**
 * Class for handling values associated with Attributes.
 */
class Attribute_Value extends Object {
	protected $_table = 'attribute_values';
	protected $_table_id = 'attribute_value_id';

	protected $_unsanitized_fields = array('value');
}
?>