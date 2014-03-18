<?php
class Data_Object {
	/**
	 * Unique ID for this object generally the primary key from the database.
	 */
	protected $_ID;

	/**
	 * Array were we store our data from the DB.
	 */
	protected $_data = array();

	/**
	 * Default values for Object::$_data.
	 */
	protected $_default_vals = array();

	/**
	 * Fields that won't be sanitized. (Put fields that contain HTML in here.)
	 */
	protected $_unsanitized_fields = array();

	/**
	 * Hooks to run when setting a value.
	 */
	protected $_set_hooks = array();

	/**
	 * Hooks to run when getting a value.
	 */
	protected $_get_hooks = array();

	/**
	 * Validators to run when setting a value.
	 */
	protected $_validators = array();

	/**
	 * Unique hash for the instance of this Object with this ID.
	 */
	public $hash;

	/**
	 * Override the set method.
	 *
	 * @param name Name of the field to set.
	 * @param value New value..
	 * @return Returns true if the value was successfully set, false otherwise.
	 */
	public function __set($name, $value) {
		$value_set = false;
		if(true == array_key_exists($name, $this->_data)) {
			if(true == $this->_runValidators($name, $value)) {
				if(false == in_array($name, $this->_unsanitized_fields)) {
					$value = sanitize_string($value);
				}
				$value = $this->_runSetHooks($name, $value);
				$this->_data[$name] = $value;
				$value_set = true;
			}
		}
		return $value_set;
	}

	/**
	 * Runs validator functions defined in $this->_validator and returns true if they pass,
	 * false if any one of them fails.
	 *
	 * @param name Name of the field to run validators against.
	 * @param value Value to check if valid.
	 * @return Returns true if $value is a valid value for key $name, otherwise false.
	 */
	private function _runValidators($name, $value) {
		$valid = true;
		if(true == array_key_exists($name, $this->_validators)) {
			if(true == is_array($this->_validators[$name])) {
				foreach($this->_validators[$name] as $validator) {
					if(false == $this->$validator($value)) {
						$valid = false;
					}
				}
			} else {
				$validator = $this->_validators[$name];
				$valid = $this->$validator($value);
			}
		}
		return $valid;
	}

	/**
	 * Returns the value of after processing through any hooks from the
	 * $_set_hooks array.
	 *
	 * @param name Name of the field to run the hooks for.
	 * @param value New value that we'd like to set name to.
	 * @return Returns the potentially modified value after the set hooks have been run.
	 */
	private function _runSetHooks($name, $value) {
		return $this->_runHooks($name, $value, $this->_set_hooks);
	}

	/**
	 * Returns the value of after processing through any hooks from the
	 * $_get_hooks array.
	 *
	 * @param name Name of key to get the "get hooked" value from.
	 * @return Returns the value of $name after it's been run through all appropriate "get hooks".
	 */
	private function _runGetHooks($name) {
		$value = $this->_data[$name];
		return $this->_runHooks($name, $value, $this->_get_hooks);
	}

	/**
	 * Runs any hook found in $hook_array for field $name on $value
	 * @param name Name of the data field.
	 * @param value Value of the field for hook purposes.
	 * @param hook_array Array of hooks to run against the $name/$value pair.
	 * @return Returns the value of $name after it's been run through the hooks in $hook_array.
	 */
	private function _runHooks($name, $value, $hook_array) {
		$value = $value;
		if(true == array_key_exists($name, $hook_array)) {
			if(true == is_array($hook_array[$name])) {
				foreach($hook_array[$name] as $hook) {
					$value = $this->$hook($value);
				}
			} else {
				$hook = $hook_array[$name];
				$value = $this->$hook($value);
			}
		}
		return $value;
	}

	/**
	 * Override the get method.
	 *
	 * @param name Name of the field to return.
	 * @return Value of field with name `name`
	 */
	public function __get($name) {
		$value = null;
		if(true == array_key_exists($name, $this->_data)) {
			$value = $this->_runGetHooks($name, $this->_data[$name]);
			if(false == in_array($name, $this->_unsanitized_fields)) {
				$value = sanitize_string($value);
			}
		} elseif('ID' == $name) {
			$value = intval($this->_ID);
		}
		return $value;
	}

	/**
	 * Returns the unique ID of this object.
	 *
	 * @return Returns the unique ID for this Object.
	 */
	public function getID() {
		return $this->ID;
	}

	/**
	 * Returns true if the object "exists" (has an ID > 0).
	 *
	 * @return Returns true if the Object exists (in the database), false otherwise.
	 */
	public function exists() {
		$exists = false;
		if(intval($this->_ID) > 0) {
			$exists = true;
		}
		return $exists;
	}

	/**
	 * Dumps the data array.
	 *
	 * @return Array of key => value pairs of the data for this Object.
	 */
	public function dataDump() {
		$data = array();
		$data[$this->_table_id] = intval($this->_ID);
		foreach($this->_data as $key => $value) {
			$data[$key] = stripslashes($value);
			if(false == in_array($key, $this->_unsanitized_fields)) {
				$data[$key] == sanitize_string(stripslashes($value));
			}
		}
		return $data;
	}

	/**
	 * Returns all the data keys. Great for determining table structure.
	 *
	 * @return Returns an array of data keys.
	 */
	public function listKeys() {
		$keys = array();
		foreach($this->_data as $key => $value) {
			$keys[] = $key;
		}
		return $keys;
	}

	/**
	 * Loads the protected $_data array with the values from $new_data
	 *
	 * @param new_data key => value array for bulk loading new data into the object.
	 */
	public function load(array $new_data) {
		foreach($new_data as $key => $value) {
			if(true == array_key_exists($key, $this->_data)) {
				$this->$key = $value;
			}
		}
	}
}
?>
