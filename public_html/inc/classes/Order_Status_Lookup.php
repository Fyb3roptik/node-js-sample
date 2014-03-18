<?php

/**
 * Class for getting a text/human value for a given syspro order status code.
 */
class Order_Status_Lookup {

	/**
	 * Hash table of status codes => status text.
	 */
	public static $lookup = array(
		'0' => 'Pending',
		'1' => 'Processing',
		'2' => 'Partial Order Shipped',
		'3' => 'Processing',
		'4' => 'Pre-Shipment',
		'8' => 'Shipped',
		'9' => 'Invoiced',
		'S' => 'Suspense',
		'\\' => 'Cancelled'
	);

	/**
	 * Returns the status text for a given status code.
	 */
	public static function lookup($status_code) {
		$status = 'N/A';
		if(true == array_key_exists($status_code, self::$lookup)) {
			$status = self::$lookup[$status_code];
		}
		return $status;
	}
}
?>