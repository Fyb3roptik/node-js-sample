<?php
require_once dirname(__FILE__) . '/Object_Factory.php';
require_once dirname(__FILE__) . '/SQL.php';
require_once dirname(__FILE__) . '/Data_Object.php';
require_once dirname(__FILE__) . '/Schema_Locator.php';
require_once dirname(__FILE__) . '/../functions/db.php';
require_once dirname(__FILE__) . '/Sanity_Check_Exception.php';

/**
 * This is a basic Active Record style class. Handles the CRUD aspects that are so tiring
 * and gives you access to getWhatever() and setWhatever($new_value).
 */
class Object extends Data_Object {

	/**
	 * Name of the table for this object in the database.
	 *
	 * Must be explicitly set in child classes.
	 */
	protected $_table = null;

	/**
	 * Name of the primary key of the table for this object in the database.
	 *
	 * Must be explicitly set in child classes.
	 */
	protected $_table_id = null;


	/**
	 * Takes a key to lookup and a key field to load up the object with. Defaults to the given
	 * table_id (primary key).
	 *
	 * @param ID Unique value that identifieds this Object.
	 * @param field The field to look for ID in. If null, defaults to the value of Object::$_table_id
	 */
	public function __construct($ID = 0, $field = null) {
		if(true == is_null($field)) {
			$field = $this->_table_id;
		}
		if(false == is_null($ID)) {
			$this->_load($ID, $field);
		} else {
			$this->_loadTable();
		}
		if(true == $this->exists()) {
			$this->hash = sha1($this->ID);
		} else {
			$this->hash = md5(uniqid(rand(), true));
		}

		Object_Factory::OF()->addObject($this);
	}

	/**
	 * This is where the getFoo() / setBar($bar) magic is handled.
	 *
	 * TODO: deprecate this function.
	 *
	 * @param name Name of the function being called.
	 * @param args Array of arguments passed to the fictitious function.
	 *
	 * @return Depending upon what the value of $name was, it either returns null, or the value of a given value.
	 */
	public function __call($name, $args = null) {
		if(false == is_array($args)) {
			//this is so we can call parent::__call('setWhatevs', $value) without wrapping $value in an array
			$args = array($args);
		}
		$first_three = substr($name, 0, 3);
		if('get' == $first_three || 'set' == $first_three) {
			$data_key = $this->_methodToKey($name);
			if('get' == $first_three) {
				return $this->$data_key;
			} elseif(true == is_array($args) && 1 == count($args)) {
				$this->$data_key = $args[0];
			}
		}
	}

	/**
	 * Takes a given method name and converts it to a data key.
	 *
	 * example: _methodToKey('getFooBar') returns 'foo_bar'
	 *
	 * @param method Method name we need to convert into a data key.
	 * @return Returns the data key.
	 */
	private function _methodToKey($method) {
		$method = substr($method, 3, (strlen($method) - 3));
		$upper = range('A', 'Z');

		$new_string = "";
		$method_length = strlen($method);
		$upper_switch = in_array($method[$i], $upper);
		for( $i = 0 ; $i < $method_length ; $i++) {
			if(false == $upper_switch && true == in_array($method[$i], $upper)) {
				$new_string .= "_";
			}
			if(false == $numeric_switch && true == is_numeric($method[$i])) {
				$new_string .= "_";
			}

			$new_string .= strtolower($method[$i]);
			$upper_switch = in_array($method[$i], $upper);
			$numeric_switch = is_numeric($method[$i]);
		}
		return $new_string;
	}

	/**
	 * Tries to load up a distinct object given a field / field value.
	 *
	 * @param ID Unique ID to lookup a record by.
	 * @param field Optional field name to do the lookup by, if null, will use the value from $this->_table_id.
	 */
	protected function _load($ID, $field = null) {
		if(true == is_null($field)) {
			$field = $this->_table_id;
		}
		if(false == is_null($this->_table) && false == is_null($this->_table_id)) {
			$sql = "SELECT *
				FROM `" . $this->_table . "` t
				WHERE t." . db_input($field) . " = '" . db_input($ID) . "'";
			$query = db_query($sql);
			while(1 == $query->num_rows && $row = $query->fetch_assoc()) {
				$this->_ID = $row[$this->_table_id];
				foreach($row as $key => $value) {
					if($key !== $this->_table_id) {
						$key = $key;
						$this->_data[$key] = $value;
					}
				}
			}
			$query->free();

			if(0 == $this->_ID) { //didn't find anything? prefill our data array based on the table structure.
				$this->_loadTable();
			}
			$this->_checkUnsanitizedFields();
		}
	}

