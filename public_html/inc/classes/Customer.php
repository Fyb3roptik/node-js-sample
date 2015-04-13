<?php
require_once 'User.php';

/**
 * Class that handles everything about a customer.
 */
class Customer extends User {
	protected $_table = 'customers';
	protected $_table_id = 'customer_id';

	protected $_default_vals = array(
						'account_type' => 'Personal',
						'name' => 'Guest');

	protected $_set_hooks = array('default_shipping' => '_setDefaultAddress', 'default_billing' => '_setDefaultAddress');

	protected $_user_type = User::TYPE_CUSTOMER;

	public function getShippingOptions() {
		$options = array();
		$CSO = new Customer_Shipping_Option();
		if(true == $this->exists()) {
			$options = $CSO->find('customer_id', $this->ID);
		}
		return $options;
	}

	public function getHandlingFee($order_total)
	{
		$options = $this->getShippingOptions();
		if(!empty($options))
		{
			$fee = Custom_Fee_Finder::findFee($order_total);
		} else {
			$fee = 0;
		}

		return $fee;
	}

	protected function _setDefaultAddress($address_id) {
		$address_id = abs(intval($address_id));
		$CA = new Customer_Address($address_id);
		if($CA->customer_id != $this->ID) {
			$address_id = null;
		}
		return $address_id;
	}

	public function getCreditCards() {
		$cc_list = array();
		if(true == $this->exists()) {
			$CC = new Credit_Card();
			$cc_list = $CC->find('customer_id', $this->ID);
		}
		return $cc_list;
	}

	public function getAddressBook($datadump = false) {
		$address_book = array();
		if(true == $this->exists()) {
			$A = new Customer_Address();
			$address_book = $A->find('customer_id', $this->_ID);
		}

		if(true == $datadump) {
			$address_data = array();
			foreach($address_book as $i => $book) {
				$address_data[] = $book->dataDump();
			}
			$address_book = $address_data;
		}

		return $address_book;
	}

	public function getSalesRep() {
		return Object_Factory::OF()->newObject('Sales_Rep', $this->sales_rep);
	}

	public function getSessionToken() {
		return parent::getToken();
	}
	
	public function getTeamHistory() {
  	$history = array();
  	
  	$sql = "SELECT * FROM teams WHERE customer_id = '".$this->ID."' AND (inning_data != '' && inning_data != 'Tjs=') ORDER BY match_id DESC";
  	$history = db_arr($sql);
  	
  	return $history;
  	
	}

	/**
	 * Return the orders placed by this Customer.
	 */
	public function getOrders($limit = 0) {
		$order_list = array();

		$sql = SQL::get()
			->select('order_id')
			->from('orders')
			->where("customer_id = '@customer_id'")
			->where("type = '@order_type'")
			->bind('customer_id', $this->ID)
			->bind('order_type', Order::TYPE_ORDER)
			->orderBy('date_purchased', SQL_Select::DESC);
		if(abs(intval($limit)) > 0) {
			$sql->limit(abs(intval($limit)));
		}
		$query = db_query($sql);
		while($query->num_rows > 0 && $rec = $query->fetch_assoc()) {
			$order_list[] = new Order($rec['order_id']);
		}
		return $order_list;
	}
	
	public static function getCustomers() {
    	$customer_list = array();
    	
    	$sql = "SELECT customer_id FROM customers ORDER BY name ASC";
    	
    	$arr = db_arr($sql);
    	
    	foreach($arr as $customer) {
        	$customer_list[] = new Customer($customer['customer_id']);
    	}
    	
    	return $customer_list;
	}

	/**
	 * Set the account type.
	 */
	public function setAccountType($account_type) {
		$account_type = trim($account_type);
		$accepted_values = array('Personal', 'Business');
		if(true == in_array($account_type, $accepted_values)) {
			parent::__call('setAccountType', $account_type);
		}
	}

	/**
	 * Returns a Cart object for this User.
	 */
	public function getCart() {
		$C = new Cart($this->_ID);
		return $C;
	}
	
	public function hasPermission($action_code) {
		$allowed = false;
		$PP = new Plans_Permission();
		$permission_list = $PP->find('plan_id', $this->plan_id);
		foreach($permission_list as $P) {
			if($action_code == $P->code) {
				$allowed = (bool)$P->allowed;
				break;
			}
		}
		return $allowed;
	}
}
?>
