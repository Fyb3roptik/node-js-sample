<?php
require_once dirname(__FILE__) . '/Object.php';

class Saved_Checkout extends Object {
	protected $_table = 'saved_checkouts';
	protected $_table_id = 'checkout_id';
	protected $_unsanitized_fields = array('checkout');
}
?>