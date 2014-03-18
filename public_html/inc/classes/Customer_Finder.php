<?php
require_once 'Customer.php';

class Customer_Finder {
	protected $_rep;

	public $email;
	public $company;
	public $name;
	public $address_1;
	public $address_2;
	public $sql;
	public $state;
	public $phone;
	public $city;
	public $order_id;
	public $customer_id;

	/**
	 * Builds the damned thing.
	 *
	 * @param rep A Sales_Rep that's doing the searching.
	 */
	public function __construct(Sales_Rep $rep) {
		if(false == $rep->exists()) {
			throw new Exception("Customer_Finder requires a valid Sales_Rep.");
		}
		$this->_rep = $rep;
	}

	/**
	 * @param array True if you want the results in a flat array, false otherwise
	 */
	public function find($array = false) {
		$customers = array();
		$sql = SQL::get()
			->select('customer_id')
			->from('customers')
			->where("sales_rep = '@sales_rep_id'")
			->bind('sales_rep_id' , $this->_rep->ID)
			->orderBy('date_registered', SQL_Select::DESC)
			->limit(25);
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$customers[] = new Customer($rec['customer_id']);
		}
		return $customers;
	}

	public function search() {
		$sql = SQL::get()
			->select('c.customer_id, c.name, sr.name as sales_rep, c.email, ca.company, ca.address_1, ca.address_2, ca.address_3, ca.city, ca.state, ca.zip_code, ca.phone')
			->from('customers c')
			->leftJoin('customer_addresses ca', 'c.customer_id', 'ca.customer_id')
			->leftJoin('sales_reps sr', 'sr.sales_rep_id', 'c.sales_rep')
			->limit(50);

		$where_clauses = array();
		if(true == isset($this->email) && false == empty($this->email)) {
			$where_clauses[] = "c.email LIKE '%" . db_input($this->email) . "%'";
		}

		if(true == isset($this->customer_id) && false == empty($this->customer_id)) {
			$where_clauses[] = "c.customer_id = '". db_input($this->customer_id) ."'";
		}

		if(true == isset($this->company) && false == empty($this->company)) {
			$where_clauses[] = "ca.company LIKE '%" . db_input($this->company) . "%'";
		}

		if(true == isset($this->zip) && false == empty($this->zip)) {
			$where_clauses[] = "ca.zip_code = '" . db_input($this->zip) . "'";
		}

		if(true == isset($this->name) && false == empty($this->name)) {
			$where_clauses[] = "c.name LIKE '%" . db_input($this->name) . "%'";
		}

		if(true == isset($this->state) && false == empty($this->state)) {
			$city_state_where = "ca.state = '" . db_input($this->state) . "'";
			if(true == isset($this->city) && false == empty($this->city)) {
				$city_state_where .= " AND ca.city LIKE '%" . db_input($this->city) . "%'";
			}
			$where_clauses[] = $city_state_where;
		}

		if(true == isset($this->phone) && false == empty($this->phone)) {
			$where_clauses[] = "ca.phone LIKE '%" . db_input($this->phone) . "%'";
		}

		if(true == isset($this->order_id) && abs(intval($this->order_id)) > 0) {
			$sql->leftJoin('orders o', 'c.customer_id', 'o.customer_id');
			$where_clauses[] = "o.order_id = '" . abs(intval($this->order_id)) . "'";
			$sql->limit(1);
		}

		if(count($where_clauses) > 0) {
			foreach($where_clauses as $i => $clause) {
				$where_clauses[$i] = " (" . $clause . ") ";
			}
			$sql->where(implode(' AND ', $where_clauses));
		}

		$this->sql = $sql;
		$query = db_query($sql);
		$results = array();
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$results[] = $rec;
		}
		return $results;
	}
}
?>