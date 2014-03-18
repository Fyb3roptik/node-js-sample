<?php
require_once dirname(__FILE__) . '/../functions/standard_lib.php';
require_once 'SQL_Statement.php';

class SQL_Select extends SQL_Statement {
	private $_selected_fields = array();
	private $_from_tables = array();
	private $_table_aliases = array();
	private $_left_joins = array();
	private $_order_by = array();
	private $_group_by = array();
	private $_limit_1;
	private $_limit_2;

	const ASC = 'ASC';
	const DESC = 'DESC';

	public function __construct($field_list = array()) {
		foreach($field_list as $field) {
			$this->select($field);
		}
	}

	public function select() {
		$fields = func_get_args();
		foreach($fields as $field) {
			$this->_selected_fields[] = trim($field);
		}
		return $this;
	}

	public function from($table, $alias = null) {
		$this->_from_tables[] = $table;
		if(false == is_null($alias)) {
			$this->_table_aliases[$table] = $alias;
		}
		return $this;
	}

	public function leftJoin($table, $field_1, $field_2) {
		$join_array = array(
				'table' => $table,
				'field_1' => $field_1,
				'field_2' => $field_2
			);
		$this->_left_joins[] = $join_array;
		return $this;
	}

	public function getSql() {
		$sql = null;
		if(count($this->_selected_fields) > 0) {
			$sql .= "SELECT "; $sql .= implode(',', $this->_selected_fields); $sql .= "\n";
		}

		if(count($this->_from_tables) > 0) {
			$sql .= " FROM ";
			$table_list = array();
			foreach($this->_from_tables as $table) {
				$alias = exists($table, $this->_table_aliases, null);
				$table_list[] = $table . ' ' . $alias;	
			}
			$sql .= implode(', ', $table_list);
		}

		if(count($this->_left_joins) > 0) {
			foreach($this->_left_joins as $join_data) {
				$sql .= " LEFT JOIN " . $join_data['table'];
				$sql .= " ON " . $join_data['field_1'] . ' = ' 
					. $join_data['field_2']. " ";
			}
		}

		if(count($this->_where_clause_list) > 0) {
			$sql .= ' WHERE ' . implode(' AND ', $this->_where_clause_list);
		}

		if(count($this->_order_by) > 0) {
			$sql .= " ORDER BY ";
			$sql .= implode(',', $this->_order_by);
		}
		
		if(count($this->_group_by) > 0) {
			$sql .= " GROUP BY ";
			$sql .= implode(',', $this->_group_by);
		}

		$sql = $this->_bindVariables($sql);
		if(true == isset($this->_limit_1)) {
			$sql .= " LIMIT " . $this->_limit_1;
		}
		if(true == isset($this->_limit_2)) {
			$sql .= ", " . $this->_limit_2;
		}

		return $sql;
	}

	public function orderBy($field, $direction = self::ASC) {
		$good_order_direction = array(self::ASC, self::DESC);
		if(false == in_array($direction, $good_order_direction)) {
			$direction = self::ASC;
		}
		$this->_order_by[] = $field . " " . $direction;
		return $this;
	}
	
	public function groupBy($field) {
		$this->_group_by[] = $field;
		return $this;
	}

	/**
	 * Sets the query limiting.
	 *
	 * NOTE: I don't have time to properly name these variables, I'm so sorry. -z
	 */
	public function limit($limit_1, $limit_2 = null) {
		$this->_limit_1 = intval($limit_1);
		if(false == is_null($limit_2)) {
			$this->_limit_2 = intval($limit_2);
		}
		return $this;
	}
}
?>
