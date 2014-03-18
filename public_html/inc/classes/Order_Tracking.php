<?php
require_once dirname(__FILE__) . '/Object.php';

class Order_Tracking extends Object {
	protected $_table = 'order_tracking';
	protected $_table_id = 'order_tracking_id';

	public function getTrackingLink() {
		$format = '<a href="%s" target="_blank">%s</a>';
		$url = 'http://www.fedex.com/Tracking?cntry_code=us&tracknumber_list=%s&language=english';
		return sprintf($format, sprintf($url, $this->tracking_number), $this->tracking_number);
	}
}
?>
