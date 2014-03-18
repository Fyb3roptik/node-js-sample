<?php
abstract class SQL_Statement {
	protected $_where_clause_list = array();
	protected $_bound_variables = array();

	abstract public function getSql();
	
	public function bind($field_name, $value) {
		$this->_bound_variables[$field_name] = $value;
		return $this;
	}

	public function where($where_clause) {
		$this->_where_clause_list[] = $where_clause;
		return $this;
	}

	protected function _bindVariables($sql) {
		foreach($this->_bound_variables as $var => $val) {
			$sql = str_replace('@'.$var, $val, $sql);
		}
		return $sql;
	}

	public function __toString() {
		return $this->getSql();
	}
}
?>