	/**
	 * Returns an array of objects based on a field / value. Makes it easy to lookup stuff.
	 *
	 * @param field Field (database column) to look up by. (Ex. parent_id)
	 * @param value Value of the field for which we are looking. (Ex. 1337)
	 * @param order_by_field Field to sort results by.
	 * @param order_direction Sort direction ASC/DESC
	 * @return Array of objects matching the criteria.
	 */
	public function find($field, $value, $order_by_field = null, $order_direction = 'ASC') {
		if(true == is_subclass_of($this, 'Object')) {
			$order_direction_list = array('DESC', 'ASC');
			$order_direction = trim(strtoupper($order_direction));
			if(false == in_array($order_direction, $order_direction_list)) {
				$order_direction = 'ASC';
			}

			$sql = "WHERE t.`" . db_input($field) . "` = '" . db_input($value) . "'" ;

			if(false == is_null($order_by_field)) {
				$sql .= " ORDER BY t.`" . db_input($order_by_field) . "` " . $order_direction;
			}
			return $this->findWhere($sql);
		}
	}

	/**
	 * Takes a literal SQL WHERE clause and returns an array of objects
	 * meeting that WHERE clause.
	 *
	 * TODO: Update this to maybe take parameterized options in an array maybe?
	 *
	 * @param where String that is a literal SQL "WHERE" clause.
	 * @return Array of Objects based on the results of the query.
	 */
	public function findWhere($where) {
		if(true == is_subclass_of($this, 'Object')) {
			$class = get_class($this);

			$sql = "SELECT `" . db_input($this->_table_id) . "`
				FROM `" . db_input($this->_table) . "` t " . $where;
			$object_array = array();
			$query = db_query($sql);
			while($query->num_rows > 0 && $o = $query->fetch_assoc()) {
				$object_array[] = Object_Factory::OF()->newObject($class, $o[$this->_table_id]);
			}
			$query->free();
			return $object_array;
		}
	}

	/**
	 * Loads up the data array for a given class if no record can be found.
	 */
	protected function _loadTable() {
		$fields = Schema_Locator::get()->describe($this->_table);
		foreach($fields as $field) {
			if($field != $this->_table_id) {
				$this->_data[$field] = exists($field, $this->_default_vals, null);
			}
		}
		$this->_checkUnsanitizedFields();
	}

	protected function _checkUnsanitizedFields() {
		foreach($this->_unsanitized_fields as $field) {
			if(false == array_key_exists($field, $this->_data)) {
				throw new Sanity_Check_Exception("Unsanitized field `$field` does not exist in the table structure");
			}
		}
	}

	/**
	 * Writes the object to the table. Handles both SQL INSERT/UPDATE functionality without having to call them
	 * explicitly.
	 */
	public function write() {
		if(intval($this->_ID) > 0) {
			$this->_update();
		} else {
			$this->_insert();
		}
	}

	/**
	 * Updates the record in the database.
	 */
	protected function _update() {
		if(intval($this->_ID) > 0 && false == is_null($this->_table) && false == is_null($this->_table_id)) {
			$data = $this->_makeRecord();
			$where = $this->_table_id . " = '" . intval($this->_ID) . "'";
			
			$sql = SQL::get()->update($this->_table)->where($where);
			foreach($data as $field => $value) {
				$sql->set($field, $value);
			}
			db_query($sql);
		}
	}

	/**
	 * Inserts a new record in teh database.
	 */
	protected function _insert() {
		if(false == is_null($this->_table) && false == is_null($this->_table_id)) {
			$data = $this->_makeRecord();
			db_perform($this->_table, $data, SQL_INSERT);
			$this->_ID = db_insert_id();
			Object_Factory::OF()->updateMap($this);
		}
	}

	/**
	 * This function creates an array suitable for use with db_perform.
	 *
	 * @return A key => value array suitable for use with db_perform().
	 */
	protected function _makeRecord() {
		$data = array();
		foreach($this->_data as $key => $value) {
			$data[$key] = $value;
		}
		return $data;
	}

	public function dump() {
		return $this->_makeRecord();
	}

	/**
	 * Delete's a the object from the table. Can be overridden if need be to do extra processing
	 * before / after a delete.
	 */
	public function delete() {
		if(intval($this->_ID) > 0) {
			$sql = "DELETE FROM `" . db_input($this->_table) . "`
				WHERE `" . db_input($this->_table_id) . "` = '" . intval($this->_ID) . "'";
			db_query($sql);
			foreach($this->_data as $key => $value) {
				$this->_data[$key] = null;
			}
			$this->_ID = 0;
		}
	}
}
?>