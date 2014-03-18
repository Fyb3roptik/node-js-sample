<?php
require_once 'Object.php';

/**
 * Active Record for Customer Address information.
 */
class Customer_Address extends Object {
	protected $_table = 'customer_addresses';
	protected $_table_id = 'address_id';

	protected $_default_vals = array(
					'country' => 'United States'
				);
}
?>