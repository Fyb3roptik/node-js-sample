<?php
require_once 'Object.php';

class Config_Record extends Object {
	protected $_table = 'config';
	protected $_table_id = 'config_id';

	protected $_unsanitized_fields = array('config_text');
}
?>
