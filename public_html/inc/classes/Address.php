<?php
/**
 * Handles a standard Address. We basically use this as a formal data structure for Address information.
 */
class Address {
	private $_name;
	private $_company;
	private $_address_1;
	private $_address_2;
	private $_address_3;
	private $_city;
	private $_state;
	private $_zip_code;
	private $_country;
	private $_phone;
	private $_ext;

	/**
	 * Builds the address using a given array of Address data, or optionally a blank array to instantiate an empty Address.
	 *
	 * @param address Array of key/value pairs to fill the Address with.
	 */
	public function __construct($address = array()) {
		if(true == is_array($address)) {
			foreach($address as $key => $value) {
				$address[$key] = trim($value);
			}

			$this->_address_id = $address['address_id'];
			$this->_nickname = $address['nickname'];
			$this->_name = $address['name'];
			$this->_company = $address['company'];
			$this->_address_1 = $address['address_1'];
			$this->_address_2 = $address['address_2'];
			$this->_address_3 = $address['address_3'];
			$this->_city = $address['city'];
			$this->_state = $address['state'];
			$this->_zip_code = $address['zip_code'];
			$this->_country = $address['country'];
			$this->_phone = $address['phone'];
			$this->_ext = $address['ext'];
		}
	}

	/**
	 * Dump the key/value pairs of Address info into an array.
	 *
	 * @param $key_prefix Prefix added to the keys of the returned array.
	 * @return Array of key/value pairs of Address data.
	 */
	public function dump($key_prefix = null) {
		$dump = array();
		foreach($this as $key => $value) {
			if($key == '_country' && 0 == strlen($value)) {
				$value = 'USA';
			}
			if(null == $key_prefix) {
				$key = substr($key, 1);
			}
			$dump[$key_prefix . $key] = stripslashes($value);
		}
		return $dump;
	}

	/**
	 * Determines whether or not the Address is valid.
	 *
	 * @return True if a valid address, False otherwise.
	 */
	public function validate() {
		$valid_address = true;
		if('USA' == $this->_country) {
			if(true == empty($this->_address_1) || true == empty($this->_city) ||
				true == empty($this->_name) || false == $this->_validateState() ||
				true == empty($this->_phone) || true == empty($this->_zip_code)) {

				$valid_address = false;
			}
		}
		return $valid_address;
	}

	/**
	 * Validates the "state" field of the Address.
	 *
	 * @return True if the state exists in the `states` table of the database. False otherwise.
	 */
	private function _validateState() {
		$valid_state = true;
		$states = get_states();
		if(false == array_key_exists($this->_state, $states)) {
			$valid_state = false;
		}
		return $valid_state;
	}

	public function __get($field) {
		$field = '_' . $field;
		$value = null;
		if(true == isset($this->$field)) {
			$value = stripslashes($this->$field);
		}
		return $value;
	}
}
?>