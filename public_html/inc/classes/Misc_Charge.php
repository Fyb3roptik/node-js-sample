<?php
require_once dirname(__FILE__) . '/Object.php';

class Misc_Charge extends Object {
	protected $_table = 'misc_charges';
	protected $_table_id = 'misc_charge_id';
	protected $_default_vals = array('active' => 0);
}
?>