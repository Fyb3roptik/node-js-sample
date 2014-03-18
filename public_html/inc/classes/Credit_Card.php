<?php
require_once 'Object.php';

class Credit_Card extends Object {
	protected $_table = 'credit_cards';
	protected $_table_id = 'credit_card_id';

	protected $_unsanitized_fields = array('number', 'expires_month', 'expires_year');

	protected $_set_hooks = array(	'number' => '_encrypt',
					'expires_month' => '_encrypt',
					'expires_year' => '_encrypt');

	protected function _encrypt($value) {
		return encrypt($value);
	}

	public function getPlainNumber() {
		$decrypted_number = null;
		if(strlen($this->number) > 0) {
			$decrypted_number = decrypt($this->number);
		}
		return $decrypted_number;
	}

	public function getPlainMonth() {
		$decrypted_month = null;
		if(strlen($this->expires_month) > 0) {
			$decrypted_month = decrypt($this->expires_month);
		}
		return $decrypted_month;
	}

	public function getPlainYear() {
		$decrypted_year = null;
		if(strlen($this->expires_year) > 0) {
			$decrypted_year = decrypt($this->expires_year);
		}
		return $decrypted_year;
	}
}
?>
