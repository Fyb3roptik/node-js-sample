<?php
require_once 'SQL_Statement.php';

class SQL_Delete extends SQL_Statement {
	private $_table;

	public function __construct($table) {
		$this->_table = $table;
	}

	public function getSql() {
		$sql = "DELETE FROM `" . trim(db_input($this->_table)) . "` ";
		if(count($this->_where_clause_list) > 0) {
			$sql .= " WHERE ";
			$sql .= implode(' AND ', $this->_where_clause_list);
		}

		$sql = $this->_bindVariables($sql);
		return $sql;
	}
}
?>
