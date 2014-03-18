<?php
class Address_Validator {
	private $_validator;
	private $_matches = array();

	public function __construct(AVS_Interface $validator) {
		$this->_validator = $validator;
	}

	public function validate(Address $address) {
		$this->_matches = $this->_validator->validateAddress($address);
		return $this->_matches;
	}

	public function getMatches() {
		return $this->_matches;
	}
}
?>
