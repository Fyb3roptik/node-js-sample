<?php
/**
 * Singleton class for caching table schemas so we only have to describe
 * a table once per run-time.
 */
class Schema_Locator {
	private static $_instance;
	private $_table_descriptions = array();

	private function __construct() {
		/* not much to see here */
	}

	public static function get() {
		if(false == isset(self::$_instance)) {
			$c = __CLASS__;
			self::$_instance = new $c();
		}
		return self::$_instance;
	}

	public function describe($table) {
		$table_hash = sha1($table);
		if(false == array_key_exists($table_hash, $this->_table_descriptions)) {
			$sql = "DESCRIBE `" . $table . "`";
			$query = db_query($sql);
			$description = array();
			while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
				$description[] = $rec['Field'];
			}
			$this->_table_descriptions[$table_hash] = $description;
		} else {
			$description = $this->_table_descriptions[$table_hash];
		}
		return $description;
	}

	/**
	 * Overrides a table's description.
	 *
	 * Namely for dependency injection.
	 */
	public function setDescription($table_name, $field_list) {
		$this->_table_descriptions[sha1($table_name)] = $field_list;
	}
}
?>
